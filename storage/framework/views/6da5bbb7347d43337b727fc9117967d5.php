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
        Subscription Contract
     <?php $__env->endSlot(); ?>

    <div class="space-y-8 pb-12">
        <!-- Contract Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-900 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl font-black">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-1">
                                <h2 class="text-2xl font-black tracking-tight"><?php echo e($subscription->service->name); ?></h2>
                                <span class="px-3 py-0.5 rounded-full text-[8px] font-black uppercase tracking-[0.2em] bg-white/20 text-white border border-white/10">
                                    <?php echo e($subscription->status->value); ?>

                                </span>
                            </div>
                            <p class="text-indigo-100 font-medium opacity-80">Contract for <span class="font-bold underline"><?php echo e($subscription->client->name); ?></span></p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <?php if($subscription->status->value === 'active'): ?>
                            <form method="POST" action="<?php echo e(route('app.subscriptions.suspend', $subscription)); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:bg-amber-600 transition-all">
                                    Suspend
                                </button>
                            </form>
                        <?php elseif($subscription->status->value === 'suspended'): ?>
                            <form method="POST" action="<?php echo e(route('app.subscriptions.activate', $subscription)); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-900/20 hover:bg-emerald-600 transition-all">
                                    Activate
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <button @click="$dispatch('open-modal', 'edit-subscription-<?php echo e($subscription->id); ?>')" class="px-6 py-2.5 bg-white text-indigo-900 rounded-xl text-sm font-bold shadow-lg hover:bg-indigo-50 transition-all">
                            Edit Terms
                        </button>
                        
                        <a href="<?php echo e(route('app.subscriptions.index')); ?>" class="px-4 py-2.5 bg-white/10 text-white rounded-xl text-sm font-bold hover:bg-white/20 transition-all">
                            <i class="fas fa-list"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contract Stats -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-8 border-b border-gray-50">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Recurring Price</p>
                    <p class="text-2xl font-black text-gray-900">$<?php echo e(number_format($subscription->price, 2)); ?></p>
                    <p class="text-[10px] text-gray-500 font-bold uppercase"><?php echo e($subscription->service->billing_cycle->value); ?> cycle</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contract Start</p>
                    <p class="text-lg font-black text-gray-900"><?php echo e($subscription->start_date->format('M d, Y')); ?></p>
                    <p class="text-[10px] text-indigo-600 font-bold uppercase"><?php echo e($subscription->start_date->diffForHumans()); ?></p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Next Renewal</p>
                    <p class="text-lg font-black text-gray-900"><?php echo e($subscription->next_due_date ? $subscription->next_due_date->format('M d, Y') : 'N/A'); ?></p>
                    <?php if($subscription->next_due_date): ?>
                        <p class="text-[10px] <?php echo e($subscription->next_due_date->isPast() ? 'text-red-500' : 'text-emerald-600'); ?> font-bold uppercase">
                            <?php echo e($subscription->next_due_date->isPast() ? 'Overdue' : 'Coming up'); ?>

                        </p>
                    <?php endif; ?>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Auto Billing</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="w-2 h-2 rounded-full <?php echo e($subscription->auto_invoice ? 'bg-emerald-500' : 'bg-gray-300'); ?>"></span>
                        <p class="text-sm font-black text-gray-900 uppercase tracking-tighter"><?php echo e($subscription->auto_invoice ? 'Enabled' : 'Disabled'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Client Snapshot -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-fit">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Client Snapshot</p>
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-500 text-xl font-black">
                        <?php echo e(substr($subscription->client->name, 0, 1)); ?>

                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-900"><?php echo e($subscription->client->name); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e($subscription->client->email); ?></p>
                    </div>
                </div>
                <a href="<?php echo e(route('app.clients.show', $subscription->client)); ?>" class="flex items-center justify-center w-full py-3 bg-gray-50 text-gray-900 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-gray-100 transition-all">
                    View Full Profile
                </a>
            </div>

            <!-- Recent Billing History -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Related Invoices</p>
                </div>
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-900">No invoices generated yet</p>
                    <p class="text-xs text-gray-500 mt-1">Invoices will appear here once they are generated automatically or manually.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Subscription Modal -->
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'edit-subscription-'.e($subscription->id).'','title' => 'Edit Subscription Terms']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit-subscription-'.e($subscription->id).'','title' => 'Edit Subscription Terms']); ?>
        <form method="POST" action="<?php echo e(route('app.subscriptions.update', $subscription)); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Service Package</label>
                <select name="service_id" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($service->id); ?>" <?php echo e($subscription->service_id === $service->id ? 'selected' : ''); ?>>
                            <?php echo e($service->name); ?> ($<?php echo e(number_format($service->price, 2)); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Custom Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                        <input type="number" name="price" value="<?php echo e($subscription->price); ?>" step="0.01" min="0" required class="w-full pl-8 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Effective Date</label>
                    <input type="date" name="start_date" value="<?php echo e($subscription->start_date->format('Y-m-d')); ?>" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>

            <div class="flex items-center p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                <input type="hidden" name="auto_invoice" value="0">
                <input type="checkbox" name="auto_invoice" id="auto_invoice" value="1" <?php echo e($subscription->auto_invoice ? 'checked' : ''); ?> class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="auto_invoice" class="ml-3 text-sm font-bold text-gray-700">Enable automatic invoice generation</label>
            </div>

            <div class="flex justify-between items-center pt-6 border-t border-gray-50">
                <button type="button" onclick="if(confirm('Are you sure you want to cancel this contract?')) { document.getElementById('cancel-form').submit(); }" class="text-xs font-black text-red-400 hover:text-red-600 uppercase tracking-widest">
                    Terminate Contract
                </button>
                <div class="flex space-x-3">
                    <button type="button" @click="$dispatch('close-modal')" class="text-sm font-bold text-gray-400 hover:text-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                        Update Contract
                    </button>
                </div>
            </div>
        </form>
        <form id="cancel-form" action="<?php echo e(route('app.subscriptions.destroy', $subscription)); ?>" method="POST" class="hidden">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
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
<?php endif; ?><?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/app/subscriptions/show.blade.php ENDPATH**/ ?>