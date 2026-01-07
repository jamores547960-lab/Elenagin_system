<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Elenagin</title>
  <link rel="stylesheet" href="<?php echo e(asset('css/login.css')); ?>">
</head>
<body>
  <div class="login-wrapper">
    <div class="login-box">
      <div class="login-header">
        <h1>Login</h1>
      </div>

      <form action="<?php echo e(route('login.post')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <label for="name">Login:</label>
        <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
      </form>

      <?php if($errors->any()): ?>
            <div style="color: #ffb3b3; font-size: 13px; margin-top: 10px;">
                <ul style="padding-left: 20px;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

     <!-- <div class="login-footer">
        <img src="<?php echo e(asset('images/Sample.png')); ?>" alt="Morel Logo">
      </div>-->
    </div>
  </div>
</body> 
</html><?php /**PATH C:\Users\Sydney Jagape\kerk\resources\views/login/login.blade.php ENDPATH**/ ?>