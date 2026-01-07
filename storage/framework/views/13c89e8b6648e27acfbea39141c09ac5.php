<?php $__env->startSection('title','Dashboard - Elenagin System'); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        /* Dashboard Filter Section */
        .dashboard-filters {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.08);
        }
        
        .filter-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-size: 1.125rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
        }
        
        .filter-header i {
            font-size: 1.5rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .filter-input,
        .filter-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid rgba(255, 255, 255, 0.25);
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            color: white;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .filter-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        
        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: white;
            background: rgba(255, 255, 255, 0.25);
        }
        
        .filter-select option {
            background: #1f2937;
            color: white;
        }
        
        .filter-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .filter-btn {
            flex: 1;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn-primary {
            background: white;
            color: #667eea;
        }
        
        .filter-btn-primary:hover {
            background: #f3f4f6;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        
        .filter-btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }
        
        .filter-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: white;
        }
        
        /* Loading State & Transition Fixes */
        .dashboard-grid {
            padding: 0;
            max-height: none;
            overflow-y: visible;
            transition: opacity 0.3s ease-in-out;
        }
        
        .dashboard-grid.loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        /* Prevent layout shift during data updates */
        .dm-card,
        .panel {
            min-height: 120px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dm-value,
        .lr-val {
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
        }
        
        /* Skeleton Loading State */
        @keyframes skeleton-loading {
            0% {
                background-position: -200px 0;
            }
            100% {
                background-position: calc(200px + 100%) 0;
            }
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 0px, #f8f8f8 40px, #f0f0f0 80px);
            background-size: 200px 100%;
            animation: skeleton-loading 1.2s ease-in-out infinite;
            border-radius: 4px;
            display: inline-block;
            height: 1em;
            width: 100%;
        }
        
        .skeleton-text {
            height: 0.8em;
            margin-bottom: 0.5em;
        }
        
        .skeleton-title {
            height: 1.5em;
            width: 60%;
        }
        
        /* Smooth data transition */
        .data-transition-enter {
            opacity: 0;
            transform: translateY(-10px);
        }
        
        .data-transition-enter-active {
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
        
        .dash-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Modern KPI Cards - Clickable with Proper Alignment */
        .dm-card {
            width: 100%;
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }
        
        .dm-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .dm-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.1);
            border-color: #667eea;
        }
        
        .dm-card:hover::before {
            transform: scaleX(1);
        }
        
        .dm-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }
        
        /* LEFT ALIGNED - Labels/Text */
        .dm-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 700;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            line-height: 1.3;
        }
        
        .dm-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
            color: #ffffff;
            transition: all 0.3s ease;
        }
        
        .dm-card:hover .dm-icon {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.2);
        }
        
        .dm-icon i {
            color: #ffffff;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1));
        }
        
        /* RIGHT ALIGNED - Numerical Values */
        .dm-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1f2937;
            text-align: right;
            line-height: 1;
            margin: 0.75rem 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .dm-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* LEFT ALIGNED - Sub text */
        .dm-sub {
            font-size: 0.875rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-align: left;
            font-weight: 500;
        }
        
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        
        .dot-green { background: #10b981; }
        .dot-blue { background: #3b82f6; }
        .dot-purple { background: #8b5cf6; }
        .dot-amber { background: #f59e0b; }
        .dot-red { background: #ef4444; }
        .dot-cyan { background: #06b6d4; }
        
        /* NEW: 2 Column Grid Layout */
        .dash-main-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .panel {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        
        .panel:hover {
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        }
        
        /* Stock In Panel - Dynamic Expansion */
        .stock-in-panel {
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }
        
        .stock-in-panel .list-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .stock-in-panel table {
            flex: 1;
        }
        
        .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .panel-head h3 {
            font-size: 1.125rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        
        /* Increased chart height */
        .chart-container {
            position: relative;
            height: 400px !important;
            width: 100% !important;
        }
        
        .list-body {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .list-row {
            display: grid;
            grid-template-columns: 1fr 120px 100px;
            gap: 1rem;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .list-row:last-child {
            border-bottom: none;
        }
        
        .lr-name {
            font-weight: 500;
            color: #374151;
            font-size: 0.875rem;
        }
        
        .lr-val {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }
        
        .lr-sub {
            font-size: 0.75rem;
            color: #9ca3af;
        }
        
        .empty-alt {
            text-align: center;
            padding: 2rem;
            color: #9ca3af;
            font-style: italic;
        }
        
        /* Vertical scroll styling */
        .dashboard-grid::-webkit-scrollbar,
        .list-body::-webkit-scrollbar {
            width: 8px;
        }
        
        .dashboard-grid::-webkit-scrollbar-track,
        .list-body::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }
        
        .dashboard-grid::-webkit-scrollbar-thumb,
        .list-body::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        
        .dashboard-grid::-webkit-scrollbar-thumb:hover,
        .list-body::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1200px) {
            .dash-main-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .low-stock-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #fef2f2;
            color: #dc2626;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .alert-banner {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-left: 4px solid #ef4444;
            padding: 1.25rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.08);
        }
        
        .alert-banner h4 {
            color: #dc2626;
            margin: 0 0 0.5rem 0;
            font-size: 1.125rem;
            font-weight: 700;
        }
        
        .alert-banner p {
            color: #991b1b;
            margin: 0;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .page-header-modern {
            margin-bottom: 2rem;
        }
        
        .page-title-modern {
            font-size: 2.25rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .dash-main-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 768px) {
            .filter-grid {
                grid-template-columns: 1fr;
            }
            
            .dash-metrics {
                grid-template-columns: 1fr;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header-modern">
    <h1 class="page-title-modern"><i class="fas fa-chart-pie"></i> ADMIN DASHBOARD</h1>
</div>

<!-- Dashboard Filters -->
<div class="dashboard-filters">
    <div class="filter-header">
        <i class="fas fa-sliders-h"></i>
        <span>Dashboard Filters</span>
    </div>
    <form method="GET" action="<?php echo e(route('system')); ?>">
        <div class="filter-grid">
            <div class="filter-group">
                <label>Start Date</label>
                <input type="date" name="start_date" class="filter-input" value="<?php echo e(request('start_date')); ?>" max="<?php echo e(date('Y-m-d')); ?>">
            </div>
            <div class="filter-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="filter-input" value="<?php echo e(request('end_date')); ?>" max="<?php echo e(date('Y-m-d')); ?>">
            </div>
            <div class="filter-group">
                <label>Category</label>
                <select name="category" class="filter-select">
                    <option value="">All Categories</option>
                    <?php $__currentLoopData = \App\Models\ItemCategory::where('active', 1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->item_category_id); ?>" <?php echo e(request('category') == $cat->item_category_id ? 'selected' : ''); ?>>
                            <?php echo e($cat->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="filter-group">
                <label>&nbsp;</label>
                <div class="filter-buttons">
                    <button type="submit" class="filter-btn filter-btn-primary">Apply Filters</button>
                    <a href="<?php echo e(route('system')); ?>" class="filter-btn filter-btn-secondary" style="text-align: center; display: flex; align-items: center; justify-content: center; text-decoration: none;">Reset</a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if($lowStockCount > 0): ?>
<div class="alert-banner">
    <h4><i class="fas fa-exclamation-triangle"></i> Low Stock Alert</h4>
    <p><?php echo e($lowStockCount); ?> item(s) are running low on stock (≤10 units). Please reorder soon.</p>
</div>
<?php endif; ?>

<div class="dashboard-grid">
    <!-- Sales Metrics -->
    <div class="dash-metrics">
        <!-- Sales Today Card -->
        <a href="<?php echo e(route('cashier.sales')); ?>?period=day" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Sales Today</div>
                <div class="dm-icon"><i class="fas fa-dollar-sign"></i></div>
            </div>
            <div class="dm-value">₱<?php echo e(number_format($salesToday, 2)); ?></div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot dot-green"></span>
                    Point of Sale
                </div>
            </div>
        </a>

        <!-- Sales This Week Card -->
        <a href="<?php echo e(route('cashier.sales')); ?>?period=week" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Sales This Week</div>
                <div class="dm-icon"><i class="fas fa-chart-line"></i></div>
            </div>
            <div class="dm-value">₱<?php echo e(number_format($salesThisWeek, 2)); ?></div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot dot-blue"></span>
                    <?php echo e($totalTransactions); ?> transactions
                </div>
            </div>
        </a>

        <!-- Sales This Month Card -->
        <a href="<?php echo e(route('cashier.sales')); ?>?period=month" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Sales This Month</div>
                <div class="dm-icon"><i class="fas fa-chart-bar"></i></div>
            </div>
            <div class="dm-value">₱<?php echo e(number_format($salesThisMonth, 2)); ?></div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot dot-purple"></span>
                    <?php echo e($transactionsThisMonth); ?> transactions
                </div>
            </div>
        </a>

        <!-- Total Sales Card -->
        <a href="<?php echo e(route('cashier.sales')); ?>?period=year" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Total Sales</div>
                <div class="dm-icon"><i class="fas fa-gem"></i></div>
            </div>
            <div class="dm-value">₱<?php echo e(number_format($totalSales, 2)); ?></div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot dot-cyan"></span>
                    All-time revenue
                </div>
            </div>
        </a>

        <!-- Total Items Card -->
        <a href="<?php echo e(route('inventory.index')); ?>" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Total Items</div>
                <div class="dm-icon"><i class="fas fa-box"></i></div>
            </div>
            <div class="dm-value"><?php echo e($totalItems); ?></div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot dot-blue"></span>
                    <?php echo e(number_format($totalStockQuantity)); ?> units
                </div>
            </div>
        </a>

        <!-- Inventory Value Card -->
        <a href="<?php echo e(route('inventory.index')); ?>" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Inventory Value</div>
                <div class="dm-icon"><i class="fas fa-money-bill-wave"></i></div>
            </div>
            <div class="dm-value">₱<?php echo e(number_format($totalInventoryValue, 2)); ?></div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot dot-amber"></span>
                    Total qty × price
                </div>
            </div>
        </a>

        <!-- Low Stock Alert Card -->
        <a href="<?php echo e(route('inventory.index')); ?>?filter=low_stock" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Low Stock Alert</div>
                <div class="dm-icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
            <div class="dm-value" style="<?php echo e($lowStockCount > 0 ? 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;' : ''); ?>">
                <?php echo e($lowStockCount); ?>

            </div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot <?php echo e($lowStockCount > 0 ? 'dot-red' : 'dot-green'); ?>"></span>
                    <?php echo e($lowStockCount > 0 ? 'Items need restock' : 'All items stocked'); ?>

                </div>
            </div>
        </a>

        <!-- Out of Stock Card -->
        <a href="<?php echo e(route('inventory.index')); ?>?filter=out_of_stock" class="dm-card">
            <div class="dm-header">
                <div class="dm-label">Out of Stock</div>
                <div class="dm-icon"><i class="fas fa-ban"></i></div>
            </div>
            <div class="dm-value" style="<?php echo e($outOfStockCount > 0 ? 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;' : ''); ?>">
                <?php echo e($outOfStockCount); ?>

            </div>
            <div class="dm-footer">
                <div class="dm-sub">
                    <span class="dot <?php echo e($outOfStockCount > 0 ? 'dot-red' : 'dot-green'); ?>"></span>
                    <?php echo e($outOfStockCount > 0 ? 'Items unavailable' : 'All items available'); ?>

                </div>
            </div>
        </a>
    </div>

    <!-- Charts and Lists - 2 PER ROW -->
    <div class="dash-main-grid">
        <!-- Sales Trend Chart -->
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-chart-line"></i> Sales Trend (Last 7 Days)</h3>
            </div>
            <div class="chart-container">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <!-- Monthly Sales Chart -->
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-chart-bar"></i> Monthly Sales (Last 6 Months)</h3>
            </div>
            <div class="chart-container">
                <canvas id="monthlySalesChart"></canvas>
            </div>
        </div>

        <!-- Top Selling Items -->
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-fire"></i> Top Selling Items</h3>
            </div>
            <div class="list-body">
                <?php $__empty_1 = true; $__currentLoopData = $topSellingItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-row">
                        <div>
                            <div class="lr-name"><?php echo e($item->name); ?></div>
                            <div class="lr-sub"><?php echo e($item->item_id); ?></div>
                        </div>
                        <div class="lr-sub">
                            <?php echo e($item->total_quantity_sold); ?> units sold
                        </div>
                        <div class="lr-val">
                            ₱<?php echo e(number_format($item->total_revenue, 2)); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-alt">No sales data available yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="panel">
            <div class="panel-head">
                <h3><i class="fas fa-exclamation-triangle"></i> Low Stock Items</h3>
            </div>
            <div class="list-body">
                <?php $__empty_1 = true; $__currentLoopData = $lowStockItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="list-row">
                        <div>
                            <div class="lr-name"><?php echo e($item->name); ?></div>
                            <div class="lr-sub"><?php echo e($item->item_id); ?></div>
                        </div>
                        <div>
                            <span class="low-stock-badge"><?php echo e($item->quantity); ?> left</span>
                        </div>
                        <div class="lr-val">
                            ₱<?php echo e(number_format($item->unit_price, 2)); ?>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="empty-alt">All items are well stocked! <i class="fas fa-check-circle" style="color: #10b981;"></i></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Sales - Full Width -->
        <div class="panel" style="grid-column: span 2;">
            <div class="panel-head">
                <h3><i class="fas fa-receipt"></i> Recent Transactions</h3>
            </div>
            <div class="list-body">
                <table style="width: 100%; font-size: 0.875rem;">
                    <thead style="background: #f9fafb;">
                        <tr>
                            <th style="padding: 0.75rem; text-align: left;">Sale ID</th>
                            <th style="padding: 0.75rem; text-align: left;">Date</th>
                            <th style="padding: 0.75rem; text-align: left;">Cashier</th>
                            <th style="padding: 0.75rem; text-align: left;">Payment</th>
                            <th style="padding: 0.75rem; text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentSales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.75rem;">#<?php echo e(str_pad($sale->id, 4, '0', STR_PAD_LEFT)); ?></td>
                                <td style="padding: 0.75rem;"><?php echo e($sale->sale_date->format('M d, Y g:i A')); ?></td>
                                <td style="padding: 0.75rem;"><?php echo e($sale->user->name ?? 'N/A'); ?></td>
                                <td style="padding: 0.75rem;">
                                    <span style="padding: 0.25rem 0.5rem; background: #e0f2fe; color: #0369a1; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                        <?php echo e($sale->payment_method); ?>

                                    </span>
                                </td>
                                <td style="padding: 0.75rem; text-align: right; font-weight: 600;">
                                    ₱<?php echo e(number_format($sale->total_amount, 2)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" style="padding: 2rem; text-align: center; color: #9ca3af;">
                                    No transactions yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Stock In - Full Width, Dynamic Height -->
        <div class="panel stock-in-panel" style="grid-column: span 2;">
            <div class="panel-head">
                <h3><i class="fas fa-arrow-down"></i> Recent Stock In</h3>
                <a href="<?php echo e(route('stock_in.index')); ?>" style="color: #667eea; font-size: 0.875rem; font-weight: 600; text-decoration: none;">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="list-body" style="max-height: none; overflow-y: visible;">
                <table style="width: 100%; font-size: 0.875rem;">
                    <thead style="background: #f9fafb;">
                        <tr>
                            <th style="padding: 0.75rem; text-align: left;">Stock ID</th>
                            <th style="padding: 0.75rem; text-align: left;">Date</th>
                            <th style="padding: 0.75rem; text-align: left;">Item</th>
                            <th style="padding: 0.75rem; text-align: left;">Supplier</th>
                            <th style="padding: 0.75rem; text-align: center;">Quantity</th>
                            <th style="padding: 0.75rem; text-align: right;">Unit Cost</th>
                            <th style="padding: 0.75rem; text-align: right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $recentStockIns = \App\Models\StockIn::with(['item', 'supplier'])
                                ->orderBy('stockin_date', 'desc')
                                ->take(10)
                                ->get();
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $recentStockIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stockIn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                <td style="padding: 0.75rem; font-weight: 600; color: #667eea;">
                                    #<?php echo e(str_pad($stockIn->stockin_id, 4, '0', STR_PAD_LEFT)); ?>

                                </td>
                                <td style="padding: 0.75rem;">
                                    <?php echo e(\Carbon\Carbon::parse($stockIn->stockin_date)->format('M d, Y')); ?>

                                </td>
                                <td style="padding: 0.75rem;">
                                    <div style="font-weight: 500;"><?php echo e($stockIn->item->name ?? 'N/A'); ?></div>
                                    <div style="font-size: 0.75rem; color: #9ca3af;"><?php echo e($stockIn->item->item_id ?? ''); ?></div>
                                </td>
                                <td style="padding: 0.75rem;"><?php echo e($stockIn->supplier->supplier_name ?? 'N/A'); ?></td>
                                <td style="padding: 0.75rem; text-align: center;">
                                    <span style="padding: 0.25rem 0.75rem; background: #d1fae5; color: #065f46; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;">
                                        +<?php echo e($stockIn->quantity); ?>

                                    </span>
                                </td>
                                <td style="padding: 0.75rem; text-align: right;">
                                    ₱<?php echo e(number_format($stockIn->unit_cost, 2)); ?>

                                </td>
                                <td style="padding: 0.75rem; text-align: right; font-weight: 600;">
                                    ₱<?php echo e(number_format($stockIn->quantity * $stockIn->unit_cost, 2)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" style="padding: 2rem; text-align: center; color: #9ca3af;">
                                    No stock in records yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Trend Chart (Last 7 Days)
    const salesTrendData = <?php echo json_encode($salesTrend, 15, 512) ?>;
    
    new Chart(document.getElementById('salesTrendChart'), {
        type: 'line',
        data: {
            labels: salesTrendData.map(d => d.date),
            datasets: [{
                label: 'Daily Sales (₱)',
                data: salesTrendData.map(d => d.amount),
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Monthly Sales Chart (Last 6 Months)
    const monthlySalesData = <?php echo json_encode($monthlySalesTrend, 15, 512) ?>;
    
    new Chart(document.getElementById('monthlySalesChart'), {
        type: 'bar',
        data: {
            labels: monthlySalesData.map(d => d.month),
            datasets: [{
                label: 'Monthly Sales (₱)',
                data: monthlySalesData.map(d => d.amount),
                backgroundColor: '#8b5cf6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Handle smooth transitions on filter form submission
    const filterForm = document.querySelector('.dashboard-filters form');
    const dashboardGrid = document.querySelector('.dashboard-grid');
    
    if (filterForm && dashboardGrid) {
        filterForm.addEventListener('submit', function(e) {
            // Add loading state
            dashboardGrid.classList.add('loading');
            
            // Store scroll position
            sessionStorage.setItem('dashboardScrollPos', window.scrollY);
        });
    }
    
    // Restore scroll position after page load
    const scrollPos = sessionStorage.getItem('dashboardScrollPos');
    if (scrollPos) {
        window.scrollTo(0, parseInt(scrollPos));
        sessionStorage.removeItem('dashboardScrollPos');
    }
    
    // Add smooth entry animation for data values
    const dmValues = document.querySelectorAll('.dm-value, .lr-val');
    dmValues.forEach((value, index) => {
        value.style.opacity = '0';
        value.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            value.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
            value.style.opacity = '1';
            value.style.transform = 'translateY(0)';
        }, 50 * index);
    });
    
    // Prevent layout shift on date input changes
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Debounce to prevent multiple rapid submissions
            clearTimeout(this.submitTimeout);
            this.submitTimeout = setTimeout(() => {
                // Auto-submit with smooth transition (optional)
                // Uncomment the line below to enable auto-submit on date change
                // filterForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            }, 300);
        });
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/dashboard/index.blade.php ENDPATH**/ ?>