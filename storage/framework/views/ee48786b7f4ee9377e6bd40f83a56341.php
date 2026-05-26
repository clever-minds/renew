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
        <?php if(session('success')): ?>
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 text-sm font-bold shadow-sm">
                <i class="fas fa-check-circle text-base"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm font-bold shadow-sm">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest px-2">Payment History</h3>
            <button onclick="openPaymentModal()" 
               class="inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-emerald-100">
                <i class="fas fa-plus mr-2"></i> Record Payment
            </button>
        </div>

        <!-- Payments Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="overflow-x-auto">
                <table class="w-full" id="payments-table">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Reference</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('app.payments.index')); ?>",
                columns: [
                    {data: 'payment_date', name: 'payment_date'},
                    {data: 'client_name', name: 'clients.name'},
                    {data: 'invoice_number', name: 'invoices.invoice_number'},
                    {data: 'payment_method', name: 'payment_method'},
                    {data: 'transaction_reference', name: 'transaction_reference'},
                    {data: 'amount', name: 'amount'},
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search payments...",
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
        });
    </script>
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
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Unpaid Invoice</label>
                        <?php if($invoices->isEmpty()): ?>
                            <p class="text-sm text-gray-500 font-bold bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">No outstanding unpaid invoices found.</p>
                        <?php else: ?>
                            <select name="invoice_id" id="invoice-select" required onchange="handleInvoiceChange()"
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="" disabled selected>-- Choose Invoice --</option>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $remaining = $inv->total - $inv->amount_paid;
                                    ?>
                                    <option value="<?php echo e($inv->id); ?>" data-remaining="<?php echo e($remaining); ?>">
                                        <?php echo e($inv->invoice_number); ?> - <?php echo e($inv->client_name); ?> (Total: ₹<?php echo e(number_format((float)$inv->total, 2)); ?>, Bal: ₹<?php echo e(number_format((float)$remaining, 2)); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Amount (₹)</label>
                        <input type="number" name="amount" id="payment-amount" step="0.01" required min="0.01"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-gray-900">
                        <p class="text-[10px] text-gray-400 mt-1" id="remaining-hint">Remaining balance: ₹0.00</p>
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
                        <?php if(!$invoices->isEmpty()): ?>
                            <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 transition-all">
                                Save Payment
                            </button>
                        <?php endif; ?>
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
        function handleInvoiceChange() {
            var select = document.getElementById('invoice-select');
            var selectedOption = select.options[select.selectedIndex];
            if (selectedOption) {
                var remaining = parseFloat(selectedOption.getAttribute('data-remaining') || 0);
                document.getElementById('payment-amount').value = remaining.toFixed(2);
                document.getElementById('payment-amount').max = remaining.toFixed(2);
                document.getElementById('remaining-hint').innerText = "Remaining balance: ₹" + remaining.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
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
<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/payments/index.blade.php ENDPATH**/ ?>