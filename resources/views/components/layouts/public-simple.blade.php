<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white text-zinc-900 antialiased dark:bg-zinc-900 dark:text-zinc-100">
        <header class="border-b border-zinc-200 bg-zinc-50 px-4 py-4 dark:border-zinc-700 dark:bg-zinc-950">
            <div class="mx-auto flex max-w-3xl flex-wrap items-center justify-between gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-medium text-zinc-800 hover:underline dark:text-zinc-100" wire:navigate>
                    <x-brand-logo-mark decorative />
                    {{ config('app.name') }}
                </a>
                <nav class="flex items-center gap-3 text-sm">
                    @auth
                        <flux:link :href="route('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:link>
                    @else
                        @if (Route::has('login'))
                            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>
        <main class="mx-auto max-w-3xl px-4 py-8">
            {{ $slot }}
        </main>
    </body>
</html>
