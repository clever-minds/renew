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
        Super Admin Panel
     <?php $__env->endSlot(); ?>

    <div class="space-y-8">
        <!-- Global SaaS Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Total Tenants -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex items-center justify-between overflow-hidden relative group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Agencies</p>
                    <p class="text-4xl font-black text-gray-900"><?php echo e($totalTenants); ?></p>
                    <p class="text-[10px] text-blue-600 font-bold mt-2 uppercase tracking-tighter">Registered in system</p>
                </div>
                <div class="relative w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-100">
                    <i class="fas fa-building text-2xl"></i>
                </div>
            </div>

            <!-- Active Tenants -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex items-center justify-between overflow-hidden relative group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Active Accounts</p>
                    <p class="text-4xl font-black text-gray-900"><?php echo e($activeTenants); ?></p>
                    <p class="text-[10px] text-emerald-600 font-bold mt-2 uppercase tracking-tighter"><?php echo e(round(($activeTenants / max($totalTenants, 1)) * 100)); ?>% retention rate</p>
                </div>
                <div class="relative w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <i class="fas fa-check-double text-2xl"></i>
                </div>
            </div>

            <!-- SaaS Revenue -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 flex items-center justify-between overflow-hidden relative group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full transition-transform group-hover:scale-110"></div>
                <div class="relative">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">SaaS Revenue (MRR)</p>
                    <p class="text-4xl font-black text-indigo-600">₹<?php echo e(number_format($totalSaaSRevenue, 2)); ?></p>
                    <p class="text-[10px] text-indigo-400 font-bold mt-2 uppercase tracking-tighter">Projected monthly income</p>
                </div>
                <div class="relative w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-gray-900 uppercase tracking-wider text-sm">Recently Joined Agencies</h3>
                    <p class="text-xs text-gray-400 mt-1">Latest tenant registrations across the platform</p>
                </div>
                <a href="<?php echo e(route('admin.tenants')); ?>" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition-colors">
                    View All Tenants
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Agency / Owner</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">SaaS Plan</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined On</th>
                            <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Operations</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php $__empty_1 = true; $__currentLoopData = $recentTenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 font-bold">
                                        <?php echo e(substr($tenant->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors"><?php echo e($tenant->name); ?></p>
                                        <p class="text-[10px] text-gray-400 font-medium"><?php echo e($tenant->email); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-widest bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    <?php echo e($tenant->plan_name); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter 
                                    <?php if($tenant->status === 'active'): ?> bg-emerald-100 text-emerald-700 
                                    <?php elseif($tenant->status === 'trial'): ?> bg-blue-100 text-blue-700
                                    <?php else: ?> bg-red-100 text-red-700 <?php endif; ?>">
                                    <span class="w-1 h-1 rounded-full mr-1.5 
                                        <?php if($tenant->status === 'active'): ?> bg-emerald-500 
                                        <?php elseif($tenant->status === 'trial'): ?> bg-blue-500
                                        <?php else: ?> bg-red-500 <?php endif; ?>"></span>
                                    <?php echo e($tenant->status); ?>

                                </span>
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($tenant->created_at)->format('M d, Y')); ?>

                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <?php if($tenant->status !== 'suspended'): ?>
                                    <form action="<?php echo e(route('admin.tenants.suspend', $tenant->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-red-600 hover:text-white transition-all">
                                            Suspend
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <form action="<?php echo e(route('admin.tenants.activate', $tenant->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button class="px-3 py-1.5 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">
                                            Activate
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-gray-400">No activity recorded yet.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
<?php endif; ?>

<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>