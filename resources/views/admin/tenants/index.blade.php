<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Tenants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-900">All Registered Agencies</h3>
                    <form action="{{ route('admin.tenants') }}" method="GET" class="flex space-x-2">
                        <input type="text" name="search" placeholder="Search tenants..." value="{{ request('search') }}"
                               class="px-4 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-indigo-700 transition">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Agency Name</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($tenants as $tenant)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $tenant->name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $tenant->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700">
                                        {{ $tenant->plan_name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                                        @if($tenant->status === 'active') bg-green-100 text-green-700 
                                        @elseif($tenant->status === 'trial') bg-blue-100 text-blue-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-sm">
                                    {{ \Carbon\Carbon::parse($tenant->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        @if($tenant->status !== 'suspended')
                                        <form action="{{ route('admin.tenants.suspend', $tenant->id) }}" method="POST">
                                            @csrf
                                            <button class="text-red-600 hover:text-red-900 font-bold text-xs">Suspend</button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.tenants.activate', $tenant->id) }}" method="POST">
                                            @csrf
                                            <button class="text-green-600 hover:text-green-900 font-bold text-xs">Activate</button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $tenants->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
