<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Service Details') }}
            </h2>
            <div class="flex space-x-2">
                <button @click="$dispatch('open-drawer', 'edit-service-{{ $service->id }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    Edit Service
                </button>
                <a href="{{ route('app.services.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                    Back to Services
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Service Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Service Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->description ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($service->price, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Billing Cycle</dt>
                                    <dd class="text-sm text-gray-900">{{ ucfirst($service->billing_cycle->value) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Statistics -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Active Subscriptions</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->subscriptions()->where('status', 'active')->count() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Total Subscriptions</dt>
                                    <dd class="text-sm text-gray-900">{{ $service->subscriptions()->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Active Subscriptions -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Active Subscriptions</h3>
                        @if($service->subscriptions()->where('status', 'active')->count() > 0)
                            <div class="space-y-4">
                                @foreach($service->subscriptions()->where('status', 'active')->with('client')->get() as $subscription)
                                    <div class="border rounded p-4 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium">{{ $subscription->client->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $subscription->client->email }}</p>
                                                <p class="text-sm text-gray-500">Next due: {{ $subscription->next_due_date ? $subscription->next_due_date->format('M j, Y') : 'N/A' }}</p>
                                            </div>
                                            <span class="px-2 text-xs rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No active subscriptions for this service</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Drawer -->
    <x-drawer name="edit-service-{{ $service->id }}" title="Edit Service">
        <form method="POST" action="{{ route('app.services.update', $service) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Service Name</label>
                <input type="text" name="name" value="{{ $service->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $service->description }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" value="{{ $service->price }}" step="0.01" min="0" required class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Billing Cycle</label>
                    <select name="billing_cycle" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="monthly" {{ $service->billing_cycle->value === 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="quarterly" {{ $service->billing_cycle->value === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                        <option value="semi-annually" {{ $service->billing_cycle->value === 'semi-annually' ? 'selected' : '' }}>Semi-Annually</option>
                        <option value="annually" {{ $service->billing_cycle->value === 'annually' ? 'selected' : '' }}>Annually</option>
                        <option value="one-time" {{ $service->billing_cycle->value === 'one-time' ? 'selected' : '' }}>One-Time</option>
                    </select>
                </div>
            </div>
            <div>
                <input type="hidden" name="is_active" value="0">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="text-sm text-gray-900">Active</span>
                </label>
            </div>
            <div class="flex justify-between items-center">
                <form method="POST" action="{{ route('app.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete Service
                    </button>
                </form>
                <div class="flex space-x-2">
                    <button type="button" @click="$dispatch('close-drawer')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Update Service
                    </button>
                </div>
            </div>
        </form>
    </x-drawer>
</x-app-layout>