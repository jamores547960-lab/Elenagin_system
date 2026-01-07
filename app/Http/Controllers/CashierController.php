<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockOut;
use App\Models\ActivityLog;

class CashierController extends Controller
{
    /**
     * Display Cashier Dashboard / POS
     */
    public function index()
    {
        $items = Item::where('quantity', '>', 0)
                     ->where('active', 1)
                     ->whereNull('deleted_at')
                     ->orderBy('name')
                     ->get();

        // Prepare items for JavaScript
        $itemsJson = $items->map(function($item) {
            return [
                'item_id' => $item->item_id,
                'name' => $item->name,
                'price' => (float) $item->unit_price,
                'stock' => (int) $item->quantity
            ];
        })->values();

        return view('cashier.dashboard', compact('items', 'itemsJson'));
    }

    /**
     * Process a Sale (POS Transaction)
     */
    public function store(Request $request)
    {
        Log::info('POS Transaction Started', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'items' => 'required|json',
            'payment_method' => 'required|string|in:Cash,Card,GCash,PayMaya,Bank Transfer',
            'amount_received' => 'nullable|numeric|min:0',
            'change_amount' => 'nullable|numeric|min:0',
        ]);

        $items = json_decode($request->items, true);

        if (empty($items) || !is_array($items)) {
            Log::error('Invalid cart data', ['items' => $items]);
            return redirect()->back()->with('error', 'No items in cart!');
        }

        Log::info('Cart items decoded', ['items' => $items]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $totalAmount = 0;
            $validatedItems = [];

            // STEP 1: Validate all items
            foreach ($items as $index => $saleItem) {
                Log::info("Validating item {$index}", ['item_data' => $saleItem]);

                $itemIdentifier = $saleItem['item_id'] ?? $saleItem['id'] ?? null;
                $quantity = isset($saleItem['quantity']) ? (int)$saleItem['quantity'] : 0;
                $price = isset($saleItem['price']) ? (float)$saleItem['price'] : null;

                if (!$itemIdentifier) {
                    throw new \Exception("Missing item identifier at position " . ($index + 1));
                }

                if ($quantity <= 0) {
                    throw new \Exception("Invalid quantity for item at position " . ($index + 1));
                }

                if ($price === null || $price < 0) {
                    throw new \Exception("Invalid price for item at position " . ($index + 1));
                }

                $item = Item::where('item_id', $itemIdentifier)->first();

                if (!$item) {
                    throw new \Exception("Item not found: " . $itemIdentifier);
                }

                if ($item->quantity < $quantity) {
                    throw new \Exception("Insufficient stock for: {$item->name}. Available: {$item->quantity}, Requested: {$quantity}");
                }

                $validatedItems[] = [
                    'item' => $item,
                    'quantity' => $quantity,
                    'price' => $price,
                    'line_total' => $quantity * $price
                ];

                $totalAmount += $quantity * $price;
            }

            // Validate cash payment
            if ($request->payment_method === 'Cash') {
                $amountReceived = $request->amount_received;
                if ($amountReceived < $totalAmount) {
                    throw new \Exception("Insufficient amount received. Total: ₱{$totalAmount}, Received: ₱{$amountReceived}");
                }
            }

            Log::info('All items validated', [
                'total_amount' => $totalAmount,
                'item_count' => count($validatedItems),
                'payment_method' => $request->payment_method,
                'amount_received' => $request->amount_received,
                'change' => $request->change_amount
            ]);

            // STEP 2: Create Sale with correct timezone and payment details
            $sale = Sale::create([
                'user_id'        => $user->id,
                'total_amount'   => $totalAmount,
                'payment_method' => $request->payment_method,
                'amount_received' => $request->amount_received ?? $totalAmount,
                'change_amount'  => $request->change_amount ?? 0,
                'sale_date'      => Carbon::now('Asia/Manila'),
            ]);

            Log::info('Sale created', ['sale_id' => $sale->id]);

            // STEP 3: Process each item
            foreach ($validatedItems as $index => $validatedItem) {
                $item = $validatedItem['item'];
                $quantity = $validatedItem['quantity'];
                $price = $validatedItem['price'];
                $lineTotal = $validatedItem['line_total'];

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'item_id'    => $item->item_id,
                    'quantity'   => $quantity,
                    'unit_price' => $price,
                    'line_total' => $lineTotal,
                ]);

                $stockOutId = 'SOUT-' . Carbon::now('Asia/Manila')->format('YmdHis') . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

                StockOut::create([
                    'stockout_id'    => $stockOutId,
                    'item_id'        => $item->item_id,
                    'user_id'        => $user->id,
                    'quantity'       => $quantity,
                    'stockout_date'  => Carbon::now('Asia/Manila')->toDateString(),
                    'reference_type' => Sale::class,
                    'reference_id'   => $sale->id,
                ]);

                $oldQuantity = $item->quantity;
                $item->quantity -= $quantity;
                $item->save();

                Log::info("Stock updated", [
                    'item_id' => $item->item_id,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $item->quantity
                ]);
            }

            // STEP 4: Log activity
            ActivityLog::create([
                'event_type'  => 'sale.processed',
                'subject_type'=> Sale::class,
                'subject_id'  => $sale->id,
                'user_id'     => $user->id,
                'description' => 'Sale processed by ' . $user->name . ' - Amount: ₱' . number_format($totalAmount, 2),
                'meta'        => json_encode([
                    'sale_id'      => $sale->id,
                    'items_count'  => count($validatedItems),
                    'payment_method' => $request->payment_method
                ]),
                'occurred_at' => Carbon::now('Asia/Manila'),
            ]);

            DB::commit();
            Log::info('Transaction committed successfully', ['sale_id' => $sale->id]);

            return redirect()->route('cashier.dashboard')
                ->with('success', 'Sale completed! Total: ₱' . number_format($totalAmount, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('POS Transaction Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Transaction failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Sales Report Page
     */
    public function sales(Request $request)
    {
        $period = $request->get('period', 'day');

        $query = Sale::with(['items.item', 'user'])->orderBy('sale_date', 'desc');

        // Filter based on period
        switch ($period) {
            case 'day':
                $query->whereDate('sale_date', today());
                $title = "Today's Sales";
                break;

            case 'week':
                $query->whereBetween('sale_date', [now()->startOfWeek(), now()->endOfWeek()]);
                $title = "This Week's Sales";
                break;

            case 'month':
                $query->whereMonth('sale_date', now()->month)
                      ->whereYear('sale_date', now()->year);
                $title = "This Month's Sales";
                break;

            case 'year':
                $query->whereYear('sale_date', now()->year);
                $title = "This Year's Sales";
                break;
        }

        // Clone query for statistics
        $statsQuery = clone $query;

        // Paginated data
        $sales = $query->paginate(20);

        // Stats
        $totalRevenue = $statsQuery->sum('total_amount');
        $totalTransactions = $statsQuery->count();
        $averageSale = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        Log::info('Sales Report Generated', [
            'period' => $period,
            'transactions' => $totalTransactions,
            'revenue' => $totalRevenue
        ]);

        return view('cashier.sales', compact(
            'sales',
            'totalRevenue',
            'totalTransactions',
            'averageSale',
            'period',
            'title'
        ));
    }

    /**
     * Detailed Transaction View
     */
    public function transaction($id)
    {
        $sale = Sale::with(['items.item', 'user'])->findOrFail($id);

        return view('cashier.transaction', compact('sale'));
    }
}