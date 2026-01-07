<?php $__env->startSection('title', 'Employees - TITLE'); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-users"></i> EMPLOYEES
    </h2>

</div>


<div class="glass-card glass-card-wide" style="height: calc(100vh - 250px); display: flex; flex-direction: column;">

    <?php if(session('success')): ?>
        <div class="alert alert-success mb-3" style="background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(72, 187, 120, 0.05)); border-left: 4px solid #48bb78; border-radius: 10px; padding: 14px 18px; color: #2f855a; font-weight: 500;">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger mb-3" style="background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(245, 101, 101, 0.05)); border-left: 4px solid #f56565; border-radius: 10px; padding: 14px 18px; color: #c53030; font-weight: 500;">
            <i class="fas fa-exclamation-triangle"></i> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="toolbar-modern mb-4">

        <div class="search-bar-wrapper" style="flex: 1; max-width: 600px;">
            <form action="<?php echo e(route('employees.index')); ?>" method="GET" class="search-bar-modern" autocomplete="off">
                <span class="search-icon" style="color: #667eea;">
                    <i class="fas fa-search"></i>
                </span>
                <input
                    id="employeeSearch"
                    type="text"
                    name="search"
                    value="<?php echo e(request('search')); ?>"
                    placeholder="Search employee name, email, contact or SSS..."
                    style="flex: 1; border: none; outline: none; font-size: 0.95rem; background: transparent;"
                    aria-label="Search employees">
                <?php if(request('search')): ?>
                    <button type="button"
                            style="background: none; border: none; color: #667eea; cursor: pointer; padding: 4px 8px; transition: all 0.2s;"
                            title="Clear search"
                            onclick="window.location='<?php echo e(route('employees.index')); ?>'">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
                <button type="submit" style="background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 8px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>

            <div class="search-meta" style="margin-top: 12px; display: flex; align-items: center; gap: 12px; font-size: 0.85rem; color: #718096;">
                <?php
                    $total = method_exists($employees,'total') ? $employees->total() : $employees->count();
                ?>
                <span style="font-weight: 500;">
                    <i class="fas fa-list"></i> <?php echo e($total); ?> <?php echo e(\Illuminate\Support\Str::plural('result', $total)); ?>

                    <?php if(request('search')): ?>
                        for <strong style="color: #667eea;">"<?php echo e(e(request('search'))); ?>"</strong>
                    <?php endif; ?>
                </span>
                <?php if(request('search')): ?>
                    <span class="badge-modern badge-info" style="font-size: 0.7rem;">
                        <i class="fas fa-filter"></i> Filter active
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive" style="flex: 1; overflow-y: auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
        <table class="table table-modern align-middle" style="margin: 0;">
            <thead>
                <tr>
                    <th style="width:70px;"><i class="fas fa-user-circle"></i></th>
                    <th><i class="fas fa-id-badge"></i> Employee Name</th>
                    <th><i class="fas fa-envelope"></i> Email</th>
                    <th><i class="fas fa-phone"></i> Contact Number</th>
                    <th><i class="fas fa-award"></i> Role</th>
                    <th style="width:130px; text-align: center;"><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $email = $employee->user->email ?? '—';
                    $role  = $employee->user->role ?? '—';
                    $profile = $employee->profile_picture
                        ? 'storage/'.$employee->profile_picture
                        : 'images/TCEmployeeProfile.png';
                ?>

                <tr>
                    <td>
                        <div class="avatar-modern">
                            <img src="<?php echo e(asset($profile)); ?>" alt="Profile">
                        </div>
                    </td>
                    <td style="font-weight: 600; color: #2d3748;"><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></td>
                    <td style="color: #667eea;"><?php echo e($email); ?></td>
                    <td style="color: #4a5568;"><?php echo e($employee->contact_number ?? '—'); ?></td>
                    <td>
                        <span class="badge-modern badge-info" style="text-transform: capitalize;">
                            <?php echo e($role); ?>

                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="#" class="btn-action btn-action-view" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="#" class="btn-action btn-action-edit" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="empty-state-title">No Employees Found</div>
                            <div class="empty-state-description">
                                <?php if(request('search')): ?>
                                    No results match your search criteria. Try different keywords.
                                <?php else: ?>
                                    Start by adding your first employee to the system.
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <?php if(method_exists($employees,'links')): ?>
        <div class="mt-3 pagination-wrapper">
            <?php echo e($employees->appends(['search'=>request('search')])->links()); ?>

        </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/employees/index.blade.php ENDPATH**/ ?>