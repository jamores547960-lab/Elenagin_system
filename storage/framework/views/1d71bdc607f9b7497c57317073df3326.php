<?php $__env->startSection('title', 'Edit Item - TITLE'); ?>

<?php $__env->startSection('head'); ?>
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<h2 class="text-accent">EDIT ITEM</h2>

<div class="glass-card" style="max-width:900px;margin:0 auto;">

    <?php if(session('success')): ?>
        <div class="alert alert-success mb-3"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger mb-3">
            <ul class="m-0 ps-3" style="font-size:.7rem;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?php echo e(route('inventory.update',$item->item_id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="form-row">
            <div class="form-group" style="flex:1 0 55%;">
                <label>Name</label>
                <input name="name" class="form-input" required value="<?php echo e(old('name',$item->name)); ?>">
            </div>
            <div class="form-group">
                <label>Category</label>
                <select name="itemctgry_id" class="form-input" style="width:100%;" required>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->itemctgry_id); ?>"
                            <?php if(old('itemctgry_id',$item->itemctgry_id)==$cat->itemctgry_id): echo 'selected'; endif; ?>>
                            <?php echo e($cat->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="active" class="form-input" style="width:100%;" >
                    <option value="1" <?php if(old('active',$item->active)): echo 'selected'; endif; ?>>Active</option>
                    <option value="0" <?php if(!old('active',$item->active)): echo 'selected'; endif; ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Quantity</label>
                <input name="quantity" type="number" min="0" class="form-input"
                       value="<?php echo e(old('quantity',$item->quantity)); ?>">
            </div>
            <div class="form-group">
                <label>Unit Price</label>
                <input name="unit_price" type="number" step="0.01" min="0" class="form-input"
                       required value="<?php echo e(old('unit_price',$item->unit_price)); ?>">
            </div>
            <div class="form-group">
                <label>Unit</label>
                <input name="unit" class="form-input" value="<?php echo e(old('unit',$item->unit)); ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group" style="flex:1 0 100%;">
                <label>Description</label>
                <textarea name="description" rows="3" class="form-input" style="resize:vertical; width:100%;"><?php echo e(old('description',$item->description)); ?></textarea>
            </div>
        </div>
        <div class="button-row" style="margin-top:18px;display:flex;gap:10px;justify-content:flex-end;">
            <a href="<?php echo e(route('inventory.index')); ?>" class="btn-secondary">Back</a>
            <button type="submit" class="btn-primary">Update</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('system', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/inventory/edit.blade.php ENDPATH**/ ?>