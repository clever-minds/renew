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
        Clients
     <?php $__env->endSlot(); ?>

    <div id="ajax-container" class="space-y-6">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex-1 max-w-md">
                <form method="GET" action="<?php echo e(route('app.clients.index')); ?>" class="relative ajax-form">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by name, email or company..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </form>
            </div>
            
            <div class="flex items-center gap-3">
                <form method="GET" action="<?php echo e(route('app.clients.index')); ?>" class="flex items-center gap-2 ajax-form">
                    <select name="status" onchange="this.form.requestSubmit()" class="rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="active" <?php if(request('status') === 'active'): echo 'selected'; endif; ?>>Active</option>
                        <option value="inactive" <?php if(request('status') === 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
                    </select>
                </form>
                
                <button x-data @click="$dispatch('open-modal', 'create-client')" 
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100">
                    <i class="fas fa-plus mr-2"></i> New Client
                </button>
            </div>
        </div>

        <!-- Clients Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client Info</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                                            <?php echo e(substr($client->name, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <a href="<?php echo e(route('app.clients.show', $client)); ?>" class="text-sm font-bold text-gray-900 hover:text-indigo-600"><?php echo e($client->name); ?></a>
                                            <p class="text-xs text-gray-500"><?php echo e($client->email); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-600"><?php echo e($client->company ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($client->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800'); ?>">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 <?php echo e($client->status === 'active' ? 'bg-emerald-500' : 'bg-gray-400'); ?>"></span>
                                        <?php echo e(ucfirst($client->status)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500 uppercase tracking-tighter">
                                    <?php echo e($client->created_at->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="<?php echo e(route('app.clients.edit', $client)); ?>" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit Client">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('app.clients.destroy', $client)); ?>" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Client">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-user-friends text-3xl text-gray-300"></i>
                                        </div>
                                        <p class="text-lg font-semibold text-gray-900">No clients yet</p>
                                        <p class="text-sm text-gray-500 mt-1">Start by adding your first client to manage their subscriptions.</p>
                                        <button @click="$dispatch('open-modal', 'create-client')" class="mt-6 inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-700">
                                            <i class="fas fa-plus-circle mr-2"></i> Add Client
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($clients->hasPages()): ?>
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 pagination">
                    <?php echo e($clients->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Create Client Modal -->
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'create-client','title' => 'Add New Client']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'create-client','title' => 'Add New Client']); ?>
        <form method="POST" action="<?php echo e(route('app.clients.store')); ?>" class="space-y-5 p-1">
            <?php echo csrf_field(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name</label>
                    <input type="text" name="name" required class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="John Doe">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" name="email" required class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="john@example.com">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Company (Optional)</label>
                    <input type="text" name="company" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="Acme Inc.">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Phone Number</label>
                    <input type="text" name="phone" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="+1 (555) 000-0000">
                </div>
            </div>
            
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 mt-6">
                <button type="button" x-on:click="show = false" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                    Create Client
                </button>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
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
<?php endif; ?>

<?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/app/clients/index.blade.php ENDPATH**/ ?>