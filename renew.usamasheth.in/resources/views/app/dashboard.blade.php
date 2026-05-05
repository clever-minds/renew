<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- MRR Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Monthly Recurring Revenue</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">${{ number_format($stats['mrr'] ?? 0, 2) }}</div>
                </div>

                <!-- Overdue Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Total Overdue</div>
                    <div class="mt-2 text-3xl font-bold text-red-600">${{ number_format($stats['overdue_amount'] ?? 0, 2) }}</div>
                </div>

                <!-- Renewals Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500 font-medium uppercase tracking-wide">Upcoming Renewals (30 Days)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $stats['upcoming_renewals_count'] ?? 0 }}</div>
                </div>
            </div>

            <!-- Lists Area -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Upcoming Renewals List -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Upcoming Renewals</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($upcomingRenewals as $sub)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $sub->client_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $sub->service_name }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-gray-900">${{ number_format((float)$sub->price, 2) }}</p>
                                    <p class="text-xs text-orange-600">{{ \Carbon\Carbon::parse($sub->next_due_date)->diffForHumans() }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-gray-500">No upcoming renewals.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Recent Payments List -->
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Recent Payments</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($recentPayments as $payment)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->client_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->invoice_number }} &middot; {{ ucfirst($payment->payment_method) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-green-600">+${{ number_format((float)$payment->amount, 2) }}</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-3 text-sm text-gray-500">No recent payments.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
