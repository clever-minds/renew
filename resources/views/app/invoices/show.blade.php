<x-app-layout>
    <x-slot name="header">
        Invoice Detail
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-8 pb-12">
        <!-- Actions Toolbar -->
        <div class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center space-x-2">
                <a href="{{ route('app.invoices.index') }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-900">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-50 transition-all">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
                <a href="#" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </a>
            </div>
        </div>

        <!-- Invoice Document -->
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden print:shadow-none print:border-none">
            <!-- Header -->
            <div class="p-12 border-b border-gray-50 flex flex-col md:flex-row justify-between gap-8 bg-gray-50/30">
                <div class="space-y-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-gray-900">RenewPilot</span>
                    </div>
                    <div class="text-sm text-gray-500 leading-relaxed">
                        <p class="font-bold text-gray-900">Agency Name Placeholder</p>
                        <p>123 Business Avenue, Suite 100</p>
                        <p>New York, NY 10001</p>
                        <p>support@renewpilot.com</p>
                    </div>
                </div>

                <div class="text-right space-y-1">
                    <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tighter">Invoice</h1>
                    <p class="text-indigo-600 font-black text-lg">{{ $invoice->invoice_number }}</p>
                    <div class="pt-4 text-sm text-gray-500">
                        <p><span class="font-bold text-gray-900 uppercase text-[10px] tracking-widest mr-2">Issued:</span> {{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</p>
                        <p><span class="font-bold text-gray-900 uppercase text-[10px] tracking-widest mr-2">Due:</span> {{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Billing Info -->
            <div class="p-12 grid grid-cols-1 md:grid-cols-2 gap-12 border-b border-gray-50">
                <div>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Billed To</p>
                    <div class="space-y-1">
                        <p class="text-lg font-black text-gray-900">{{ $client->name }}</p>
                        <p class="text-sm text-gray-500">{{ $client->company ?? 'Independent' }}</p>
                        <p class="text-sm text-gray-500">{{ $client->email }}</p>
                        @if($client->phone) <p class="text-sm text-gray-500">{{ $client->phone }}</p> @endif
                    </div>
                </div>
                <div class="md:text-right">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Payment Status</p>
                    <div class="inline-flex flex-col items-end">
                        <span class="px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest 
                            @if($invoice->status === 'paid') bg-emerald-100 text-emerald-700 
                            @elseif($invoice->status === 'unpaid') bg-amber-100 text-amber-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $invoice->status }}
                        </span>
                        @if($invoice->status === 'paid')
                            <p class="text-[10px] text-emerald-600 font-bold mt-2 uppercase tracking-tighter">Fully Settled</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="p-0">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-12 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Description</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Qty</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Price</th>
                            <th class="px-12 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($items as $item)
                        <tr>
                            <td class="px-12 py-6">
                                <p class="text-sm font-bold text-gray-900">{{ $item->description }}</p>
                            </td>
                            <td class="px-8 py-6 text-center text-sm font-medium text-gray-600">
                                {{ (int)$item->quantity }}
                            </td>
                            <td class="px-8 py-6 text-right text-sm font-medium text-gray-600">
                                ${{ number_format($item->unit_price, 2) }}
                            </td>
                            <td class="px-12 py-6 text-right text-sm font-black text-gray-900">
                                ${{ number_format($item->total, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="p-12 bg-gray-50/30 flex justify-end">
                <div class="w-full max-w-xs space-y-4">
                    <div class="flex justify-between text-sm">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Subtotal</span>
                        <span class="font-bold text-gray-900">${{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->tax_total > 0)
                    <div class="flex justify-between text-sm">
                        <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Tax</span>
                        <span class="font-bold text-gray-900">${{ number_format($invoice->tax_total, 2) }}</span>
                    </div>
                    @endif
                    <div class="pt-4 border-t border-gray-200 flex justify-between items-center">
                        <span class="font-black text-gray-900 uppercase tracking-widest text-xs">Total Amount</span>
                        <span class="text-3xl font-black text-indigo-600">${{ number_format($invoice->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="p-12 border-t border-gray-50">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Terms & Conditions</p>
                <p class="text-xs text-gray-500 leading-relaxed max-w-xl">
                    Please make the payment by the due date. A late fee may be applicable for payments made after the deadline. For any queries regarding this invoice, please contact support@renewpilot.com.
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
