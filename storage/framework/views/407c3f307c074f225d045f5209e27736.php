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
        Edit Client
     <?php $__env->endSlot(); ?>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Client Information</h3>
                <a href="<?php echo e(route('app.clients.index')); ?>" class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-widest">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
            
            <div class="p-6">
                <form method="POST" action="<?php echo e(route('app.clients.update', $client)); ?>" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Full Name</label>
                            <input type="text" name="name" id="name" value="<?php echo e(old('name', $client->name)); ?>" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Aarav Sharma">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email', $client->email)); ?>" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="aarav@example.com">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="phone" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Mobile Number</label>
                            <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $client->phone)); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="+91 98765 43210">
                        </div>

                        <div>
                            <label for="company" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Company (Optional)</label>
                            <input type="text" name="company" id="company" value="<?php echo e(old('company', $client->company)); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Tata Consultancy Services">
                        </div>
                    </div>

                    <div>
                        <label for="gst_number" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">GST Number (Optional)</label>
                        <input type="text" name="gst_number" id="gst_number" value="<?php echo e(old('gst_number', $client->gst_number)); ?>"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="27AAAAA1111A1Z1">
                    </div>

                    <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Billing Address</h4>
                        
                        <div>
                            <label for="billing_address_line1" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Street Address</label>
                            <input type="text" name="billing_address[line1]" id="billing_address_line1" value="<?php echo e(old('billing_address.line1', $client->billing_address['line1'] ?? '')); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="123, MG Road">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="billing_address_city" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">City</label>
                                <input type="text" name="billing_address[city]" id="billing_address_city" value="<?php echo e(old('billing_address.city', $client->billing_address['city'] ?? '')); ?>"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Mumbai">
                            </div>

                            <div>
                                <label for="billing_address_country" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Country</label>
                                <input type="text" name="billing_address[country]" id="billing_address_country" value="<?php echo e(old('billing_address.country', $client->billing_address['country'] ?? 'India')); ?>"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="India">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center justify-end space-x-3">
                        <a href="<?php echo e(route('app.clients.index')); ?>" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Update Client
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
<?php endif; ?><?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/clients/edit.blade.php ENDPATH**/ ?>