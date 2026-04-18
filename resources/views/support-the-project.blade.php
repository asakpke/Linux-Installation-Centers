<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-gradient-to-b from-zinc-50 via-[#FAFAF8] to-[#F5F5F0] text-[#1b1b18] antialiased dark:from-[#0c0c0c] dark:via-[#0a0a0a] dark:to-[#121211] dark:text-[#EDEDEC]">
        <header class="sticky top-0 z-10 w-full border-b border-[#e5e7eb]/90 bg-zinc-50/80 shadow-sm backdrop-blur-md dark:border-[#3E3E3A]/80 dark:bg-[#0c0c0c]/80">
            <div class="mx-auto flex w-full max-w-3xl flex-wrap items-center justify-between gap-4 px-4 py-5 sm:px-6">
                <a
                    href="{{ route('home') }}"
                    class="group flex min-w-0 items-center gap-3 text-left"
                >
                    <x-brand-logo-mark decorative />
                    <span class="text-sm font-medium text-[#1b1b18] group-hover:underline dark:text-[#EDEDEC]">
                        ← {{ __('Home') }}
                    </span>
                </a>
                @if (Route::has('login'))
                    <nav class="flex shrink-0 flex-wrap items-center gap-3 sm:gap-4">
                        <a href="{{ route('whats-new') }}" class="rounded-md px-3 py-2 text-sm font-medium text-[#1b1b18] hover:bg-black/5 dark:text-[#EDEDEC] dark:hover:bg-white/5">{{ __("What's new") }}</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-md border border-[#19140035] px-4 py-2 text-sm font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:hover:border-[#62605b]">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md px-4 py-2 text-sm font-medium hover:bg-black/5 dark:hover:bg-white/5">{{ __('Log in') }}</a>
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 pb-16 pt-2 sm:px-6">
            <h1 class="mb-2 text-3xl font-semibold tracking-tight text-[#1b1b18] dark:text-white sm:text-4xl">
                {{ __('Support the project') }}
            </h1>
            <p class="mb-10 text-lg text-[#4b5563] dark:text-[#A1A09A]">
                {{ __('Linux Installation Centers is built and paid for by one maintainer right now. This page explains what costs money today, what we need most, and how you can help—financially or not.') }}
            </p>

            <div class="space-y-10 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                <section>
                    <h2 class="mb-3 text-lg font-semibold text-[#1b1b18] dark:text-white">{{ __('Top priority: a real production domain') }}</h2>
                    <p class="mb-3">
                        {{ __('The site is easier to trust and share with a dedicated domain (for example LinuxInstallationCenters.com, Linux-IC.com, or a close available name). Registration and renewal are a concrete, recurring cost.') }}
                    </p>
                    <p class="mb-3">
                        {{ __('If you can sponsor or purchase a domain, the safest approach is to register it in a registrar account controlled by the project maintainer, or to purchase it and transfer ownership so DNS and renewal are not locked to a third party.') }}
                    </p>
                    <p>
                        {{ __('Open a GitHub issue or use your usual contact channel to coordinate names, budget, and transfer—no payment details are collected on this page.') }}
                    </p>
                </section>

                <section>
                    <h2 class="mb-3 text-lg font-semibold text-[#1b1b18] dark:text-white">{{ __('Other costs') }}</h2>
                    <ul class="list-inside list-disc space-y-2">
                        <li>{{ __('Hosting and TLS for the web application') }}</li>
                        <li>{{ __('Email and messaging providers later, as the product grows') }}</li>
                        <li>{{ __('Occasional tools and certificates (e.g. Apple/Google assets where applicable)') }}</li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-3 text-lg font-semibold text-[#1b1b18] dark:text-white">{{ __('Ways to help') }}</h2>
                    <ul class="list-inside list-disc space-y-2">
                        <li>{{ __('Sponsor the domain or hosting (coordinate via GitHub).') }}</li>
                        <li>{{ __('Star the repository and share Linux ICs with local Linux groups, universities, or friends who need installs.') }}</li>
                        <li>{{ __('File thoughtful issues: bugs, copy, accessibility, or feature ideas.') }}</li>
                        <li>{{ __('Sign up as a seeker or expert when the network in your city matters to you.') }}</li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-3 text-lg font-semibold text-[#1b1b18] dark:text-white">{{ __('Transparency') }}</h2>
                    <p>
                        {{ __('There is no separate legal entity or donation processor wired into this MVP yet. Any support is understood as informal help toward infrastructure and development time. Thank you for considering it.') }}
                    </p>
                </section>
            </div>

            <p class="mt-12 border-t border-[#e5e7eb] pt-8 text-center text-sm text-[#6b7280] dark:text-[#9ca3af] dark:border-[#3E3E3A]">
                <a href="{{ $github_url ?? 'https://github.com/asakpke/Linux-Installation-Centers' }}" class="text-[#2563eb] underline underline-offset-2 hover:text-[#1d4ed8] dark:text-[#60a5fa] dark:hover:text-[#93c5fd]" target="_blank" rel="noopener noreferrer">{{ __('GitHub repository') }}</a>
                ·
                <a href="{{ route('whats-new') }}" class="text-[#2563eb] underline underline-offset-2 hover:text-[#1d4ed8] dark:text-[#60a5fa] dark:hover:text-[#93c5fd]">{{ __("What's new") }}</a>
                ·
                <a href="{{ route('home') }}" class="text-[#2563eb] underline underline-offset-2 hover:text-[#1d4ed8] dark:text-[#60a5fa] dark:hover:text-[#93c5fd]">{{ __('Back to home') }}</a>
            </p>
        </main>
    </body>
</html>
