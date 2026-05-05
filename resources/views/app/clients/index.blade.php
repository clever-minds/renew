<x-app-layout>
    <x-slot name="header">
        Clients
    </x-slot>

    <div class="space-y-6">
        <!-- Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest px-2">Client Management</h3>
            <button x-data @click="$dispatch('open-modal', 'create-client')" 
                    class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-indigo-100">
                <i class="fas fa-plus mr-2"></i> New Client
            </button>
        </div>

        <!-- Clients Table Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="overflow-x-auto">
                <table class="w-full" id="clients-table">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Company</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
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
            var table = $('#clients-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('app.clients.index') }}",
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'company', name: 'company'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search clients...",
                    lengthMenu: "_MENU_ entries",
                },
                dom: '<"flex flex-col md:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4 gap-4"ip>',
            });
        });
    </script>
    
    <!-- Create Client Modal -->
    <x-modal name="create-client" title="Add New Client">
        <form method="POST" action="{{ route('app.clients.store') }}" class="space-y-5 p-1">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Full Name</label>
                    <input type="text" name="name" required class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="John Doe">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Email Address</label>
                    <input type="email" name="email" required class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="john@example.com">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Company (Optional)</label>
                    <input type="text" name="company" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="Acme Inc.">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Phone Number</label>
                    <input type="text" name="phone" class="block w-full rounded-xl border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm text-gray-900" placeholder="+1 (555) 000-0000">
                </div>
            </div>
            
            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-gray-100 mt-6">
                <button type="button" x-on:click="show = false" class="px-4 py-2 text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                    Create Client
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>

