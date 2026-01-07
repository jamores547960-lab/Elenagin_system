<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Item;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpoilageController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with(['item', 'user'])
            ->whereNotNull('reason')
            ->orderByDesc('stockout_date')
            ->orderByDesc('created_at');

        // Filter by reason if specified
        if ($request->has('reason_filter') && $request->reason_filter !== '') {
            $query->where('reason', $request->reason_filter);
        }

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('item', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('item_id', 'LIKE', "%{$search}%");
            });
        }

        $spoilages = $query->paginate(15);

        return view('spoilage.index', compact('spoilages'));
    }

    public function create()
    {
        $items = Item::orderBy('name')->get();
        return view('spoilage.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|in:expired,damaged,contaminated,other',
            'notes' => 'nullable|string|max:500',
            'stockout_date' => 'required|date|before_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            // Get the item
            $item = Item::where('item_id', $validated['item_id'])->firstOrFail();

            // Check if sufficient stock
            if ($item->quantity < $validated['quantity']) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['quantity' => 'Insufficient stock. Available: ' . $item->quantity]);
            }

            // Generate stockout_id
            $latestStockOut = StockOut::withTrashed()
                ->orderByDesc('id')
                ->first();
            
            $nextNum = $latestStockOut ? (intval(substr($latestStockOut->stockout_id, 3)) + 1) : 1;
            $stockoutId = 'SO-' . str_pad($nextNum, 7, '0', STR_PAD_LEFT);

            // Create stock out record
            $stockOut = StockOut::create([
                'stockout_id' => $stockoutId,
                'item_id' => $validated['item_id'],
                'user_id' => Auth::id(),
                'quantity' => $validated['quantity'],
                'stockout_date' => $validated['stockout_date'],
                'reference_type' => 'spoilage',
                'reference_id' => null,
                'reason' => $validated['reason'],
                'notes' => $validated['notes'],
            ]);

            // Update item stock
            $item->quantity -= $validated['quantity'];
            $item->save();

            // Log activity
            ActivityLog::record(
                'spoilage_recorded',
                $item,
                "Recorded spoilage for {$item->name} (Qty: {$validated['quantity']}, Reason: {$validated['reason']})",
                [
                    'quantity' => $validated['quantity'],
                    'reason' => $validated['reason'],
                    'stockout_id' => $stockoutId,
                ]
            );

            DB::commit();

            return redirect()->route('spoilage.index')
                ->with('success', 'Spoilage recorded successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to record spoilage: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $spoilage = StockOut::with(['item', 'user'])
            ->whereNotNull('reason')
            ->findOrFail($id);

        return view('spoilage.show', compact('spoilage'));
    }
}
