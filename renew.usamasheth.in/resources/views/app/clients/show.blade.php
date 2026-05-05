<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Client Details') }}
            </h2>
            <div class="flex space-x-2">
                <button @click="$dispatch('open-drawer', 'edit-client-{{ $client->id }}')" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                    Edit Client
                </button>
                <a href="{{ route('app.clients.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                    Back to Clients
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Client Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Client Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->phone ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Company</dt>
                                    <dd class="text-sm text-gray-900">{{ $client->company ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $client->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($client->status ?? 'active') }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Billing Address -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Billing Address</h3>
                            @if($client->billing_address)
                                <div class="text-sm text-gray-900">
                                    @if(isset($client->billing_address['street']))
                                        <p>{{ $client->billing_address['street'] }}</p>
                                    @endif
                                    @if(isset($client->billing_address['city']) || isset($client->billing_address['state']) || isset($client->billing_address['zip']))
                                        <p>
                                            {{ $client->billing_address['city'] ?? '' }}
                                            {{ $client->billing_address['state'] ?? '' }}
                                            {{ $client->billing_address['zip'] ?? '' }}
                                        </p>
                                    @endif
                                    @if(isset($client->billing_address['country']))
                                        <p>{{ $client->billing_address['country'] }}</p>
                                    @endif
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No billing address provided</p>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($client->notes)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Notes</h3>
                            <p class="text-sm text-gray-900">{{ $client->notes }}</p>
                        </div>
                    @endif

                    <!-- Subscriptions -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Active Subscriptions</h3>
                            <a href="{{ route('app.subscriptions.create', ['client' => $client->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                                + Add Subscription
                            </a>
                        </div>
                        @if($client->subscriptions->count() > 0)
                            <div class="space-y-4">
                                @foreach($client->subscriptions as $subscription)
                                    <div class="border rounded p-4 shadow-sm">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-medium">{{ $subscription->service->name }}</h4>
                                                <p class="text-sm text-gray-600">{{ $subscription->service->description }}</p>
                                                <p class="text-sm text-gray-500">Next due: {{ $subscription->next_due_date ? $subscription->next_due_date->format('M j, Y') : 'N/A' }}</p>
                                            </div>
                                            <span class="px-2 text-xs rounded-full {{ $subscription->status->value === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($subscription->status->value) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No active subscriptions</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Client Drawer -->
    <x-drawer name="edit-client-{{ $client->id }}" title="Edit Client">
        <form method="POST" action="{{ route('app.clients.update', $client) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" value="{{ $client->name }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" value="{{ $client->email }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" value="{{ $client->phone }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="company" value="{{ $client->company }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="active" {{ $client->status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $client->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $client->notes }}</textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" @click="$dispatch('close-drawer')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    Update Client
                </button>
            </div>
        </form>
    </x-drawer>
</x-app-layout>