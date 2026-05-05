<x-app-layout>
    <x-slot name="header">
        Create Invoice
    </x-slot>

    <div class="max-w-5xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Invoice Information</h3>
                <a href="{{ route('app.invoices.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-widest">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('app.invoices.store') }}" class="space-y-8" id="invoice-form">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="md:col-span-2">
                            <label for="client_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Select Client</label>
                            <select name="client_id" id="client_id" required
                                    class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">Select a client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="issue_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Issue Date</label>
                            <input type="date" name="issue_date" id="issue_date" required value="{{ date('Y-m-d') }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label for="due_date" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Due Date</label>
                            <input type="date" name="due_date" id="due_date" required value="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Invoice Items</h3>
                            <button type="button" id="add-item" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-lg shadow-emerald-100">
                                <i class="fas fa-plus mr-2"></i> Add Item
                            </button>
                        </div>

                        <div id="items-container" class="space-y-4">
                            <!-- Template Row -->
                            <div class="item-row group p-5 bg-gray-50/50 rounded-2xl border border-gray-100 transition-all hover:bg-gray-50 hover:border-gray-200">
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                                    <div class="md:col-span-5">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Description</label>
                                        <input type="text" name="items[0][description]" required
                                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Service or product name...">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Quantity</label>
                                        <input type="number" name="items[0][quantity]" required min="1" value="1"
                                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm item-quantity">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Unit Price</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">$</span>
                                            <input type="number" name="items[0][unit_price]" required step="0.01" min="0"
                                                   class="w-full pl-7 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm item-price" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Row Total</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold">$</span>
                                            <input type="number" readonly step="0.01"
                                                   class="w-full pl-7 rounded-xl border-gray-200 bg-gray-100 text-gray-600 text-sm font-bold item-total" value="0.00">
                                        </div>
                                    </div>
                                    <div class="md:col-span-1 flex items-end justify-center">
                                        <button type="button" class="remove-item p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors" style="display: none;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Totals and Settings -->
                    <div class="pt-8 border-t border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-4">
                                <div class="max-w-xs">
                                    <label for="tax_rate" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Tax Rate (%)</label>
                                    <input type="number" name="tax_rate" id="tax_rate" step="0.01" min="0" max="100" value="0"
                                           class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="0.00">
                                </div>
                                <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                    <p class="text-xs text-indigo-700 leading-relaxed">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Tax will be calculated automatically based on the subtotal.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gray-50/50 p-6 rounded-2xl border border-gray-100 space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 font-medium">Subtotal</span>
                                    <span id="subtotal" class="font-bold text-gray-900">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500 font-medium">Tax Amount</span>
                                    <span id="tax" class="font-bold text-gray-900">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                                    <span class="text-lg font-extrabold text-gray-900 uppercase tracking-wider">Grand Total</span>
                                    <span id="total" class="text-2xl font-black text-indigo-600">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex items-center justify-end space-x-4">
                        <a href="{{ route('app.invoices.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            Discard
                        </a>
                        <button type="submit" class="px-10 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-xl shadow-indigo-100 transition-all transform hover:-translate-y-0.5">
                            Create Invoice
                        </button>
                    </div>
                </form>
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
                if (input.classList.contains('item-quantity')) {
                    input.value = '1';
                }
            });

            // Show remove button
            newItem.querySelector('.remove-item').style.display = 'block';

            container.appendChild(newItem);
            itemIndex++;
            updateTotals();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item') || e.target.closest('.remove-item')) {
                const row = e.target.closest('.item-row');
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    updateTotals();
                }
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

            document.getElementById('subtotal').textContent = '$' + subtotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('tax').textContent = '$' + tax.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            document.getElementById('total').textContent = '$' + total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // Initialize totals
        updateTotals();
    </script>
</x-app-layout>