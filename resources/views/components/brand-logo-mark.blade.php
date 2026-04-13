@props([
    'decorative' => false,
])

<div
    {{ $attributes->class([
        'min-w-0 w-24 max-w-24 shrink-0 overflow-hidden sm:w-28 sm:max-w-28 lg:w-24 lg:max-w-24',
    ]) }}
    @if ($decorative) aria-hidden="true" @endif
>
    <div class="flex justify-center rounded-lg bg-white p-1.5 shadow-sm ring-1 ring-[#e5e7eb] dark:ring-[#3E3E3A]">
        <img
            src="{{ asset('images/linux-ics-logo-full.png') }}"
            alt="{{ $decorative ? '' : config('app.name') }}"
            class="block h-auto w-full max-w-full object-contain"
            width="96"
            height="96"
            decoding="async"
        />
    </div>
</div>
