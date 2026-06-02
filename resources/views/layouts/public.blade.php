<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ── Core SEO ────────────────────────────────────────── --}}
    <title>{{ $seo['title'] ?? setting('site_name', config('app.name')) }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">

    {{-- ── Favicon ─────────────────────────────────────────── --}}
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    {{-- ── Open Graph ──────────────────────────────────────── --}}
    <meta property="og:type"        content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:title"       content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:url"         content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:site_name"   content="{{ setting('site_name', config('app.name')) }}">
    @if(!empty($seo['og_image']))
        <meta property="og:image"        content="{{ $seo['og_image'] }}">
        <meta property="og:image:width"  content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt"    content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
    @endif
    @if(!empty($seo['published']))
        <meta property="article:published_time" content="{{ $seo['published'] }}">
    @endif
    @if(!empty($seo['author']))
        <meta property="article:author" content="{{ $seo['author'] }}">
    @endif
    @if(!empty($seo['category']))
        <meta property="article:section" content="{{ $seo['category'] }}">
    @endif

    {{-- ── Twitter Card ────────────────────────────────────── --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $seo['title'] ?? setting('site_name', config('app.name')) }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    @if(!empty($seo['og_image']))
        <meta name="twitter:image" content="{{ $seo['og_image'] }}">
    @endif

    {{-- ── Structured Data (JSON-LD) ──────────────────────── --}}
    @stack('structured-data')

    {{-- ── Fonts & Assets ─────────────────────────────────── --}}
    @fonts

    {{-- Dynamic Google Font loading based on admin setting --}}
    @php
        $fontMap = [
            'instrument-sans'   => ['family' => "'Instrument Sans', ui-sans-serif, system-ui, sans-serif", 'google' => null],
            'inter'             => ['family' => "'Inter', ui-sans-serif, system-ui, sans-serif",             'google' => 'Inter:wght@300;400;500;600;700;800;900'],
            'plus-jakarta-sans' => ['family' => "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif", 'google' => 'Plus+Jakarta+Sans:wght@300;400;500;600;700;800'],
            'outfit'            => ['family' => "'Outfit', ui-sans-serif, system-ui, sans-serif",            'google' => 'Outfit:wght@300;400;500;600;700;800;900'],
            'dm-sans'           => ['family' => "'DM Sans', ui-sans-serif, system-ui, sans-serif",           'google' => 'DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400'],
            'nunito'            => ['family' => "'Nunito', ui-sans-serif, system-ui, sans-serif",            'google' => 'Nunito:wght@300;400;500;600;700;800'],
            'poppins'           => ['family' => "'Poppins', ui-sans-serif, system-ui, sans-serif",           'google' => 'Poppins:wght@300;400;500;600;700;800'],
            'sora'              => ['family' => "'Sora', ui-sans-serif, system-ui, sans-serif",              'google' => 'Sora:wght@300;400;500;600;700;800'],
        ];
        $selectedFont = setting('theme_font', 'instrument-sans');
        $font = $fontMap[$selectedFont] ?? $fontMap['instrument-sans'];
    @endphp
    @if($font['google'])
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ $font['google'] }}&display=swap">
    @endif

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>
        /* ── Design tokens — Apple-inspired ─────────────────────── */
        :root {
            --bg:     #f5f5f7;
            --bg-alt: #ffffff;
            --card:   #ffffff;
            --border: rgba(0,0,0,.08);
            --text:   #1d1d1f;
            --muted:  #6e6e73;

            --primary: {{ setting('theme_primary_color', '#08484A') }};

            --primary-50:  color-mix(in oklab, var(--primary)  8%, white);
            --primary-100: color-mix(in oklab, var(--primary) 15%, white);
            --primary-200: color-mix(in oklab, var(--primary) 28%, white);
            --primary-300: color-mix(in oklab, var(--primary) 45%, white);
            --primary-400: color-mix(in oklab, var(--primary) 68%, white);
            --primary-500: color-mix(in oklab, var(--primary) 85%, white);
            --primary-600: var(--primary);
            --primary-700: color-mix(in oklab, var(--primary) 78%, black);
            --primary-800: color-mix(in oklab, var(--primary) 58%, black);
            --primary-900: color-mix(in oklab, var(--primary) 42%, black);
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: {{ $font['family'] }};
            -webkit-font-smoothing: antialiased;
        }

        /* ── Cards — Apple large radius ─────────────────────────── */
        .fi-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            transition: box-shadow .3s ease, transform .3s ease, border-color .3s ease;
        }
        .fi-card-hover:hover {
            box-shadow: 0 20px 60px rgba(0,0,0,.12);
            transform: translateY(-3px);
            border-color: var(--primary-200);
        }

        /* ── Buttons ─────────────────────────────────────────────── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem;
            border-radius: .875rem;
            font-size: .9375rem; font-weight: 600;
            background: var(--primary); color: #fff;
            transition: background .2s, box-shadow .2s, transform .15s;
            box-shadow: 0 4px 16px color-mix(in oklab, var(--primary) 35%, transparent);
        }
        .btn-primary:hover {
            background: var(--primary-700);
            box-shadow: 0 6px 24px color-mix(in oklab, var(--primary) 45%, transparent);
            transform: translateY(-1px);
        }
        .btn-outline {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem;
            border-radius: .875rem;
            font-size: .9375rem; font-weight: 600;
            border: 1.5px solid var(--border);
            color: var(--text); background: var(--card);
            transition: border-color .2s, color .2s, box-shadow .2s, transform .15s;
        }
        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            box-shadow: 0 4px 16px rgba(0,0,0,.06);
            transform: translateY(-1px);
        }

        /* ── Labels & badges ─────────────────────────────────────── */
        .fi-label {
            font-size: .6875rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--primary);
        }

        /* ── Accent bar ──────────────────────────────────────────── */
        .amber-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--primary), color-mix(in oklab, var(--primary) 55%, white) 60%, transparent);
        }

        /* ── Prose content ───────────────────────────────────────── */
        .prose h2 { font-size: 1.375rem; font-weight: 700; margin-top: 2rem; margin-bottom: .875rem; color: var(--text); }
        .prose h3 { font-size: 1.125rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: .625rem; color: var(--text); }
        .prose p  { margin-bottom: 1.125rem; line-height: 1.85; color: var(--muted); }
        .prose ul { list-style: disc; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--muted); }
        .prose ol { list-style: decimal; padding-left: 1.5rem; margin-bottom: 1rem; color: var(--muted); }
        .prose li { margin-bottom: .5rem; line-height: 1.7; }
        .prose blockquote { border-left: 3px solid var(--primary); padding-left: 1.25rem; margin: 1.75rem 0; color: var(--muted); font-style: italic; }
        .prose a  { color: var(--primary); text-decoration: underline; text-underline-offset: 2px; }
        .prose strong { color: var(--text); }
    </style>

    @stack('head')
