<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ setting('site_name', config('app.name', "Qurrota A'yun")) }} — {{ setting('site_tagline', 'Membentuk Generasi Qurani yang Berilmu dan Berakhlak') }}</title>
    <meta name="description" content="{{ setting('site_description', 'Website resmi Pondok Pesantren ' . setting('site_name', config('app.name')) . '. Informasi penerimaan santri baru, program tahfidz, kegiatan, dan berita pesantren.') }}">

    {{-- ── Favicon ─────────────────────────────────────────── --}}
    @if(setting('site_favicon'))
        <link rel="icon" href="{{ asset('storage/' . setting('site_favicon')) }}">
    @else
        <link rel="icon" href="/favicon.ico">
    @endif

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

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

    {{-- Alpine.js for mobile menu & slider --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- AOS — Animate On Scroll (no SEO impact: content stays in DOM) --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>
        /* ── Design tokens — Apple-inspired ─────────────────────── */
        :root {
            --bg:       #f5f5f7;   /* Apple off-white */
            --bg-alt:   #ffffff;
            --card:     #ffffff;
            --border:   rgba(0,0,0,.08);
            --text:     #1d1d1f;   /* Apple near-black */
            --muted:    #6e6e73;   /* Apple secondary gray */

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

        /* ── Buttons — Apple style ──────────────────────────────── */
        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem;
            border-radius: .875rem;
            font-size: .9375rem; font-weight: 600;
            background: var(--primary); color: #fff;
            transition: background .2s, box-shadow .2s, transform .15s;
            box-shadow: 0 4px 16px color-mix(in oklab, var(--primary) 40%, transparent);
        }
        .btn-primary:hover {
            background: var(--primary-700);
            box-shadow: 0 6px 20px color-mix(in oklab, var(--primary) 50%, transparent);
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

        /* ── Labels & badges ────────────────────────────────────── */
        .fi-label {
            font-size: .6875rem; font-weight: 700;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--primary);
        }
        .fi-badge {
            display: inline-flex; align-items: center; gap: .25rem;
            padding: .2rem .75rem;
            border-radius: 9999px;
            font-size: .75rem; font-weight: 600;
            background: var(--primary-50);
            color: var(--primary-800);
            border: 1px solid var(--primary-200);
        }

        /* ── Hero slider ─────────────────────────────────────────── */
        .slide { position: absolute; inset: 0; transition: opacity .7s ease; }
        .slide.active   { opacity: 1; z-index: 1; }
        .slide.inactive { opacity: 0; z-index: 0; }

        /* ── Gallery masonry ─────────────────────────────────────── */
        .masonry { columns: 3; column-gap: .875rem; }
        @media(max-width:640px){ .masonry { columns: 2; } }
        .masonry-item {
            break-inside: avoid;
            margin-bottom: .875rem;
            border-radius: 1.25rem;
            overflow: hidden;
            display: block;
            position: relative;
            cursor: pointer;
        }

        /* ── Primary accent bar ──────────────────────────────────── */
        .amber-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--primary), color-mix(in oklab, var(--primary) 55%, white) 60%, transparent);
        }

        /* ── Navbar primary-based hover helpers ─────────────────── */
        .nav-link-scrolled { transition: background .2s, color .2s; }
        .nav-link-scrolled:hover { background: color-mix(in oklab, var(--primary) 8%, white); color: var(--primary); }
        .nav-dropdown-item:hover { background: color-mix(in oklab, var(--primary) 8%, white); color: var(--primary); }
        .nav-icon-scrolled:hover { background: color-mix(in oklab, var(--primary) 8%, white); }
        .nav-auth-scrolled:hover { border-color: var(--primary); color: var(--primary); }
        .mobile-nav-hover:hover .mobile-nav-num { color: var(--primary); }
        .mobile-nav-hover:hover { border-color: color-mix(in oklab, var(--primary) 40%, transparent); }
        .mobile-nav-arrow:hover { color: var(--primary); }
        .mobile-subnav-hover:hover { color: color-mix(in oklab, var(--primary) 60%, white); }

        /* ── Section dividers replaced by spacing ─────────────────── */
        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0;
        }

        @keyframes fadeUp { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp .6s ease both; }
        .d1 { animation-delay: .1s; } .d2 { animation-delay: .2s; } .d3 { animation-delay: .3s; }
    </style>
</head>

