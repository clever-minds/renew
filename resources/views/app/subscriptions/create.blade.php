<x-app-layout>
    <x-slot name="header">
        Create Subscription
    </x-slot>

    <div class="max-w-2xl" x-data="{
        openAddClientModal: false,
        clientName: '',
        clientEmail: '',
        clientPhone: '',
        clientCompany: '',
        clientGst: '',
        clientAddressLine1: '',
        clientAddressCity: '',
        clientAddressCountry: 'India',
        isSaving: false,
        errorMessage: '',
        errors: {},
        async submitClient() {
            if (!this.clientName) {
                this.errorMessage = 'Client Name is required.';
                return;
            }
            this.isSaving = true;
            this.errorMessage = '';
            this.errors = {};
            try {
                const response = await fetch('{{ route('app.clients.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        name: this.clientName,
                        email: this.clientEmail || null,
                        phone: this.clientPhone || null,
                        company: this.clientCompany || null,
                        gst_number: this.clientGst || null,
                        billing_address: {
                            line1: this.clientAddressLine1 || null,
                            city: this.clientAddressCity || null,
                            country: this.clientAddressCountry || 'India'
                        }
                    })
                });
                const result = await response.json();
                if (response.ok && result.success) {
                    const selectEl = document.getElementById('client_id');
                    const newOption = new Option(result.client.name + ' (' + (result.client.email || 'No email') + ')', result.client.id, true, true);
                    selectEl.add(newOption);
                    selectEl.dispatchEvent(new Event('change'));
                    
                    this.clientName = '';
                    this.clientEmail = '';
                    this.clientPhone = '';
                    this.clientCompany = '';
                    this.clientGst = '';
                    this.clientAddressLine1 = '';
                    this.clientAddressCity = '';
                    this.clientAddressCountry = 'India';
                    this.openAddClientModal = false;
                } else {
                    this.errorMessage = result.message || 'Validation failed.';
                    if (result.errors) {
                        this.errors = result.errors;
                    }
                }
            } catch (err) {
                this.errorMessage = 'An error occurred. Please try again.';
                console.error(err);
            } finally {
                this.isSaving = false;
            }
        }
    }">
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
                        <div class="flex justify-between items-center mb-2">
                            <label for="client_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Select Client</label>
                            <button type="button" @click="openAddClientModal = true" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-wider flex items-center">
                                <i class="fas fa-plus-circle mr-1"></i> Add New Client
                            </button>
                        </div>
                        <select name="client_id" id="client_id" required
                                @change="if($el.value === 'new') { openAddClientModal = true; $el.value = ''; }"
                                class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Select a client...</option>
                            <option value="new" class="text-indigo-600 font-bold bg-indigo-50">+ Add New Client...</option>
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
                                    {{ $service->name }} - ₹{{ number_format($service->price, 2) }}/{{ ucfirst($service->billing_cycle->value) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Subscription Price</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">₹</span>
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
    <!-- Premium Add Client Modal -->
    <div x-show="openAddClientModal" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Box Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100"
                 @click.away="if(!isSaving) openAddClientModal = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <!-- Header -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-base font-bold text-gray-800 flex items-center">
                        <i class="fas fa-user-plus mr-2 text-indigo-600"></i>
                        Add New Client
                    </h3>
                    <button type="button" @click="openAddClientModal = false" class="text-gray-400 hover:text-gray-600 transition-colors" :disabled="isSaving">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Content Form -->
                <form @submit.prevent="submitClient()" class="p-6 space-y-4">
                    <!-- Global error message -->
                    <div x-show="errorMessage" class="p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 flex items-start space-x-2" x-cloak>
                        <i class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                        <span x-text="errorMessage"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Client Name <span class="text-red-500">*</span></label>
                        <input type="text" x-model="clientName" required placeholder="Aarav Sharma"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <template x-if="errors.name">
                            <p class="text-red-500 text-xs mt-1" x-text="errors.name[0]"></p>
                        </template>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" x-model="clientEmail" placeholder="aarav@example.com"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <template x-if="errors.email">
                            <p class="text-red-500 text-xs mt-1" x-text="errors.email[0]"></p>
                        </template>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Mobile Number</label>
                            <input type="text" x-model="clientPhone" placeholder="+91 98765 43210"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <template x-if="errors.phone">
                                <p class="text-red-500 text-xs mt-1" x-text="errors.phone[0]"></p>
                            </template>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Company Name</label>
                            <input type="text" x-model="clientCompany" placeholder="Tata Consultancy Services"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <template x-if="errors.company">
                                <p class="text-red-500 text-xs mt-1" x-text="errors.company[0]"></p>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">GST Number (Optional)</label>
                        <input type="text" x-model="clientGst" placeholder="27AAAAA1111A1Z1"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <template x-if="errors.gst_number">
                            <p class="text-red-500 text-xs mt-1" x-text="errors.gst_number[0]"></p>
                        </template>
                    </div>

                    <div class="bg-gray-50/50 p-4 rounded-xl border border-gray-100 space-y-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Billing Address</h4>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Street Address</label>
                            <input type="text" x-model="clientAddressLine1" placeholder="123, MG Road"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">City</label>
                                <input type="text" x-model="clientAddressCity" placeholder="Mumbai"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-600 uppercase tracking-wider mb-1">Country</label>
                                <input type="text" x-model="clientAddressCountry" placeholder="India"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-end space-x-3">
                        <button type="button" @click="openAddClientModal = false" :disabled="isSaving"
                                class="px-5 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 transition-colors uppercase tracking-wider">
                            Cancel
                        </button>
                        <button type="submit" :disabled="isSaving"
                                class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white text-xs font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all uppercase tracking-wider flex items-center">
                            <template x-if="isSaving">
                                <i class="fas fa-spinner fa-spin mr-2"></i>
                            </template>
                            <span x-text="isSaving ? 'Saving...' : 'Save Client'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>