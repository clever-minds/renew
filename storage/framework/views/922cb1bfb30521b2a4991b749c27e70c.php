<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RenewPilot - Modern SaaS Subscription Management</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo e(asset('favicon.svg')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between glass rounded-3xl px-8 py-4 shadow-sm border border-gray-100">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-bolt text-sm"></i>
                </div>
                <span class="text-xl font-black tracking-tight">RenewPilot</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">Home</a>
                <a href="#about" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">About</a>
                <a href="#features" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">Features</a>
                <a href="#pricing" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">Pricing</a>
                <a href="#contact" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition-colors">Contact</a>
                <div class="h-4 w-px bg-gray-200"></div>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(auth()->user()->is_super_admin ? route('admin.dashboard') : route('app.dashboard')); ?>" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="text-sm font-bold text-gray-900 hover:text-indigo-600 transition-colors">Login</a>
                    <a href="<?php echo e(route('register')); ?>" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all">Start Free</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="pt-40 pb-20 px-6 relative overflow-hidden" id="home">
        <div class="absolute top-0 -left-4 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        <div class="absolute top-0 -right-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-50 text-indigo-600 text-xs font-black uppercase tracking-widest mb-8 border border-indigo-100">
                    <span class="mr-2 flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    The Future of Agency Billing
                </div>
                <h1 class="text-6xl md:text-7xl font-black tracking-tight text-gray-900 mb-8">
                    Automate Your <span class="text-indigo-600">SaaS Renewals</span> Like a Pro.
                </h1>
                <p class="text-xl text-gray-500 font-medium leading-relaxed mb-12">
                    The all-in-one command center for agencies to manage subscriptions, automate recurring invoices, and scale MRR without the headache.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(auth()->user()->is_super_admin ? route('admin.dashboard') : route('app.dashboard')); ?>" class="w-full sm:w-auto px-10 py-5 bg-indigo-600 text-white rounded-[2rem] text-lg font-black shadow-2xl shadow-indigo-200 hover:bg-indigo-700 transition-all transform hover:-translate-y-1">
                            Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('register')); ?>" class="w-full sm:w-auto px-10 py-5 bg-indigo-600 text-white rounded-[2rem] text-lg font-black shadow-2xl shadow-indigo-200 hover:bg-indigo-700 transition-all transform hover:-translate-y-1">
                            Start Growing Free
                        </a>
                    <?php endif; ?>
                    <a href="#features" class="w-full sm:w-auto px-10 py-5 bg-white text-gray-900 rounded-[2rem] text-lg font-black border-2 border-gray-100 hover:border-indigo-100 transition-all">
                        Explore Features
                    </a>
                </div>
                
                <div class="mt-20 flex flex-wrap justify-center items-center gap-8 opacity-50 grayscale">
                    <span class="text-xl font-black tracking-tighter">STRIPE</span>
                    <span class="text-xl font-black tracking-tighter">PAYPAL</span>
                    <span class="text-xl font-black tracking-tighter">WISE</span>
                    <span class="text-xl font-black tracking-tighter">PADDLE</span>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section class="py-20 px-6 bg-white" id="about">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-4xl font-black tracking-tight text-gray-900 mb-4">About RenewPilot</h2>
            <p class="text-gray-500 font-medium max-w-2xl mx-auto">We are building the future of SaaS subscription management for agencies and service businesses. Our goal is to make billing, invoicing, and client management as effortless as possible.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-24 px-6 bg-indigo-50/50" id="features">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-4xl font-black tracking-tight text-gray-900 mb-4">Powerful Features</h2>
            <p class="text-gray-500 font-medium max-w-2xl mx-auto mb-16">Everything you need to run your agency efficiently and scale your revenue.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h3 class="text-xl font-black mb-4 text-gray-900">Automated Invoicing</h3>
                    <p class="text-gray-500 font-medium leading-relaxed">Send professional invoices automatically and get paid faster with our integrated payment gateways.</p>
                </div>
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl"><i class="fas fa-users"></i></div>
                    <h3 class="text-xl font-black mb-4 text-gray-900">Client Management</h3>
                    <p class="text-gray-500 font-medium leading-relaxed">Keep track of all your clients, their subscriptions, and payment history in one unified dashboard.</p>
                </div>
                <div class="bg-white p-10 rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 hover:-translate-y-2 transition-transform duration-300">
                    <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl"><i class="fas fa-chart-line"></i></div>
                    <h3 class="text-xl font-black mb-4 text-gray-900">Revenue Analytics</h3>
                    <p class="text-gray-500 font-medium leading-relaxed">Understand your MRR, churn rate, and growth metrics with detailed, actionable insights.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="py-32 px-6 bg-[#fafafa]" id="pricing">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black tracking-tight text-gray-900 mb-4">Simple, Transparent Pricing</h2>
                <p class="text-gray-500 font-medium">Choose the perfect plan for your agency's scale.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group bg-white p-12 rounded-[3rem] shadow-xl shadow-gray-200/50 border border-white hover:border-indigo-100 transition-all duration-500 flex flex-col relative overflow-hidden">
                    <?php if($loop->last): ?>
                        <div class="absolute top-8 right-[-35px] bg-indigo-600 text-white px-10 py-1 rotate-45 text-[10px] font-black uppercase tracking-widest">Popular</div>
                    <?php endif; ?>
                    
                    <h3 class="text-2xl font-black mb-2 text-gray-900"><?php echo e($plan->name); ?></h3>
                    <p class="text-gray-500 text-sm font-medium mb-8 leading-relaxed"><?php echo e($plan->description); ?></p>
                    
                    <div class="flex items-baseline gap-1 mb-10">
                        <span class="text-5xl font-black tracking-tighter text-gray-900">₹<?php echo e(number_format($plan->price, 0)); ?></span>
                        <span class="text-gray-400 font-bold uppercase tracking-widest text-xs">/ <?php echo e(str_replace('_', ' ', $plan->billing_cycle->value)); ?></span>
                    </div>
                    
                    <ul class="mb-12 space-y-4 flex-grow">
                        <?php if($plan->features): ?>
                            <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="flex items-center text-gray-600 font-medium">
                                <div class="w-6 h-6 bg-emerald-50 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-check text-[10px] text-emerald-500"></i>
                                </div>
                                <?php echo e($feature); ?>

                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </ul>

                    <a href="<?php echo e(route('register', ['plan' => $plan->slug])); ?>" 
                       class="block text-center py-5 rounded-[2rem] font-black text-sm uppercase tracking-widest transition-all
                       <?php echo e($loop->last ? 'bg-indigo-600 text-white shadow-xl shadow-indigo-100 hover:bg-indigo-700' : 'bg-gray-50 text-gray-900 hover:bg-gray-100'); ?>">
                        Get Started
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-20 px-6 bg-white border-t border-gray-100" id="contact">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center text-white">
                    <i class="fas fa-bolt text-sm"></i>
                </div>
                <span class="text-xl font-black tracking-tight text-gray-900">RenewPilot</span>
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">&copy; <?php echo e(date('Y')); ?> RenewPilot Inc. Built for winners.</p>
            <div class="flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors"><i class="fab fa-linkedin"></i></a>
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors"><i class="fab fa-github"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>

<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/welcome.blade.php ENDPATH**/ ?>