@php
    $navItems = collect(json_decode(setting('nav_items', ''), true) ?: [
        ['label' => 'Beranda',  'url' => '/',         'target' => '_self', 'is_active' => true, 'children' => []],
        ['label' => 'Profil',   'url' => '#profil',   'target' => '_self', 'is_active' => true, 'children' => []],
        ['label' => 'SPMB',     'url' => '#spmb',     'target' => '_self', 'is_active' => true, 'children' => []],
        ['label' => 'Akademik', 'url' => '#akademik', 'target' => '_self', 'is_active' => true, 'children' => []],
        ['label' => 'Guru',     'url' => '/guru',     'target' => '_self', 'is_active' => true, 'children' => []],
        ['label' => 'Blog',     'url' => '/blog',     'target' => '_self', 'is_active' => true, 'children' => []],
        ['label' => 'Kontak',   'url' => '#kontak',   'target' => '_self', 'is_active' => true, 'children' => []],
    ])->where('is_active', true)->values();

    $defaultSectionOrder = [
        ['key' => 'section_hero',        'visible' => true],
        ['key' => 'section_quick_links', 'visible' => true],
        ['key' => 'section_spmb',        'visible' => true],
        ['key' => 'section_stats',       'visible' => true],
        ['key' => 'section_principal',   'visible' => true],
        ['key' => 'section_spmb_steps',  'visible' => true],
        ['key' => 'section_programs',    'visible' => true],
        ['key' => 'section_activities',  'visible' => true],
        ['key' => 'section_events',      'visible' => true],
        ['key' => 'section_gallery',     'visible' => true],
        ['key' => 'section_blog',        'visible' => true],
        ['key' => 'section_donasi',      'visible' => true],
        ['key' => 'section_contact',     'visible' => true],
    ];

    $sectionOrder = json_decode(setting('section_order', ''), true) ?: $defaultSectionOrder;

    $sectionPartials = [
        'section_hero'        => 'sections.hero',
        'section_quick_links' => 'sections.quick-links',
        'section_spmb'        => 'sections.spmb',
        'section_stats'       => 'sections.stats',
        'section_principal'   => 'sections.principal',
        'section_spmb_steps'  => 'sections.spmb-steps',
        'section_programs'    => 'sections.programs',
        'section_activities'  => 'sections.activities',
        'section_events'      => 'sections.events',
        'section_books'       => 'sections.books',
        'section_gallery'     => 'sections.gallery',
        'section_blog'        => 'sections.blog',
        'section_donasi'      => 'sections.donasi',
        'section_contact'     => 'sections.contact',
    ];
@endphp

