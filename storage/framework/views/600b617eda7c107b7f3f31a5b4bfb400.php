<?php
    $currencySymbols = [
        'INR' => '₹',
        'USD' => '$',
        'EUR' => '€',
        'GBP' => '£',
        'AED' => 'د.إ',
        'CAD' => 'C$',
        'AUD' => 'A$',
        'SGD' => 'S$',
        'JPY' => '¥',
    ];
    $symbol = $currencySymbols[$company['currency'] ?? 'INR'] ?? ($company['currency'] ?? '₹');
    // Normalize status — it may be an Enum object or a plain string
    $invoiceStatus = is_string($invoice->status) ? $invoice->status : $invoice->status->value;
?>
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
        Invoice Detail
     <?php $__env->endSlot(); ?>

    <!-- Custom Print & A4 Styles -->
    <style>
        @page {
            size: A4;
            margin: 0mm;
        }
        @media print {
            html, body {
                height: auto !important;
                min-height: auto !important;
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            main {
                padding: 0 !important;
                margin: 0 !important;
            }
            .a4-container {
                width: 100% !important;
                max-width: none !important;
                box-shadow: none !important;
                border: none !important;
                margin: 0 !important;
                padding: 15mm !important;
                min-height: auto !important;
                page-break-inside: avoid;
            }
            .print-exact {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        /* UI representation of A4 paper */
        @media screen {
            .a4-container {
                width: 100%;
                max-width: 210mm;
                min-height: 297mm;
                margin: 0 auto;
                background: white;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid #e5e7eb;
            }
        }
    </style>

    <div class="pb-20 pt-4 print:p-0 print:m-0">
        <?php if(session('success')): ?>
            <div class="max-w-[210mm] mx-auto mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 text-sm font-bold shadow-sm">
                <i class="fas fa-check-circle text-base"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="max-w-[210mm] mx-auto mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm font-bold shadow-sm">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Actions Toolbar -->
        <div class="max-w-[210mm] mx-auto mb-8 flex items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-gray-100 print:hidden">
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('app.invoices.index')); ?>" class="p-2 text-gray-400 hover:text-gray-800 rounded-lg transition-colors bg-gray-50 hover:bg-gray-100">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Invoice <?php echo e($invoice->invoice_number); ?></h2>
                    <p class="text-xs text-gray-500">Professional A4 Document</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <?php if($invoiceStatus !== 'paid'): ?>
                    <button onclick="openPaymentModal()" class="px-5 py-2.5 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 shadow-sm shadow-emerald-200 transition-all flex items-center">
                        <i class="fas fa-money-bill-wave mr-2"></i> Record Payment
                    </button>
                <?php endif; ?>
                <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center">
                    <i class="fas fa-print mr-2 text-gray-400"></i> Print
                </button>
                <a href="<?php echo e(route('app.invoices.download', $invoice->id)); ?>" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all flex items-center">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </a>
            </div>
        </div>

        <!-- A4 Document Container -->
        <div class="a4-container print-exact p-[15mm] relative overflow-hidden flex flex-col justify-between">
            <!-- Top Decorative Bar -->
            <div class="absolute top-0 left-0 w-full h-3 bg-indigo-600 print-exact"></div>
            
            <div class="flex-1">
                <!-- Invoice Header -->
                <div class="flex justify-between items-start mb-14 mt-4">
                    <div class="flex items-center space-x-4">
                        <?php if(!empty($company['logo_url'])): ?>
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center overflow-hidden print-exact bg-white">
                                <img src="<?php echo e(asset('storage/' . $company['logo_url'])); ?>" alt="Logo" class="w-full h-full object-contain">
                            </div>
                        <?php else: ?>
                            <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl print-exact">
                                <i class="fas fa-paper-plane"></i>
                            </div>
                        <?php endif; ?>
                        <div>
                            <span class="text-3xl font-black tracking-tight text-gray-900"><?php echo e($company['company_name'] ?? $tenant->name ?? 'RenewPilot'); ?></span>
                            <?php if(!empty($company['company_tagline'])): ?>
                                <p class="text-[10px] text-indigo-600 font-bold tracking-widest uppercase mt-1"><?php echo e($company['company_tagline']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <h1 class="text-5xl font-black text-gray-900 tracking-tighter mb-2">INVOICE</h1>
                        <p class="text-lg font-bold text-gray-400">#<?php echo e($invoice->invoice_number); ?></p>
                    </div>
                </div>

                <!-- Addresses Section -->
                <div class="grid grid-cols-2 gap-12 mb-12">
                    <!-- From -->
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">From</h3>
                        <div class="space-y-1">
                            <p class="text-base font-bold text-gray-900"><?php echo e($company['company_name'] ?? $tenant->name ?? 'RenewPilot Inc.'); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e($company['address_line1'] ?? '123 Business Avenue, Suite 100'); ?></p>
                            <p class="text-sm text-gray-600">
                                <?php echo e($company['address_city'] ?? 'New York'); ?><?php echo e(isset($company['address_state']) ? ', ' . $company['address_state'] : ', NY 10001'); ?>

                            </p>
                            <?php if(!empty($company['support_phone'])): ?>
                                <p class="text-sm text-gray-600"><?php echo e($company['support_phone']); ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-600 mt-2"><?php echo e($company['support_email'] ?? $tenant->email ?? 'support@renewpilot.com'); ?></p>
                        </div>
                    </div>
                    
                    <!-- To -->
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Bill To</h3>
                        <div class="space-y-1">
                            <p class="text-base font-bold text-gray-900"><?php echo e($client->name); ?></p>
                            <?php if($client->company): ?>
                                <p class="text-sm font-medium text-gray-700"><?php echo e($client->company); ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-600"><?php echo e($client->email); ?></p>
                            <?php if($client->phone): ?> 
                                <p class="text-sm text-gray-600"><?php echo e($client->phone); ?></p> 
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Invoice Meta Details Bar -->
                <div class="grid grid-cols-3 gap-6 p-6 rounded-2xl bg-gray-50 mb-14 print-exact border border-gray-100">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Issue Date</p>
                        <p class="text-sm font-bold text-gray-900"><?php echo e(\Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y')); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Due Date</p>
                        <p class="text-sm font-bold text-gray-900"><?php echo e(\Carbon\Carbon::parse($invoice->due_date)->format('M d, Y')); ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Payment Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest
                            <?php if($invoiceStatus === 'paid'): ?> bg-green-100 text-green-700 print-exact
                            <?php elseif($invoiceStatus === 'unpaid'): ?> bg-amber-100 text-amber-700 print-exact
                            <?php else: ?> bg-gray-200 text-gray-800 print-exact <?php endif; ?>">
                            <?php echo e(ucfirst($invoiceStatus)); ?>

                        </span>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div class="mb-14">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="py-3 border-b-2 border-gray-900 text-[10px] font-black text-gray-900 uppercase tracking-widest">Description</th>
                                <th class="py-3 border-b-2 border-gray-900 text-[10px] font-black text-gray-900 uppercase tracking-widest text-center w-24">Qty</th>
                                <th class="py-3 border-b-2 border-gray-900 text-[10px] font-black text-gray-900 uppercase tracking-widest text-right w-32">Price</th>
                                <th class="py-3 border-b-2 border-gray-900 text-[10px] font-black text-gray-900 uppercase tracking-widest text-right w-32">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="py-5 pr-4">
                                    <p class="text-sm font-bold text-gray-900"><?php echo e($item->description); ?></p>
                                </td>
                                <td class="py-5 text-center text-sm font-medium text-gray-600">
                                    <?php echo e((int)$item->quantity); ?>

                                </td>
                                <td class="py-5 text-right text-sm font-medium text-gray-600">
                                    <?php echo e($symbol); ?><?php echo e(number_format($item->unit_price, 2)); ?>

                                </td>
                                <td class="py-5 text-right text-sm font-black text-gray-900">
                                    <?php echo e($symbol); ?><?php echo e(number_format($item->total, 2)); ?>

                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bottom Section: Totals & Footer -->
            <div class="mt-8">
                <!-- Totals -->
                <div class="flex justify-end mb-16">
                    <div class="w-80 space-y-3 bg-gray-50 print-exact p-6 rounded-2xl border border-gray-100">
                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-gray-500 uppercase tracking-wider text-[10px]">Subtotal</span>
                            <span class="font-bold text-gray-900"><?php echo e($symbol); ?><?php echo e(number_format($invoice->subtotal, 2)); ?></span>
                        </div>
                        <?php if($invoice->tax_total > 0): ?>
                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-gray-500 uppercase tracking-wider text-[10px]">Tax</span>
                            <span class="font-bold text-gray-900"><?php echo e($symbol); ?><?php echo e(number_format($invoice->tax_total, 2)); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($invoice->amount_paid > 0): ?>
                        <div class="flex justify-between text-sm pt-2">
                            <span class="font-bold text-gray-500 uppercase tracking-wider text-[10px]">Amount Paid</span>
                            <span class="font-bold text-emerald-600">-<?php echo e($symbol); ?><?php echo e(number_format($invoice->amount_paid, 2)); ?></span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-2">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Balance Due</span>
                            <span class="text-3xl font-black text-indigo-600"><?php echo e($symbol); ?><?php echo e(number_format($invoice->total - $invoice->amount_paid, 2)); ?></span>
                        </div>
                        <?php else: ?>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-2">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Total Due</span>
                            <span class="text-3xl font-black text-indigo-600"><?php echo e($symbol); ?><?php echo e(number_format($invoice->total, 2)); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Footer details -->
                <div class="border-t-2 border-gray-100 pt-8">
                    <div class="grid grid-cols-2 gap-12">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Payment Details</p>
                            <div class="space-y-1">
                                <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-28 inline-block">Bank Name:</span> <?php echo e($company['bank_name'] ?? 'Chase Bank'); ?></p>
                                <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-28 inline-block">Account No:</span> <?php echo e($company['bank_account'] ?? '1234 5678 9000'); ?></p>
                                <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-28 inline-block">IFSC Code:</span> <?php echo e($company['bank_ifsc'] ?? $company['bank_routing'] ?? '123456789'); ?></p>
                                <?php if(!empty($company['bank_address'])): ?>
                                    <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-28 inline-block">Bank Address:</span> <?php echo e($company['bank_address']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Terms & Conditions</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium">
                                <?php echo e($company['terms_conditions'] ?? 'Please remit payment within 14 days of receiving this invoice. There will be a 1.5% interest charge per month on late invoices. Thank you for your business!'); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div id="payment-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closePaymentModal()"></div>

            <!-- Center elements -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Box -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Record Payment</h3>
                    <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
                <form action="<?php echo e(route('app.payments.store')); ?>" method="POST" class="p-6 space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Invoice Number</label>
                        <input type="text" value="<?php echo e($invoice->invoice_number); ?>" readonly disabled
                               class="w-full rounded-xl border-gray-200 bg-gray-50 text-gray-500 text-sm font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Amount (<?php echo e($symbol); ?>)</label>
                        <input type="number" name="amount" step="0.01" max="<?php echo e($invoice->total - $invoice->amount_paid); ?>" value="<?php echo e($invoice->total - $invoice->amount_paid); ?>" required
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-gray-900">
                        <p class="text-[10px] text-gray-400 mt-1">Remaining balance: <?php echo e($symbol); ?><?php echo e(number_format((float)($invoice->total - $invoice->amount_paid), 2)); ?></p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Method</label>
                        <select name="payment_method" required
                                class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI / GPay / PhonePe</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card Payment</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Transaction Reference (Optional)</label>
                        <input type="text" name="transaction_reference" placeholder="e.g. TXN12345678"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="pt-4 flex justify-end space-x-3">
                        <button type="button" onclick="closePaymentModal()" class="px-4 py-2 border border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 transition-all">
                            Save Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPaymentModal() {
            document.getElementById('payment-modal').classList.remove('hidden');
        }
        function closePaymentModal() {
            document.getElementById('payment-modal').classList.add('hidden');
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
<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/invoices/show.blade.php ENDPATH**/ ?>