<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Global KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Tenants -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 border-l-8 border-blue-500">
                    <div class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Tenants</div>
                    <div class="mt-2 text-4xl font-black text-gray-900">{{ $totalTenants }}</div>
                </div>

                <!-- Active Tenants -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 border-l-8 border-green-500">
                    <div class="text-sm text-gray-500 font-bold uppercase tracking-wider">Active Tenants</div>
                    <div class="mt-2 text-4xl font-black text-gray-900">{{ $activeTenants }}</div>
                </div>

                <!-- SaaS Revenue -->
                <div class="bg-white overflow-hidden shadow-xl rounded-2xl p-6 border-l-8 border-indigo-600">
                    <div class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total SaaS Revenue</div>
                    <div class="mt-2 text-4xl font-black text-indigo-600">${{ number_format($totalSaaSRevenue, 2) }}</div>
                </div>
            </div>

            <!-- Recent Tenants Table -->
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">Recently Joined Tenants</h3>
                    <a href="{{ route('admin.tenants') }}" class="text-indigo-600 hover:text-indigo-800 font-bold text-sm">View All Tenants →</a>
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
                            @forelse($recentTenants as $tenant)
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
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">No tenants found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
