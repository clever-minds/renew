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
                <?php echo e(__('Client Details')); ?>

            </h2>
            <div class="flex space-x-2">
                <button @click="$dispatch('open-drawer', 'edit-client-<?php echo e($client->id); ?>')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    Edit Client
                </button>
                <a href="<?php echo e(route('app.clients.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                    Back to Clients
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($client->name); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($client->email); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($client->phone ?? '-'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Company</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($client->company ?? '-'); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($client->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                            <?php echo e(ucfirst($client->status ?? 'active')); ?>

                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Billing Address -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                            <?php if($client->billing_address): ?>
                                <div class="text-sm text-gray-900">
                                    <?php if(isset($client->billing_address['street'])): ?>
                                        <p><?php echo e($client->billing_address['street']); ?></p>
                                    <?php endif; ?>
                                    <?php if(isset($client->billing_address['city']) || isset($client->billing_address['state']) || isset($client->billing_address['zip'])): ?>
                                        <p>
                                            <?php echo e($client->billing_address['city'] ?? ''); ?>

                                            <?php echo e($client->billing_address['state'] ?? ''); ?>

                                            <?php echo e($client->billing_address['zip'] ?? ''); ?>

                                        </p>
                                    <?php endif; ?>
                                    <?php if(isset($client->billing_address['country'])): ?>
                                        <p><?php echo e($client->billing_address['country']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">No billing address provided</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Notes -->
                    <?php if($client->notes): ?>
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            <p class="text-sm text-gray-900"><?php echo e($client->notes); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Subscriptions -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Active Subscriptions</h3>
                            <a href="<?php echo e(route('app.subscriptions.create', ['client' => $client->id])); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                                + Add Subscription
                            </a>
                        </div>
                        <?php if($client->subscriptions->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $client->subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border rounded p-4 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium"><?php echo e($subscription->service->name); ?></h4>
                                                <p class="text-sm text-gray-600"><?php echo e($subscription->service->description); ?></p>
                                                <p class="text-sm text-gray-500">Next due: <?php echo e($subscription->next_due_date ? $subscription->next_due_date->format('M j, Y') : 'N/A'); ?></p>
                                            </div>
                                            <span class="px-2 text-xs rounded-full <?php echo e($subscription->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                                                <?php echo e(ucfirst($subscription->status->value)); ?>

                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500">No active subscriptions</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Client Drawer -->
    <?php if (isset($component)) { $__componentOriginale67024a204ba6be83f3556a31cc49b4d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale67024a204ba6be83f3556a31cc49b4d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.drawer','data' => ['name' => 'edit-client-'.e($client->id).'','title' => 'Edit Client']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('drawer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit-client-'.e($client->id).'','title' => 'Edit Client']); ?>
        <form method="POST" action="<?php echo e(route('app.clients.update', $client)); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" value="<?php echo e($client->name); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" value="<?php echo e($client->email); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" value="<?php echo e($client->phone); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="company" value="<?php echo e($client->company); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="active" <?php echo e($client->status === 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="inactive" <?php echo e($client->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e($client->notes); ?></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" @click="$dispatch('close-drawer')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Update Client
                </button>
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
<?php endif; ?><?php /**PATH /var/www/renew.usamasheth.in/resources/views/app/clients/show.blade.php ENDPATH**/ ?>