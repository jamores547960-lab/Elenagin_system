<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white;
            }
            @page {
                margin: 0;
                size: auto;
            }
            /* Remove browser-generated headers and footers */
            html, body {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen p-4">
        <!-- Back Button (No Print) -->
        <div class="no-print max-w-4xl mx-auto mb-4">
            <a href="<?php echo e(route('cashier.sales')); ?>" class="text-indigo-600 hover:text-indigo-700 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Sales Report
            </a>
        </div>

        <!-- Receipt Container -->
        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Receipt Body -->
            <div class="p-8">
                <!-- Transaction Header -->
                <div class="flex justify-between items-start mb-6 pb-6 border-b-2 border-gray-300">
                    <div>
                        <h2 class="text-2xl font-bold text-indigo-600">#SALE<?php echo e(str_pad($sale->id, 3, '0', STR_PAD_LEFT)); ?></h2>
                        <p class="text-sm text-gray-600 mt-1">Transaction ID</p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-900 font-semibold"><?php echo e(\Carbon\Carbon::parse($sale->sale_date)->format('F d, Y')); ?></p>
                        <p class="text-gray-700"><?php echo e(\Carbon\Carbon::parse($sale->sale_date)->format('g:i A')); ?></p>
                        <p class="text-sm text-gray-600 mt-1">Cashier: <?php echo e($sale->user->name ?? 'N/A'); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Items Purchased -->
                    <div class="lg:col-span-3 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Items Purchased</h3>
                        
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-300">
                                    <th class="py-3 text-left text-sm font-semibold text-gray-700">Item</th>
                                    <th class="py-3 text-left text-sm font-semibold text-gray-700">Code</th>
                                    <th class="py-3 text-center text-sm font-semibold text-gray-700">Qty</th>
                                    <th class="py-3 text-right text-sm font-semibold text-gray-700">Price</th>
                                    <th class="py-3 text-right text-sm font-semibold text-gray-700">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $saleItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="border-b border-gray-200">
                                    <td class="py-3 text-sm text-gray-900"><?php echo e($saleItem->item->name ?? 'N/A'); ?></td>
                                    <td class="py-3 text-sm text-gray-600"><?php echo e($saleItem->item->item_id ?? 'N/A'); ?></td>
                                    <td class="py-3 text-sm text-gray-900 text-center"><?php echo e($saleItem->quantity); ?></td>
                                    <td class="py-3 text-sm text-gray-900 text-right">₱<?php echo e(number_format($saleItem->unit_price, 2)); ?></td>
                                    <td class="py-3 text-sm font-semibold text-gray-900 text-right">₱<?php echo e(number_format($saleItem->line_total, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Transaction Summary -->
                    <div class="lg:col-span-3 border-t-2 border-gray-300 pt-6">
                        <div class="max-w-md ml-auto">
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Total Items:</span>
                                    <span class="font-semibold text-gray-900"><?php echo e($sale->items->count()); ?></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Total Quantity:</span>
                                    <span class="font-semibold text-gray-900"><?php echo e($sale->items->sum('quantity')); ?></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold text-gray-900">₱<?php echo e(number_format($sale->total_amount, 2)); ?></span>
                                </div>
                                <div class="border-t-2 border-gray-300 pt-3 mt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xl font-bold text-gray-900">TOTAL:</span>
                                        <span class="text-3xl font-bold text-green-600">₱<?php echo e(number_format($sale->total_amount, 2)); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-gray-600">Payment Method:</span>
                                    <?php
                                        $badgeColor = match($sale->payment_method) {
                                            'Cash' => 'bg-green-100 text-green-800',
                                            'Card' => 'bg-blue-100 text-blue-800',
                                            'GCash' => 'bg-purple-100 text-purple-800',
                                            'PayMaya' => 'bg-pink-100 text-pink-800',
                                            'Bank Transfer' => 'bg-indigo-100 text-indigo-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    ?>
                                    <span class="px-3 py-1 <?php echo e($badgeColor); ?> rounded-full text-xs font-semibold">
                                        <?php echo e($sale->payment_method); ?>

                                    </span>
                                </div>

                                <?php if($sale->payment_method === 'Cash' && $sale->amount_received): ?>
                                <div class="space-y-2 mt-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Amount Received:</span>
                                        <span class="font-semibold text-gray-900">₱<?php echo e(number_format($sale->amount_received, 2)); ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Change Given:</span>
                                        <span class="font-semibold text-green-600">₱<?php echo e(number_format($sale->change_amount ?? 0, 2)); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-gray-300 text-center text-gray-600 text-sm">
                    <p>Thank you for your purchase!</p>
                    <p class="mt-2">This serves as your official receipt.</p>
                    <p class="mt-4 text-xs text-gray-500">
                        Transaction completed on <?php echo e(\Carbon\Carbon::parse($sale->sale_date)->format('F d, Y \a\t g:i A')); ?>

                    </p>
                </div>

                <!-- Print Button (No Print) -->
                <div class="no-print mt-6 flex justify-center gap-4">
                    <button onclick="window.print()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print Receipt
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/cashier/transaction.blade.php ENDPATH**/ ?>