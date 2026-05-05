<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create Invoice') }}
            </h2>
            <a href="{{ route('app.invoices.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                Back to Invoices
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('app.invoices.store') }}" class="space-y-6" id="invoice-form">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_id" class="block text-sm font-medium text-gray-700">Client</label>
                                <select name="client_id" id="client_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select a client...</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">
                                            {{ $client->name }} ({{ $client->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tax_rate" class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                                <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                                <input type="date" name="issue_date" id="issue_date" required value="{{ date('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" id="due_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Invoice Items</h3>
                                <button type="button" id="add-item" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow">
                                    + Add Item
                                </button>
                            </div>

                            <div id="items-container" class="space-y-4">
                                <div class="item-row border rounded p-4 bg-gray-50">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <div class="md:col-span-5">
                                            <label class="block text-sm font-medium text-gray-700">Description</label>
                                            <input type="text" name="items[0][description]" required
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                            <input type="number" name="items[0][quantity]" required min="1" value="1"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 item-quantity">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                            <input type="number" name="items[0][unit_price]" required step="0.01" min="0"
                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 item-price">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700">Total</label>
                                            <input type="number" readonly step="0.01"
                                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm item-total">
                                        </div>
                                        <div class="md:col-span-1 flex items-end">
                                            <button type="button" class="remove-item bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded shadow" style="display: none;">
                                                ×
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="border-t pt-4">
                            <div class="flex justify-end">
                                <div class="w-64 space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">Subtotal:</span>
                                        <span id="subtotal" class="text-sm text-gray-900">$0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm font-medium text-gray-700">Tax:</span>
                                        <span id="tax" class="text-sm text-gray-900">$0.00</span>
                                    </div>
                                    <div class="flex justify-between border-t pt-2">
                                        <span class="text-lg font-medium text-gray-900">Total:</span>
                                        <span id="total" class="text-lg font-medium text-gray-900">$0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('app.invoices.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Create Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = 1;

        document.getElementById('add-item').addEventListener('click', function() {
            const container = document.getElementById('items-container');
            const newItem = document.querySelector('.item-row').cloneNode(true);

            // Update input names
            const inputs = newItem.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name.replace('[0]', '[' + itemIndex + ']');
                input.name = name;
                input.value = '';
                if (input.classList.contains('item-total')) {
                    input.value = '0.00';
                }
            });

            // Show remove button
            newItem.querySelector('.remove-item').style.display = 'block';

            container.appendChild(newItem);
            itemIndex++;
            updateTotals();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item')) {
                e.target.closest('.item-row').remove();
                updateTotals();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-quantity') || e.target.classList.contains('item-price')) {
                updateRowTotal(e.target.closest('.item-row'));
                updateTotals();
            }
        });

        document.getElementById('tax_rate').addEventListener('input', updateTotals);

        function updateRowTotal(row) {
            const quantity = parseFloat(row.querySelector('.item-quantity').value) || 0;
            const price = parseFloat(row.querySelector('.item-price').value) || 0;
            const total = quantity * price;
            row.querySelector('.item-total').value = total.toFixed(2);
        }

        function updateTotals() {
            let subtotal = 0;
            document.querySelectorAll('.item-total').forEach(totalInput => {
                subtotal += parseFloat(totalInput.value) || 0;
            });

            const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
            const tax = subtotal * (taxRate / 100);
            const total = subtotal + tax;

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('tax').textContent = '$' + tax.toFixed(2);
            document.getElementById('total').textContent = '$' + total.toFixed(2);
        }

        // Initialize totals
        updateTotals();
    </script>
</x-app-layout>