<x-app-layout>
    <x-slot name="header">
        Manage Agencies
    </x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-black text-gray-500 uppercase tracking-widest px-2">Agency Management</h3>
        </div>

        <!-- Tenants Table Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="overflow-x-auto">
                <table class="w-full" id="tenants-table">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Agency Name</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Plan</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Joined</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#tenants-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.tenants') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'plan_name', name: 'saas_plans.name'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search agencies...",
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
        });
    </script>
        </div>
    </div>
</x-app-layout>

