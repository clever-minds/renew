<x-app-layout>
    <x-slot name="header">
        Profile Settings
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-8">
        
        <!-- Profile Information Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                        <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-bold text-gray-700">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-gray-900" required>
                            @error('name') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-bold text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-gray-900" required>
                            @error('email') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-sm text-gray-600 font-medium" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">Saved.</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Update Password</h2>
                        <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <form method="POST" action="{{ route('profile.password') }}" class="space-y-6 max-w-xl">
                    @csrf
                    @method('put')

                    <div class="space-y-2">
                        <label for="current_password" class="block text-sm font-bold text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-gray-900" required>
                        @error('current_password') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-bold text-gray-700">New Password</label>
                        <input type="password" name="password" id="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-gray-900" required>
                        @error('password') <p class="text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 transition-all text-sm font-medium text-gray-900" required>
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold shadow-lg shadow-gray-200 hover:bg-gray-800 transition-all flex items-center">
                            <i class="fas fa-key mr-2"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
