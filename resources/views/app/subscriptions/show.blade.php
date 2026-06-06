@php
    $companySettings = \Illuminate\Support\Facades\DB::table('settings')
        ->where('tenant_id', session('tenant_id'))
        ->where('key', 'company_settings')
        ->first();
    $company = $companySettings ? json_decode($companySettings->value, true) : [];
    $currency = $company['currency'] ?? 'INR';
    $currencySymbols = [
        'INR' => '&#8377;',
        'USD' => '$',
        'EUR' => '&euro;',
        'GBP' => '&pound;',
        'AED' => 'د.إ',
        'CAD' => 'C$',
        'AUD' => 'A$',
        'SGD' => 'S$',
        'JPY' => '&yen;',
    ];
    $symbol = $currencySymbols[$currency] ?? '&#8377;';

    $hasCompanyGst = !empty($company['tax_number']);
    $hasClientGst = !empty($subscription->client->gst_number);
    $taxRate = ($hasCompanyGst && $hasClientGst) ? ($subscription->service->tax_rate ?? 0) : 0;
    $totalAmount = $subscription->price + ($subscription->price * $taxRate / 100);
@endphp
<x-app-layout>
    <x-slot name="header">
        Subscription Contract
    </x-slot>

    <div class="space-y-8 pb-12">
        <!-- Contract Header -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-indigo-900 via-indigo-800 to-indigo-900 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl font-black">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-1">
                                <h2 class="text-2xl font-black tracking-tight">{{ $subscription->service->name }}</h2>
                                <span class="px-3 py-0.5 rounded-full text-[8px] font-black uppercase tracking-[0.2em] bg-white/20 text-white border border-white/10">
                                    {{ $subscription->status->value }}
                                </span>
                            </div>
                            <p class="text-indigo-100 font-medium opacity-80">Contract for <span class="font-bold underline">{{ $subscription->client->name }}</span></p>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        @if($subscription->status->value === 'active')
                            <button onclick="openPaymentModal()" class="px-6 py-2.5 bg-indigo-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-900/20 hover:bg-indigo-600 transition-all flex items-center">
                                <i class="fas fa-file-invoice-dollar mr-2"></i> Collect Payment
                            </button>

                            <form method="POST" action="{{ route('app.subscriptions.suspend', $subscription) }}">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:bg-amber-600 transition-all">
                                    Suspend
                                </button>
                            </form>
                        @elseif($subscription->status->value === 'suspended')
                            <form method="POST" action="{{ route('app.subscriptions.activate', $subscription) }}">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-900/20 hover:bg-emerald-600 transition-all">
                                    Activate
                                </button>
                            </form>
                        @endif
                        
                        <button @click="$dispatch('open-modal', 'edit-subscription-{{ $subscription->id }}')" class="px-6 py-2.5 bg-white text-indigo-900 rounded-xl text-sm font-bold shadow-lg hover:bg-indigo-50 transition-all">
                            Edit Terms
                        </button>
                        
                        <a href="{{ route('app.subscriptions.index') }}" class="px-4 py-2.5 bg-white/10 text-white rounded-xl text-sm font-bold hover:bg-white/20 transition-all">
                            <i class="fas fa-list"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contract Stats -->
            <div class="p-8 grid grid-cols-1 md:grid-cols-4 gap-8 border-b border-gray-50">
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Recurring Price</p>
                    <p class="text-2xl font-black text-gray-900">{!! $symbol !!}{{ number_format($subscription->price, 2) }}</p>
                    <p class="text-[10px] text-gray-500 font-bold uppercase">{{ $subscription->service->billing_cycle->value }} cycle</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Contract Start</p>
                    <p class="text-lg font-black text-gray-900">{{ $subscription->start_date->format('M d, Y') }}</p>
                    <p class="text-[10px] text-indigo-600 font-bold uppercase">{{ $subscription->start_date->diffForHumans() }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Next Renewal</p>
                    <p class="text-lg font-black text-gray-900">{{ $subscription->next_due_date ? $subscription->next_due_date->format('M d, Y') : 'N/A' }}</p>
                    @if($subscription->next_due_date)
                        <p class="text-[10px] {{ $subscription->next_due_date->isPast() ? 'text-red-500' : 'text-emerald-600' }} font-bold uppercase">
                            {{ $subscription->next_due_date->isPast() ? 'Overdue' : 'Coming up' }}
                        </p>
                    @endif
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Auto Billing</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="w-2 h-2 rounded-full {{ $subscription->auto_invoice ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                        <p class="text-sm font-black text-gray-900 uppercase tracking-tighter">{{ $subscription->auto_invoice ? 'Enabled' : 'Disabled' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Client Snapshot -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-fit">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Client Snapshot</p>
                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-500 text-xl font-black">
                        {{ substr($subscription->client->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-gray-900">{{ $subscription->client->name }}</p>
                        <p class="text-xs text-gray-500">{{ $subscription->client->email }}</p>
                    </div>
                </div>
                <a href="{{ route('app.clients.show', $subscription->client) }}" class="flex items-center justify-center w-full py-3 bg-gray-50 text-gray-900 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-gray-100 transition-all">
                    View Full Profile
                </a>
            </div>

            <!-- Recent Billing History -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Related Invoices</p>
                </div>
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-invoice text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-bold text-gray-900">No invoices generated yet</p>
                    <p class="text-xs text-gray-500 mt-1">Invoices will appear here once they are generated automatically or manually.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Subscription Modal -->
    <x-modal name="edit-subscription-{{ $subscription->id }}" title="Edit Subscription Terms">
        <form method="POST" action="{{ route('app.subscriptions.update', $subscription) }}" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="client_id" value="{{ $subscription->client_id }}">

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Service Package</label>
                <select name="service_id" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ $subscription->service_id === $service->id ? 'selected' : '' }}>
                            {{ $service->name }} ({!! $symbol !!}{{ number_format($service->price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 font-bold">Custom Price ({{ $currency }})</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-800 text-sm font-semibold">{!! $symbol !!}</span>
                        <input type="number" name="price" value="{{ $subscription->price }}" step="0.01" min="0" required class="w-full pl-10 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold" style="padding-left: 2.75rem !important;">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Effective Date</label>
                    <input type="date" name="start_date" value="{{ $subscription->start_date->format('Y-m-d') }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
            </div>

            <div class="flex items-center p-4 bg-indigo-50/50 rounded-xl border border-indigo-100">
                <input type="hidden" name="auto_invoice" value="0">
                <input type="checkbox" name="auto_invoice" id="auto_invoice" value="1" {{ $subscription->auto_invoice ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="auto_invoice" class="ml-3 text-sm font-bold text-gray-700">Enable automatic invoice generation</label>
            </div>

            <div class="flex justify-between items-center pt-6 border-t border-gray-50">
                <button type="button" onclick="if(confirm('Are you sure you want to cancel this contract?')) { document.getElementById('cancel-form').submit(); }" class="text-xs font-black text-red-400 hover:text-red-600 uppercase tracking-widest">
                    Terminate Contract
                </button>
                <div class="flex space-x-3">
                    <button type="button" @click="$dispatch('close-modal', 'edit-subscription-{{ $subscription->id }}')" class="text-sm font-bold text-gray-400 hover:text-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                        Update Contract
                    </button>
                </div>
            </div>
        </form>
        <form id="cancel-form" action="{{ route('app.subscriptions.destroy', $subscription) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-modal>

    <!-- Record Payment Modal -->
    <div id="payment-modal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closePaymentModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <!-- Modal Box -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-100">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Collect Payment</h3>
                    <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fas fa-times text-base"></i>
                    </button>
                </div>
                <form action="{{ route('app.subscriptions.record_payment', $subscription) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl mb-4 text-xs text-indigo-700 leading-relaxed font-semibold">
                        <i class="fas fa-info-circle mr-1"></i> Recording this payment will automatically generate the invoice for the current cycle and mark it as paid.
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Amount ({!! $symbol !!})</label>
                        <input type="text" id="payment-amount" value="{{ number_format($totalAmount, 2) }}" readonly
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-gray-900 cursor-not-allowed">
                        <input type="hidden" name="amount" value="{{ number_format($totalAmount, 2, '.', '') }}">
                        @if($taxRate > 0)
                            <p class="text-[10px] text-emerald-600 font-bold mt-1">Total amount includes {{ $taxRate }}% GST.</p>
                        @else
                            <p class="text-[10px] text-gray-400 mt-1">Exact invoice total.</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Payment Method</label>
                        <select name="payment_method" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
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
                        <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-100 transition-all flex items-center">
                            <i class="fas fa-check mr-2"></i> Save Payment
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
</x-app-layout>