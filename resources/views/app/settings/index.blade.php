<x-app-layout>
    <x-slot name="header">
        System Settings
    </x-slot>

    <div class="max-w-4xl space-y-8">
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 text-sm font-bold">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-bold space-y-1">
                @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-[10px]"></i>
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Company Settings -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center space-x-3">
                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600">
                    <i class="fas fa-building text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Company Profile</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('app.settings.company') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Agency Name</label>
                            <input type="text" name="company_name" value="{{ $company['company_name'] ?? '' }}" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Support Email</label>
                            <input type="email" name="support_email" value="{{ $company['support_email'] ?? '' }}" required
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Default Currency</label>
                            <div class="w-full rounded-xl border border-gray-100 bg-gray-50 px-4 py-2.5 text-sm font-bold text-gray-900 flex items-center">
                                <span class="mr-2 text-indigo-600">₹</span> INR - Indian Rupee
                                <input type="hidden" name="currency" value="INR">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Save Company Profile
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- SMTP Settings -->
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center space-x-3">
                <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center text-amber-600">
                    <i class="fas fa-paper-plane text-sm"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-sm uppercase tracking-widest">Email Configuration (SMTP)</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('app.settings.smtp') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Host</label>
                            <input type="text" name="host" value="{{ $smtp['host'] ?? '' }}" placeholder="smtp.mailtrap.io"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">SMTP Port</label>
                            <input type="number" name="port" value="{{ $smtp['port'] ?? '' }}" placeholder="2525"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Username</label>
                            <input type="text" name="username" value="{{ $smtp['username'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                            <input type="password" name="password" value="{{ $smtp['password'] ?? '' }}"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Encryption</label>
                            <select name="encryption" class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="tls" @selected(($smtp['encryption'] ?? '') === 'tls')>TLS</option>
                                <option value="ssl" @selected(($smtp['encryption'] ?? '') === 'ssl')>SSL</option>
                            </select>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Address</label>
                            <input type="email" name="from_address" value="{{ $smtp['from_address'] ?? '' }}" placeholder="noreply@agency.com"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">From Name</label>
                            <input type="text" name="from_name" value="{{ $smtp['from_name'] ?? '' }}" placeholder="Agency Name"
                                   class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Save Email Settings
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</x-app-layout>
