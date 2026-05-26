@php
    $settingsService = app(\App\Services\Settings\SettingsService::class);
    $currency = $settingsService->get(auth()->user()->tenant_id, 'currency', 'INR');
    $symbols = [
        'INR' => '&#8377;',
        'USD' => '&#36;',
        'EUR' => '&#8364;',
        'GBP' => '&#163;',
        'AED' => 'AED',
        'CAD' => 'C&#36;',
        'AUD' => 'A&#36;',
        'SGD' => 'S&#36;',
        'JPY' => '&#165;'
    ];
    $symbol = $symbols[$currency] ?? $currency;
@endphp
<x-app-layout>
    <x-slot name="header">
        Service Details
    </x-slot>

    <div class="space-y-8 pb-12">
        <!-- Service Header Card -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 bg-gradient-to-r from-slate-800 to-slate-900 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center space-x-6">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-white text-3xl font-black">
                            <i class="fas fa-concierge-bell"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-black">{{ $service->name }}</h2>
                            <p class="text-slate-400 font-medium opacity-80">{!! $symbol !!}{{ number_format($service->price, 2) }} / {{ ucfirst($service->billing_cycle->value) }}</p>
                            <div class="flex items-center mt-2 space-x-3">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $service->is_active ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }} border border-white/10">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button @click="$dispatch('open-modal', 'edit-service-{{ $service->id }}')" class="px-6 py-2.5 bg-white text-slate-900 rounded-xl text-sm font-bold shadow-lg hover:bg-slate-50 transition-all">
                            Edit Service
                        </button>
                        <a href="{{ route('app.services.index') }}" class="px-4 py-2.5 bg-slate-700 text-white rounded-xl text-sm font-bold hover:bg-slate-600 transition-all">
                            Back to Catalog
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="p-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Stats -->
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Total MRR</p>
                    <p class="text-2xl font-black text-gray-900">{!! $symbol !!}{{ number_format($service->subscriptions()->where('status', 'active')->count() * $service->price, 2) }}</p>
                    <p class="text-[10px] text-emerald-600 font-bold mt-1 uppercase">From active subs</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Active Subs</p>
                    <p class="text-2xl font-black text-gray-900">{{ $service->subscriptions()->where('status', 'active')->count() }}</p>
                    <p class="text-[10px] text-indigo-600 font-bold mt-1 uppercase">Current customers</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Cycle</p>
                    <p class="text-xl font-black text-gray-900 uppercase tracking-tighter">{{ $service->billing_cycle->value }}</p>
                    <p class="text-[10px] text-gray-500 font-bold mt-1 uppercase tracking-tighter">Billing Frequency</p>
                </div>
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Lifetime Subs</p>
                    <p class="text-2xl font-black text-gray-900">{{ $service->subscriptions()->count() }}</p>
                    <p class="text-[10px] text-gray-500 font-bold mt-1 uppercase">Total ever joined</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Description -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 h-fit">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Service Description</p>
                <div class="text-sm text-gray-600 leading-relaxed">
                    {{ $service->description ?? 'No description provided for this service.' }}
                </div>
            </div>

            <!-- Active Subscriptions List -->
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Currently Subscribed Clients</p>
                    <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-lg">
                        {{ $service->subscriptions()->where('status', 'active')->count() }} Active
                    </span>
                </div>
                
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/30">
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Client</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Next Due</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($service->subscriptions()->where('status', 'active')->with('client')->get() as $sub)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 font-bold text-xs">
                                            {{ substr($sub->client->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">{{ $sub->client->name }}</p>
                                            <p class="text-[10px] text-gray-400">{{ $sub->client->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-sm font-black text-gray-900">{!! $symbol !!}{{ number_format($sub->price, 2) }}</p>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="text-xs font-bold text-gray-500">{{ $sub->next_due_date ? $sub->next_due_date->format('M d, Y') : 'N/A' }}</p>
                                </td>
                                <td class="px-8 py-4 text-right space-x-3">
                                    <a href="{{ route('app.clients.show', $sub->client->id) }}" class="text-[10px] font-black text-indigo-600 hover:text-indigo-800 uppercase tracking-widest">
                                        Profile
                                    </a>
                                    <a href="{{ route('app.subscriptions.show', $sub->id) }}" class="text-[10px] font-black text-emerald-600 hover:text-emerald-800 uppercase tracking-widest">
                                        Contract
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic text-sm">
                                    No active subscriptions for this service.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <x-modal name="edit-service-{{ $service->id }}" title="Edit Service Catalog Item">
        <form method="POST" action="{{ route('app.services.update', $service) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Service Name</label>
                <input type="text" name="name" value="{{ $service->name }}" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Description</label>
                <textarea name="description" rows="3" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ $service->description }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Price</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-800 text-sm font-bold">{!! $symbol !!}</span>
                        <input type="number" name="price" value="{{ $service->price }}" step="0.01" min="0" required class="w-full pl-10 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" style="padding-left: 2.75rem !important;">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Billing Cycle</label>
                    <select name="billing_cycle" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @foreach(['monthly', 'quarterly', 'semi-annually', 'annually', 'one-time'] as $cycle)
                            <option value="{{ $cycle }}" {{ $service->billing_cycle->value === $cycle ? 'selected' : '' }}>{{ ucfirst($cycle) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-100">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_active" class="ml-3 text-sm font-bold text-gray-700">Active and available for new subscriptions</label>
            </div>

            <div class="flex justify-between items-center pt-6 border-t border-gray-50">
                <button type="button" onclick="if(confirm('Are you sure you want to delete this service?')) { document.getElementById('delete-form').submit(); }" class="text-xs font-bold text-red-400 hover:text-red-600 uppercase tracking-widest">
                    Delete Service
                </button>
                <div class="flex space-x-3">
                    <button type="button" @click="$dispatch('close-modal')" class="text-sm font-bold text-gray-400 hover:text-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
        <form id="delete-form" action="{{ route('app.services.destroy', $service) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </x-modal>
</x-app-layout>