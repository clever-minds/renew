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
        Client Profile
     <?php $__env->endSlot(); ?>

    <div class="space-y-8">
        <!-- Client Header Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl font-black">
                            <?php echo e(substr($client->name, 0, 1)); ?>

                        </div>
                        <div>
                            <h2 class="text-2xl font-black"><?php echo e($client->name); ?></h2>
                            <p class="text-indigo-100 font-medium opacity-80"><?php echo e($client->company ?? 'Independent Professional'); ?></p>
                            <div class="flex items-center mt-2 space-x-3">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-white/20 text-white border border-white/20">
                                    <?php echo e($client->status ?? 'active'); ?>

                                </span>
                                <span class="text-xs text-indigo-100 opacity-60">ID: #<?php echo e(str_pad((string)$client->id, 5, '0', STR_PAD_LEFT)); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="$dispatch('open-modal', 'edit-client-<?php echo e($client->id); ?>')" class="px-6 py-2.5 bg-white text-indigo-600 rounded-xl text-sm font-bold shadow-lg shadow-indigo-900/20 hover:bg-indigo-50 transition-all">
                            Edit Profile
                        </button>
                        <a href="<?php echo e(route('app.clients.index')); ?>" class="px-4 py-2.5 bg-indigo-500/30 text-white rounded-xl text-sm font-bold hover:bg-indigo-500/50 transition-all">
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Details -->
                <div class="space-y-8">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Contact & Tax Information</p>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400" title="Email">
                                    <i class="fas fa-envelope text-xs"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-900"><?php echo e($client->email); ?></p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400" title="Mobile Number">
                                    <i class="fas fa-mobile-alt text-xs"></i>
                                </div>
                                <p class="text-sm font-bold text-gray-900"><?php echo e($client->phone ?? 'Not provided'); ?></p>
                            </div>
                            <?php if($client->gst_number): ?>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400" title="GST Number">
                                        <i class="fas fa-file-invoice text-xs"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-900">GST: <?php echo e($client->gst_number); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Billing Address</p>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <?php if($client->billing_address): ?>
                                <div class="text-sm font-medium text-gray-600 space-y-1">
                                    <?php if(isset($client->billing_address['line1'])): ?>
                                        <p><?php echo e($client->billing_address['line1']); ?></p>
                                    <?php elseif(isset($client->billing_address['street'])): ?>
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
                                        <p class="font-bold text-gray-900"><?php echo e($client->billing_address['country']); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-xs text-gray-400 italic text-center py-4">No billing address on file</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Active Subscriptions -->
                <div class="lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Active Subscriptions</p>
                        <a href="<?php echo e(route('app.subscriptions.create', ['client' => $client->id])); ?>" class="text-xs font-black text-indigo-600 hover:text-indigo-700">
                            + ADD NEW
                        </a>
                    </div>

                    <?php if($client->subscriptions->count() > 0): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $client->subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('app.subscriptions.show', $subscription)); ?>" class="block p-5 bg-white rounded-2xl border border-gray-100 shadow-sm hover:border-indigo-300 hover:shadow-md transition-all group cursor-pointer">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                <i class="fas fa-sync-alt text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-900 group-hover:text-indigo-700 transition-colors"><?php echo e($subscription->service->name); ?></p>
                                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">Next: <?php echo e($subscription->next_due_date ? $subscription->next_due_date->format('M d, Y') : 'N/A'); ?></p>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded-full text-[8px] font-black uppercase tracking-widest <?php echo e($subscription->status->value === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500'); ?>">
                                            <?php echo e($subscription->status->value); ?>

                                        </span>
                                    </div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="py-12 border-2 border-dashed border-gray-100 rounded-3xl flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-calendar-times text-4xl mb-4 opacity-20"></i>
                            <p class="text-sm font-bold">No active subscriptions found</p>
                            <a href="<?php echo e(route('app.subscriptions.create', ['client' => $client->id])); ?>" class="mt-4 text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">Setup one now</a>
                        </div>
                    <?php endif; ?>

                    <?php if($client->notes): ?>
                        <div class="mt-12">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Internal Notes</p>
                            <div class="p-6 bg-amber-50/50 rounded-2xl border border-amber-100 text-sm text-amber-900 leading-relaxed italic">
                                "<?php echo e($client->notes); ?>"
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'edit-client-'.e($client->id).'','title' => 'Edit Client Profile']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit-client-'.e($client->id).'','title' => 'Edit Client Profile']); ?>
        <form method="POST" action="<?php echo e(route('app.clients.update', $client)); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                    <input type="text" name="name" value="<?php echo e($client->name); ?>" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                    <input type="email" name="email" value="<?php echo e($client->email); ?>" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Mobile Number</label>
                    <input type="text" name="phone" value="<?php echo e($client->phone); ?>" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="+91 98765 43210">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Company</label>
                    <input type="text" name="company" value="<?php echo e($client->company); ?>" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Tata Consultancy Services">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">GST Number</label>
                    <input type="text" name="gst_number" value="<?php echo e($client->gst_number); ?>" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="27AAAAA1111A1Z1">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Status</label>
                    <select name="status" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="active" <?php echo e($client->status === 'active' ? 'selected' : ''); ?>>Active</option>
                        <option value="inactive" <?php echo e($client->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-4">
                <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Billing Address</h4>
                
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Street Address</label>
                    <input type="text" name="billing_address[line1]" value="<?php echo e($client->billing_address['line1'] ?? $client->billing_address['street'] ?? ''); ?>" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="123, MG Road">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">City</label>
                        <input type="text" name="billing_address[city]" value="<?php echo e($client->billing_address['city'] ?? ''); ?>" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Mumbai">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Country</label>
                        <input type="text" name="billing_address[country]" value="<?php echo e($client->billing_address['country'] ?? 'India'); ?>" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="India">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Internal Notes</label>
                <textarea name="notes" rows="3" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"><?php echo e($client->notes); ?></textarea>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button" @click="$dispatch('close-modal')" class="text-sm font-bold text-gray-400 hover:text-gray-600">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                    Update Profile
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
<?php endif; ?><?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/clients/show.blade.php ENDPATH**/ ?>