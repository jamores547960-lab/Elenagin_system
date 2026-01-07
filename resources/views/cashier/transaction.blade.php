<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-indigo-800 text-white flex flex-col">
            <div class="p-6 border-b border-indigo-700">
                <h1 class="text-2xl font-bold">POS System</h1>
                <p class="text-sm text-indigo-300 mt-1">Cashier Panel</p>
            </div>
            
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('cashier.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Point of Sale</span>
                </a>
                
                <a href="{{ route('cashier.sales') }}?period=day" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Sales Report</span>
                </a>
            </nav>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
            </form>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="p-6">
                <!-- Header -->
                <div class="mb-6">
                    <a href="{{ route('cashier.sales') }}" class="text-indigo-600 hover:text-indigo-700 flex items-center mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Sales Report
                    </a>
                    <h2 class="text-3xl font-bold text-gray-800">Transaction Details</h2>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Main Transaction Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Transaction Info Card -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-indigo-600">#SALE{{ str_pad($sale->id, 3, '0', STR_PAD_LEFT) }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">Transaction ID</p>
                                </div>
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    Completed
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Date & Time</p>
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($sale->sale_date)->format('F d, Y') }}</p>
                                    <p class="text-gray-700">{{ \Carbon\Carbon::parse($sale->sale_date)->format('g:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Cashier</p>
                                    <p class="font-semibold text-gray-900">{{ $sale->user->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Employee ID: {{ $sale->user_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                                    @php
                                        $badgeColor = match($sale->payment_method) {
                                            'Cash' => 'bg-green-100 text-green-800',
                                            'Card' => 'bg-blue-100 text-blue-800',
                                            'GCash' => 'bg-purple-100 text-purple-800',
                                            'PayMaya' => 'bg-pink-100 text-pink-800',
                                            'Bank Transfer' => 'bg-indigo-100 text-indigo-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <span class="px-3 py-1 {{ $badgeColor }} rounded-full text-sm font-semibold inline-block">
                                        {{ $sale->payment_method }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                                    <p class="text-2xl font-bold text-green-600">₱{{ number_format($sale->total_amount, 2) }}</p>
                                </div>
                            </div>

                            <!-- Payment Details (for Cash transactions) -->
                            @if($sale->payment_method === 'Cash' && $sale->amount_received)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3"><i class="fas fa-coins"></i> Cash Payment Details</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Amount Received</p>
                                        <p class="text-lg font-bold text-gray-900">₱{{ number_format($sale->amount_received, 2) }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Total Amount</p>
                                        <p class="text-lg font-bold text-gray-900">₱{{ number_format($sale->total_amount, 2) }}</p>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded-lg">
                                        <p class="text-xs text-gray-600 mb-1">Change Given</p>
                                        <p class="text-lg font-bold text-green-600">₱{{ number_format($sale->change_amount ?? 0, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Items Purchased -->
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6 border-b border-gray-200">
                                <h3 class="text-xl font-semibold text-gray-800">Items Purchased</h3>
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Item Name
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Item Code
                                            </th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Quantity
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Unit Price
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Subtotal
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($sale->items as $saleItem)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $saleItem->item->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                {{ $saleItem->item->item_id ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                                                {{ $saleItem->quantity }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                                                ₱{{ number_format($saleItem->unit_price, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                                ₱{{ number_format($saleItem->line_total, 2) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="space-y-6">
                        <!-- Transaction Summary -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Transaction Summary</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Items:</span>
                                    <span class="font-semibold text-gray-900">{{ $sale->items->count() }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total Quantity:</span>
                                    <span class="font-semibold text-gray-900">{{ $sale->items->sum('quantity') }}</span>
                                </div>
                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-semibold text-gray-900">₱{{ number_format($sale->total_amount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600">Discount:</span>
                                        <span class="font-semibold text-gray-900">₱0.00</span>
                                    </div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600">Tax:</span>
                                        <span class="font-semibold text-gray-900">₱0.00</span>
                                    </div>
                                </div>
                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between">
                                        <span class="text-lg font-semibold text-gray-900">Total:</span>
                                        <span class="text-2xl font-bold text-green-600">₱{{ number_format($sale->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Impact -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Stock Impact</h3>
                            
                            <div class="space-y-3">
                                @foreach($sale->items as $saleItem)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-700">{{ $saleItem->item->name ?? 'N/A' }}</span>
                                    <span class="text-red-600 font-semibold">-{{ $saleItem->quantity }} units</span>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-xs text-gray-500">
                                    Stock was automatically deducted from inventory when this transaction was processed.
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Actions</h3>
                            
                            <div class="space-y-2">
                                <button onclick="window.print()" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print Receipt
                                </button>
                                <button class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>