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
        Payments
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex-1 max-w-md">
                <form method="GET" action="<?php echo e(route('app.payments.index')); ?>" class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by reference, invoice or client..." 
                           class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </form>
            </div>
            
            <div class="flex items-center gap-3">
                <form method="GET" action="<?php echo e(route('app.payments.index')); ?>" class="flex items-center gap-2">
                    <select name="method" onchange="this.form.submit()" class="rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Methods</option>
                        <option value="cash" <?php if(request('method') === 'cash'): echo 'selected'; endif; ?>>Cash</option>
                        <option value="bank_transfer" <?php if(request('method') === 'bank_transfer'): echo 'selected'; endif; ?>>Bank Transfer</option>
                        <option value="online" <?php if(request('method') === 'online'): echo 'selected'; endif; ?>>Online</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Client / Invoice</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Reference</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</th>
                            <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Method</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter"><?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-900"><?php echo e($payment->client_name); ?></span>
                                        <span class="text-xs text-indigo-600 font-medium"><?php echo e($payment->invoice_number); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-xs text-gray-500 font-mono"><?php echo e($payment->transaction_reference ?? 'N/A'); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-bold text-emerald-600">+$<?php echo e(number_format((float)$payment->amount, 2)); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest bg-gray-100 text-gray-600 border border-gray-200">
                                        <?php echo e(str_replace('_', ' ', $payment->payment_method)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-money-bill-wave text-4xl text-gray-200 mb-3"></i>
                                        <p>No payments recorded yet.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if($payments->hasPages()): ?>
                <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
                    <?php echo e($payments->links()); ?>

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
<?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/app/payments/index.blade.php ENDPATH**/ ?>