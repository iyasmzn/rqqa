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

            --color-amber-50:  color-mix(in oklab, var(--primary)  8%, white);
            --color-amber-100: color-mix(in oklab, var(--primary) 15%, white);
            --color-amber-200: color-mix(in oklab, var(--primary) 28%, white);
            --color-amber-300: color-mix(in oklab, var(--primary) 45%, white);
            --color-amber-400: color-mix(in oklab, var(--primary) 68%, white);
            --color-amber-500: color-mix(in oklab, var(--primary) 85%, white);
            --color-amber-600: var(--primary);
            --color-amber-700: color-mix(in oklab, var(--primary) 78%, black);
            --color-amber-800: color-mix(in oklab, var(--primary) 58%, black);
            --color-amber-900: color-mix(in oklab, var(--primary) 42%, black);
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
            border-color: var(--color-amber-200);
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
            background: var(--color-amber-700);
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
            :style="scrolled
                ? 'background:rgba(245,245,247,0.88);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid rgba(0,0,0,.08);box-shadow:0 1px 12px rgba(0,0,0,.06)'
                : 'background:rgba(245,245,247,0.95);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);border-bottom:1px solid rgba(0,0,0,.06)'">

        <div class="amber-bar"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3 shrink-0">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}"
                         alt="{{ setting('site_name', config('app.name')) }}"
                         class="w-9 h-9 rounded-2xl object-contain">
                @else
                    <div class="w-9 h-9 rounded-2xl flex items-center justify-center shadow-sm"
                         style="background:var(--primary)">
                        <span class="text-white font-extrabold text-sm">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <div class="hidden sm:block leading-tight">
                    <div class="font-bold text-sm" style="color:var(--text)">{{ setting('site_name', config('app.name')) }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-amber-600">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                </div>
            </a>

            {{-- Nav links --}}
            <div class="flex items-center gap-1">
                @php
                    $pubNavItems = collect(json_decode(setting('nav_items', ''), true) ?: [
                        ['label' => 'Beranda',  'url' => '/',         'target' => '_self', 'is_active' => true],
                        ['label' => 'Guru',     'url' => '/guru',     'target' => '_self', 'is_active' => true],
                        ['label' => 'Blog',     'url' => '/blog',     'target' => '_self', 'is_active' => true],
                        ['label' => 'Unduhan',  'url' => '/unduhan',  'target' => '_self', 'is_active' => true],
                        ['label' => 'Kontak',   'url' => '/#kontak',  'target' => '_self', 'is_active' => true],
                    ])->where('is_active', true)->values();
                @endphp
                @foreach($pubNavItems as $item)
                    @php
                        $navUrl  = str_starts_with($item['url'], '#') ? '/' . $item['url'] : $item['url'];
                        $navPath = parse_url($navUrl, PHP_URL_PATH) ?? '/';
                        $isActive = $navPath === '/'
                            ? request()->is('/')
                            : request()->is(ltrim($navPath, '/'), ltrim($navPath, '/') . '/*');
                    @endphp
                    <a href="{{ $navUrl }}" target="{{ $item['target'] ?? '_self' }}"
                       class="hidden sm:inline-flex text-sm font-medium px-3.5 py-2 rounded-xl transition-all
                              {{ $isActive
                                  ? 'bg-amber-50 font-semibold'
                                  : 'hover:bg-black/4' }}"
                       style="{{ $isActive ? 'color:var(--primary)' : 'color:var(--muted)' }}"
                       @if($isActive) aria-current="page" @endif>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                {{-- Cart icon with badge --}}
                @php $cartCount = \App\Http\Controllers\CartController::itemCount(); @endphp
                <a href="{{ route('cart.index') }}"
                   class="relative inline-flex items-center justify-center w-9 h-9 rounded-xl transition-all hover:bg-black/5"
                   title="Keranjang">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color:var(--text)">
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
                                class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition-all hover:bg-black/5"
                                style="color:var(--text)">
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                 style="background:var(--primary)">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span class="hidden md:inline max-w-25 truncate">{{ Auth::user()->name }}</span>
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
                             class="absolute right-0 top-full mt-1.5 min-w-45 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50 py-1.5">

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
                    <a href="{{ route('login') }}" class="btn-outline text-sm ml-1 hidden sm:inline-flex py-2 px-4">Masuk</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ── Page Content ─────────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────── --}}
    <footer class="mt-20" style="background:#1d1d1f">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">

            {{-- Top row --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-10 pb-10 border-b border-white/10">
                <div class="flex items-center gap-3">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}"
                             alt="{{ setting('site_name', config('app.name')) }}"
                             class="w-10 h-10 rounded-2xl object-contain">
                    @else
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow"
                             style="background:var(--primary)">
                            <span class="text-white font-extrabold">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <div class="font-bold text-white text-sm">{{ setting('site_name', config('app.name')) }}</div>
                        <div class="text-[10px] text-amber-500 font-semibold uppercase tracking-widest mt-0.5">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                    </div>
                </div>
                <nav class="flex flex-wrap gap-x-6 gap-y-2">
                    @foreach($pubNavItems as $item)
                        <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                           class="text-sm text-white/50 hover:text-white transition-colors">{{ $item['label'] }}</a>
                    @endforeach
                </nav>
            </div>

            {{-- Bottom row --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-white/30">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. Semua hak dilindungi.</p>
                <div class="flex gap-5">
                    @foreach(['Kebijakan Privasi', 'Syarat & Ketentuan'] as $l)
                        <a href="#" class="text-xs text-white/30 hover:text-white/60 transition-colors">{{ $l }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>AOS.init({ once: true, duration: 700, easing: 'ease-out-quart', offset: 60 });</script>
</body>
</html>
