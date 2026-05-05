<x-app-layout>
    <x-slot name="header">
        Edit Client
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800">Client Information</h3>
                <a href="{{ route('app.clients.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-700 uppercase tracking-widest">
                    <i class="fas fa-arrow-left mr-1"></i> Back to List
                </a>
            </div>
            
            <div class="p-6">
                <form method="POST" action="{{ route('app.clients.update', $client) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="John Doe">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" required
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="john@example.com">
                    </div>

                    <div>
                        <label for="company" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Company (Optional)</label>
                        <input type="text" name="company" id="company" value="{{ old('company', $client->company) }}"
                               class="w-full rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Acme Inc.">
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex items-center justify-end space-x-3">
                        <a href="{{ route('app.clients.index') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-100 transition-all">
                            Update Client
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>