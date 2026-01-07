<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
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
                
                <a href="{{ route('cashier.sales') }}?period=day" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-700 hover:bg-indigo-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="font-medium">Sales Report</span>
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
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-800">{{ $title ?? 'Sales Report' }}</h2>
                    
                    <!-- Period Filter -->
                    <div class="flex space-x-2">
                        <a href="{{ route('cashier.sales') }}?period=day" 
                           class="px-4 py-2 rounded-lg {{ $period === 'day' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-indigo-700 hover:text-white transition">
                            Today
                        </a>
                        <a href="{{ route('cashier.sales') }}?period=week" 
                           class="px-4 py-2 rounded-lg {{ $period === 'week' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-indigo-700 hover:text-white transition">
                            This Week
                        </a>
                        <a href="{{ route('cashier.sales') }}?period=month" 
                           class="px-4 py-2 rounded-lg {{ $period === 'month' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-indigo-700 hover:text-white transition">
                            This Month
                        </a>
                        <a href="{{ route('cashier.sales') }}?period=year" 
                           class="px-4 py-2 rounded-lg {{ $period === 'year' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-indigo-700 hover:text-white transition">
                            This Year
                        </a>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                                <p class="text-3xl font-bold text-green-600">₱{{ number_format($totalRevenue, 2) }}</p>
                            </div>
                            <div class="bg-green-100 p-3 rounded-full" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <svg class="w-8 h-8" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Total Transactions</p>
                                <p class="text-3xl font-bold text-indigo-600">{{ $totalTransactions }}</p>
                            </div>
                            <div class="bg-indigo-100 p-3 rounded-full" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <svg class="w-8 h-8" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Average Sale</p>
                                <p class="text-3xl font-bold text-blue-600">₱{{ number_format($averageSale, 2) }}</p>
                            </div>
                            <div class="bg-blue-100 p-3 rounded-full" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                                <svg class="w-8 h-8" style="color: #ffffff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transactions Table -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800">Recent Transactions</h3>
                    </div>
                    
                    @if($sales->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Transaction ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date & Time
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cashier
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Payment Method
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total Amount
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sales as $sale)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                        #SALE{{ str_pad($sale->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('M d, Y g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $sale->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $badgeColor }}">
                                            {{ $sale->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">
                                        ₱{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <a href="{{ route('cashier.transaction', $sale->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing <span class="font-medium">{{ $sales->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $sales->lastItem() ?? 0 }}</span> of{' '}
                                <span class="font-medium">{{ $sales->total() }}</span> results
                            </div>
                            <div class="flex space-x-2">
                                @if($sales->previousPageUrl())
                                    <a href="{{ $sales->previousPageUrl() }}" class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300">
                                        Previous
                                    </a>
                                @else
                                    <button class="px-3 py-1 rounded-md bg-gray-200 text-gray-700 opacity-50 cursor-not-allowed" disabled>
                                        Previous
                                    </button>
                                @endif

                                @if($sales->nextPageUrl())
                                    <a href="{{ $sales->nextPageUrl() }}" class="px-3 py-1 rounded-md bg-indigo-600 text-white hover:bg-indigo-700">
                                        Next
                                    </a>
                                @else
                                    <button class="px-3 py-1 rounded-md bg-indigo-600 text-white opacity-50 cursor-not-allowed" disabled>
                                        Next
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="p-8 text-center text-gray-500">
                        <p class="text-lg">No transactions found for this period.</p>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>