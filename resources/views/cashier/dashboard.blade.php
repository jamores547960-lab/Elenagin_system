<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sale</title>
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
                <a href="{{ route('cashier.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg bg-indigo-700 hover:bg-indigo-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-medium">Point of Sale</span>
                </a>
                
                <a href="{{ route('cashier.sales') }}?period=day" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Sales Report</span>
                </a>
            </nav>
            
            <div class="p-4 border-t border-indigo-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>

            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <div class="p-6">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">Point of Sale (POS)</h2>

                <!-- Messages -->
                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    {{ session('error') }}
                </div>
                @endif

                <div id="message-container"></div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Product Selection -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Available Items</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 max-h-[calc(100vh-250px)] overflow-y-auto" id="item-grid">
                            <div class="col-span-full text-center text-gray-500 py-8">Loading items...</div>
                        </div>
                    </div>

                    <!-- Cart Panel -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-semibold text-gray-700 mb-4">Current Sale</h3>
                        
                        <form id="pos-form" action="{{ route('cashier.process') }}" method="POST">
                            @csrf
                            
                            <!-- Cart Items -->
                            <div class="border rounded-lg mb-4 max-h-[350px] overflow-y-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                            <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                                            <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                            <th class="px-1 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-body">
                                        <tr id="empty-cart-message">
                                            <td colspan="4" class="p-4 text-center text-gray-500 italic text-sm">Cart is empty</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <input type="hidden" name="items" id="items-input">

                            <!-- Totals -->
                            <div class="border-t pt-4 space-y-2 mb-4">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Items:</span>
                                    <span id="items-count">0</span>
                                </div>
                                <div class="flex justify-between text-2xl font-bold text-indigo-700">
                                    <span>TOTAL:</span>
                                    <span id="grand-total">₱0.00</span>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <select name="payment_method" id="payment-method" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="handlePaymentMethodChange()">
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card (Debit/Credit)</option>
                                    <option value="GCash">GCash</option>
                                    <option value="PayMaya">PayMaya</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <!-- Cash Payment Fields (shown only for Cash) -->
                            <div id="cash-payment-section" class="mb-4 space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount Received</label>
                                    <input type="number" id="amount-received" step="0.01" min="0" placeholder="Enter amount received" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-lg font-semibold"
                                        oninput="calculateChange()">
                                </div>
                                <div id="change-display" class="hidden p-3 bg-green-50 border-2 border-green-300 rounded-lg">
                                    <div class="text-sm text-gray-700 mb-1">Change:</div>
                                    <div class="text-2xl font-bold text-green-600" id="change-amount">₱0.00</div>
                                </div>
                                <div id="insufficient-warning" class="hidden p-3 bg-red-50 border-2 border-red-300 rounded-lg">
                                    <div class="text-sm font-semibold text-red-700"><i class="fas fa-exclamation-triangle"></i> Insufficient amount received</div>
                                </div>
                            </div>

                            <!-- Hidden inputs for payment details -->
                            <input type="hidden" name="amount_received" id="amount-received-input">
                            <input type="hidden" name="change_amount" id="change-amount-input">

                            <!-- Actions -->
                            <div class="space-y-2">
                                <button type="submit" id="process-btn" disabled 
                                    class="w-full py-3 px-4 rounded-lg text-lg font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
                                    <i class="fas fa-cash-register"></i> Process Sale
                                </button>
                                <button type="button" id="clear-cart-btn" 
                                    class="w-full py-2 px-4 rounded-lg text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 transition">
                                    <i class="fas fa-trash-alt"></i> Clear Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Load items from backend
        const items = @json($itemsJson ?? []);

        console.log('Items loaded:', items);
        console.log('Number of items:', items.length);

        let cart = {};

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, rendering items...');
            renderItems();
            renderCart();
            handlePaymentMethodChange(); // Initialize payment section
            
            document.getElementById('clear-cart-btn').addEventListener('click', clearCart);
            document.getElementById('pos-form').addEventListener('submit', validateForm);
        });

        function renderItems() {
            const grid = document.getElementById('item-grid');
            
            console.log('renderItems called, items:', items);
            
            if (!items || items.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center text-gray-500 py-8">No items available in inventory</div>';
                return;
            }
            
            const itemsHtml = items.map(item => {
                const escapedName = (item.name || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                const itemId = item.item_id || '';
                const price = parseFloat(item.price) || 0;
                const stock = parseInt(item.stock) || 0;
                
                return `
                    <div class="item-card bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 p-4 rounded-xl cursor-pointer hover:shadow-md hover:border-indigo-400 transition-all duration-200 text-center"
                         onclick="addToCart('${itemId}', '${escapedName}', ${price}, ${stock})">
                        <div class="text-lg font-bold text-indigo-600 truncate" title="${item.name}">${item.name}</div>
                        <div class="text-xs text-gray-500 mt-1">Stock: ${stock}</div>
                        <div class="text-xl font-extrabold text-green-600 mt-2">₱${price.toFixed(2)}</div>
                    </div>
                `;
            }).join('');
            
            grid.innerHTML = itemsHtml;
            console.log('Items rendered successfully');
        }

        function addToCart(item_id, name, price, stock) {
            console.log('Adding to cart:', { item_id, name, price, stock });
            
            if (!item_id) {
                showMessage('Invalid item', 'error');
                return;
            }
            
            const currentQty = cart[item_id] ? cart[item_id].quantity : 0;
            
            if (currentQty >= stock) {
                showMessage(`Cannot add more. Stock limit (${stock}) reached for ${name}`, 'error');
                return;
            }

            if (cart[item_id]) {
                cart[item_id].quantity += 1;
            } else {
                cart[item_id] = {
                    item_id: item_id,
                    name: name,
                    price: parseFloat(price),
                    stock: parseInt(stock),
                    quantity: 1
                };
            }
            
            renderCart();
            showMessage(`Added ${name} to cart`, 'success');
        }

        function updateQuantity(item_id, newQty) {
            newQty = parseInt(newQty);
            
            if (!cart[item_id]) return;
            
            if (newQty <= 0) {
                delete cart[item_id];
            } else if (newQty > cart[item_id].stock) {
                showMessage(`Cannot exceed stock limit of ${cart[item_id].stock}`, 'error');
                cart[item_id].quantity = cart[item_id].stock;
            } else {
                cart[item_id].quantity = newQty;
            }
            
            renderCart();
        }

        function removeFromCart(item_id) {
            if (cart[item_id]) {
                const name = cart[item_id].name;
                delete cart[item_id];
                renderCart();
                showMessage(`Removed ${name} from cart`, 'info');
            }
        }

        function clearCart() {
            if (Object.keys(cart).length === 0) return;
            
            if (confirm('Are you sure you want to clear the entire cart?')) {
                cart = {};
                renderCart();
                showMessage('Cart cleared', 'info');
            }
        }

        function renderCart() {
            const cartBody = document.getElementById('cart-body');
            let total = 0;
            let itemCount = 0;
            let html = '';

            for (const item_id in cart) {
                const item = cart[item_id];
                const subtotal = item.quantity * item.price;
                total += subtotal;
                itemCount += item.quantity;

                html += `
                    <tr class="border-b">
                        <td class="px-3 py-2 text-xs font-medium text-gray-800">${item.name}</td>
                        <td class="px-2 py-2">
                            <input type="number" value="${item.quantity}" min="1" max="${item.stock}" 
                                onchange="updateQuantity('${item_id}', this.value)"
                                class="w-12 text-center text-xs rounded border-gray-300 p-1">
                        </td>
                        <td class="px-3 py-2 text-xs font-semibold text-right">₱${subtotal.toFixed(2)}</td>
                        <td class="px-1 py-2 text-center">
                            <button type="button" onclick="removeFromCart('${item_id}')" 
                                class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                `;
            }

            cartBody.innerHTML = html || '<tr id="empty-cart-message"><td colspan="4" class="p-4 text-center text-gray-500 italic text-sm">Cart is empty</td></tr>';
            
            document.getElementById('items-count').textContent = itemCount;
            document.getElementById('grand-total').textContent = `₱${total.toFixed(2)}`;
            
            const cartArray = Object.values(cart).map(item => ({
                item_id: item.item_id,
                quantity: item.quantity,
                price: item.price
            }));
            
            console.log('Cart array to send:', cartArray);
            
            document.getElementById('items-input').value = JSON.stringify(cartArray);
            
            // Update process button state based on payment method
            const paymentMethod = document.getElementById('payment-method').value;
            if (cartArray.length === 0) {
                document.getElementById('process-btn').disabled = true;
            } else if (paymentMethod === 'Cash') {
                calculateChange(); // Re-validate cash payment
            } else {
                document.getElementById('process-btn').disabled = false;
                updatePaymentInputs();
            }
        }

        function handlePaymentMethodChange() {
            const paymentMethod = document.getElementById('payment-method').value;
            const cashSection = document.getElementById('cash-payment-section');
            const amountReceived = document.getElementById('amount-received');
            
            if (paymentMethod === 'Cash') {
                cashSection.style.display = 'block';
                amountReceived.required = true;
            } else {
                cashSection.style.display = 'none';
                amountReceived.required = false;
                amountReceived.value = '';
                document.getElementById('change-display').classList.add('hidden');
                document.getElementById('insufficient-warning').classList.add('hidden');
                // For non-cash, amount received equals total
                updatePaymentInputs();
            }
        }

        function calculateChange() {
            const total = getCurrentTotal();
            const received = parseFloat(document.getElementById('amount-received').value) || 0;
            const change = received - total;
            
            const changeDisplay = document.getElementById('change-display');
            const insufficientWarning = document.getElementById('insufficient-warning');
            const processBtn = document.getElementById('process-btn');
            
            if (received > 0) {
                if (change >= 0) {
                    document.getElementById('change-amount').textContent = `₱${change.toFixed(2)}`;
                    changeDisplay.classList.remove('hidden');
                    insufficientWarning.classList.add('hidden');
                    processBtn.disabled = Object.keys(cart).length === 0;
                } else {
                    changeDisplay.classList.add('hidden');
                    insufficientWarning.classList.remove('hidden');
                    processBtn.disabled = true;
                }
            } else {
                changeDisplay.classList.add('hidden');
                insufficientWarning.classList.add('hidden');
                processBtn.disabled = true;
            }
            
            updatePaymentInputs();
        }

        function getCurrentTotal() {
            let total = 0;
            for (const item_id in cart) {
                total += cart[item_id].quantity * cart[item_id].price;
            }
            return total;
        }

        function updatePaymentInputs() {
            const paymentMethod = document.getElementById('payment-method').value;
            const total = getCurrentTotal();
            
            if (paymentMethod === 'Cash') {
                const received = parseFloat(document.getElementById('amount-received').value) || 0;
                const change = received - total;
                document.getElementById('amount-received-input').value = received;
                document.getElementById('change-amount-input').value = Math.max(0, change);
            } else {
                // For non-cash, amount received = total, change = 0
                document.getElementById('amount-received-input').value = total;
                document.getElementById('change-amount-input').value = 0;
            }
        }

        function validateForm(e) {
            const items = document.getElementById('items-input').value;
            const paymentMethod = document.getElementById('payment-method').value;
            const total = getCurrentTotal();
            
            console.log('Submitting form with items:', items);
            
            try {
                const parsed = JSON.parse(items);
                if (!parsed || parsed.length === 0) {
                    e.preventDefault();
                    showMessage('Cart is empty! Please add items before processing.', 'error');
                    return false;
                }
                
                for (let item of parsed) {
                    if (!item.item_id) {
                        e.preventDefault();
                        showMessage('Error: Invalid item data (missing item_id)', 'error');
                        console.error('Invalid item:', item);
                        return false;
                    }
                }

                // Validate cash payment
                if (paymentMethod === 'Cash') {
                    const received = parseFloat(document.getElementById('amount-received').value) || 0;
                    if (received < total) {
                        e.preventDefault();
                        showMessage('Insufficient amount received! Please enter correct amount.', 'error');
                        return false;
                    }
                }

                // Update payment inputs before submit
                updatePaymentInputs();
                
                return true;
            } catch (err) {
                e.preventDefault();
                showMessage('Error: Invalid cart data', 'error');
                console.error('JSON parse error:', err);
                return false;
            }
        }

        function showMessage(text, type = 'info') {
            const container = document.getElementById('message-container');
            const colors = {
                success: 'bg-green-100 border-green-500 text-green-700',
                error: 'bg-red-100 border-red-500 text-red-700',
                info: 'bg-blue-100 border-blue-500 text-blue-700'
            };
            
            const msg = document.createElement('div');
            msg.className = `${colors[type]} border-l-4 p-4 mb-4 rounded`;
            msg.textContent = text;
            container.appendChild(msg);
            
            setTimeout(() => msg.remove(), 3000);
        }
    </script>
</body>
</html>