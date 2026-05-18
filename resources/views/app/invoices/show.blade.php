<x-app-layout>
    <x-slot name="header">
        Invoice Detail
    </x-slot>

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
        <!-- Actions Toolbar -->
        <div class="max-w-[210mm] mx-auto mb-8 flex items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-gray-100 print:hidden">
            <div class="flex items-center space-x-4">
                <a href="{{ route('app.invoices.index') }}" class="p-2 text-gray-400 hover:text-gray-800 rounded-lg transition-colors bg-gray-50 hover:bg-gray-100">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Invoice {{ $invoice->invoice_number }}</h2>
                    <p class="text-xs text-gray-500">Professional A4 Document</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center">
                    <i class="fas fa-print mr-2 text-gray-400"></i> Print
                </button>
                <a href="{{ route('app.invoices.download', $invoice->id) }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all flex items-center">
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
                        <div class="w-14 h-14 bg-indigo-600 rounded-2xl flex items-center justify-center text-white text-2xl print-exact">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div>
                            <span class="text-3xl font-black tracking-tight text-gray-900">RenewPilot</span>
                            <p class="text-[10px] text-indigo-600 font-bold tracking-widest uppercase mt-1">SaaS Solutions</p>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <h1 class="text-5xl font-black text-gray-900 tracking-tighter mb-2">INVOICE</h1>
                        <p class="text-lg font-bold text-gray-400">#{{ $invoice->invoice_number }}</p>
                    </div>
                </div>

                <!-- Addresses Section -->
                <div class="grid grid-cols-2 gap-12 mb-12">
                    <!-- From -->
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">From</h3>
                        <div class="space-y-1">
                            <p class="text-base font-bold text-gray-900">RenewPilot Inc.</p>
                            <p class="text-sm text-gray-600">123 Business Avenue, Suite 100</p>
                            <p class="text-sm text-gray-600">New York, NY 10001</p>
                            <p class="text-sm text-gray-600 mt-2">support@renewpilot.com</p>
                        </div>
                    </div>
                    
                    <!-- To -->
                    <div>
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 border-b border-gray-100 pb-2">Bill To</h3>
                        <div class="space-y-1">
                            <p class="text-base font-bold text-gray-900">{{ $client->name }}</p>
                            @if($client->company)
                                <p class="text-sm font-medium text-gray-700">{{ $client->company }}</p>
                            @endif
                            <p class="text-sm text-gray-600">{{ $client->email }}</p>
                            @if($client->phone) 
                                <p class="text-sm text-gray-600">{{ $client->phone }}</p> 
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoice Meta Details Bar -->
                <div class="grid grid-cols-3 gap-6 p-6 rounded-2xl bg-gray-50 mb-14 print-exact border border-gray-100">
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Issue Date</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($invoice->issue_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Due Date</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Payment Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest
                            @if($invoice->status === 'paid') bg-green-100 text-green-700 print-exact
                            @elseif($invoice->status === 'unpaid') bg-amber-100 text-amber-700 print-exact
                            @else bg-gray-200 text-gray-800 print-exact @endif">
                            {{ $invoice->status }}
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
                            @foreach($items as $item)
                            <tr>
                                <td class="py-5 pr-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $item->description }}</p>
                                </td>
                                <td class="py-5 text-center text-sm font-medium text-gray-600">
                                    {{ (int)$item->quantity }}
                                </td>
                                <td class="py-5 text-right text-sm font-medium text-gray-600">
                                    ₹{{ number_format($item->unit_price, 2) }}
                                </td>
                                <td class="py-5 text-right text-sm font-black text-gray-900">
                                    ₹{{ number_format($item->total, 2) }}
                                </td>
                            </tr>
                            @endforeach
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
                            <span class="font-bold text-gray-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        @if($invoice->tax_total > 0)
                        <div class="flex justify-between text-sm">
                            <span class="font-bold text-gray-500 uppercase tracking-wider text-[10px]">Tax</span>
                            <span class="font-bold text-gray-900">₹{{ number_format($invoice->tax_total, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200 mt-2">
                            <span class="text-xs font-black text-gray-900 uppercase tracking-widest">Total Due</span>
                            <span class="text-3xl font-black text-indigo-600">₹{{ number_format($invoice->total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Footer details -->
                <div class="border-t-2 border-gray-100 pt-8">
                    <div class="grid grid-cols-2 gap-12">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Payment Details</p>
                            <div class="space-y-1">
                                <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-16 inline-block">Bank:</span> Chase Bank</p>
                                <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-16 inline-block">Account:</span> 1234 5678 9000</p>
                                <p class="text-xs font-semibold text-gray-700"><span class="text-gray-400 font-normal w-16 inline-block">Routing:</span> 123456789</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Terms & Conditions</p>
                            <p class="text-xs text-gray-500 leading-relaxed font-medium">
                                Please remit payment within 14 days of receiving this invoice. There will be a 1.5% interest charge per month on late invoices. Thank you for your business!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
