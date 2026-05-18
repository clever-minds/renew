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
        Revenue Report
     <?php $__env->endSlot(); ?>

    <div class="space-y-8">
        <!-- Date Filter Card -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <form method="GET" action="<?php echo e(route('app.reports.revenue')); ?>" class="flex flex-col md:flex-row md:items-end gap-4">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Start Date</label>
                    <input type="date" name="start_date" value="<?php echo e($startDate); ?>" 
                           class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">End Date</label>
                    <input type="date" name="end_date" value="<?php echo e($endDate); ?>" 
                           class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <button type="submit" class="w-full md:w-auto px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                        Update Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Total Revenue Card -->
            <div class="lg:col-span-1 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center">
                <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Total Revenue</p>
                <p class="text-4xl font-extrabold text-gray-900 mt-2">₹<?php echo e(number_format($totalRevenue, 2)); ?></p>
                <div class="mt-6 space-y-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b pb-2">By Payment Method</p>
                    <?php $__currentLoopData = $revenueByMethod; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method => $amount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 capitalize"><?php echo e(str_replace('_', ' ', $method)); ?></span>
                            <span class="text-sm font-bold text-gray-900">₹<?php echo e(number_format($amount, 2)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-6">Revenue Mix</h3>
                <div class="h-64 flex items-center justify-center">
                    <?php if($revenueByMethod->isEmpty()): ?>
                        <div class="text-center text-gray-400">
                            <i class="fas fa-chart-pie text-4xl mb-2"></i>
                            <p>No data available for the selected period.</p>
                        </div>
                    <?php else: ?>
                        <canvas id="revenueChart"></canvas>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                <h3 class="font-bold text-gray-800">Transaction Details</h3>
                <button class="text-xs font-semibold text-indigo-600 hover:text-indigo-700">Export CSV</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Invoice</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-xs font-bold text-gray-500">
                                    <?php echo e(\Carbon\Carbon::parse($payment->payment_date)->format('M d, Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    <?php echo e($payment->client_name); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-indigo-600 font-medium">
                                    <?php echo e($payment->invoice_number); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                        <?php echo e(str_replace('_', ' ', $payment->payment_method)); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-emerald-600">
                                    +₹<?php echo e(number_format((float)$payment->amount, 2)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No transactions in this period.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if(!$revenueByMethod->isEmpty()): ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($revenueByMethod->keys()); ?>,
                    datasets: [{
                        data: <?php echo json_encode($revenueByMethod->values()); ?>,
                        backgroundColor: [
                            '#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: "'Inter', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
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
<?php endif; ?>
<?php /**PATH /var/www/html/ReNewPilot_new/resources/views/app/reports/revenue.blade.php ENDPATH**/ ?>