@props([
    'name',
    'show' => false,
    'maxWidth' => 'md'
])

@php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth] ?? 'max-w-md';
@endphp

<div
    x-data="{ show: @js($show) }"
    x-on:open-drawer.window="if ($event.detail == '{{ $name }}') show = true"
    x-on:close-drawer.window="if ($event.detail == '{{ $name }}') show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 overflow-hidden z-50"
    style="display: none;"
>
    <div class="absolute inset-0 overflow-hidden">
        <div
            x-show="show"
            x-transition:enter="ease-in-out duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in-out duration-500"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"
            x-on:click="show = false"
        ></div>

        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div
                x-show="show"
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-screen {{ $maxWidth }}"
            >
                <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                    <div class="px-6 py-6 bg-indigo-700">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-bold text-white">
                                {{ $title ?? '' }}
                            </h2>
                            <div class="ml-3 h-7 flex items-center">
                                <button
                                    x-on:click="show = false"
                                    class="bg-indigo-700 rounded-md text-indigo-200 hover:text-white focus:outline-none"
                                >
                                    <span class="sr-only">Close panel</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="relative flex-1 py-6 px-6">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
