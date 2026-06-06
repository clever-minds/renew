<x-app-layout>
    <x-slot name="header">
        System Settings
    </x-slot>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <div class="max-w-4xl mx-auto space-y-8">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-700 text-sm font-bold">
                <i class="fas fa-check-circle text-emerald-500 text-lg"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2"><i class="fas fa-exclamation-circle text-[10px]"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <!-- Company Profile Card -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-white border-b border-gray-100 flex items-center space-x-3">
                <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                    <i class="fas fa-building text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Company Profile</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Basic information shown on invoices and communications</p>
                </div>
            </div>

            <form action="{{ route('app.settings.company') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8" id="company-form">
                @csrf

                {{-- ── LOGO ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-image text-[10px]"></i> Agency Logo
                    </h4>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden" id="logo-preview-wrap">
                            @if(!empty($company['logo_url']))
                                <img id="logo-preview" src="{{ asset('storage/' . $company['logo_url']) }}" class="w-full h-full object-contain p-1">
                            @else
                                <div id="logo-placeholder" class="text-center text-gray-300">
                                    <i class="fas fa-image text-3xl"></i>
                                    <p class="text-[9px] mt-1 font-bold uppercase tracking-wider">No Logo</p>
                                </div>
                                <img id="logo-preview" class="w-full h-full object-contain p-1 hidden">
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Upload Logo</label>
                            <input type="file" name="logo" id="logo-input" accept="image/*"
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all"
                                   style="border: none !important; padding: 0 !important; background: transparent !important;"
                                   onchange="previewLogo(this)">
                            <p class="text-[10px] text-gray-400 mt-2">JPG, PNG, SVG or WebP — max 2MB. Displayed on invoices &amp; PDF.</p>
                        </div>
                    </div>
                </div>

                {{-- ── BASIC INFO ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-[10px]"></i> Basic Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agency Name <span class="text-red-400">*</span></label>
                            <input type="text" name="company_name" value="{{ $company['company_name'] ?? '' }}" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. Pixel Digital Agency">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agency Tagline</label>
                            <input type="text" name="company_tagline" value="{{ $company['company_tagline'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. SaaS Solutions">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">GSTIN / VAT No.</label>
                            <input type="text" name="tax_number" value="{{ $company['tax_number'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. 27AADCB2230M1Z2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Currency <span class="text-red-400">*</span></label>
                            <select name="currency" required class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-semibold">
                                <option value="INR" {{ ($company['currency'] ?? 'INR') === 'INR' ? 'selected' : '' }}>₹ - INR (Indian Rupee)</option>
                                <option value="USD" {{ ($company['currency'] ?? '') === 'USD' ? 'selected' : '' }}>$ - USD (US Dollar)</option>
                                <option value="EUR" {{ ($company['currency'] ?? '') === 'EUR' ? 'selected' : '' }}>€ - EUR (Euro)</option>
                                <option value="GBP" {{ ($company['currency'] ?? '') === 'GBP' ? 'selected' : '' }}>£ - GBP (British Pound)</option>
                                <option value="AED" {{ ($company['currency'] ?? '') === 'AED' ? 'selected' : '' }}>د.إ - AED (UAE Dirham)</option>
                                <option value="CAD" {{ ($company['currency'] ?? '') === 'CAD' ? 'selected' : '' }}>C$ - CAD (Canadian Dollar)</option>
                                <option value="AUD" {{ ($company['currency'] ?? '') === 'AUD' ? 'selected' : '' }}>A$ - AUD (Australian Dollar)</option>
                                <option value="SGD" {{ ($company['currency'] ?? '') === 'SGD' ? 'selected' : '' }}>S$ - SGD (Singapore Dollar)</option>
                                <option value="JPY" {{ ($company['currency'] ?? '') === 'JPY' ? 'selected' : '' }}>¥ - JPY (Japanese Yen)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ── CONTACT INFO ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-phone-alt text-[10px]"></i> Contact Information
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Primary Email <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="support_email" value="{{ $company['support_email'] ?? '' }}" required
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="hello@agency.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Additional Email</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="additional_email" value="{{ $company['additional_email'] ?? '' }}"
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="billing@agency.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Primary Phone</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-phone"></i></span>
                                <input type="text" name="support_phone" value="{{ $company['support_phone'] ?? '' }}"
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="+91 98765 43210">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Additional Phone</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"><i class="fas fa-phone"></i></span>
                                <input type="text" name="additional_phone" value="{{ $company['additional_phone'] ?? '' }}"
                                       style="padding-left: 2.2rem !important;"
                                       class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="+91 98765 43211">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── ADDRESS ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-[10px]"></i> Address
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Shown on invoices)</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Address Line</label>
                            <input type="text" name="address_line1" value="{{ $company['address_line1'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="123 Business Avenue, Suite 100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">City</label>
                            <input type="text" name="address_city" value="{{ $company['address_city'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Mumbai">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">State / Country</label>
                            <input type="text" name="address_state" value="{{ $company['address_state'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Maharashtra, India">
                        </div>
                    </div>
                </div>

                {{-- ── BANK DETAILS ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-university text-[10px]"></i> Bank Details
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Printed on invoices)</span>
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ $company['bank_name'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. ICICI Bank, SBI">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Account Number</label>
                            <input type="text" name="bank_account" value="{{ $company['bank_account'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. 1234 5678 9000 1234">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">IFSC Code</label>
                            <input type="text" name="bank_ifsc" value="{{ $company['bank_ifsc'] ?? $company['bank_routing'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. ICIC0001234">
                            <input type="hidden" name="bank_routing" value="{{ $company['bank_routing'] ?? '' }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Bank Branch / Address</label>
                            <input type="text" name="bank_address" value="{{ $company['bank_address'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="e.g. MG Road Branch, Mumbai">
                        </div>
                    </div>
                </div>

                {{-- ── PAYMENT QR CODE ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-qrcode text-[10px]"></i> Payment QR Code
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Printed on invoices next to Bank Details)</span>
                    </h4>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-2xl border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden" id="qr-preview-wrap">
                            @if(!empty($company['qr_code_url']))
                                <img id="qr-preview" src="{{ asset('storage/' . $company['qr_code_url']) }}" class="w-full h-full object-contain p-1">
                            @else
                                <div id="qr-placeholder" class="text-center text-gray-300">
                                    <i class="fas fa-qrcode text-3xl"></i>
                                    <p class="text-[9px] mt-1 font-bold uppercase tracking-wider">No QR</p>
                                </div>
                                <img id="qr-preview" class="w-full h-full object-contain p-1 hidden">
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Upload QR Code</label>
                            <input type="file" name="qr_code" id="qr-input" accept="image/*"
                                   class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all"
                                   style="border: none !important; padding: 0 !important; background: transparent !important;"
                                   onchange="previewQr(this)">
                            <p class="text-[10px] text-gray-400 mt-2">JPG, PNG, SVG or WebP — max 2MB. Displayed on invoices &amp; PDF next to payment details.</p>
                        </div>
                    </div>
                </div>
                {{-- ── TERMS & CONDITIONS ── --}}
                <div>
                    <h4 class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fas fa-file-contract text-[10px]"></i> Terms & Conditions
                        <span class="text-[9px] text-gray-400 normal-case font-normal tracking-normal">(Printed at bottom of invoices)</span>
                    </h4>
                    <div id="terms-editor-container" class="bg-white" style="height: 150px; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem;">{!! $company['terms_conditions'] ?? '' !!}</div>
                    <input type="hidden" name="terms_conditions" id="terms_conditions">
                </div>

                <div class="pt-2 flex justify-end">
                    <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Company Profile
                    </button>
                </div>
            </form>
        </section>

        <!-- SMTP Settings -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-amber-50 to-white border-b border-gray-100 flex items-center space-x-3">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Email Configuration (SMTP)</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5">Used to send invoices and notifications to your clients</p>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('app.settings.smtp') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Host</label>
                            <input type="text" name="host" value="{{ $smtp['host'] ?? '' }}" placeholder="smtp.mailtrap.io"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Port</label>
                            <input type="number" name="port" value="{{ $smtp['port'] ?? '' }}" placeholder="587"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                            <input type="text" name="username" value="{{ $smtp['username'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                            <input type="password" name="password" value="{{ $smtp['password'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Encryption</label>
                            <select name="encryption" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="tls" @selected(($smtp['encryption'] ?? '') === 'tls')>TLS</option>
                                <option value="ssl" @selected(($smtp['encryption'] ?? '') === 'ssl')>SSL</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Address</label>
                            <input type="email" name="from_address" value="{{ $smtp['from_address'] ?? '' }}" placeholder="noreply@agency.com"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Name</label>
                            <input type="text" name="from_name" value="{{ $smtp['from_name'] ?? '' }}" placeholder="Agency Name"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-8 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-amber-100 transition-all flex items-center gap-2">
                            <i class="fas fa-save"></i> Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        function previewLogo(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('logo-preview');
                    const placeholder = document.getElementById('logo-placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewQr(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('qr-preview');
                    const placeholder = document.getElementById('qr-placeholder');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            var quill = new Quill('#terms-editor-container', {
                theme: 'snow',
                placeholder: 'e.g. Payment is due within 14 days of invoice date. Late payments may attract a 2% monthly interest charge...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['clean']
                    ]
                }
            });

            // To style the top toolbar radius to match our design
            const toolbar = document.querySelector('.ql-toolbar');
            if(toolbar) {
                toolbar.style.borderTopLeftRadius = '0.75rem';
                toolbar.style.borderTopRightRadius = '0.75rem';
                toolbar.style.borderColor = '#e5e7eb';
            }
            const container = document.querySelector('.ql-container');
            if(container) {
                container.style.borderColor = '#e5e7eb';
            }

            document.getElementById('company-form').addEventListener('submit', function() {
                var html = quill.root.innerHTML;
                if(html === '<p><br></p>') html = '';
                document.getElementById('terms_conditions').value = html;
            });
        });
    </script>
</x-app-layout>
