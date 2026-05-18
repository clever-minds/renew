<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RenewPilot</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        input:not([type="checkbox"]):not([type="radio"]), textarea, select { 
            color: #111827 !important; 
            background-color: #ffffff !important;
            border: 1px solid #d1d5db !important;
        }
        input:not([type="checkbox"]):not([type="radio"]):focus, textarea:focus, select:focus {
            border-color: #4f46e5 !important;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
            outline: none !important;
        }
    </style>
</head>
<body class="bg-[#fafafa] antialiased">
    <div class="min-h-screen flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Background Orbs -->
        <div class="absolute top-0 -left-4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

        <div class="max-w-lg w-full z-10">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-3xl shadow-xl shadow-indigo-200 mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">RenewPilot</h1>
                <p class="text-gray-500 mt-2 font-medium">Join 500+ agencies worldwide</p>
            </div>

            <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-indigo-100 p-10 border border-white">
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-gray-900">Start Your Journey</h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Scale your subscription business with confidence</p>
                </div>

                <!-- Validation Errors -->
                <?php if($errors->any()): ?>
                    <div class="mb-6 p-4 bg-red-50 rounded-2xl text-xs font-bold text-red-600 border border-red-100">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-2">
                                <span class="w-1 h-1 bg-red-400 rounded-full"></span>
                                <?php echo e($error); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('register')); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Agency Name</label>
                            <input id="company_name" type="text" name="company_name" value="<?php echo e(old('company_name')); ?>" required
                                   class="w-full px-5 py-4 rounded-2xl bg-white border border-gray-200 text-gray-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all outline-none font-medium text-sm placeholder-gray-400" placeholder="e.g. Acme Marketing">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Full Name</label>
                                <input id="name" type="text" name="name" value="<?php echo e(old('name')); ?>" required autofocus
                                       class="w-full px-5 py-4 rounded-2xl bg-white border border-gray-200 text-gray-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all outline-none font-medium text-sm placeholder-gray-400" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Email Address</label>
                                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required
                                       class="w-full px-5 py-4 rounded-2xl bg-white border border-gray-200 text-gray-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all outline-none font-medium text-sm placeholder-gray-400" placeholder="john@example.com">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Password</label>
                            <input id="password" type="password" name="password" required
                                   class="w-full px-5 py-4 rounded-2xl bg-white border border-gray-200 text-gray-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all outline-none font-medium text-sm placeholder-gray-400">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2 ml-1">Confirm</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   class="w-full px-5 py-4 rounded-2xl bg-white border border-gray-200 text-gray-900 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all outline-none font-medium text-sm placeholder-gray-400">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition-all transform active:scale-[0.98] shadow-xl shadow-indigo-100">
                        Create Free Account
                    </button>
                </form>

                <div class="mt-10 text-center">
                    <p class="text-sm text-gray-500 font-medium">
                        Already have an account? 
                        <a href="<?php echo e(route('login')); ?>" class="text-indigo-600 font-black hover:text-indigo-700 ml-1">Sign In</a>
                    </p>
                </div>
            </div>
            
            <p class="text-center text-[10px] text-gray-400 font-black uppercase tracking-[0.3em] mt-12 opacity-50">&copy; 2026 RenewPilot Inc.</p>
        </div>
    </div>
</body>
</html><?php /**PATH /var/www/html/ReNewPilot_new/resources/views/auth/register.blade.php ENDPATH**/ ?>