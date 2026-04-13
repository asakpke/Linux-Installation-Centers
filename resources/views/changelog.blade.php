<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased">
        <header class="mx-auto w-full max-w-3xl px-4 py-6 sm:px-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
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
                    <nav class="flex shrink-0 items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="rounded-md border border-[#19140035] px-4 py-2 text-sm font-medium hover:border-[#1915014a] dark:border-[#3E3E3A] dark:hover:border-[#62605b]">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md px-4 py-2 text-sm font-medium hover:bg-black/5 dark:hover:bg-white/5">{{ __('Log in') }}</a>
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <main class="mx-auto max-w-3xl px-4 pb-16 sm:px-6">
            <h1 class="mb-2 text-3xl font-semibold tracking-tight text-[#1b1b18] dark:text-white sm:text-4xl">
                {{ __("What's new") }}
            </h1>
            <p class="mb-10 text-lg text-[#4b5563] dark:text-[#A1A09A]">
                {{ __('Recent updates to Linux Installation Centers. Newest first.') }}
            </p>

            <ol class="relative space-y-10 border-s border-[#e5e7eb] ps-6 dark:border-[#3E3E3A]">
                <li class="relative">
                    <span class="absolute -start-[calc(0.5rem+1px)] mt-1.5 h-3 w-3 rounded-full border-2 border-[#1b1b18] bg-[#FDFDFC] dark:border-white dark:bg-[#0a0a0a]"></span>
                    <article>
                        <header class="mb-3">
                            <time class="text-sm font-semibold text-[#2563eb] dark:text-[#60a5fa]" datetime="2026-04-13">
                                13 April 2026
                            </time>
                            <p class="text-xs text-[#6b7280] dark:text-[#9ca3af]">
                                {{ __('Evening (local time) — project log & changelog page') }}
                            </p>
                        </header>
                        <ul class="list-inside list-disc space-y-2 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                            <li>{{ __("Added this What's new page and linked it from the home footer.") }}</li>
                        </ul>
                    </article>
                </li>

                <li class="relative">
                    <span class="absolute -start-[calc(0.5rem+1px)] mt-1.5 h-3 w-3 rounded-full border-2 border-[#d1d5db] bg-[#FDFDFC] dark:border-[#525252] dark:bg-[#0a0a0a]"></span>
                    <article>
                        <header class="mb-3">
                            <time class="text-sm font-semibold text-[#1b1b18] dark:text-white" datetime="2026-04-13">
                                13 April 2026
                            </time>
                            <p class="text-xs text-[#6b7280] dark:text-[#9ca3af]">
                                {{ __('Branding, favicons, and layout pass') }}
                            </p>
                        </header>
                        <ul class="list-inside list-disc space-y-2 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                            <li>{{ __('Generated favicons and touch icon from the Linux ICs artwork (ICO, SVG with embedded raster, 16/32 PNG, Apple touch).') }}</li>
                            <li>{{ __('Published full wordmark and icon PNGs under public/images/.') }}</li>
                            <li>{{ __('Introduced the brand-logo-mark Blade component for consistent logo sizing and safe flex behavior (min-width / overflow).') }}</li>
                            <li>{{ __('Redesigned the home hero: logo on the left, headline and tagline on the right, with typography restored to the original scale.') }}</li>
                            <li>{{ __('Aligned login, register, and other auth layouts with the same logo treatment as the home page.') }}</li>
                            <li>{{ __('Updated dashboard and admin chrome (app-logo) to use the same mark; tightened nav links with min-w-0 for truncation.') }}</li>
                            <li>{{ __('Extended partials/head and the welcome page with explicit PNG favicon link tags.') }}</li>
                            <li>{{ __('Recorded changes in Git (commit 0f99516 on main).') }}</li>
                        </ul>
                    </article>
                </li>

                <li class="relative">
                    <span class="absolute -start-[calc(0.5rem+1px)] mt-1.5 h-3 w-3 rounded-full border-2 border-[#d1d5db] bg-[#FDFDFC] dark:border-[#525252] dark:bg-[#0a0a0a]"></span>
                    <article>
                        <header class="mb-3">
                            <time class="text-sm font-semibold text-[#1b1b18] dark:text-white" datetime="2026-04-02">
                                {{ __('Early April 2026') }}
                            </time>
                            <p class="text-xs text-[#6b7280] dark:text-[#9ca3af]">
                                {{ __('Seeker ↔ expert matching in the app') }}
                            </p>
                        </header>
                        <ul class="list-inside list-disc space-y-2 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                            <li>{{ __('Milestone 1: install requests, expert offers, and local dev quality-of-life.') }}</li>
                            <li>{{ __('Assignments, “mark install complete,” and Milestone 2 messaging on matched jobs.') }}</li>
                        </ul>
                    </article>
                </li>

                <li class="relative">
                    <span class="absolute -start-[calc(0.5rem+1px)] mt-1.5 h-3 w-3 rounded-full border-2 border-[#d1d5db] bg-[#FDFDFC] dark:border-[#525252] dark:bg-[#0a0a0a]"></span>
                    <article>
                        <header class="mb-3">
                            <time class="text-sm font-semibold text-[#1b1b18] dark:text-white" datetime="2026-03-16">
                                {{ __('March 2026') }}
                            </time>
                            <p class="text-xs text-[#6b7280] dark:text-[#9ca3af]">
                                {{ __('Admin, marketing page, and platform upgrades') }}
                            </p>
                        </header>
                        <ul class="list-inside list-disc space-y-2 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                            <li>{{ __('Admin panel: auth, user CRUD, install-request admin views, seeder.') }}</li>
                            <li>{{ __('Landing refresh (hero, cards, how-it-works) plus site footer and README links.') }}</li>
                            <li>{{ __('Expert vs seeker navigation, verified middleware, Livewire 4 + Flux + Laravel 12 bump, PHP 8.3 lockfile for hosting.') }}</li>
                        </ul>
                    </article>
                </li>

                <li class="relative">
                    <span class="absolute -start-[calc(0.5rem+1px)] mt-1.5 h-3 w-3 rounded-full border-2 border-[#d1d5db] bg-[#FDFDFC] dark:border-[#525252] dark:bg-[#0a0a0a]"></span>
                    <article>
                        <header class="mb-3">
                            <time class="text-sm font-semibold text-[#1b1b18] dark:text-white" datetime="2025-11-01">
                                {{ __('October–November 2025') }}
                            </time>
                            <p class="text-xs text-[#6b7280] dark:text-[#9ca3af]">
                                {{ __('Two kinds of users') }}
                            </p>
                        </header>
                        <ul class="list-inside list-disc space-y-2 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                            <li>{{ __('Expert registration and login; UserRole enum; expert dashboard and profile in Flux.') }}</li>
                        </ul>
                    </article>
                </li>

                <li class="relative">
                    <span class="absolute -start-[calc(0.5rem+1px)] mt-1.5 h-3 w-3 rounded-full border-2 border-[#d1d5db] bg-[#FDFDFC] dark:border-[#525252] dark:bg-[#0a0a0a]"></span>
                    <article>
                        <header class="mb-3">
                            <time class="text-sm font-semibold text-[#1b1b18] dark:text-white" datetime="2025-08-08">
                                {{ __('August 2025') }}
                            </time>
                            <p class="text-xs text-[#6b7280] dark:text-[#9ca3af]">
                                {{ __('Project start') }}
                            </p>
                        </header>
                        <ul class="list-inside list-disc space-y-2 text-sm leading-relaxed text-[#374151] dark:text-[#d1d5db]">
                            <li>{{ __('Laravel (TALL-style) app scaffold, role-based auth, docs folder and AI helper docs (e.g. GEMINI), early business pitch notes.') }}</li>
                        </ul>
                    </article>
                </li>
            </ol>

            <p class="mt-12 border-t border-[#e5e7eb] pt-8 text-center text-sm text-[#6b7280] dark:text-[#9ca3af]">
                <a href="{{ $github_url ?? 'https://github.com/asakpke/Linux-Installation-Centers' }}" class="text-[#2563eb] underline underline-offset-2 hover:text-[#1d4ed8] dark:text-[#60a5fa] dark:hover:text-[#93c5fd]" target="_blank" rel="noopener noreferrer">{{ __('GitHub repository') }}</a>
                ·
                <a href="{{ route('home') }}" class="text-[#2563eb] underline underline-offset-2 hover:text-[#1d4ed8] dark:text-[#60a5fa] dark:hover:text-[#93c5fd]">{{ __('Back to home') }}</a>
            </p>
        </main>
    </body>
</html>
