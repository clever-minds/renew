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
        Invoices
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
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest px-2">Invoice Management</h3>
            <a href="<?php echo e(route('app.invoices.create')); ?>" 
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100">
                <i class="fas fa-plus mr-2"></i> New Invoice
            </a>
        </div>

        <!-- Invoices Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="overflow-x-auto">
                <table class="w-full" id="invoices-table">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#invoices-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "<?php echo e(route('app.invoices.index')); ?>",
                columns: [
                    {data: 'invoice_number', name: 'invoice_number'},
                    {data: 'client_name', name: 'clients.name'},
                    {data: 'total', name: 'total'},
                    {data: 'due_date', name: 'due_date'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search invoices...",
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
        });
    </script>
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

<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/invoices/index.blade.php ENDPATH**/ ?>