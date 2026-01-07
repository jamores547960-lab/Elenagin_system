<?php $__env->startSection('title','Reports - TITLE'); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet">
    <style>
        .pagination-wrapper nav ul { justify-content: center; }
        .pagination-wrapper nav { display: flex; justify-content: center; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="position: relative; margin-bottom: 24px;">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-chart-bar"></i> REPORTS
    </h2>
    <div style="position: absolute; top: 0; right: 0; display: flex; gap: 10px;">
        <a href="<?php echo e(route('stock_out.index')); ?>"
           style="display: inline-flex; align-items: center; gap: 6px; background: #ffffff; border: 2px solid rgba(102, 126, 234, 0.2); color: #667eea; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; text-decoration: none; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04); transition: all 0.2s ease; white-space: nowrap;"
           onmouseover="this.style.borderColor='#667eea';this.style.boxShadow='0 4px 12px rgba(102,126,234,0.15)'"
           onmouseout="this.style.borderColor='rgba(102,126,234,0.2)';this.style.boxShadow='0 2px 8px rgba(0,0,0,0.04)'">
            <i class="fas fa-arrow-up"></i> Stock-Out Records
        </a>
        <a href="<?php echo e(route('reports.automated')); ?>"
           style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; text-decoration: none; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2); transition: all 0.2s ease; white-space: nowrap;"
           onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(102,126,234,0.3)'"
           onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 8px rgba(102,126,234,0.2)'">
            <i class="fas fa-robot"></i> Automated Reports
        </a>
    </div>
</div>

<!--<div class="metrics-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin-bottom:18px;">
    <div class="metric-card"><div class="metric-label">Appointments (Month)</div><div class="metric-value"><?php echo e($appointmentsThisMonth); ?></div></div>
    <div class="metric-card"><div class="metric-label">Avg App / Day</div><div class="metric-value"><?php echo e($avgAppointmentsPerDay); ?></div></div>
    <div class="metric-card"><div class="metric-label">Services Completed</div><div class="metric-value"><?php echo e($servicesCompletedMonth); ?></div></div>
    <div class="metric-card"><div class="metric-label">Items Added</div><div class="metric-value"><?php echo e($itemsAddedMonth); ?></div></div>
    <div class="metric-card">
        <div class="metric-label">Top Items Used</div>
        <div class="metric-mini-list">
            <?php $__empty_1 = true; $__currentLoopData = $topItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ti): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div>#<?php echo e($ti->item_id); ?> <span><?php echo e($ti->uses); ?></span></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="opacity:.6;">None</div>
            <?php endif; ?>
        </div>
    </div>
</div>-->
<div class="glass-card glass-card-wide">
    <form id="reportsFilterForm" method="GET"
        style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:12px;align-items:end;">

        <div>
            <label class="filter-label">From</label>
            <input type="date" name="date_from" value="<?php echo e($dateFrom); ?>" class="form-input" style="width:100%;">
        </div>
        <div>
            <label class="filter-label">To</label>
            <input type="date" name="date_to" value="<?php echo e($dateTo); ?>" class="form-input" style="width:100%;">
        </div>
        <div>
            <label class="filter-label">User</label>
            <select name="user_id" class="form-input" style="width:100%;">
                <option value="">All</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>" <?php if($userId==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div>
            <label class="filter-label">Event</label>
            <select name="event_type" class="form-input" style="width:100%;">
                <option value="">All</option>
                <?php $__currentLoopData = $eventTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $et): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($et); ?>" <?php if($event===$et): echo 'selected'; endif; ?>><?php echo e($et); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </form>
    <form method="GET" style="margin-bottom:12px;">
        <div>
            <label class="filter-label">Search</label>
            <input type="text" name="search" value="<?php echo e($search); ?>" class="form-input" style="width:100%;" placeholder="Desc / event / subject id">
        </div>
    </form>
    <div style="display:flex;gap:10px;margin-bottom:10px;">
        <button class="btn btn-primary"
                style="flex:1;display:flex;justify-content:center;align-items:center;"
                onclick="document.getElementById('reportsFilterForm').submit(); return false;">
            Apply
        </button>
        <a href="<?php echo e(route('reports.index')); ?>"
        class="btn btn-secondary"
        style="flex:1;display:flex;justify-content:center;align-items:center;">
            Reset
        </a>
    </div>

    <div class="table-responsive">
        <table class="table compact">
            <thead>
            <tr>
                <th>Time</th>
                <th>User</th>
                <th>Subject</th>
                <th>Description</th>
            </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="white-space:nowrap;"><?php echo e($log->occurred_at->format('Y-m-d H:i:s')); ?></td>
                    <td><?php echo e($log->user?->name ?? '—'); ?></td>
                    <td>
                        <?php if($log->subject_type): ?>
                            <?php echo e(class_basename($log->subject_type)); ?> #<?php echo e($log->subject_id); ?>

                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td style="max-width:240px;text-align:left;"><?php echo e($log->description); ?></td>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="empty-row text-center">No activity.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($logs->hasPages()): ?>
        <div class="mt-2 pagination-wrapper" style="display:flex;flex-direction:column;align-items:center;gap:4px;">
            <div style="font-size:.65rem;opacity:.75;">
                Page <?php echo e($logs->currentPage()); ?> of <?php echo e($logs->lastPage()); ?>

            </div>
            <div style="display:flex;justify-content:center;">
                <?php echo e($logs->onEachSide(1)->links()); ?>

            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/reports/index.blade.php ENDPATH**/ ?>