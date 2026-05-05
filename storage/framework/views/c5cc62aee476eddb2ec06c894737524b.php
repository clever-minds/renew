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
        System Settings
     <?php $__env->endSlot(); ?>

    <div class="max-w-4xl space-y-12">
        <!-- Company Settings -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                    <i class="fas fa-building text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Company Profile</h3>
            </div>
            <div class="p-6">
                <form action="<?php echo e(route('app.settings.company')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agency Name</label>
                            <input type="text" name="company_name" value="<?php echo e($company['company_name'] ?? ''); ?>" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Support Email</label>
                            <input type="email" name="support_email" value="<?php echo e($company['support_email'] ?? ''); ?>" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Default Currency</label>
                            <select name="currency" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="USD" <?php if(($company['currency'] ?? '') === 'USD'): echo 'selected'; endif; ?>>USD - US Dollar</option>
                                <option value="EUR" <?php if(($company['currency'] ?? '') === 'EUR'): echo 'selected'; endif; ?>>EUR - Euro</option>
                                <option value="INR" <?php if(($company['currency'] ?? '') === 'INR'): echo 'selected'; endif; ?>>INR - Indian Rupee</option>
                                <option value="GBP" <?php if(($company['currency'] ?? '') === 'GBP'): echo 'selected'; endif; ?>>GBP - British Pound</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Timezone</label>
                            <select name="timezone" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <?php $__currentLoopData = timezone_identifiers_list(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tz); ?>" <?php if(($company['timezone'] ?? 'UTC') === $tz): echo 'selected'; endif; ?>><?php echo e($tz); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Save Company Profile
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- SMTP Settings -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center space-x-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Email Configuration (SMTP)</h3>
            </div>
            <div class="p-6">
                <form action="<?php echo e(route('app.settings.smtp')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Host</label>
                            <input type="text" name="host" value="<?php echo e($smtp['host'] ?? ''); ?>" placeholder="smtp.mailtrap.io"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Port</label>
                            <input type="number" name="port" value="<?php echo e($smtp['port'] ?? ''); ?>" placeholder="2525"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                            <input type="text" name="username" value="<?php echo e($smtp['username'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                            <input type="password" name="password" value="<?php echo e($smtp['password'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Encryption</label>
                            <select name="encryption" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="tls" <?php if(($smtp['encryption'] ?? '') === 'tls'): echo 'selected'; endif; ?>>TLS</option>
                                <option value="ssl" <?php if(($smtp['encryption'] ?? '') === 'ssl'): echo 'selected'; endif; ?>>SSL</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Address</label>
                            <input type="email" name="from_address" value="<?php echo e($smtp['from_address'] ?? ''); ?>" placeholder="noreply@agency.com"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>
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
<?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/app/settings/index.blade.php ENDPATH**/ ?>