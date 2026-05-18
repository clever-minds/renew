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
        Subscription Activity
     <?php $__env->endSlot(); ?>

    <div class="space-y-8">
        <!-- Filter Card -->
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <form method="GET" action="<?php echo e(route('app.reports.subscriptions')); ?>" class="flex items-center space-x-2">
                    <label class="text-xs font-bold text-gray-500 uppercase tracking-widest">Filter Status:</label>
                    <select name="status" onchange="this.form.submit()" 
                            class="rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500 py-1 pl-3 pr-8">
                        <option value="active" <?php if($status === 'active'): echo 'selected'; endif; ?>>Active</option>
                        <option value="suspended" <?php if($status === 'suspended'): echo 'selected'; endif; ?>>Suspended</option>
                        <option value="cancelled" <?php if($status === 'cancelled'): echo 'selected'; endif; ?>>Cancelled</option>
                    </select>
                </form>
            </div>
            <div class="text-xs text-gray-400 font-medium">
                Total <?php echo e($subscriptions->total()); ?> records found
            </div>
        </div>

        <!-- Subscriptions Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cycle</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Next Renewal</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 font-bold text-xs">
                                            <?php echo e(substr($sub->client_name, 0, 1)); ?>

                                        </div>
                                        <span class="text-sm font-bold text-gray-900"><?php echo e($sub->client_name); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700 font-medium"><?php echo e($sub->service_name); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-widest border border-gray-200">
                                        <?php echo e($sub->billing_cycle); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    ₹<?php echo e(number_format((float)$sub->price, 2)); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($sub->next_due_date): ?>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900"><?php echo e(\Carbon\Carbon::parse($sub->next_due_date)->format('M d, Y')); ?></span>
                                            <span class="text-[10px] <?php echo e(\Carbon\Carbon::parse($sub->next_due_date)->isPast() ? 'text-red-500' : 'text-gray-400'); ?>">
                                                <?php echo e(\Carbon\Carbon::parse($sub->next_due_date)->diffForHumans()); ?>

                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="<?php echo e(route('app.subscriptions.show', $sub->id)); ?>" class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 text-indigo-600 hover:bg-indigo-50 rounded-lg text-xs font-bold transition-colors">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-sync text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-900">No <?php echo e($status); ?> subscriptions</p>
                                        <p class="text-sm text-gray-500 mt-1">There are no subscriptions found with the selected status.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($subscriptions->hasPages()): ?>
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                    <?php echo e($subscriptions->links()); ?>

                </div>
            <?php endif; ?>
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
<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/reports/subscriptions.blade.php ENDPATH**/ ?>