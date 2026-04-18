<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Linux Installation Centers</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            .landing a:not(.btn) {
                color: #2563eb !important;
                text-decoration: underline !important;
                text-underline-offset: 2px;
            }
            .landing a:not(.btn):hover { color: #1d4ed8 !important; }
            .dark .landing a:not(.btn) { color: #60a5fa !important; }
            .dark .landing a:not(.btn):hover { color: #93c5fd !important; }
        </style>
    </head>
    <body class="min-h-screen bg-gradient-to-b from-zinc-50 via-[#FAFAF8] to-[#F5F5F0] text-[#1b1b18] antialiased dark:from-[#0c0c0c] dark:via-[#0a0a0a] dark:to-[#121211] dark:text-[#EDEDEC]">
        @if (Route::has('login'))
            <header class="sticky top-0 z-10 w-full border-b border-[#e5e7eb]/90 bg-zinc-50/80 shadow-sm backdrop-blur-md dark:border-[#3E3E3A]/80 dark:bg-[#0c0c0c]/80">
                <div class="mx-auto flex w-full max-w-5xl justify-end px-4 py-5 sm:px-6">
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-2 border border-[#19140035] dark:border-[#3E3E3A] hover:border-[#1915014a] dark:hover:border-[#62605b] rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-5 py-2 rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-black/5 dark:hover:bg-white/5">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-block px-5 py-2 border border-[#19140035] dark:border-[#3E3E3A] rounded-md text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">Register</a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>
        @endif

        <div class="landing max-w-5xl mx-auto px-4 sm:px-6 pb-16">
            {{-- Hero: always logo left, headline + tagline right (row at all widths) --}}
            <section class="relative mb-14 lg:mb-18">
                <div class="pointer-events-none absolute -inset-x-4 -top-4 bottom-0 -z-0 rounded-3xl bg-[radial-gradient(ellipse_85%_70%_at_0%_20%,rgba(37,99,235,0.09),transparent_58%)] dark:bg-[radial-gradient(ellipse_85%_70%_at_0%_20%,rgba(59,130,246,0.14),transparent_58%)] sm:-inset-x-6" aria-hidden="true"></div>
                <div class="relative flex flex-row items-center gap-4 text-left sm:gap-6 lg:gap-8">
                    <div class="shrink-0 rounded-2xl bg-white/70 p-2 ring-1 ring-black/[0.06] shadow-sm backdrop-blur-sm dark:bg-white/[0.04] dark:ring-white/10">
                        <x-brand-logo-mark decorative />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h1 class="mb-3 text-3xl font-semibold tracking-tight text-[#1b1b18] dark:text-white sm:text-4xl lg:text-5xl">
                            Linux Installation Centers
                        </h1>
                        <p class="text-lg text-[#4b5563] dark:text-[#A1A09A] sm:text-xl lg:max-w-2xl">
                            Get help switching to Linux—or help others make the switch. Connect with local experts for installation and distro advice.
                        </p>
                    </div>
                </div>
            </section>

            {{-- Who it's for: two cards --}}
            <section class="grid md:grid-cols-2 gap-6 lg:gap-8 mb-14 lg:mb-18">
                <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm transition-shadow duration-200 hover:border-zinc-300 hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-zinc-600 lg:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#dbeafe] dark:bg-[#1e3a5f] text-[#2563eb] dark:text-[#60a5fa]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </span>
                        <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-white">Need Linux help?</h2>
                    </div>
                    <p class="text-[#4b5563] dark:text-[#A1A09A] text-sm lg:text-base mb-4">New to Linux or unsure which distro to pick? Get matched with an expert near you for in-person or guided support—installation, dual-boot, or just advice.</p>
                    <ul class="space-y-2 text-sm text-[#374151] dark:text-[#d1d5db]">
                        <li class="flex items-start gap-2"><span class="text-[#2563eb] dark:text-[#60a5fa] mt-0.5">•</span> Expert match by location</li>
                        <li class="flex items-start gap-2"><span class="text-[#2563eb] dark:text-[#60a5fa] mt-0.5">•</span> Free or paid sessions</li>
                        <li class="flex items-start gap-2"><span class="text-[#2563eb] dark:text-[#60a5fa] mt-0.5">•</span> Distro choice & install help</li>
                    </ul>
                </article>
                <article class="rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm transition-shadow duration-200 hover:border-zinc-300 hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-zinc-600 lg:p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="flex items-center justify-center w-10 h-10 rounded-lg bg-[#dcfce7] dark:bg-[#14532d] text-[#16a34a] dark:text-[#4ade80]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        </span>
                        <h2 class="text-xl font-semibold text-[#1b1b18] dark:text-white">I'm an expert</h2>
                    </div>
                    <p class="text-[#4b5563] dark:text-[#A1A09A] text-sm lg:text-base mb-4">Already help others with Linux? Get discovered by people looking for local support. Set your own terms—free, paid, or both.</p>
                    <ul class="space-y-2 text-sm text-[#374151] dark:text-[#d1d5db]">
                        <li class="flex items-start gap-2"><span class="text-[#16a34a] dark:text-[#4ade80] mt-0.5">•</span> Share your experience</li>
                        <li class="flex items-start gap-2"><span class="text-[#16a34a] dark:text-[#4ade80] mt-0.5">•</span> Free or paid—you choose</li>
                        <li class="flex items-start gap-2"><span class="text-[#16a34a] dark:text-[#4ade80] mt-0.5">•</span> Grow the community</li>
                    </ul>
                </article>
            </section>

            {{-- How it works --}}
            <section class="mb-14 lg:mb-18">
                <h2 class="text-xl font-semibold text-center text-[#1b1b18] dark:text-white mb-8">How it works</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 lg:gap-10">
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#2563eb] text-xl font-semibold text-white shadow-md ring-2 ring-blue-500/25 dark:bg-[#3b82f6] dark:ring-blue-400/20">1</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-white mb-1">Sign up</h3>
                        <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">As someone who needs help or as an expert.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#2563eb] text-xl font-semibold text-white shadow-md ring-2 ring-blue-500/25 dark:bg-[#3b82f6] dark:ring-blue-400/20">2</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-white mb-1">Find a match</h3>
                        <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">By location and the support you need or offer.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-[#2563eb] text-xl font-semibold text-white shadow-md ring-2 ring-blue-500/25 dark:bg-[#3b82f6] dark:ring-blue-400/20">3</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-white mb-1">Connect</h3>
                        <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">Get or give help—in person or however works for you.</p>
                    </div>
                </div>
            </section>

            {{-- CTA --}}
            <section class="text-center mb-12">
                <p class="text-[#4b5563] dark:text-[#A1A09A] mb-6">Linux ICs makes adopting Linux less confusing and more human.</p>
                <div class="flex flex-wrap items-center justify-center gap-3">
                    <a href="/register" class="btn inline-flex items-center justify-center px-6 py-3 rounded-lg font-medium text-white bg-[#1b1b18] hover:bg-[#374151] dark:bg-white dark:text-[#1b1b18] dark:hover:bg-[#e5e7eb] transition-colors">Register</a>
                    <a href="/login" class="btn inline-flex items-center justify-center px-6 py-3 rounded-lg font-medium text-[#1b1b18] dark:text-white border border-[#d1d5db] dark:border-[#3E3E3A] hover:bg-black/5 dark:hover:bg-white/5 transition-colors">Log in</a>
                </div>
            </section>

            {{-- Community snapshot: recent requests & seeker sign-ups --}}
            <section class="mb-14 lg:mb-18" aria-labelledby="snapshot-heading">
                <h2 id="snapshot-heading" class="text-xl font-semibold text-[#1b1b18] dark:text-white mb-2 text-center">{{ __('Community activity') }}</h2>
                <p class="text-sm text-center text-[#6b7280] dark:text-[#9ca3af] mb-8 max-w-2xl mx-auto">{{ __('A live peek at what people are posting and who is joining.') }} <a href="{{ route('register') }}">{{ __('Create a free account') }}</a> {{ __('to respond to requests or offer help.') }}</p>
                <div class="grid md:grid-cols-2 gap-8 lg:gap-10">
                    <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm transition-shadow duration-200 hover:border-zinc-300 hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-zinc-600">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-white mb-4">{{ __('Recent install requests') }}</h3>
                        <ul class="space-y-4 text-sm text-[#374151] dark:text-[#d1d5db]">
                            @forelse (($recentInstallRequests ?? []) as $req)
                                <li class="border-b border-[#e5e7eb] dark:border-[#3E3E3A] pb-3 last:border-0 last:pb-0">
                                    <p class="font-medium text-[#1b1b18] dark:text-white leading-snug">{{ \Illuminate\Support\Str::limit($req->title, 72) }}</p>
                                    <p class="mt-1 text-[#6b7280] dark:text-[#9ca3af]">
                                        @if (filled($req->city) || filled($req->country))
                                            {{ collect([$req->city, $req->country])->filter()->implode(', ') }}
                                            <span class="text-[#9ca3af] dark:text-[#6b7280]"> · </span>
                                        @endif
                                        <span class="capitalize">{{ __($req->status->value) }}</span>
                                        <span class="text-[#9ca3af] dark:text-[#6b7280]"> · </span>
                                        {{ $req->created_at->diffForHumans() }}
                                    </p>
                                </li>
                            @empty
                                <li class="text-[#6b7280] dark:text-[#9ca3af]">{{ __('No install requests yet—be the first to post after you sign up.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm transition-shadow duration-200 hover:border-zinc-300 hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-zinc-600">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-white mb-4">{{ __('Recent seeker sign-ups') }}</h3>
                        <ul class="space-y-3 text-sm text-[#374151] dark:text-[#d1d5db]">
                            @forelse (($recentSeekerSignups ?? []) as $u)
                                <li class="flex flex-wrap items-baseline justify-between gap-2 border-b border-[#e5e7eb] dark:border-[#3E3E3A] pb-3 last:border-0 last:pb-0">
                                    <span class="font-medium text-[#1b1b18] dark:text-white">{{ \Illuminate\Support\Str::of($u->name)->explode(' ')->first() }}</span>
                                    <span class="text-[#6b7280] dark:text-[#9ca3af]">{{ $u->created_at->diffForHumans() }}</span>
                                </li>
                            @empty
                                <li class="text-[#6b7280] dark:text-[#9ca3af]">{{ __('No seekers yet—invite friends who want Linux help.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </section>

            {{-- Experts: new & popular --}}
            <section class="mb-14 lg:mb-18" aria-labelledby="experts-heading">
                <h2 id="experts-heading" class="text-xl font-semibold text-[#1b1b18] dark:text-white mb-2 text-center">{{ __('Linux experts on the platform') }}</h2>
                <p class="text-sm text-center text-[#6b7280] dark:text-[#9ca3af] mb-8 max-w-2xl mx-auto">{{ __('Experts set their own areas and offers.') }} <a href="{{ route('expert.register') }}">{{ __('Register as an expert') }}</a> {{ __('to appear in local browse lists.') }}</p>
                <div class="grid md:grid-cols-2 gap-8 lg:gap-10">
                    <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm transition-shadow duration-200 hover:border-zinc-300 hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-zinc-600">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-white mb-4">{{ __('Newest expert sign-ups') }}</h3>
                        <ul class="space-y-4 text-sm text-[#374151] dark:text-[#d1d5db]">
                            @forelse (($recentExpertSignups ?? []) as $ex)
                                <li class="border-b border-[#e5e7eb] dark:border-[#3E3E3A] pb-3 last:border-0 last:pb-0">
                                    <p class="font-medium text-[#1b1b18] dark:text-white">{{ \Illuminate\Support\Str::of($ex->name)->explode(' ')->first() }}</p>
                                    @php $ep = $ex->expertProfile; @endphp
                                    @if ($ep && (filled($ep->city) || filled($ep->country)))
                                        <p class="mt-1 text-[#6b7280] dark:text-[#9ca3af]">{{ collect([$ep->city, $ep->country])->filter()->implode(', ') }}</p>
                                    @endif
                                    <p class="mt-1 text-xs text-[#9ca3af] dark:text-[#6b7280]">{{ $ex->created_at->diffForHumans() }}</p>
                                </li>
                            @empty
                                <li class="text-[#6b7280] dark:text-[#9ca3af]">{{ __('No experts yet—the first local pros are onboarding now.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="rounded-xl border border-[#e5e7eb] bg-white p-6 shadow-sm transition-shadow duration-200 hover:border-zinc-300 hover:shadow-md dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-zinc-600">
                        <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-white mb-4">{{ __('Popular experts') }}</h3>
                        <p class="text-xs text-[#6b7280] dark:text-[#9ca3af] mb-4">{{ __('Ranked by completed reviews from seekers (average rating, then review count).') }}</p>
                        <ul class="space-y-4 text-sm text-[#374151] dark:text-[#d1d5db]">
                            @forelse (($popularExperts ?? []) as $ex)
                                <li class="border-b border-[#e5e7eb] dark:border-[#3E3E3A] pb-3 last:border-0 last:pb-0">
                                    <p class="font-medium text-[#1b1b18] dark:text-white">{{ \Illuminate\Support\Str::of($ex->name)->explode(' ')->first() }}</p>
                                    @php $ep = $ex->expertProfile; @endphp
                                    @if ($ep && (filled($ep->city) || filled($ep->country)))
                                        <p class="mt-1 text-[#6b7280] dark:text-[#9ca3af]">{{ collect([$ep->city, $ep->country])->filter()->implode(', ') }}</p>
                                    @endif
                                    <p class="mt-1 text-xs text-[#9ca3af] dark:text-[#6b7280]">
                                        @if (($ex->reviews_received_count ?? 0) > 0)
                                            {{ trans_choice(':count review|:count reviews', $ex->reviews_received_count, ['count' => $ex->reviews_received_count]) }}
                                            @if ($ex->reviews_received_avg_rating !== null)
                                                <span class="text-[#9ca3af] dark:text-[#6b7280]"> · </span>
                                                {{ __('Avg. :n / 5', ['n' => number_format((float) $ex->reviews_received_avg_rating, 1)]) }}
                                            @endif
                                        @else
                                            {{ __('No reviews yet') }}
                                        @endif
                                    </p>
                                </li>
                            @empty
                                <li class="text-[#6b7280] dark:text-[#9ca3af]">{{ __('No experts to highlight yet.') }}</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </section>

            {{-- Footer note --}}
            <footer class="pt-8 border-t border-[#e5e7eb] dark:border-[#3E3E3A] text-center space-y-3">
                <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">
                    {{ __('This project is under development. See') }}
                    <a href="{{ route('whats-new') }}">{{ __("What's new") }}</a>,
                    {{ __('follow progress, report issues, or contribute on') }}
                    <a href="{{ $github_url ?? 'https://github.com/asakpke/Linux-Installation-Centers' }}" target="_blank" rel="noopener noreferrer">GitHub</a>.
                </p>
                <p class="text-sm font-medium text-[#374151] dark:text-[#d1d5db]">
                    {{ __('Priority: we need a production domain (e.g. LinuxInstallationCenters.com).') }}
                    <a href="{{ route('support-the-project') }}">{{ __('Support the project') }}</a>.
                </p>
                <p class="text-xs text-[#9ca3af] dark:text-[#6b7280]">
                    Powered by <a href="https://www.esite.pk" target="_blank" rel="noopener noreferrer" class="hover:underline">RoshanTech</a> · <a href="https://www.esite.pk" target="_blank" rel="noopener noreferrer" class="hover:underline">www.eSite.pk</a>
                </p>
            </footer>
        </div>

        @if (Route::has('login'))
            <div class="h-12"></div>
        @endif
    </body>
</html>
