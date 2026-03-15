<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Linux Installation Centers</title>
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
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
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] antialiased">
        <header class="w-full max-w-5xl mx-auto px-4 sm:px-6 py-6">
            @if (Route::has('login'))
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
            @endif
        </header>

        <div class="landing max-w-5xl mx-auto px-4 sm:px-6 pb-16">
            {{-- Hero --}}
            <section class="text-center mb-14 lg:mb-18">
                <div class="inline-flex items-center justify-center w-24 h-24 lg:w-32 lg:h-32 rounded-2xl bg-[#e8f0fe] dark:bg-[#1e3a5f] text-[#2563eb] dark:text-[#60a5fa] mb-6" aria-hidden="true">
                    <svg class="w-12 h-12 lg:w-16 lg:h-16" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 20h40v32H12V20z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 52V20M32 52V20M44 52V20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="22" cy="14" r="2" fill="currentColor"/>
                        <path d="M8 28h48" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="32" cy="44" r="6" stroke="currentColor" stroke-width="2"/>
                        <path d="M32 40v4M29 42h6M32 46v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-[#1b1b18] dark:text-white mb-3 tracking-tight">Linux Installation Centers</h1>
                <p class="text-lg sm:text-xl text-[#4b5563] dark:text-[#A1A09A] max-w-2xl mx-auto">Get help switching to Linux—or help others make the switch. Connect with local experts for installation and distro advice.</p>
            </section>

            {{-- Who it's for: two cards --}}
            <section class="grid md:grid-cols-2 gap-6 lg:gap-8 mb-14 lg:mb-18">
                <article class="p-6 lg:p-8 rounded-xl bg-white dark:bg-[#161615] border border-[#e5e7eb] dark:border-[#3E3E3A] shadow-sm">
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
                <article class="p-6 lg:p-8 rounded-xl bg-white dark:bg-[#161615] border border-[#e5e7eb] dark:border-[#3E3E3A] shadow-sm">
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
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-[#2563eb] dark:bg-[#3b82f6] text-white font-semibold text-xl mb-4">1</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-white mb-1">Sign up</h3>
                        <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">As someone who needs help or as an expert.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-[#2563eb] dark:bg-[#3b82f6] text-white font-semibold text-xl mb-4">2</div>
                        <h3 class="font-medium text-[#1b1b18] dark:text-white mb-1">Find a match</h3>
                        <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">By location and the support you need or offer.</p>
                    </div>
                    <div class="flex flex-col items-center text-center">
                        <div class="flex items-center justify-center w-14 h-14 rounded-full bg-[#2563eb] dark:bg-[#3b82f6] text-white font-semibold text-xl mb-4">3</div>
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

            {{-- Footer note --}}
            <footer class="pt-8 border-t border-[#e5e7eb] dark:border-[#3E3E3A] text-center">
                <p class="text-sm text-[#6b7280] dark:text-[#9ca3af]">
                    This project is under development. Follow progress, report issues, or contribute on <a href="{{ $github_url ?? 'https://github.com/asakpke/Linux-Installation-Centers' }}" target="_blank" rel="noopener noreferrer">GitHub</a>.
                </p>
            </footer>
        </div>

        @if (Route::has('login'))
            <div class="h-12"></div>
        @endif
    </body>
</html>
