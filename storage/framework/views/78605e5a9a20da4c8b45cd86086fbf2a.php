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
        Edit Service
     <?php $__env->endSlot(); ?>

    <div class="max-w-3xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Edit Service Information</h3>
                <a href="<?php echo e(route('app.services.index')); ?>" class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-widest">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Catalog
                </a>
            </div>
            
            <div class="p-6">
                <form method="POST" action="<?php echo e(route('app.services.update', $service)); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Service Name</label>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name', $service->name)); ?>" required
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. Premium Support">
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Describe what this service includes..."><?php echo e(old('description', $service->description)); ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Pricing (INR)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₹</span>
                                <input type="number" name="price" id="price" step="0.01" min="0" value="<?php echo e(old('price', $service->price)); ?>" required
                                       class="w-full pl-8 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0.00">
                            </div>
                        </div>

                        <div>
                            <label for="billing_cycle" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Billing Cycle</label>
                            <select name="billing_cycle" id="billing_cycle" required
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="monthly" <?php if(old('billing_cycle', $service->billing_cycle->value) === 'monthly'): echo 'selected'; endif; ?>>Monthly</option>
                                <option value="quarterly" <?php if(old('billing_cycle', $service->billing_cycle->value) === 'quarterly'): echo 'selected'; endif; ?>>Quarterly</option>
                                <option value="semi-annually" <?php if(old('billing_cycle', $service->billing_cycle->value) === 'semi-annually'): echo 'selected'; endif; ?>>Semi-Annually</option>
                                <option value="annually" <?php if(old('billing_cycle', $service->billing_cycle->value) === 'annually'): echo 'selected'; endif; ?>>Annually</option>
                                <option value="one-time" <?php if(old('billing_cycle', $service->billing_cycle->value) === 'one-time'): echo 'selected'; endif; ?>>One-Time</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_active" id="is_active" value="1" <?php if(old('is_active', $service->is_active)): echo 'checked'; endif; ?>
                                   class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-bold text-gray-700">Active Service</label>
                            <p class="text-xs text-gray-500">This service will be visible and available for new subscriptions.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center justify-end space-x-3">
                        <a href="<?php echo e(route('app.services.index')); ?>" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Update Service
                        </button>
                    </div>
                </form>
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
<?php endif; ?><?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/services/edit.blade.php ENDPATH**/ ?>