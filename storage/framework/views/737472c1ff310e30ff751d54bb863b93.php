<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'show' => false,
    'maxWidth' => 'md'
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name',
    'show' => false,
    'maxWidth' => 'md'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$maxWidth = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
][$maxWidth] ?? 'max-w-md';
?>

<div
    x-data="{ show: <?php echo \Illuminate\Support\Js::from($show)->toHtml() ?> }"
    x-on:open-drawer.window="if ($event.detail == '<?php echo e($name); ?>') show = true"
    x-on:close-drawer.window="if ($event.detail == '<?php echo e($name); ?>') show = false"
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
                class="w-screen <?php echo e($maxWidth); ?>"
            >
                <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                    <div class="px-6 py-6 bg-indigo-700">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-bold text-white">
                                <?php echo e($title ?? ''); ?>

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
                        <?php echo e($slot); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\ReNewPilot_new\resources\views/components/drawer.blade.php ENDPATH**/ ?>