</head>

<body class="min-h-screen antialiased"
      x-data="{ scrolled: false }"
      x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 40, { passive: true })">

    {{-- ── Navbar — frosted glass on scroll ─────────────────────── --}}
    <header class="sticky top-0 z-50 transition-all duration-300"
            x-data="{ mobileOpen: false }"
            :style="scrolled
                ? 'background:rgba(245,245,247,0.88);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid rgba(0,0,0,.08);box-shadow:0 1px 12px rgba(0,0,0,.06)'
                : 'background:rgba(245,245,247,0.95);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border-bottom:1px solid rgba(0,0,0,.06)'">

        <div class="amber-bar"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 min-w-0">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}"
                         alt="{{ setting('site_name', config('app.name')) }}"
                         class="w-9 h-9 rounded-2xl object-contain shrink-0">
                @else
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center shadow-sm shrink-0"
                         style="background:var(--primary)">
                        <span class="text-white font-extrabold text-sm">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <div class="leading-tight min-w-0">
                    <div class="font-bold text-sm truncate" style="color:var(--text)">{{ setting('site_name', config('app.name')) }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest truncate" style="color:var(--primary)">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                </div>
            </a>

            {{-- Desktop Nav links --}}
            @php
                $pubNavItems = collect(json_decode(setting('nav_items', ''), true) ?: [
                    ['label' => 'Beranda',  'url' => '/',         'target' => '_self', 'is_active' => true],
                    ['label' => 'Guru',     'url' => '/guru',     'target' => '_self', 'is_active' => true],
                    ['label' => 'Blog',     'url' => '/blog',     'target' => '_self', 'is_active' => true],
                    ['label' => 'Unduhan',  'url' => '/unduhan',  'target' => '_self', 'is_active' => true],
                    ['label' => 'Kontak',   'url' => '/#kontak',  'target' => '_self', 'is_active' => true],
                ])->where('is_active', true)->values();
                $cartCount = \App\Http\Controllers\CartController::itemCount();
            @endphp

            <div class="hidden sm:flex items-center gap-1">
                @foreach($pubNavItems as $item)
                    @php
                        $navUrl  = str_starts_with($item['url'], '#') ? '/' . $item['url'] : $item['url'];
                        $navPath = parse_url($navUrl, PHP_URL_PATH) ?? '/';
                        $isActive = $navPath === '/'
                            ? request()->is('/')
                            : request()->is(ltrim($navPath, '/'), ltrim($navPath, '/') . '/*');
                    @endphp
                    <a href="{{ $navUrl }}" target="{{ $item['target'] ?? '_self' }}"
                       class="text-sm font-medium px-3.5 py-2 rounded-xl transition-all
                              {{ $isActive ? 'bg-amber-50 font-semibold' : 'hover:bg-black/5' }}"
                       style="{{ $isActive ? 'color:var(--primary)' : 'color:var(--muted)' }}"
                       @if($isActive) aria-current="page" @endif>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                {{-- Cart icon --}}
                <a href="{{ route('cart.index') }}"
                   class="relative inline-flex items-center justify-center w-9 h-9 rounded-xl transition-all hover:bg-black/5"
                   title="Keranjang">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-4.5 h-4.5 px-1 flex items-center justify-center rounded-full text-[10px] font-bold text-white"
                              style="background:var(--primary)">{{ $cartCount }}</span>
                    @endif
                </a>

                @auth
                    {{-- User dropdown --}}
                    <div x-data="{ userOpen: false }" class="relative">
                        <button @click="userOpen = !userOpen"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition-all hover:bg-black/5"
                                style="color:var(--text)">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                 style="background:var(--primary)">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:inline max-w-24 truncate">{{ Auth::user()->name }}</span>
                            <svg class="w-3 h-3 transition-transform" :class="userOpen ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="userOpen" @click.outside="userOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1"
                             class="absolute right-0 top-full mt-1.5 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50 py-1.5">

                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-sm font-semibold truncate" style="color:var(--text)">{{ Auth::user()->name }}</p>
                                <p class="text-xs truncate" style="color:var(--muted)">{{ Auth::user()->email }}</p>
                            </div>

                            <a href="{{ route('cart.index') }}"
                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50 transition-colors"
                               style="color:var(--text)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Keranjang
                                @if($cartCount > 0)
                                    <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full text-white"
                                          style="background:var(--primary)">{{ $cartCount }}</span>
                                @endif
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-outline text-sm ml-1 py-2 px-4">Masuk</a>
                @endauth
            </div>

            {{-- Mobile right controls --}}
            <div class="flex sm:hidden items-center gap-2">
                {{-- Cart icon --}}
                <a href="{{ route('cart.index') }}"
                   class="relative inline-flex items-center justify-center w-9 h-9 rounded-xl transition-all hover:bg-black/5"
                   title="Keranjang">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-4.5 h-4.5 px-1 flex items-center justify-center rounded-full text-[10px] font-bold text-white"
                              style="background:var(--primary)">{{ $cartCount }}</span>
                    @endif
                </a>

                {{-- Hamburger button --}}
                <button @click="mobileOpen = !mobileOpen"
                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl transition-all hover:bg-black/5"
                        :aria-expanded="mobileOpen"
                        aria-label="Menu navigasi">
                    <svg x-show="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu dropdown --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="sm:hidden border-t"
             style="background:rgba(245,245,247,0.97);backdrop-filter:blur(20px);border-color:rgba(0,0,0,.08)">
            <nav class="max-w-7xl mx-auto px-4 py-3 flex flex-col gap-1">
                @foreach($pubNavItems as $item)
                    @php
                        $navUrl  = str_starts_with($item['url'], '#') ? '/' . $item['url'] : $item['url'];
                        $navPath = parse_url($navUrl, PHP_URL_PATH) ?? '/';
                        $isActive = $navPath === '/'
                            ? request()->is('/')
                            : request()->is(ltrim($navPath, '/'), ltrim($navPath, '/') . '/*');
                    @endphp
                    <a href="{{ $navUrl }}" target="{{ $item['target'] ?? '_self' }}"
                       @click="mobileOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all
                              {{ $isActive ? 'font-semibold' : 'hover:bg-black/5' }}"
                       style="{{ $isActive ? 'color:var(--primary);background:color-mix(in oklab,var(--primary) 8%,white)' : 'color:var(--text)' }}"
                       @if($isActive) aria-current="page" @endif>
                        @if($isActive)
                            <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:var(--primary)"></span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full shrink-0 opacity-0"></span>
                        @endif
                        {{ $item['label'] }}
                    </a>
                @endforeach

                {{-- Divider --}}
                <div class="my-1 border-t" style="border-color:rgba(0,0,0,.07)"></div>

                @auth
                    <div class="flex items-center gap-3 px-4 py-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                             style="background:var(--primary)">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold truncate" style="color:var(--text)">{{ Auth::user()->name }}</p>
                            <p class="text-xs truncate" style="color:var(--muted)">{{ Auth::user()->email }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="px-4 pb-3">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                @else
                    <div class="px-4 pb-3">
                        <a href="{{ route('login') }}"
                           class="btn-primary w-full justify-center text-sm py-2.5">
                            Masuk
                        </a>
                    </div>
                @endauth
            </nav>
        </div>
    </header>

    {{-- ── Page Content ─────────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────── --}}
    <footer style="background:#111827;border-top:1px solid rgba(255,255,255,.07)">

        {{-- Top accent bar --}}
        <div style="height:3px;background:linear-gradient(90deg,var(--primary),color-mix(in oklab,var(--primary) 50%,white) 60%,transparent)"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 mb-12">

                {{-- ── Col 1: Brand ── --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <div class="flex items-center gap-3 mb-4">
                        @if(setting('site_logo'))
                            <img src="{{ asset('storage/'.setting('site_logo')) }}"
                                 alt="{{ setting('site_name', config('app.name')) }}"
                                 class="w-10 h-10 rounded-2xl object-contain">
                        @else
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                                 style="background:var(--primary)">
                                <span class="text-white font-extrabold text-base">
                                    {{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-white text-sm leading-tight">
                                {{ setting('site_name', config('app.name')) }}
                            </div>
                            <div class="text-[10px] font-semibold uppercase tracking-widest mt-0.5"
                                 style="color:color-mix(in oklab,var(--primary) 80%,white)">
                                {{ setting('site_tagline', 'Unggul · Berkarakter') }}
                            </div>
                        </div>
                    </div>

                    @if(setting('site_description'))
                    <p class="text-xs leading-relaxed mb-5 text-white/45 max-w-xs">
                        {{ Str::limit(setting('site_description'), 120) }}
                    </p>
                    @endif

                    {{-- Social icons --}}
                    <div class="flex gap-2">
                        @if(setting('social_facebook'))
                        <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener"
                           title="Facebook"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        @endif
                        @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener"
                           title="Instagram"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                        </a>
                        @endif
                        @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" target="_blank" rel="noopener"
                           title="YouTube"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/>
                            </svg>
                        </a>
                        @endif
                        @if(setting('social_whatsapp'))
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', setting('social_whatsapp')) }}"
                           target="_blank" rel="noopener" title="WhatsApp"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- ── Col 2: Navigasi ── --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color:color-mix(in oklab,var(--primary) 80%,white)">Navigasi</h4>
                    <ul class="space-y-2.5">
                        @foreach($pubNavItems as $item)
                        <li>
                            <a href="{{ str_starts_with($item['url'], '#') ? '/'.$item['url'] : $item['url'] }}"
                               target="{{ $item['target'] ?? '_self' }}"
                               class="text-sm text-white/50 hover:text-white transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                      style="background:var(--primary)"></span>
                                {{ $item['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- ── Col 3: Layanan ── --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color:color-mix(in oklab,var(--primary) 80%,white)">Layanan</h4>
                    <ul class="space-y-2.5">
                        @foreach([
                            ['label' => 'Toko Buku',      'url' => route('books.index')],
                            ['label' => 'Daftar Santri',  'url' => route('ppdb.index')],
                            ['label' => 'Blog & Berita',  'url' => route('blog.index')],
                            ['label' => 'Unduhan',        'url' => route('downloads.index')],
                            ['label' => 'Tenaga Pendidik','url' => route('teachers.index')],
                            ['label' => 'Keranjang',      'url' => route('cart.index')],
                        ] as $link)
                        <li>
                            <a href="{{ $link['url'] }}"
                               class="text-sm text-white/50 hover:text-white transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                      style="background:var(--primary)"></span>
                                {{ $link['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- ── Col 4: Kontak ── --}}
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color:color-mix(in oklab,var(--primary) 80%,white)">Kontak</h4>
                    <ul class="space-y-3">
                        @if(setting('contact_address'))
                        <li class="flex gap-3 text-xs text-white/50 leading-relaxed">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ setting('contact_address') }}</span>
                        </li>
                        @endif
                        @if(setting('contact_phone'))
                        <li class="flex gap-3 text-xs text-white/50">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ setting('contact_phone') }}</span>
                        </li>
                        @endif
                        @if(setting('contact_email'))
                        <li class="flex gap-3 text-xs text-white/50">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ setting('contact_email') }}</span>
                        </li>
                        @endif
                        @if(setting('contact_hours'))
                        <li class="flex gap-3 text-xs text-white/50">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ setting('contact_hours') }}</span>
                        </li>
                        @endif
                        @if(setting('shop_whatsapp') || setting('social_whatsapp'))
                        @php $waNum = preg_replace('/\D/', '', setting('shop_whatsapp', setting('social_whatsapp', ''))); @endphp
                        <li class="pt-1">
                            <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white transition-all hover:opacity-90 hover:scale-[1.02]"
                               style="background:var(--primary)">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Hubungi via WhatsApp
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

            </div>

            {{-- Bottom bar --}}
            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-white/30">
                    © {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. Semua hak dilindungi.
                </p>
                <div class="flex items-center gap-1 text-white/20 text-xs">
                    <span>Dibuat dengan</span>
                    <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    <span>penuh semangat</span>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init({ once: true, duration: 700, easing: 'ease-out-quart', offset: 60 });</script>

    @stack('scripts')

    <x-popup-overlay />
    <x-floating-buttons />
</body>
</html>
