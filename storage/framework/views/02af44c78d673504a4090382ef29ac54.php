<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RenewPilot</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <a href="/" class="text-3xl font-extrabold text-indigo-600">RenewPilot</a>
                <h2 class="text-xl font-bold text-gray-800 mt-4">Welcome Back</h2>
                <p class="text-gray-500">Log in to manage your agency subscriptions</p>
            </div>

            <!-- Session Status -->
            <?php if(session('status')): ?>
                <div class="mb-4 font-medium text-sm text-green-600 text-center">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="mb-4 text-sm text-red-600 list-disc list-inside bg-red-50 p-3 rounded-lg">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div><?php echo e($error); ?></div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                </div>

                <div>
                    <div class="flex justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" class="text-sm text-indigo-600 hover:underline">Forgot password?</a>
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition">
                </div>

                <div class="flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-600">Remember me</label>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition transform active:scale-95 shadow-lg shadow-indigo-200">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-500">
                Don't have an account? 
                <a href="<?php echo e(route('register')); ?>" class="text-indigo-600 font-bold hover:underline">Sign up for free</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\ReNewPilot_new\ReNewPilot\resources\views/auth/login.blade.php ENDPATH**/ ?>