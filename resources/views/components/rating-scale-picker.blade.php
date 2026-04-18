@props([
    'current' => 5,
    'model' => 'rating',
    'label' => null,
])

@php($labelText = $label ?? __('Rating'))

<div {{ $attributes->class(['max-w-md']) }}>
    <span class="mb-2 block text-sm font-medium leading-6 text-zinc-800 dark:text-zinc-200">{{ $labelText }}</span>
    <div
        class="grid grid-cols-5 gap-2"
        role="radiogroup"
        aria-label="{{ $labelText }}"
    >
        @foreach (range(1, 5) as $n)
            @php($selected = (int) $current === (int) $n)
            <button
                type="button"
                wire:click="$set('{{ $model }}', {{ $n }})"
                role="radio"
                aria-checked="{{ $selected ? 'true' : 'false' }}"
                @class([
                    'min-h-11 rounded-lg border-2 text-center text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-zinc-950',
                    'border-amber-500 bg-amber-500 text-white shadow-md shadow-amber-500/30 ring-2 ring-amber-400 ring-offset-2 ring-offset-white dark:border-amber-400 dark:bg-amber-500 dark:text-white dark:shadow-amber-500/20 dark:ring-amber-300 dark:ring-offset-zinc-950' => $selected,
                    'border-zinc-300 bg-white text-zinc-700 hover:border-amber-400/80 hover:bg-amber-50/80 dark:border-zinc-600 dark:bg-zinc-800/80 dark:text-zinc-200 dark:hover:border-amber-500/60 dark:hover:bg-zinc-800' => ! $selected,
                ])
            >
                {{ $n }}
            </button>
        @endforeach
    </div>
</div>
