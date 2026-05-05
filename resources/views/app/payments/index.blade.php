<x-app-layout>
    <x-slot name="header">
        Payments
    </x-slot>

    <div class="space-y-6">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest px-2">Payment History</h3>
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
                ajax: "{{ route('app.payments.index') }}",
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
</x-app-layout>
