<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <span>Dashboard</span>
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('app.invoices.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-indigo-100">
                    <i class="fas fa-plus mr-2"></i> Create Invoice
                </a>
                <a href="<?php echo e(route('app.subscriptions.create')); ?>" class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 text-xs font-bold rounded-xl transition-all">
                    <i class="fas fa-sync-alt mr-2 text-indigo-500"></i> New Subscription
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-8">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- MRR Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">₹<?php echo e(number_format($stats['mrr'] ?? 0, 2)); ?></p>
                </div>
            </div>

            <!-- Overdue Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Overdue</p>
                    <p class="text-2xl font-bold text-red-600">₹<?php echo e(number_format($stats['overdue_amount'] ?? 0, 2)); ?></p>
                </div>
            </div>

            <!-- Renewals Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                    <i class="fas fa-sync text-2xl"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Upcoming Renewals</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($stats['upcoming_renewals_count'] ?? 0); ?></p>
                </div>
            </div>
        </div>

        <!-- Lists Area -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Upcoming Renewals List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Upcoming Renewals</h3>
                    <a href="<?php echo e(route('app.subscriptions.index')); ?>" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingRenewals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                                            <?php echo e(substr($sub->client_name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900"><?php echo e($sub->client_name); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($sub->service_name); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">₹<?php echo e(number_format((float)$sub->price, 2)); ?></p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e(\Carbon\Carbon::parse($sub->next_due_date)->isPast() ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800'); ?>">
                                            <?php echo e(\Carbon\Carbon::parse($sub->next_due_date)->diffForHumans()); ?>

                                        </span>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="px-6 py-12 text-center text-sm text-gray-500">
                                <i class="fas fa-calendar-check text-4xl text-gray-200 mb-3 block"></i>
                                No upcoming renewals found.
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Recent Payments List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <h3 class="font-bold text-gray-800">Recent Payments</h3>
                    <a href="<?php echo e(route('app.payments.index')); ?>" class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">View All</a>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold">
                                            <i class="fas fa-arrow-down text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900"><?php echo e($payment->client_name); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($payment->invoice_number); ?> &middot; <?php echo e(ucfirst($payment->payment_method)); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-emerald-600">+₹<?php echo e(number_format((float)$payment->amount, 2)); ?></p>
                                        <p class="text-[10px] uppercase font-bold text-gray-400"><?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')); ?></p>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="px-6 py-12 text-center text-sm text-gray-500">
                                <i class="fas fa-money-bill-wave text-4xl text-gray-200 mb-3 block"></i>
                                No recent payments logged.
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>

<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/dashboard.blade.php ENDPATH**/ ?>