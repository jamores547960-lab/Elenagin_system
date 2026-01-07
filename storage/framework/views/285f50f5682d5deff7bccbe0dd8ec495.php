<?php $__env->startSection('title', 'Suppliers - SubWFour'); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="position: relative; margin-bottom: 24px;">
    <h2 class="text-accent" style="font-size: 1.75rem; font-weight: 700; margin: 0; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
        <i class="fas fa-truck"></i> SUPPLIERS
    </h2>
    <button type="button"
            class="btn btn-primary"
            data-action="register-supplier"
            style="position: absolute; top: 0; right: 0; display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2); transition: all 0.2s ease; white-space: nowrap;"
            onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.3)'"
            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(102, 126, 234, 0.2)'">
        <i class="fas fa-plus"></i> Add Supplier
    </button>
</div>

<div class="glass-card glass-card-wide" style="height: calc(100vh - 250px); display: flex; flex-direction: column;">

    <?php if(session('success')): ?>
        <div class="alert alert-success mb-3" style="background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(72, 187, 120, 0.05)); border-left: 4px solid #48bb78; border-radius: 10px; padding: 14px 18px; color: #2f855a; font-weight: 500; border: 1px solid rgba(72, 187, 120, 0.2);">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3" style="background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(245, 101, 101, 0.05)); border-left: 4px solid #f56565; border-radius: 10px; padding: 14px 18px; color: #c53030; font-weight: 500; border: 1px solid rgba(245, 101, 101, 0.2);">
            <i class="fas fa-exclamation-triangle"></i> Please fix the errors:
            <ul class="m-0 ps-3 mt-2" style="font-size:.85rem;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="toolbar-modern mb-4">
        <div class="search-bar-wrapper" style="flex:1;max-width:600px;">
            <form action="<?php echo e(route('suppliers.index')); ?>" method="GET" class="search-bar-modern" autocomplete="off">
                <span class="search-icon" style="color:#667eea;"><i class="fas fa-search"></i></span>
                <input type="text"
                       name="search"
                       value="<?php echo e(request('search')); ?>"
                       style="flex:1;border:none;outline:none;font-size:0.95rem;background:transparent;"
                       placeholder="Search supplier name, address, phone, contact...">
                <?php if(request('search')): ?>
                    <button type="button"
                            style="background:none;border:none;color:#667eea;cursor:pointer;padding:4px 8px;"
                            onclick="window.location='<?php echo e(route('suppliers.index')); ?>'">
                        <i class="fas fa-times"></i>
                    </button>
                <?php endif; ?>
                <button type="submit" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;border:none;padding:8px 20px;border-radius:8px;font-weight:600;cursor:pointer;transition:all 0.2s ease;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'"><i class="fas fa-search"></i> Search</button>
            </form>
            <div class="search-meta" style="margin-top:12px;display:flex;align-items:center;gap:12px;font-size:0.85rem;color:#718096;">
                <?php $total = $suppliers->count(); ?>
                <span style="font-weight:500;">
                    <i class="fas fa-list"></i> <?php echo e($total); ?> <?php echo e(\Illuminate\Support\Str::plural('result',$total)); ?>

                    <?php if(request('search')): ?> for <strong style="color:#667eea;">"<?php echo e(e(request('search'))); ?>"</strong> <?php endif; ?>
                </span>
                <?php if(request('search')): ?>
                    <span class="badge-modern badge-info" style="font-size:0.7rem;"><i class="fas fa-filter"></i> Filter active</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="table-responsive" style="flex: 1; overflow-y: auto; border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.05);">
        <table class="table table-modern align-middle" style="margin:0;">
            <thead>
                <tr>
                    <th><i class="fas fa-hashtag"></i> Supplier ID</th>
                    <th><i class="fas fa-building"></i> Name</th>
                    <th><i class="fas fa-map-marker-alt"></i> Address</th>
                    <th><i class="fas fa-phone"></i> Phone</th>
                    <th><i class="fas fa-user"></i> Contact Person</th>
                    <th style="width:160px;text-align:center;"><i class="fas fa-cog"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight:600;color:#667eea;">#<?php echo e($supplier->supplier_id); ?></td>
                    <td style="font-weight:600;color:#2d3748;"><?php echo e($supplier->name); ?></td>
                    <td style="color:#718096;"><?php echo e($supplier->address); ?></td>
                    <td style="color:#4a5568;"><?php echo e($supplier->number); ?></td>
                    <td style="color:#4a5568;"><?php echo e($supplier->contact_person); ?></td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="<?php echo e(route('suppliers.edit', $supplier->supplier_id)); ?>"
                               class="btn-action btn-action-edit" title="Edit Supplier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('suppliers.destroy', $supplier->supplier_id)); ?>"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-action btn-action-delete" title="Delete Supplier">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-5">
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="fas fa-truck"></i></div>
                        <div class="empty-state-title">No Suppliers Found</div>
                        <div class="empty-state-description">
                            <?php if(request('search')): ?>
                                No suppliers match your search. Try different keywords.
                            <?php else: ?>
                                Add your first supplier to start managing inventory sources.
                            <?php endif; ?>
                        </div>
                    </div>
                </td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div class="modal hidden" id="createSupplierModal" data-modal <?php if($errors->any()): ?> data-auto-open="true" <?php endif; ?>>
    <div class="modal-content" style="max-width:560px;">
        <h2 style="margin-bottom:14px;">Add Supplier</h2>
        <form action="<?php echo e(route('suppliers.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="form-row">
                <div class="form-group" style="flex:1 0 100%;">
                    <label>Supplier Name</label>
                    <input name="name" class="form-input" required value="<?php echo e(old('name')); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group" style="flex:1 0 100%;">
                    <label>Address</label>
                    <input name="address" class="form-input" required value="<?php echo e(old('address')); ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Contact Person</label>
                    <input name="contact_person" class="form-input" required value="<?php echo e(old('contact_person')); ?>">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input name="number" class="form-input" required value="<?php echo e(old('number')); ?>">
                </div>
            </div>

            <div class="button-row" style="margin-top:18px; display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn-secondary" data-close>Cancel</button>
                <button type="submit" class="btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/suppliers/index.blade.php ENDPATH**/ ?>