<body class="min-h-screen antialiased overflow-x-hidden"
      x-data="{
          mobileOpen: false,
          scrolled: false,
          slide: 0,
          total: {{ max($slides->count(), 1) }}
      }"
      x-init="
          setInterval(() => slide = (slide + 1) % total, 5000);
          window.addEventListener('scroll', () => scrolled = window.scrollY > 60, { passive: true });
      ">

    {{-- ═══════════════════════════════════════════════════
         NAVIGATION HEADER — transparan di atas, solid saat scroll
    ═══════════════════════════════════════════════════ --}}
    <header class="sticky top-0 z-50 transition-all duration-300"
            :style="scrolled
                ? 'background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);border-bottom:1px solid #e5e7eb;box-shadow:0 1px 3px 0 rgb(0 0 0/.1)'
                : 'background:transparent;border-bottom:1px solid transparent'">

        {{-- Amber bar — hanya tampil saat scrolled --}}
        <div class="amber-bar overflow-hidden transition-all duration-300"
             :style="scrolled ? 'height:3px;opacity:1' : 'height:0;opacity:0'"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5 shrink-0 min-w-0">
                    @if(setting('site_logo'))
                        <img src="{{ asset('storage/' . setting('site_logo')) }}"
                             alt="{{ setting('site_name', config('app.name')) }}"
                             class="w-9 h-9 rounded-xl object-contain shrink-0">
                    @else
                        <div class="w-9 h-9 shrink-0 rounded-xl shadow flex items-center justify-center" style="background:var(--primary)">
                            <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="leading-tight min-w-0">
                        <div class="font-bold text-sm truncate transition-colors duration-300"
                             :class="scrolled ? 'text-gray-900' : 'text-white'">
                            {{ setting('site_name', config('app.name', "Qurrota A'yun")) }}
                        </div>
                        <div class="text-[10px] font-medium uppercase tracking-widest transition-colors duration-300"
                             :style="scrolled ? 'color:var(--primary)' : 'color:color-mix(in oklab,var(--primary) 65%,white)'">
                            {{ setting('site_tagline', 'Unggul · Berkarakter') }}
                        </div>
                    </div>
                </a>

                {{-- Right: nav links + auth + hamburger --}}
                <div class="flex items-center gap-2">

                    {{-- Desktop nav --}}
                    <nav class="hidden lg:flex items-center gap-0.5">
                        @foreach($navItems as $item)
                            @php $children = collect($item['children'] ?? [])->where('is_active', true)->values(); @endphp
                            @if($children->isNotEmpty())
                                <div x-data="{ dropOpen: false }" class="relative">
                                    <button @mouseenter="dropOpen = true" @mouseleave="dropOpen = false"
                                            class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-1"
                                            :class="scrolled ? 'text-gray-500 nav-link-scrolled' : 'text-white/80 hover:text-white hover:bg-white/10'">
                                        {{ $item['label'] }}
                                        <svg class="w-3 h-3 transition-transform duration-200" :class="dropOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="dropOpen"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         x-transition:leave="transition ease-in duration-100"
                                         x-transition:leave-start="opacity-100 translate-y-0"
                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                         @mouseenter="dropOpen = true" @mouseleave="dropOpen = false"
                                         class="absolute top-full left-0 mt-1 min-w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50 py-1">
                                        @foreach($children as $child)
                                            <a href="{{ $child['url'] }}" target="{{ $child['target'] ?? '_self' }}"
                                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 nav-dropdown-item transition-colors">
                                                <span class="w-1 h-1 rounded-full shrink-0" style="background:var(--primary)"></span>
                                                {{ $child['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                                   :class="scrolled ? 'text-gray-500 nav-link-scrolled' : 'text-white/80 hover:text-white hover:bg-white/10'">
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </nav>

                    {{-- Cart icon --}}
                    @php $cartCount = \App\Http\Controllers\CartController::itemCount(); @endphp
                    <a href="{{ route('cart.index') }}"
                       class="relative w-9 h-9 rounded-lg flex items-center justify-center transition-colors"
                       :class="scrolled ? 'nav-icon-scrolled' : 'hover:bg-white/10'"
                       title="Keranjang">
                        <svg class="w-5 h-5 transition-colors" :class="scrolled ? 'text-gray-700' : 'text-white'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-4.5 h-4.5 px-1 flex items-center justify-center rounded-full text-[10px] font-bold text-white bg-red-500">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- Auth + hamburger --}}
                    @auth
                        <div x-data="{ userDropOpen: false }" class="relative hidden sm:block">
                            <button @click="userDropOpen = !userDropOpen"
                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold border transition-all duration-200"
                                    :class="scrolled
                                        ? 'border-gray-200 text-gray-700 bg-white nav-auth-scrolled'
                                        : 'border-white/40 text-white hover:bg-white/10'">
                                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[10px] font-bold text-white"
                                     style="background:var(--primary)">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                {{ Str::limit(Auth::user()->name, 12) }}
                            </button>
                            <div x-show="userDropOpen" @click.outside="userDropOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 top-full mt-1.5 min-w-40 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50 py-1">
                                <a href="{{ route('cart.index') }}"
                                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 nav-dropdown-item transition-colors">
                                    🛒 Keranjang
                                    @if($cartCount > 0)
                                        <span class="ml-auto text-xs font-bold px-1.5 py-0.5 rounded-full text-white bg-red-500">{{ $cartCount }}</span>
                                    @endif
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border transition-all duration-200"
                           :class="scrolled
                               ? 'border-gray-200 text-gray-700 bg-white nav-auth-scrolled'
                               : 'border-white/40 text-white hover:bg-white/10'">
                            Masuk
                        </a>
                    @endauth

                    {{-- Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="lg:hidden w-9 h-9 rounded-lg flex flex-col items-center justify-center gap-1.5 transition-colors"
                            :class="scrolled ? 'nav-icon-scrolled' : 'hover:bg-white/10'"
                            :aria-expanded="mobileOpen" aria-label="Toggle menu">
                        <span class="w-5 h-0.5 rounded transition-all duration-200"
                              :class="[mobileOpen ? 'rotate-45 translate-y-2' : '', scrolled ? 'bg-gray-700' : 'bg-white']"></span>
                        <span class="w-5 h-0.5 rounded transition-all duration-200"
                              :class="[mobileOpen ? 'opacity-0' : '', scrolled ? 'bg-gray-700' : 'bg-white']"></span>
                        <span class="w-5 h-0.5 rounded transition-all duration-200"
                              :class="[mobileOpen ? '-rotate-45 -translate-y-2' : '', scrolled ? 'bg-gray-700' : 'bg-white']"></span>
                    </button>
                </div>
            </div>
        </div>

    </header>

    {{-- ═══════════════════════════════════════════════════
         MOBILE FULL-SCREEN MENU OVERLAY
    ═══════════════════════════════════════════════════ --}}
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="lg:hidden fixed inset-0 flex flex-col"
         style="z-index:60; background:linear-gradient(145deg,#0f172a 0%,#1a2744 50%,#0f2236 100%)"
         x-trap.noscroll="mobileOpen">

        {{-- Top bar: logo + close --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-white/10 shrink-0">
            <a href="/" @click="mobileOpen = false" class="flex items-center gap-3">
                @if(setting('site_logo'))
                    <img src="{{ asset('storage/' . setting('site_logo')) }}"
                         alt="{{ setting('site_name', config('app.name')) }}"
                         class="w-10 h-10 rounded-xl object-contain">
                @else
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg" style="background:var(--primary)">
                        <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <div class="font-bold text-white text-sm">{{ setting('site_name', config('app.name', "Qurrota A'yun")) }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest" style="color:color-mix(in oklab,var(--primary) 65%,white)">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                </div>
            </a>

            {{-- Close button --}}
            <button @click="mobileOpen = false"
                    class="w-10 h-10 rounded-xl border border-white/20 flex items-center justify-center text-white/70 hover:text-white hover:border-white/40 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Nav items --}}
        <nav class="flex-1 overflow-y-auto px-6 py-6 flex flex-col justify-center gap-1">
            @foreach($navItems as $i => $item)
                @php $children = collect($item['children'] ?? [])->where('is_active', true)->values(); @endphp
                @if($children->isNotEmpty())
                    <div x-data="{ mobileSubOpen: false }">
                        <button @click="mobileSubOpen = !mobileSubOpen"
                                class="mobile-nav-hover group w-full flex items-center gap-4 py-3.5 border-b border-white/8 transition-all duration-200">
                            <span class="mobile-nav-num text-xs font-bold text-white/25 transition-colors w-6 shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-2xl font-bold text-white/70 group-hover:text-white transition-colors tracking-tight flex-1 text-left">{{ $item['label'] }}</span>
                            <svg class="mobile-nav-arrow w-4 h-4 text-white/20 shrink-0 transition-all duration-200"
                                 :class="mobileSubOpen ? 'rotate-90' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="mobileSubOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="pl-10 pb-2 space-y-1">
                            @foreach($children as $child)
                                <a href="{{ $child['url'] }}" target="{{ $child['target'] ?? '_self' }}"
                                   @click="mobileOpen = false"
                                   class="mobile-subnav-hover flex items-center gap-2 py-2 text-lg font-medium text-white/55 transition-colors">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                    {{ $child['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                       @click="mobileOpen = false"
                       class="mobile-nav-hover group flex items-center gap-4 py-3.5 border-b border-white/8 transition-all duration-200">
                        <span class="mobile-nav-num text-xs font-bold text-white/25 transition-colors w-6 shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-2xl font-bold text-white/70 group-hover:text-white transition-colors tracking-tight">{{ $item['label'] }}</span>
                        <svg class="mobile-nav-arrow w-4 h-4 text-white/20 ml-auto shrink-0 transition-all group-hover:translate-x-1 duration-200"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Auth buttons --}}
        <div class="shrink-0 px-6 py-6 border-t border-white/10 space-y-3">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="btn-primary flex items-center justify-center gap-2 w-full py-3 rounded-xl">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="btn-primary flex items-center justify-center gap-2 w-full py-3 rounded-xl">
                            Daftar SPMB Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    @endif
                    <a href="{{ route('login') }}"
                       class="flex items-center justify-center w-full py-3 rounded-xl border border-white/25 text-white/80 font-semibold hover:bg-white/10 hover:text-white transition-colors">
                        Masuk
                    </a>
                @endauth
            @endif

            {{-- Tagline --}}
            <p class="text-center text-xs text-white/30 pt-1">© {{ date('Y') }} {{ setting('site_name', config('app.name')) }}</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         SECTIONS — ordered & toggled via admin settings
    ═══════════════════════════════════════════════════ --}}
    @foreach($sectionOrder as $section)
        @if(($section['visible'] ?? true) && isset($sectionPartials[$section['key']]))
            @include($sectionPartials[$section['key']])
        @endif
    @endforeach

    {{-- ═══════════════════════════════════════════════════
         FOOTER
    ═══════════════════════════════════════════════════ --}}
    <footer id="kontak" style="background:#111827;border-top:1px solid rgba(255,255,255,.07)">

        {{-- Top accent bar --}}
        <div style="height:3px;background:linear-gradient(90deg,var(--primary),color-mix(in oklab,var(--primary) 50%,white) 60%,transparent)"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4 mb-12">

                {{-- ── Col 1: Brand ── --}}
                <div class="sm:col-span-2 lg:col-span-1" data-aos="fade-up">
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
                        <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener" title="Facebook"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        @endif
                        @if(setting('social_instagram'))
                        <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener" title="Instagram"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        @endif
                        @if(setting('social_youtube'))
                        <a href="{{ setting('social_youtube') }}" target="_blank" rel="noopener" title="YouTube"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                        </a>
                        @endif
                        @if(setting('social_whatsapp'))
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', setting('social_whatsapp')) }}"
                           target="_blank" rel="noopener" title="WhatsApp"
                           class="w-9 h-9 rounded-xl flex items-center justify-center transition-all hover:scale-110"
                           style="background:rgba(255,255,255,.08);color:rgba(255,255,255,.6)">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>

                {{-- ── Col 2: Navigasi (dari settings) ── --}}
                <div data-aos="fade-up" data-aos-delay="80">
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color:color-mix(in oklab,var(--primary) 80%,white)">Navigasi</h4>
                    <ul class="space-y-2.5">
                        @foreach($navItems as $item)
                        <li>
                            <a href="{{ str_starts_with($item['url'], '#') ? '/'.$item['url'] : $item['url'] }}"
                               target="{{ $item['target'] ?? '_self' }}"
                               class="text-sm text-white/50 hover:text-white transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shrink-0"
                                      style="background:var(--primary)"></span>
                                {{ $item['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- ── Col 3: Layanan ── --}}
                <div data-aos="fade-up" data-aos-delay="160">
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color:color-mix(in oklab,var(--primary) 80%,white)">Layanan</h4>
                    <ul class="space-y-2.5">
                        @foreach([
                            ['label' => 'Toko Buku',       'url' => route('books.index')],
                            ['label' => 'Daftar Santri',   'url' => route('ppdb.index')],
                            ['label' => 'Blog & Berita',   'url' => route('blog.index')],
                            ['label' => 'Unduhan',         'url' => route('downloads.index')],
                            ['label' => 'Tenaga Pendidik', 'url' => route('teachers.index')],
                            ['label' => 'Donasi',          'url' => route('donasi.index')],
                        ] as $link)
                        <li>
                            <a href="{{ $link['url'] }}"
                               class="text-sm text-white/50 hover:text-white transition-colors flex items-center gap-2 group">
                                <span class="w-1 h-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity shrink-0"
                                      style="background:var(--primary)"></span>
                                {{ $link['label'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- ── Col 4: Kontak ── --}}
                <div data-aos="fade-up" data-aos-delay="240">
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4"
                        style="color:color-mix(in oklab,var(--primary) 80%,white)">Kontak</h4>
                    <ul class="space-y-3">
                        @if(setting('contact_address'))
                        <li class="flex gap-3 text-xs text-white/50 leading-relaxed">
                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>{{ setting('contact_address') }}</span>
                        </li>
                        @endif
                        @if(setting('contact_phone'))
                        <li class="flex gap-3 text-xs text-white/50">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ setting('contact_phone') }}</span>
                        </li>
                        @endif
                        @if(setting('contact_email'))
                        <li class="flex gap-3 text-xs text-white/50">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ setting('contact_email') }}</span>
                        </li>
                        @endif
                        @if(setting('contact_hours'))
                        <li class="flex gap-3 text-xs text-white/50">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:color-mix(in oklab,var(--primary) 70%,white)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ setting('contact_hours') }}</span>
                        </li>
                        @endif
                        @if(setting('shop_whatsapp') || setting('social_whatsapp'))
                        @php $waNum = preg_replace('/\D/', '', setting('shop_whatsapp', setting('social_whatsapp', ''))); @endphp
                        <li class="pt-1">
                            <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white transition-all hover:opacity-90"
                               style="background:var(--primary)">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
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
                    © {{ date('Y') }} {{ setting('site_name', config('app.name')) }}. Hak cipta dilindungi.
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

    {{-- AOS init --}}
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            duration: 650,
            easing: 'ease-out-quad',
            offset: 50,
        });
    </script>

    <x-popup-overlay />
    <x-floating-buttons />
</body>
</html>
