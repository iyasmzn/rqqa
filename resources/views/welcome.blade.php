<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
            background: var(--color-amber-700);
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
            background: var(--color-amber-50);
            color: var(--color-amber-800);
            border: 1px solid var(--color-amber-200);
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
        'section_gallery'     => 'sections.gallery',
        'section_blog'        => 'sections.blog',
        'section_contact'     => 'sections.contact',
    ];
@endphp

<body class="min-h-screen antialiased"
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
                        <div class="w-9 h-9 shrink-0 rounded-xl bg-amber-500 shadow flex items-center justify-center">
                            <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="leading-tight min-w-0">
                        <div class="font-bold text-sm truncate transition-colors duration-300"
                             :class="scrolled ? 'text-gray-900' : 'text-white'">
                            {{ setting('site_name', config('app.name', "Qurrota A'yun")) }}
                        </div>
                        <div class="text-[10px] font-medium uppercase tracking-widest transition-colors duration-300"
                             :class="scrolled ? 'text-amber-600' : 'text-amber-300'">
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
                                            :class="scrolled ? 'text-gray-500 hover:bg-amber-50 hover:text-amber-700' : 'text-white/80 hover:text-white hover:bg-white/10'">
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
                                               class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-gray-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                <span class="w-1 h-1 rounded-full bg-amber-400 shrink-0"></span>
                                                {{ $child['label'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $item['url'] }}" target="{{ $item['target'] ?? '_self' }}"
                                   class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                                   :class="scrolled ? 'text-gray-500 hover:bg-amber-50 hover:text-amber-700' : 'text-white/80 hover:text-white hover:bg-white/10'">
                                    {{ $item['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </nav>

                    {{-- Auth + hamburger --}}
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary text-xs hidden sm:inline-flex">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Dashboard
                            </a>
                        @else
                            {{-- "Masuk" — outline putih saat transparan, normal saat scrolled --}}
                            <a href="{{ route('login') }}"
                               class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold border transition-all duration-200"
                               :class="scrolled
                                   ? 'border-gray-200 text-gray-700 bg-white hover:border-amber-400 hover:text-amber-700'
                                   : 'border-white/40 text-white hover:bg-white/10'">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary text-xs hidden sm:inline-flex">Daftar SPMB</a>
                            @endif
                        @endauth
                    @endif

                    {{-- Hamburger --}}
                    <button @click="mobileOpen = !mobileOpen"
                            class="lg:hidden w-9 h-9 rounded-lg flex flex-col items-center justify-center gap-1.5 transition-colors"
                            :class="scrolled ? 'hover:bg-amber-50' : 'hover:bg-white/10'"
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
                    <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow-lg">
                        <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                    </div>
                @endif
                <div>
                    <div class="font-bold text-white text-sm">{{ setting('site_name', config('app.name', "Qurrota A'yun")) }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-amber-400">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
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
                                class="group w-full flex items-center gap-4 py-3.5 border-b border-white/8 hover:border-amber-500/50 transition-all duration-200">
                            <span class="text-xs font-bold text-white/25 group-hover:text-amber-500 transition-colors w-6 shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-2xl font-bold text-white/70 group-hover:text-white transition-colors tracking-tight flex-1 text-left">{{ $item['label'] }}</span>
                            <svg class="w-4 h-4 text-white/20 group-hover:text-amber-400 shrink-0 transition-all duration-200"
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
                                   class="flex items-center gap-2 py-2 text-lg font-medium text-white/55 hover:text-amber-300 transition-colors">
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
                       class="group flex items-center gap-4 py-3.5 border-b border-white/8 hover:border-amber-500/50 transition-all duration-200">
                        <span class="text-xs font-bold text-white/25 group-hover:text-amber-500 transition-colors w-6 shrink-0">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-2xl font-bold text-white/70 group-hover:text-white transition-colors tracking-tight">{{ $item['label'] }}</span>
                        <svg class="w-4 h-4 text-white/20 group-hover:text-amber-400 ml-auto shrink-0 transition-all group-hover:translate-x-1 duration-200"
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
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                @else
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-400 transition-colors">
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
    <footer id="kontak" class="border-t" style="background:var(--card); border-color:var(--border)">
        <div class="amber-bar"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

                {{-- Brand --}}
                <div class="lg:col-span-1" data-aos="fade-up">
                    <div class="flex items-center gap-2.5 mb-4">
                        @if(setting('site_logo'))
                            <img src="{{ asset('storage/' . setting('site_logo')) }}"
                                 alt="{{ setting('site_name', config('app.name')) }}"
                                 class="w-9 h-9 rounded-xl object-contain">
                        @else
                            <div class="w-9 h-9 rounded-xl bg-amber-500 shadow flex items-center justify-center">
                                <span class="text-white font-extrabold text-base">{{ strtoupper(substr(setting('site_name', config('app.name', 'S')), 0, 1)) }}</span>
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-sm" style="color:var(--text)">{{ setting('site_name', config('app.name', "Qurrota A'yun")) }}</div>
                            <div class="text-[10px] text-amber-600 font-semibold uppercase tracking-wider">{{ setting('site_tagline', 'Unggul · Berkarakter') }}</div>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed mb-4" style="color:var(--muted)">Mencetak generasi penerus bangsa yang cerdas, berkarakter mulia, dan siap menghadapi tantangan masa depan.</p>
                    <div class="flex gap-2">
                        @if(setting('social_facebook'))
                            <a href="{{ setting('social_facebook') }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-xl border flex items-center justify-center text-[10px] font-bold transition-all hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600"
                               style="border-color:var(--border); color:var(--muted)">FB</a>
                        @endif
                        @if(setting('social_instagram'))
                            <a href="{{ setting('social_instagram') }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-xl border flex items-center justify-center text-[10px] font-bold transition-all hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600"
                               style="border-color:var(--border); color:var(--muted)">IG</a>
                        @endif
                        @if(setting('social_youtube'))
                            <a href="{{ setting('social_youtube') }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-xl border flex items-center justify-center text-[10px] font-bold transition-all hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600"
                               style="border-color:var(--border); color:var(--muted)">YT</a>
                        @endif
                        @if(setting('social_whatsapp'))
                            <a href="https://wa.me/{{ setting('social_whatsapp') }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-xl border flex items-center justify-center text-[10px] font-bold transition-all hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600"
                               style="border-color:var(--border); color:var(--muted)">WA</a>
                        @endif
                    </div>
                </div>

                {{-- Links --}}
                <div data-aos="fade-up" data-aos-delay="100">
                    <div class="fi-label mb-3">Menu Utama</div>
                    <ul class="space-y-2">
                        @foreach(['Profil Sekolah','Visi & Misi','Struktur Organisasi','Tenaga Pendidik','Fasilitas'] as $l)
                            <li><a href="#" class="text-xs hover:text-amber-600 transition-colors" style="color:var(--muted)">{{ $l }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div data-aos="fade-up" data-aos-delay="200">
                    <div class="fi-label mb-3">Layanan</div>
                    <ul class="space-y-2">
                        @foreach(['SPMB Online','Portal Siswa','Portal Orang Tua','E-Learning','Jadwal Pelajaran'] as $l)
                            <li><a href="#" class="text-xs hover:text-amber-600 transition-colors" style="color:var(--muted)">{{ $l }}</a></li>
                        @endforeach
                    </ul>
                </div>

                {{-- Contact --}}
                <div data-aos="fade-up" data-aos-delay="300">
                    <div class="fi-label mb-3">Kontak</div>
                    <ul class="space-y-3 text-xs" style="color:var(--muted)">
                        @if(setting('contact_address'))
                            <li class="flex gap-2"><span>📍</span><span>{{ setting('contact_address') }}</span></li>
                        @endif
                        @if(setting('contact_phone'))
                            <li class="flex gap-2"><span>📞</span><span>{{ setting('contact_phone') }}</span></li>
                        @endif
                        @if(setting('contact_email'))
                            <li class="flex gap-2"><span>✉️</span><span>{{ setting('contact_email') }}</span></li>
                        @endif
                        @if(setting('contact_hours'))
                            <li class="flex gap-2"><span>🕐</span><span>{{ setting('contact_hours') }}</span></li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color:var(--border)">
                <span class="text-xs" style="color:var(--muted)">© {{ date('Y') }} {{ setting('site_name', config('app.name', "Qurrota A'yun")) }}. Hak cipta dilindungi.</span>
                <div class="flex gap-4">
                    @foreach(['Kebijakan Privasi','Syarat & Ketentuan','Aksesibilitas'] as $l)
                        <a href="#" class="text-xs hover:text-amber-600 transition-colors" style="color:var(--muted)">{{ $l }}</a>
                    @endforeach
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
</body>
</html>
