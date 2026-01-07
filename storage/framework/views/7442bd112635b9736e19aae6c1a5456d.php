<?php use Illuminate\Support\Facades\Auth; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title','System - TITLE'); ?></title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link href="<?php echo e(asset('css/system.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(asset('css/pages.css')); ?>" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php echo $__env->yieldContent('head'); ?>
    <script src="<?php echo e(asset('js/system.js')); ?>" defer></script>
</head>

<body class="<?php echo e(session('first_login') ? 'fade-in' : ''); ?>">

    <?php
        session()->forget('first_login');
        $user = Auth::user();  // ← FIXED (Auth now properly imported)
        $profilePicture = $user->name === 'Admin' ? 'AdminProfile.png' : 'default-profile.jpg';
    ?>

<div class="sidebar modern-sidebar" id="sidebar">
    <div class="sidebar-logo-modern">
        <div class="logo-container">
            <img src="<?php echo e(asset('images/inventory.png')); ?>" alt="Elenagin Logo" class="logo-img" style="display: block !important; visibility: visible !important; opacity: 1 !important;">
            <div class="logo-text">
                <span class="logo-title">Elenagin</span>
                <span class="logo-subtitle">Management System</span>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul class="nav-list">
            
            <?php if(in_array($user->role, ['admin', 'cashier'])): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('system')); ?>" class="nav-link <?php echo e(request()->routeIs('system') ? 'active' : ''); ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="<?php echo e(route('stock_in.index')); ?>" class="nav-link <?php echo e(request()->routeIs('stock_in.*') ? 'active' : ''); ?>">
                    <i class="fas fa-arrow-down"></i>
                    <span>Stock-In</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('inventory.index')); ?>" class="nav-link <?php echo e(request()->routeIs('inventory.index') ? 'active' : ''); ?>">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?php echo e(route('suppliers.index')); ?>" class="nav-link <?php echo e(request()->routeIs('suppliers.*') ? 'active' : ''); ?>">
                    <i class="fas fa-truck"></i>
                    <span>Suppliers</span>
                </a>
            </li>
            <?php if($user->role === 'admin'): ?>
                <li class="nav-item">
                    <a href="<?php echo e(route('spoilage.index')); ?>" class="nav-link <?php echo e(request()->routeIs('spoilage.*') ? 'active' : ''); ?>">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Spoilage</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                        <i class="fas fa-chart-line"></i>
                        <span>Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo e(route('employees.index')); ?>" class="nav-link <?php echo e(request()->routeIs('employees.*') ? 'active' : ''); ?>">
                        <i class="fas fa-users"></i>
                        <span>Employees</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <div class="user-badge">
            <i class="fas fa-user-circle"></i>
            <div class="user-info">
                <span class="user-name"><?php echo e($user->name); ?></span>
                <span class="user-role"><?php echo e(ucfirst($user->role)); ?></span>
            </div>
        </div>
    </div>
</div>

<div class="header">
    <button class="toggle-btn" type="button" data-toggle="sidebar"><i class="fas fa-bars"></i></button>
    <h1>Elenagin System</h1>

    <div class="user-profile" id="userProfile">
        <span>Welcome, <?php echo e($user->name); ?>!</span>
        <div class="profile-picture" id="profileTrigger">
            <img src="<?php echo e($user->role === 'cashier'
                ? asset('images/kerk.jpg')
                : asset('images/kerk.jpg' . $profilePicture)); ?>" alt="Profile Picture">
        </div>

        <div class="dropdown-menu hidden" id="dropdownMenu" data-dropdown-menu>
            <button class="dropdown-item" data-action="view-profile">View Profile</button>
            <?php if($user->role === 'admin'): ?>
                <a href="<?php echo e(route('employees.index')); ?>" class="dropdown-item">View Employees</a>
                <button class="dropdown-item" data-action="register-employee">Register Employee</button>
            <?php endif; ?>
            <form action="<?php echo e(route('logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-btn dropdown-item">Log-Out</button>
            </form>

        </div>

        <div class="modal hidden" id="viewProfileModal" data-modal>
            <div class="modal-content">
                <h2>Profile</h2>
                <p><strong>Name:</strong> <?php echo e($user->name); ?></p>
                <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                <p><strong>Role:</strong> <?php echo e(ucfirst($user->role)); ?></p>
                <button class="close-btn" data-close>Close</button>
            </div>
        </div>

        <?php if($user->role === 'admin'): ?>
        <div class="modal hidden" id="createEmployeeModal" data-modal>
            <div class="modal-content" style="max-width:640px;">
                <h2 style="margin-bottom:14px;">Register Employee</h2>

                <?php if($errors->any() && url()->current() === route('system')): ?>
                    <div class="alert alert-danger mb-2">
                        <ul class="m-0 ps-3" style="font-size:.7rem;">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($e); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <?php if(session('success') && url()->current() === route('system')): ?>
                    <div class="alert alert-success mb-2"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                <form action="<?php echo e(route('employees.store')); ?>" method="POST" enctype="multipart/form-data" id="employeeCreateForm">
                    <?php echo csrf_field(); ?>

                    <h4 class="section-heading" style="margin:10px 0 8px;">Account</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" class="form-input" required value="<?php echo e(old('name')); ?>">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input name="email" type="email" class="form-input" required value="<?php echo e(old('email')); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Password</label>
                            <input name="password" type="password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label>Confirm</label>
                            <input name="password_confirmation" type="password" class="form-input" required>
                        </div>
                    </div>

                    <div class="form-row" style="margin-bottom:10px;">
                        <div class="form-group" style="flex:1 0 100%;">
                            <label>Role</label>
                            <select name="role" class="form-input" required>
                            <option value="cashier" <?php echo e(old('role') === 'cashier' ? 'selected' : ''); ?>>Cashier</option>
                            <option value="employee" <?php echo e(old('role') === 'employee' ? 'selected' : ''); ?>>Employee (Inventory User)</option>
                        </select>

                        </div>
                    </div>

                    <h4 class="section-heading" style="margin:14px 0 8px;">Information</h4>
                    <div class="form-row">
                        <div class="form-group">
                            <label>First Name</label>
                            <input name="first_name" class="form-input" required value="<?php echo e(old('first_name')); ?>">
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input name="last_name" class="form-input" required value="<?php echo e(old('last_name')); ?>">
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
                            <label>Contact #</label>
                            <input name="contact_number" class="form-input" required value="<?php echo e(old('contact_number')); ?>">
                        </div>
                        <div class="form-group">
                            <label>SSS #</label>
                            <input name="sss_number" class="form-input" required value="<?php echo e(old('sss_number')); ?>">
                        </div>
                    </div>

                    <div class="form-row" style="margin-top:10px;">
                        <div class="form-group" style="flex:1 0 100%;">
                            <label>Profile Picture (optional)</label>
                            <input type="file" name="profile_picture" accept="image/*" class="form-input" id="createProfileInput">
                            <div id="createProfilePreview" style="margin-top:6px; display:none;">
                                <img src="" alt="Preview" style="height:60px;width:60px;border-radius:10px;object-fit:cover;border:1px solid var(--gray-300);">
                            </div>
                        </div>
                    </div>

                    <div class="button-row" style="margin-top:18px; display:flex; gap:10px; justify-content:flex-end;">
                        <button type="button" class="btn-secondary" data-close>Cancel</button>
                        <button type="submit" class="btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="main-content">
    <?php echo $__env->yieldContent('content'); ?>
</div>

<div class="footer">
    <p>&copy; Elenagin. All rights reserved.</p>
</div>

<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/system.blade.php ENDPATH**/ ?>