<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Subscription Details') }}
            </h2>
            <div class="flex space-x-2">
                @if($subscription->status->value === 'active')
                    <form method="POST" action="{{ route('app.subscriptions.suspend', $subscription) }}" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded shadow" onclick="return confirm('Are you sure you want to suspend this subscription?')">
                            Suspend
                        </button>
                    </form>
                @elseif($subscription->status->value === 'suspended')
                    <form method="POST" action="{{ route('app.subscriptions.activate', $subscription) }}" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                            Activate
                        </button>
                    </form>
                @endif
                <button @click="$dispatch('open-drawer', 'edit-subscription-{{ $subscription->id }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    Edit Subscription
                </button>
                <form method="POST" action="{{ route('app.subscriptions.destroy', $subscription) }}" class="inline" onsubmit="return confirm('Delete this subscription?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded shadow">
                        Delete
                    </button>
                </form>
                <a href="{{ route('app.subscriptions.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                    Back to Subscriptions
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subscription Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Subscription Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Client</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="{{ route('app.clients.show', $subscription->client) }}" class="text-indigo-600 hover:text-indigo-800">
                                            {{ $subscription->client->name }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Service</dt>
                                    <dd class="text-sm text-gray-900">
                                        <a href="{{ route('app.services.show', $subscription->service) }}" class="text-indigo-600 hover:text-indigo-800">
                                            {{ $subscription->service->name }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Price</dt>
                                    <dd class="text-sm text-gray-900">${{ number_format($subscription->price, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Billing Cycle</dt>
                                    <dd class="text-sm text-gray-900">{{ ucfirst($subscription->service->billing_cycle->value) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($subscription->status->value === 'active') bg-green-100 text-green-800
                                            @elseif($subscription->status->value === 'suspended') bg-yellow-100 text-yellow-800
                                            @elseif($subscription->status->value === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($subscription->status->value) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Dates & Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dates & Settings</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $subscription->start_date->format('M j, Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Next Due Date</dt>
                                    <dd class="text-sm text-gray-900">{{ $subscription->next_due_date ? $subscription->next_due_date->format('M j, Y') : 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Auto Invoice</dt>
                                    <dd class="text-sm text-gray-900">{{ $subscription->auto_invoice ? 'Yes' : 'No' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Invoices -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Related Invoices</h3>
                        <p class="text-gray-500">Invoice functionality will be implemented in the next phase</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-drawer name="edit-subscription-{{ $subscription->id }}" title="Edit Subscription">
        <form method="POST" action="{{ route('app.subscriptions.update', $subscription) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700">Client</label>
                <select name="client_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ $subscription->client_id === $client->id ? 'selected' : '' }}>{{ $client->name }} ({{ $client->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Service</label>
                <select name="service_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ $subscription->service_id === $service->id ? 'selected' : '' }}>
                            {{ $service->name }} - ${{ number_format($service->price, 2) }}/{{ ucfirst($service->billing_cycle->value) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="price" required step="0.01" min="0" value="{{ $subscription->price }}" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start Date</label>
                    <input type="date" name="start_date" required value="{{ $subscription->start_date->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="auto_invoice" value="0">
                <input type="checkbox" name="auto_invoice" value="1" {{ $subscription->auto_invoice ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <label class="text-sm text-gray-900">Auto-generate invoices for this subscription</label>
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" @click="$dispatch('close-drawer')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Update Subscription
                </button>
            </div>
        </form>
    </x-drawer>
</x-app-layout>