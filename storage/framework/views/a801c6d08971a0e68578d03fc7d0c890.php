<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'RenewPilot')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- DataTables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <style>
            /* Custom DataTables Styling to match theme */
            .dataTables_wrapper .dataTables_length select,
            .dataTables_wrapper .dataTables_filter input {
                border: 1px solid #e5e7eb !important;
                border-radius: 0.75rem !important;
                padding: 0.4rem 0.8rem !important;
                outline: none !important;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: #4f46e5 !important;
                color: white !important;
                border: none !important;
                border-radius: 0.5rem !important;
            }
            table.dataTable { border-collapse: collapse !important; border: none !important; }
            table.dataTable thead th { border-bottom: 1px solid #f3f4f6 !important; background: #f9fafb !important; }
            
            /* Custom Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            
            .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); }
            .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }
        </style>

        <!-- Tailwind/Alpine -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            primary: {
                                50: '#f0f9ff',
                                100: '#e0f2fe',
                                200: '#bae6fd',
                                300: '#7dd3fc',
                                400: '#38bdf8',
                                500: '#0ea5e9',
                                600: '#0284c7',
                                700: '#0369a1',
                                800: '#075985',
                                900: '#0c4a6e',
                            },
                        }
                    }
                }
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
            .sidebar-link-active {
                background-color: rgba(255, 255, 255, 0.1);
                color: white !important;
                border-left: 4px solid white;
            }
            /* Global Card Depth */
            .bg-white.rounded-2xl, .bg-white.rounded-3xl, .bg-white.rounded-\[2\.5rem\], .form-card {
                background: #ffffff !important;
                border: 1px solid #e5e7eb !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.04), 0 4px 6px -4px rgba(0, 0, 0, 0.02) !important;
                transition: all 0.3s ease;
            }

            .bg-white.rounded-2xl:hover, .bg-white.rounded-3xl:hover {
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.06), 0 8px 10px -6px rgba(0, 0, 0, 0.04) !important;
                border-color: #d1d5db !important;
            }

            /* Premium Input Highlighting */
            input:not([type="checkbox"]):not([type="radio"]), textarea, select { 
                color: #111827 !important; 
                background-color: #ffffff !important;
                border: 1px solid #d1d5db !important;
                border-radius: 0.75rem !important;
                padding: 0.6rem 0.8rem !important;
                transition: all 0.2s ease !important;
            }

            input:not([type="checkbox"]):not([type="radio"]):focus, textarea:focus, select:focus {
                border-color: #4f46e5 !important;
                box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important;
                background-color: #fff !important;
                outline: none !important;
            }
        </style>
    </head>
    <body class="h-full font-sans antialiased text-gray-900">
        <div x-data="{ sidebarOpen: false }" class="min-h-full lg:flex">
            
            <!-- Mobile Sidebar Backdrop -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="transition-opacity ease-linear duration-300" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden" x-cloak></div>

            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                 class="fixed inset-y-0 left-0 w-64 bg-indigo-700 text-white transform transition-transform duration-300 ease-in-out z-50 lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:flex-none">
                
                <div class="flex flex-col h-full">
                    <!-- Logo Area -->
                    <div class="flex items-center justify-between h-16 px-6 bg-indigo-800">
                        <a href="<?php echo e(route('app.dashboard')); ?>" class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center">
                                <i class="fas fa-paper-plane text-indigo-700"></i>
                            </div>
                            <span class="font-bold text-xl tracking-tight">RenewPilot</span>
                        </a>
                        <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto sidebar-scroll">
                        <?php if(auth()->check() && auth()->user()->is_super_admin): ?>
                            <div class="px-3 mb-4">
                                <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 opacity-50">SaaS Management</p>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                    <i class="fas fa-chart-line w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                    SaaS Dashboard
                                </a>
                                <a href="<?php echo e(route('admin.tenants')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('admin.tenants*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                    <i class="fas fa-building w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                    Agencies
                                </a>
                            </div>

                            <div class="px-3 pt-6 border-t border-indigo-600/30">
                                <p class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 opacity-50">System</p>
                                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group opacity-50 cursor-not-allowed">
                                    <i class="fas fa-server w-6 text-indigo-300"></i>
                                    System Logs
                                </a>
                            </div>
                        <?php else: ?>
                            <p class="px-3 text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 opacity-50">Main Menu</p>
                            <a href="<?php echo e(route('app.dashboard')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?php echo e(request()->routeIs('app.dashboard') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                <i class="fas fa-th-large w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                Dashboard
                            </a>
                            <a href="<?php echo e(route('app.clients.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('app.clients*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                <i class="fas fa-users w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                Clients
                            </a>
                            <a href="<?php echo e(route('app.services.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('app.services*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                <i class="fas fa-concierge-bell w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                Services
                            </a>
                            <a href="<?php echo e(route('app.subscriptions.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('app.subscriptions*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                <i class="fas fa-calendar-alt w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                Subscriptions
                            </a>
                            <a href="<?php echo e(route('app.invoices.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('app.invoices*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                <i class="fas fa-file-invoice-dollar w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                Invoices
                            </a>
                            <a href="<?php echo e(route('app.payments.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('app.payments*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                <i class="fas fa-money-bill-wave w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                Payments
                            </a>
                            
                            <div class="pt-8">
                                <p class="px-3 text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 opacity-50">Analytics</p>
                                <a href="<?php echo e(route('app.reports.revenue')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?php echo e(request()->routeIs('app.reports.revenue') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                    <i class="fas fa-chart-pie w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                    Revenue Report
                                </a>
                                <a href="<?php echo e(route('app.reports.subscriptions')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group mt-1 <?php echo e(request()->routeIs('app.reports.subscriptions') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                    <i class="fas fa-sync w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                    Sub Activity
                                </a>
                            </div>

                            <div class="pt-8">
                                <p class="px-3 text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em] mb-4 opacity-50">Configuration</p>
                                <a href="<?php echo e(route('app.settings.index')); ?>" class="flex items-center px-3 py-2.5 text-sm font-bold rounded-xl text-indigo-100 hover:bg-white/10 hover:text-white transition-all group <?php echo e(request()->routeIs('app.settings*') ? 'bg-white/15 text-white shadow-sm' : ''); ?>">
                                    <i class="fas fa-cog w-6 text-indigo-300 group-hover:text-white transition-colors"></i>
                                    Settings
                                </a>
                            </div>
                        <?php endif; ?>
                    </nav>


                    <!-- User Footer -->
                    <div class="p-4 border-t border-indigo-600">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="flex items-center w-full px-3 py-2 text-sm font-medium text-indigo-100 hover:bg-white/10 hover:text-white rounded-lg transition-colors">
                                <i class="fas fa-sign-out-alt w-6"></i>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Top Header -->
                <header class="bg-white border-b border-gray-200 flex items-center justify-between h-16 px-4 lg:px-8">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-gray-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="ml-4 lg:ml-0 text-xl font-semibold text-gray-800">
                            <?php echo e($header ?? 'Dashboard'); ?>

                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="hidden sm:block">
                            <p class="text-sm font-medium text-gray-700"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(auth()->user()->email); ?></p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border-2 border-indigo-500">
                            <?php echo e(substr(auth()->user()->name, 0, 1)); ?>

                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 py-8 px-4 lg:px-8 overflow-y-auto">
                    <!-- Notifications -->
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-init="setTimeout(() => show = false, 5000)"
                         class="fixed top-4 right-4 z-[100] max-w-sm w-full">
                        <?php if(session('success')): ?>
                            <div class="bg-white border-l-4 border-green-500 shadow-xl rounded-lg p-4 mb-4 flex items-start space-x-3 transition-all transform animate-bounce-short">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Success</p>
                                    <p class="text-xs text-gray-600 mt-1"><?php echo e(session('success')); ?></p>
                                </div>
                                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <?php if(session('error') || session('warning')): ?>
                            <div class="bg-white border-l-4 border-red-500 shadow-xl rounded-lg p-4 mb-4 flex items-start space-x-3 transition-all transform">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Attention</p>
                                    <p class="text-xs text-gray-600 mt-1"><?php echo e(session('error') ?? session('warning')); ?></p>
                                </div>
                                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const handleAjax = async (url, containerId = 'ajax-container') => {
                    const container = document.getElementById(containerId);
                    if (!container) return;

                    container.style.opacity = '0.5';
                    container.style.pointerEvents = 'none';

                    try {
                        const response = await fetch(url, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        });
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        const newContent = doc.getElementById(containerId);
                        if (newContent) {
                            container.innerHTML = newContent.innerHTML;
                            window.history.pushState({}, '', url);
                        }
                    } catch (error) {
                        console.error('AJAX Error:', error);
                    } finally {
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';
                    }
                };

                // Intercept Pagination Links
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('.pagination a, .ajax-link');
                    if (link && link.href) {
                        e.preventDefault();
                        handleAjax(link.href);
                    }
                });

                // Intercept Search Forms
                document.addEventListener('submit', (e) => {
                    const form = e.target;
                    if (form.method.toLowerCase() === 'get' && (form.querySelector('input[name="search"]') || form.classList.contains('ajax-form'))) {
                        e.preventDefault();
                        const url = new URL(form.action || window.location.href);
                        const formData = new FormData(form);
                        for (const [key, value] of formData.entries()) {
                            url.searchParams.set(key, value);
                        }
                        handleAjax(url.toString());
                    }
                });

                // Search-as-you-type with Debounce
                let debounceTimer;
                document.addEventListener('input', (e) => {
                    const input = e.target;
                    if (input.name === 'search' && input.closest('form')) {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            input.closest('form').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                        }, 400); // 400ms debounce
                    }
                });
            });
        </script>
    </body>
</html>

<?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/layouts/app.blade.php ENDPATH**/ ?>