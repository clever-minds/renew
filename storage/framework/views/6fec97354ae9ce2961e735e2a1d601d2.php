<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RenewPilot - SaaS Subscription Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Hero Section -->
    <header class="bg-indigo-700 text-white py-20 px-4">
        <div class="max-w-7xl mx-auto flex flex-col items-center text-center">
            <h1 class="text-5xl font-extrabold mb-6">Automate Your SaaS Renewals</h1>
            <p class="text-xl text-indigo-100 mb-10 max-w-2xl">
                The ultimate platform for agencies to manage client subscriptions, automate invoicing, and track recurring revenue.
            </p>
            <div class="space-x-4">
                <a href="<?php echo e(route('register')); ?>" class="bg-white text-indigo-700 px-8 py-3 rounded-lg font-bold hover:bg-indigo-50 transition">Get Started Free</a>
                <a href="<?php echo e(route('login')); ?>" class="bg-indigo-600 text-white border border-indigo-500 px-8 py-3 rounded-lg font-bold hover:bg-indigo-500 transition">Login</a>
            </div>
        </div>
    </header>

    <!-- Pricing Section -->
    <section class="py-20 px-4" id="pricing">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-12">Flexible Pricing for Every Agency</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 flex flex-col">
                    <h3 class="text-2xl font-bold mb-2"><?php echo e($plan->name); ?></h3>
                    <p class="text-gray-500 mb-6"><?php echo e($plan->description); ?></p>
                    <div class="text-4xl font-extrabold mb-6">$<?php echo e(number_format($plan->price, 2)); ?><span class="text-lg text-gray-400 font-normal">/<?php echo e($plan->billing_cycle->value); ?></span></div>
                    
                    <ul class="mb-8 flex-grow space-y-3">
                        <?php if($plan->features): ?>
                            <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <?php echo e($feature); ?>

                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>

                    <a href="<?php echo e(route('register', ['plan' => $plan->slug])); ?>" class="block text-center bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                        Select Plan
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-12 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <p>&copy; <?php echo e(date('Y')); ?> RenewPilot. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/welcome.blade.php ENDPATH**/ ?>