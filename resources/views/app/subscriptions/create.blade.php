<x-app-layout>
    <x-slot name="header">
        Create Subscription
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Subscription Setup</h3>
                <a href="{{ route('app.subscriptions.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-widest">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('app.subscriptions.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="client_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Client</label>
                        <select name="client_id" id="client_id" required
                                class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select a client...</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $selectedClient && $selectedClient->id === $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="service_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Service</label>
                        <select name="service_id" id="service_id" required
                                class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" data-price="{{ $service->price }}" data-cycle="{{ $service->billing_cycle->value }}">
                                    {{ $service->name }} - ${{ number_format($service->price, 2) }}/{{ ucfirst($service->billing_cycle->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Subscription Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">$</span>
                                <input type="number" name="price" id="price" step="0.01" min="0"
                                       class="w-full pl-8 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0.00">
                            </div>
                            <p class="mt-1 text-[10px] text-gray-400">Overrides the default service price if set.</p>
                        </div>

                        <div>
                            <label for="start_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required value="{{ date('Y-m-d') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="auto_invoice" id="auto_invoice" value="1" checked
                                   class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="auto_invoice" class="font-bold text-indigo-900">Auto-Invoicing</label>
                            <p class="text-xs text-indigo-700">System will automatically generate invoices based on the billing cycle.</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center justify-end space-x-3">
                        <a href="{{ route('app.subscriptions.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Create Subscription
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('service_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            if (price) {
                document.getElementById('price').value = price;
            }
        });
    </script>
</x-app-layout>