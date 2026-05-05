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
                <?php echo e(__('Service Details')); ?>

            </h2>
            <div class="flex space-x-2">
                <button @click="$dispatch('open-drawer', 'edit-service-<?php echo e($service->id); ?>')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    Edit Service
                </button>
                <a href="<?php echo e(route('app.services.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                    Back to Services
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Service Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Service Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($service->name); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($service->description ?? '-'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                                    <dd class="text-sm text-gray-900">$<?php echo e(number_format($service->price, 2)); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Billing Cycle</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e(ucfirst($service->billing_cycle->value)); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($service->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Statistics -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Active Subscriptions</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($service->subscriptions()->where('status', 'active')->count()); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Subscriptions</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($service->subscriptions()->count()); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Active Subscriptions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Active Subscriptions</h3>
                        <?php if($service->subscriptions()->where('status', 'active')->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $service->subscriptions()->where('status', 'active')->with('client')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border rounded p-4 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium"><?php echo e($subscription->client->name); ?></h4>
                                                <p class="text-sm text-gray-600"><?php echo e($subscription->client->email); ?></p>
                                                <p class="text-sm text-gray-500">Next due: <?php echo e($subscription->next_due_date ? $subscription->next_due_date->format('M j, Y') : 'N/A'); ?></p>
                                            </div>
                                            <span class="px-2 text-xs rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">No active subscriptions for this service</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Drawer -->
    <?php if (isset($component)) { $__componentOriginale67024a204ba6be83f3556a31cc49b4d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale67024a204ba6be83f3556a31cc49b4d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.drawer','data' => ['name' => 'edit-service-'.e($service->id).'','title' => 'Edit Service']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('drawer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit-service-'.e($service->id).'','title' => 'Edit Service']); ?>
        <form method="POST" action="<?php echo e(route('app.services.update', $service)); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700">Service Name</label>
                <input type="text" name="name" value="<?php echo e($service->name); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e($service->description); ?></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" value="<?php echo e($service->price); ?>" step="0.01" min="0" required class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Billing Cycle</label>
                    <select name="billing_cycle" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="monthly" <?php echo e($service->billing_cycle->value === 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                        <option value="quarterly" <?php echo e($service->billing_cycle->value === 'quarterly' ? 'selected' : ''); ?>>Quarterly</option>
                        <option value="semi-annually" <?php echo e($service->billing_cycle->value === 'semi-annually' ? 'selected' : ''); ?>>Semi-Annually</option>
                        <option value="annually" <?php echo e($service->billing_cycle->value === 'annually' ? 'selected' : ''); ?>>Annually</option>
                        <option value="one-time" <?php echo e($service->billing_cycle->value === 'one-time' ? 'selected' : ''); ?>>One-Time</option>
                    </select>
                </div>
            </div>
            <div>
                <input type="hidden" name="is_active" value="0">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" <?php echo e($service->is_active ? 'checked' : ''); ?> class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="text-sm text-gray-900">Active</span>
                </label>
            </div>
            <div class="flex justify-between items-center">
                <form method="POST" action="<?php echo e(route('app.services.destroy', $service)); ?>" onsubmit="return confirm('Delete this service?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Service
                    </button>
                </form>
                <div class="flex space-x-2">
                    <button type="button" @click="$dispatch('close-drawer')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Update Service
                    </button>
                </div>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale67024a204ba6be83f3556a31cc49b4d)): ?>
<?php $attributes = $__attributesOriginale67024a204ba6be83f3556a31cc49b4d; ?>
<?php unset($__attributesOriginale67024a204ba6be83f3556a31cc49b4d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale67024a204ba6be83f3556a31cc49b4d)): ?>
<?php $component = $__componentOriginale67024a204ba6be83f3556a31cc49b4d; ?>
<?php unset($__componentOriginale67024a204ba6be83f3556a31cc49b4d); ?>
<?php endif; ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /var/www/renew.usamasheth.in/resources/views/app/services/show.blade.php ENDPATH**/ ?>