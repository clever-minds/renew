<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'RenewPilot')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind/Alpine (Fallback CDN for local dev without node, use Vite in prod) -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen">
            
            <!-- Navbar -->
            <nav class="bg-indigo-600 border-b border-indigo-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <a href="<?php echo e(route('app.dashboard')); ?>" class="text-white font-bold text-xl">
                                RenewPilot
                            </a>
                            <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                                <?php if(auth()->check() && auth()->user()->is_super_admin): ?>
                                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.dashboard') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">SaaS Dashboard</a>
                                    <a href="<?php echo e(route('admin.tenants')); ?>" class="text-indigo-100 hover:text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('admin.tenants*') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">Agencies</a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('app.dashboard')); ?>" class="text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('app.dashboard') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">Dashboard</a>
                                    <a href="<?php echo e(route('app.clients.index')); ?>" class="text-indigo-100 hover:text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('app.clients*') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">Clients</a>
                                    <a href="<?php echo e(route('app.services.index')); ?>" class="text-indigo-100 hover:text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('app.services*') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">Services</a>
                                    <a href="<?php echo e(route('app.subscriptions.index')); ?>" class="text-indigo-100 hover:text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('app.subscriptions*') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">Subscriptions</a>
                                    <a href="<?php echo e(route('app.invoices.index')); ?>" class="text-indigo-100 hover:text-white inline-flex items-center px-1 pt-1 border-b-2 <?php echo e(request()->routeIs('app.invoices*') ? 'border-white' : 'border-transparent hover:border-white'); ?> text-sm font-medium">Invoices</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-indigo-100 hover:text-white text-sm font-medium">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <?php if(isset($header)): ?>
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <?php echo e($header); ?>

                    </div>
                </header>
            <?php endif; ?>

            <!-- Flash Messages -->
            <?php if(session('success')): ?>
                <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                        <p><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        <p><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <main>
                <?php echo e($slot); ?>

            </main>
        </div>
    </body>
</html>
<?php /**PATH /var/www/renew.usamasheth.in/resources/views/layouts/app.blade.php ENDPATH**/ ?>