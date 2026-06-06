<x-app-layout>
    <x-slot name="header">
        Services
    </x-slot>

    <div class="space-y-6">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest px-2">Service Catalog</h3>
            <a href="{{ route('app.services.create') }}" 
               class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100">
                <i class="fas fa-plus mr-2"></i> New Service
            </a>
        </div>

        <!-- Services Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="overflow-x-auto">
                <table class="w-full" id="services-table">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">HSN/SAC</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">GST Rate</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Billing Cycle</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#services-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('app.services.index') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'price', name: 'price'},
                    {data: 'hsn_code', name: 'hsn_code', defaultContent: '-'},
                    {data: 'tax_rate', name: 'tax_rate', render: function(data) { return data + '%'; }},
                    {data: 'billing_cycle', name: 'billing_cycle'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search services...",
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
        });
    </script>
</x-app-layout>

