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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- MRR Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Monthly Recurring Revenue</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">$<?php echo e(number_format($stats['mrr'] ?? 0, 2)); ?></div>
                </div>

                <!-- Overdue Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Total Overdue</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">$<?php echo e(number_format($stats['overdue_amount'] ?? 0, 2)); ?></div>
                </div>

                <!-- Renewals Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Upcoming Renewals (30 Days)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900"><?php echo e($stats['upcoming_renewals_count'] ?? 0); ?></div>
                </div>
            </div>

            <!-- Lists Area -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Renewals List -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Upcoming Renewals</h3>
                    <ul class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingRenewals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($sub->client_name); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($sub->service_name); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">$<?php echo e(number_format((float)$sub->price, 2)); ?></p>
                                    <p class="text-xs text-orange-600"><?php echo e(\Carbon\Carbon::parse($sub->next_due_date)->diffForHumans()); ?></p>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="py-3 text-sm text-gray-500">No upcoming renewals.</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Recent Payments List -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Recent Payments</h3>
                    <ul class="divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($payment->client_name); ?></p>
                                    <p class="text-sm text-gray-500"><?php echo e($payment->invoice_number); ?> &middot; <?php echo e(ucfirst($payment->payment_method)); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-green-600">+$<?php echo e(number_format((float)$payment->amount, 2)); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')); ?></p>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li class="py-3 text-sm text-gray-500">No recent payments.</li>
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
<?php /**PATH /var/www/renew.usamasheth.in/resources/views/app/dashboard.blade.php ENDPATH**/ ?>