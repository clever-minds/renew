<x-app-layout>
    <x-slot name="header">
        Invoices
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 text-sm font-bold shadow-sm">
                <i class="fas fa-check-circle text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl flex items-center gap-3 text-red-600 text-sm font-bold shadow-sm">
                <i class="fas fa-exclamation-circle text-base"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest px-2">Invoice Management</h3>
            <a href="{{ route('app.invoices.create') }}" 
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
                ajax: "{{ route('app.invoices.index') }}",
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
</x-app-layout>

