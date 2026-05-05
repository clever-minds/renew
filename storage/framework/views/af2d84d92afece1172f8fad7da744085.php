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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Subscription Details')); ?>

            </h2>
            <div class="flex space-x-2">
                <?php if($subscription->status->value === 'active'): ?>
                    <form method="POST" action="<?php echo e(route('app.subscriptions.suspend', $subscription)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('POST'); ?>
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow" onclick="return confirm('Are you sure you want to suspend this subscription?')">
                            Suspend
                        </button>
                    </form>
                <?php elseif($subscription->status->value === 'suspended'): ?>
                    <form method="POST" action="<?php echo e(route('app.subscriptions.activate', $subscription)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('POST'); ?>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                            Activate
                        </button>
                    </form>
                <?php endif; ?>
                <a href="<?php echo e(route('app.subscriptions.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                    Back to Subscriptions
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subscription Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="<?php echo e(route('app.clients.show', $subscription->client)); ?>" class="text-indigo-600 hover:text-indigo-800">
                                            <?php echo e($subscription->client->name); ?>

                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Service</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="<?php echo e(route('app.services.show', $subscription->service)); ?>" class="text-indigo-600 hover:text-indigo-800">
                                            <?php echo e($subscription->service->name); ?>

                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                                    <dd class="text-sm text-gray-900">$<?php echo e(number_format($subscription->price, 2)); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Billing Cycle</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e(ucfirst($subscription->service->billing_cycle->value)); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php if($subscription->status->value === 'active'): ?> bg-green-100 text-green-800
                                            <?php elseif($subscription->status->value === 'suspended'): ?> bg-yellow-100 text-yellow-800
                                            <?php elseif($subscription->status->value === 'cancelled'): ?> bg-red-100 text-red-800
                                            <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                            <?php echo e(ucfirst($subscription->status->value)); ?>

                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Dates & Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dates & Settings</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($subscription->start_date->format('M j, Y')); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Next Due Date</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($subscription->next_due_date ? $subscription->next_due_date->format('M j, Y') : 'N/A'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Auto Invoice</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($subscription->auto_invoice ? 'Yes' : 'No'); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Invoices -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Related Invoices</h3>
                        <p class="text-gray-500">Invoice functionality will be implemented in the next phase</p>
                    </div>
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
<?php endif; ?><?php /**PATH /var/www/renew.usamasheth.in/resources/views/app/subscriptions/show.blade.php ENDPATH**/ ?>