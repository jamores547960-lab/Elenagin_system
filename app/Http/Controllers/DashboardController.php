<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Item;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today      = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd   = $today->copy()->endOfMonth();

        // Filter Parameters
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;
        $categoryId = $request->input('category');

        // ============================================
        // SALES METRICS (with filters)
        // ============================================
        
        // Base sales query
        $salesQuery = Sale::query();
        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
        }
        if ($categoryId) {
            $salesQuery->whereHas('items.item', function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            });
        }
        
        // Total Sales (filtered or all time)
        $totalSales = (clone $salesQuery)->sum('total_amount') ?? 0;
        
        // Sales This Month
        $salesThisMonth = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->whereHas('items.item', function($q2) use ($categoryId) {
                    $q2->where('item_category_id', $categoryId);
                });
            })
            ->sum('total_amount') ?? 0;
        
        // Sales Today
        $salesToday = Sale::whereDate('sale_date', today())
            ->when($categoryId, function($q) use ($categoryId) {
                $q->whereHas('items.item', function($q2) use ($categoryId) {
                    $q2->where('item_category_id', $categoryId);
                });
            })
            ->sum('total_amount') ?? 0;
        
        // Sales This Week
        $salesThisWeek = Sale::whereBetween('sale_date', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ])
        ->when($categoryId, function($q) use ($categoryId) {
            $q->whereHas('items.item', function($q2) use ($categoryId) {
                $q2->where('item_category_id', $categoryId);
            });
        })
        ->sum('total_amount') ?? 0;
        
        // Total Transactions
        $totalTransactions = (clone $salesQuery)->count();
        $transactionsThisMonth = Sale::whereMonth('sale_date', now()->month)
            ->whereYear('sale_date', now()->year)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->whereHas('items.item', function($q2) use ($categoryId) {
                    $q2->where('item_category_id', $categoryId);
                });
            })
            ->count();
        
        // ============================================
        // INVENTORY METRICS (with category filter)
        // ============================================
        
        // Total Items in Inventory
        $totalItems = Item::where('active', 1)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            })
            ->count();
        
        // Total Stock Quantity
        $totalStockQuantity = Item::where('active', 1)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            })
            ->sum('quantity') ?? 0;
        
        // Total Inventory Value (qty * unit_price)
        $totalInventoryValue = Item::where('active', 1)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            })
            ->get()
            ->sum(function($item) {
                return $item->quantity * $item->unit_price;
            });
        
        // Low Stock Items (quantity <= 10)
        $lowStockCount = Item::where('active', 1)
            ->where('quantity', '<=', 10)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            })
            ->count();
        
        $lowStockItems = Item::where('active', 1)
            ->where('quantity', '<=', 10)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            })
            ->orderBy('quantity', 'asc')
            ->limit(10)
            ->get();
        
        // Out of Stock Items
        $outOfStockCount = Item::where('active', 1)
            ->where('quantity', 0)
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            })
            ->count();
        
        // ============================================
        // TOP SELLING ITEMS (by quantity sold, with filters)
        // ============================================
        
        $topSellingQuery = DB::table('sale_items')
            ->join('items', 'sale_items.item_id', '=', 'items.item_id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id');
        
        if ($startDate && $endDate) {
            $topSellingQuery->whereBetween('sales.sale_date', [$startDate, $endDate]);
        }
        if ($categoryId) {
            $topSellingQuery->where('items.item_category_id', $categoryId);
        }
        
        $topSellingItems = $topSellingQuery
            ->select(
                'items.item_id',
                'items.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity_sold'),
                DB::raw('SUM(sale_items.line_total) as total_revenue')
            )
            ->groupBy('items.item_id', 'items.name')
            ->orderBy('total_quantity_sold', 'desc')
            ->limit(10)
            ->get();
        
        // ============================================
        // RECENT SALES (Last 10 transactions, with filters)
        // ============================================
        
        $recentSalesQuery = Sale::with(['user', 'items.item']);
        if ($startDate && $endDate) {
            $recentSalesQuery->whereBetween('sale_date', [$startDate, $endDate]);
        }
        if ($categoryId) {
            $recentSalesQuery->whereHas('items.item', function($q) use ($categoryId) {
                $q->where('item_category_id', $categoryId);
            });
        }
        
        $recentSales = $recentSalesQuery
            ->orderBy('sale_date', 'desc')
            ->limit(10)
            ->get();
        
        // ============================================
        // SALES TREND (Last 7 days, with category filter)
        // ============================================
        
        $salesTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $query = Sale::whereDate('sale_date', $date);
            if ($categoryId) {
                $query->whereHas('items.item', function($q) use ($categoryId) {
                    $q->where('item_category_id', $categoryId);
                });
            }
            $amount = $query->sum('total_amount') ?? 0;
            $salesTrend[] = [
                'date' => $date->format('M d'),
                'amount' => $amount
            ];
        }
        
        // ============================================
        // MONTHLY SALES TREND (Last 6 months, with category filter)
        // ============================================
        
        $monthlySalesTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $query = Sale::whereMonth('sale_date', $month->month)
                ->whereYear('sale_date', $month->year);
            if ($categoryId) {
                $query->whereHas('items.item', function($q) use ($categoryId) {
                    $q->where('item_category_id', $categoryId);
                });
            }
            $amount = $query->sum('total_amount') ?? 0;
            $monthlySalesTrend[] = [
                'month' => $month->format('M Y'),
                'amount' => $amount
            ];
        }
        
        // ============================================
        // STOCK MOVEMENT
        // ============================================
        
        // Total Stock In (This Month)
        $stockInThisMonth = StockIn::whereMonth('stockin_date', now()->month)
            ->whereYear('stockin_date', now()->year)
            ->sum('quantity') ?? 0;
        
        // Total Stock Out (This Month)
        $stockOutThisMonth = StockOut::whereMonth('stockout_date', now()->month)
            ->whereYear('stockout_date', now()->year)
            ->sum('quantity') ?? 0;
        
        // ============================================
        // USERS/STAFF
        // ============================================
        
        $totalCashiers = User::where('role', 'cashier')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalEmployees = User::where('role', 'employee')->count();

        return view('dashboard.index', compact(
            // Sales
            'totalSales',
            'salesThisMonth',
            'salesToday',
            'salesThisWeek',
            'totalTransactions',
            'transactionsThisMonth',
            
            // Inventory
            'totalItems',
            'totalStockQuantity',
            'totalInventoryValue',
            'lowStockCount',
            'lowStockItems',
            'outOfStockCount',
            
            // Analytics
            'topSellingItems',
            'recentSales',
            'salesTrend',
            'monthlySalesTrend',
            
            // Stock Movement
            'stockInThisMonth',
            'stockOutThisMonth',
            
            // Users
            'totalCashiers',
            'totalAdmins',
            'totalEmployees'
        ));
    }
}