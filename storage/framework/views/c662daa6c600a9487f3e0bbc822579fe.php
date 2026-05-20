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

    <div class="max-w-4xl space-y-8">
        <?php if(session('success')): ?>
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-700 text-sm font-bold">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-bold space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-2"><i class="fas fa-exclamation-circle text-[10px]"></i> <?php echo e($error); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        <!-- Company Profile Card -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100 flex items-center space-x-3">
                <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                    <i class="fas fa-building text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Company Profile</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Basic information shown on invoices and communications</p>
                </div>
            </div>

            <form action="<?php echo e(route('app.settings.company')); ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
                <?php echo csrf_field(); ?>

                
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-image text-[10px]"></i> Agency Logo
                    </h4>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden" id="logo-preview-wrap">
                            <?php if(!empty($company['logo_url'])): ?>
                                <img id="logo-preview" src="<?php echo e(asset('storage/' . $company['logo_url'])); ?>" class="w-full h-full object-contain p-1">
                            <?php else: ?>
                                <div id="logo-placeholder" class="text-center text-gray-300">
                                    <i class="fas fa-image text-3xl"></i>
                                    <p class="text-[9px] mt-1 font-bold uppercase tracking-wider">No Logo</p>
                                </div>
                                <img id="logo-preview" class="w-full h-full object-contain p-1 hidden">
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Upload Logo</label>
                            <input type="file" name="logo" id="logo-input" accept="image/*"
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all"
                                   style="border: none !important; padding: 0 !important; background: transparent !important;"
                                   onchange="previewLogo(this)">
                            <p class="text-[10px] text-gray-400 mt-2">JPG, PNG, SVG or WebP — max 2MB. Displayed on invoices &amp; PDF.</p>
                        </div>
                    </div>
                </div>

                
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[10px]"></i> Basic Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agency Name <span class="text-red-400">*</span></label>
                            <input type="text" name="company_name" value="<?php echo e($company['company_name'] ?? ''); ?>" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. Pixel Digital Agency">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Currency <span class="text-red-400">*</span></label>
                            <select name="currency" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold">
                                <option value="INR" <?php echo e(($company['currency'] ?? 'INR') === 'INR' ? 'selected' : ''); ?>>₹ - INR (Indian Rupee)</option>
                                <option value="USD" <?php echo e(($company['currency'] ?? '') === 'USD' ? 'selected' : ''); ?>>$ - USD (US Dollar)</option>
                                <option value="EUR" <?php echo e(($company['currency'] ?? '') === 'EUR' ? 'selected' : ''); ?>>€ - EUR (Euro)</option>
                                <option value="GBP" <?php echo e(($company['currency'] ?? '') === 'GBP' ? 'selected' : ''); ?>>£ - GBP (British Pound)</option>
                                <option value="AED" <?php echo e(($company['currency'] ?? '') === 'AED' ? 'selected' : ''); ?>>د.إ - AED (UAE Dirham)</option>
                                <option value="CAD" <?php echo e(($company['currency'] ?? '') === 'CAD' ? 'selected' : ''); ?>>C$ - CAD (Canadian Dollar)</option>
                                <option value="AUD" <?php echo e(($company['currency'] ?? '') === 'AUD' ? 'selected' : ''); ?>>A$ - AUD (Australian Dollar)</option>
                                <option value="SGD" <?php echo e(($company['currency'] ?? '') === 'SGD' ? 'selected' : ''); ?>>S$ - SGD (Singapore Dollar)</option>
                                <option value="JPY" <?php echo e(($company['currency'] ?? '') === 'JPY' ? 'selected' : ''); ?>>¥ - JPY (Japanese Yen)</option>
                            </select>
                        </div>
                    </div>
                </div>

                
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-phone-alt text-[10px]"></i> Contact Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Primary Email <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="support_email" value="<?php echo e($company['support_email'] ?? ''); ?>" required
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="hello@agency.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Additional Email</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="additional_email" value="<?php echo e($company['additional_email'] ?? ''); ?>"
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="billing@agency.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Primary Phone</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-phone"></i></span>
                                <input type="text" name="support_phone" value="<?php echo e($company['support_phone'] ?? ''); ?>"
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="+91 98765 43210">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Additional Phone</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-phone"></i></span>
                                <input type="text" name="additional_phone" value="<?php echo e($company['additional_phone'] ?? ''); ?>"
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="+91 98765 43211">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-[10px]"></i> Address
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Shown on invoices)</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Address Line</label>
                            <input type="text" name="address_line1" value="<?php echo e($company['address_line1'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="123 Business Avenue, Suite 100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">City</label>
                            <input type="text" name="address_city" value="<?php echo e($company['address_city'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Mumbai">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">State / Country</label>
                            <input type="text" name="address_state" value="<?php echo e($company['address_state'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Maharashtra, India">
                        </div>
                    </div>
                </div>

                
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-university text-[10px]"></i> Bank Details
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Printed on invoices)</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Bank Name</label>
                            <input type="text" name="bank_name" value="<?php echo e($company['bank_name'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. ICICI Bank, SBI">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Account Number</label>
                            <input type="text" name="bank_account" value="<?php echo e($company['bank_account'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. 1234 5678 9000 1234">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">IFSC Code</label>
                            <input type="text" name="bank_ifsc" value="<?php echo e($company['bank_ifsc'] ?? $company['bank_routing'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. ICIC0001234">
                            <input type="hidden" name="bank_routing" value="<?php echo e($company['bank_routing'] ?? ''); ?>">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Bank Branch / Address</label>
                            <input type="text" name="bank_address" value="<?php echo e($company['bank_address'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. MG Road Branch, Mumbai">
                        </div>
                    </div>
                </div>

                
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-file-contract text-[10px]"></i> Terms & Conditions
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Printed at bottom of invoices)</span>
                    </h4>
                    <textarea name="terms_conditions" rows="4"
                              class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                              placeholder="e.g. Payment is due within 14 days of invoice date. Late payments may attract a 2% monthly interest charge..."><?php echo e($company['terms_conditions'] ?? ''); ?></textarea>
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Company Profile
                    </button>
                </div>
            </form>
        </section>

        <!-- SMTP Settings -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-white border-b border-gray-100 flex items-center space-x-3">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Email Configuration (SMTP)</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Used to send invoices and notifications to your clients</p>
                </div>
            </div>
            <div class="p-6">
                <form action="<?php echo e(route('app.settings.smtp')); ?>" method="POST" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Host</label>
                            <input type="text" name="host" value="<?php echo e($smtp['host'] ?? ''); ?>" placeholder="smtp.mailtrap.io"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Port</label>
                            <input type="number" name="port" value="<?php echo e($smtp['port'] ?? ''); ?>" placeholder="587"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                            <input type="text" name="username" value="<?php echo e($smtp['username'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                            <input type="password" name="password" value="<?php echo e($smtp['password'] ?? ''); ?>"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Encryption</label>
                            <select name="encryption" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="tls" <?php if(($smtp['encryption'] ?? '') === 'tls'): echo 'selected'; endif; ?>>TLS</option>
                                <option value="ssl" <?php if(($smtp['encryption'] ?? '') === 'ssl'): echo 'selected'; endif; ?>>SSL</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Address</label>
                            <input type="email" name="from_address" value="<?php echo e($smtp['from_address'] ?? ''); ?>" placeholder="noreply@agency.com"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Name</label>
                            <input type="text" name="from_name" value="<?php echo e($smtp['from_name'] ?? ''); ?>" placeholder="Agency Name"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-8 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-100 transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('logo-preview');
                    const placeholder = document.getElementById('logo-placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
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
<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/settings/index.blade.php ENDPATH**/ ?>