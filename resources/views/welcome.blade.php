<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'SMA Negeri') }} — Unggul, Berkarakter, Berprestasi</title>
    <meta name="description" content="Website resmi {{ config('app.name') }}. Informasi SPMB, akademik, kegiatan, dan berita sekolah.">

    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    {{-- Alpine.js for mobile menu & slider --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- AOS — Animate On Scroll (no SEO impact: content stays in DOM) --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.4/dist/aos.css">

    <style>
        /* ── Filament design tokens ── */
        :root {
            --bg:       #f9fafb;
            --card:     #ffffff;
            --border:   #e5e7eb;
            --text:     #030712;
            --muted:    #6b7280;
            --amber:    #d97706;
            --amber-dk: #b45309;
            --amber-lt: #fffbeb;
        }
        .dark {
            --bg:     #030712;
            --card:   #111827;
            --border: #1f2937;
            --text:   #f9fafb;
            --muted:  #9ca3af;
        }

        body { background: var(--bg); color: var(--text); font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }

        /* Cards */
        .fi-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: .75rem;
            box-shadow: 0 1px 2px 0 rgb(0 0 0/.05);
            transition: box-shadow .15s, border-color .15s;
        }
        .fi-card-hover:hover { box-shadow: 0 6px 20px rgb(0 0 0/.1); border-color: #fcd34d; }

        /* Buttons */
        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .6rem 1.25rem; border-radius: .5rem; font-size: .875rem; font-weight: 600;
            background: #d97706; color: #fff; transition: background .15s;
        }
        .btn-primary:hover { background: #b45309; }

        .btn-outline {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .6rem 1.25rem; border-radius: .5rem; font-size: .875rem; font-weight: 600;
            border: 1px solid var(--border); color: var(--text); background: var(--card); transition: border-color .15s, color .15s;
        }
        .btn-outline:hover { border-color: #d97706; color: #d97706; }

        /* Labels */
        .fi-label { font-size: .6875rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #d97706; }
        .fi-badge  { display:inline-flex; align-items:center; gap:.25rem; padding:.125rem .625rem; border-radius:9999px; font-size:.75rem; font-weight:600; background:#fffbeb; color:#92400e; border:1px solid #fde68a; }

        /* Hero slider */
        .slide { position:absolute; inset:0; transition: opacity .7s ease; }
        .slide.active { opacity:1; z-index:1; }
        .slide.inactive { opacity:0; z-index:0; }

        /* Gallery — CSS columns masonry (no plugin needed) */
        .masonry { columns: 3; column-gap: .75rem; }
        @media(max-width:640px){ .masonry { columns: 2; } }
        .masonry-item {
            break-inside: avoid;
            margin-bottom: .75rem;
            border-radius: .75rem;
            overflow: hidden;
            display: block;
            position: relative;
            cursor: pointer;
        }

        /* Amber top divider */
        .amber-bar { height:3px; background:linear-gradient(90deg,#d97706,#fbbf24 60%,transparent); }

        @keyframes fadeUp{ from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
        .fade-up{ animation:fadeUp .55s ease both; }
        .d1{animation-delay:.1s} .d2{animation-delay:.2s} .d3{animation-delay:.3s}
    </style>
</head>

<body class="min-h-screen antialiased"
      x-data="{
          mobileOpen: false,
          scrolled: false,
          slide: 0,
          slides: [
              { title: 'Unggul dalam Akademik',      sub: 'Raih prestasi terbaik bersama guru-guru berpengalaman dan fasilitas modern.',       img: 'https://picsum.photos/seed/hero-school1/1600/900' },
              { title: 'Berkarakter & Berintegritas', sub: 'Membentuk generasi beriman, bertakwa, dan berakhlak mulia untuk bangsa.',           img: 'https://picsum.photos/seed/hero-school2/1600/900' },
              { title: 'Buka Pendaftaran 2026/2027',  sub: 'SPMB resmi dibuka. Daftarkan putra-putri Anda sekarang sebelum batas waktu.',      img: 'https://picsum.photos/seed/hero-school3/1600/900' },
          ]
      }"
      x-init="
          setInterval(() => slide = (slide + 1) % slides.length, 5000);
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
                    <div class="w-9 h-9 shrink-0 rounded-xl bg-amber-500 shadow flex items-center justify-center">
                        <span class="text-white font-extrabold text-base">{{ strtoupper(substr(config('app.name','S'),0,1)) }}</span>
                    </div>
                    <div class="leading-tight min-w-0">
                        <div class="font-bold text-sm truncate transition-colors duration-300"
                             :class="scrolled ? 'text-gray-900' : 'text-white'">
                            {{ config('app.name', 'SMA Negeri 1') }}
                        </div>
                        <div class="text-[10px] font-medium uppercase tracking-widest transition-colors duration-300"
                             :class="scrolled ? 'text-amber-600' : 'text-amber-300'">
                            Unggul · Berkarakter
                        </div>
                    </div>
                </a>

                {{-- Desktop nav --}}
                <nav class="hidden lg:flex items-center gap-0.5">
                    @foreach([
                        ['Beranda','#'],
                        ['Profil','#profil'],
                        ['SPMB','#spmb'],
                        ['Akademik','#akademik'],
                        ['Kegiatan','#kegiatan'],
                        ['Galeri','#galeri'],
                        ['Blog','#blog'],
                        ['Kontak','#kontak'],
                    ] as [$label,$href])
                        <a href="{{ $href }}"
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200"
                           :class="scrolled
                               ? 'text-gray-500 hover:bg-amber-50 hover:text-amber-700'
                               : 'text-white/80 hover:text-white hover:bg-white/10'">
                            {{ $label }}
                        </a>
                    @endforeach
                </nav>

                {{-- Right: auth + hamburger --}}
                <div class="flex items-center gap-2">
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
                <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center shadow-lg">
                    <span class="text-white font-extrabold text-base">{{ strtoupper(substr(config('app.name','S'),0,1)) }}</span>
                </div>
                <div>
                    <div class="font-bold text-white text-sm">{{ config('app.name', 'SMA Negeri 1') }}</div>
                    <div class="text-[10px] font-semibold uppercase tracking-widest text-amber-400">Unggul · Berkarakter</div>
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
            @foreach([
                ['01','Beranda',  '#'],
                ['02','Profil',   '#profil'],
                ['03','SPMB',     '#spmb'],
                ['04','Akademik', '#akademik'],
                ['05','Kegiatan', '#kegiatan'],
                ['06','Galeri',   '#galeri'],
                ['07','Blog',     '#blog'],
                ['08','Kontak',   '#kontak'],
            ] as [$num, $label, $href])
                <a href="{{ $href }}" @click="mobileOpen = false"
                   class="group flex items-center gap-4 py-3.5 border-b border-white/8 hover:border-amber-500/50 transition-all duration-200">
                    <span class="text-xs font-bold text-white/25 group-hover:text-amber-500 transition-colors w-6 shrink-0">{{ $num }}</span>
                    <span class="text-2xl font-bold text-white/70 group-hover:text-white transition-colors tracking-tight">{{ $label }}</span>
                    <svg class="w-4 h-4 text-white/20 group-hover:text-amber-400 ml-auto shrink-0 transition-all group-hover:translate-x-1 duration-200"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
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
            <p class="text-center text-xs text-white/30 pt-1">© {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         HERO IMAGE WITH SLIDER
    ═══════════════════════════════════════════════════ --}}
    <section class="relative h-130 sm:h-145 lg:h-160 overflow-hidden -mt-17">

        {{-- Slides --}}
        <template x-for="(s, i) in slides" :key="i">
            <div :class="['slide', i === slide ? 'active' : 'inactive']">
                {{-- Background image — ganti src dengan media dari admin --}}
                <img :src="s.img" :alt="s.title"
                     class="absolute inset-0 w-full h-full object-cover">
                {{-- Dark gradient overlay untuk keterbacaan teks --}}
                <div class="absolute inset-0"
                     style="background:linear-gradient(135deg, rgba(0,0,0,.65) 0%, rgba(0,0,0,.35) 60%, rgba(0,0,0,.15) 100%)"></div>
                {{-- Content --}}
                <div class="relative z-10 h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl text-white">
                            <div class="inline-flex items-center gap-2 bg-white/15 border border-white/25 backdrop-blur-sm px-4 py-1.5 rounded-full text-sm mb-5">
                                <span class="w-2 h-2 bg-amber-300 rounded-full animate-pulse"></span>
                                <span class="opacity-90 text-sm font-medium" x-text="`${i + 1} / ${slides.length}`"></span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.12] tracking-tight mb-4"
                                x-text="s.title"></h1>
                            <p class="text-white/80 text-base sm:text-lg leading-relaxed mb-8 max-w-lg"
                               x-text="s.sub"></p>
                            <div class="flex flex-wrap gap-3">
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-amber-400 text-amber-900 font-bold hover:bg-amber-300 transition shadow-lg shadow-amber-500/30 text-sm">
                                        Daftar SPMB Sekarang
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                    </a>
                                @endif
                                <a href="#profil"
                                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/15 border border-white/30 backdrop-blur-sm font-semibold hover:bg-white/25 transition text-sm">
                                    Profil Sekolah
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Dot indicators --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-10 flex gap-2">
            <template x-for="(s, i) in slides" :key="i">
                <button @click="slide = i"
                        class="transition-all duration-300 rounded-full"
                        :class="i === slide ? 'w-6 h-2.5 bg-amber-400' : 'w-2.5 h-2.5 bg-white/50 hover:bg-white/80'"></button>
            </template>
        </div>

        {{-- Prev / Next arrows --}}
        <button @click="slide = (slide - 1 + slides.length) % slides.length"
                class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm text-white flex items-center justify-center transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="slide = (slide + 1) % slides.length"
                class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm text-white flex items-center justify-center transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Bottom wave --}}
        <div class="absolute bottom-0 inset-x-0 z-10">
            <svg viewBox="0 0 1440 56" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="w-full h-14">
                <path d="M0 56L80 48C160 40 320 24 480 22.7C640 21.3 800 34.7 960 40C1120 45.3 1280 42.7 1360 41.3L1440 40V56H0Z" fill="var(--bg)"/>
            </svg>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════
         STATIC CONTENT — QUICK LINKS
    ═══════════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-2 pb-10">
        <div class="fi-card p-1 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-1"
             data-aos="fade-up" data-aos-duration="500">
            @foreach([
                ['📋', 'SPMB', '#spmb'],
                ['📚', 'E-Learning', '#akademik'],
                ['📅', 'Jadwal', '#jadwal'],
                ['🏆', 'Prestasi', '#prestasi'],
                ['👥', 'Alumni', '#alumni'],
                ['📞', 'Kontak', '#kontak'],
            ] as [$icon, $label, $href])
                <a href="{{ $href }}"
                   class="flex flex-col items-center gap-1.5 py-4 px-3 rounded-xl transition-colors hover:bg-amber-50 group"
                   data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                    <span class="text-2xl">{{ $icon }}</span>
                    <span class="text-xs font-semibold group-hover:text-amber-700 transition-colors" style="color:var(--muted)">{{ $label }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ═══════════════════════════════════════════════════
             CARD: CTA SPMB
        ═══════════════════════════════════════════════════ --}}
        <section id="spmb" class="mb-6" data-aos="fade-up">
            <div class="rounded-2xl overflow-hidden border border-amber-200"
                 style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 60%,#fde68a 100%)">
                <div class="grid lg:grid-cols-2 gap-0">
                    <div class="p-8 lg:p-10" data-aos="fade-right" data-aos-delay="100">
                        <div class="fi-badge mb-4">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block animate-pulse"></span>
                            Penerimaan Peserta Didik Baru
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-amber-900 leading-snug mb-3">
                            SPMB Tahun Ajaran<br>2026 / 2027 Dibuka!
                        </h2>
                        <p class="text-amber-800/80 text-sm leading-relaxed mb-6">
                            Pendaftaran peserta didik baru resmi dibuka. Tersedia jalur Prestasi, Zonasi, dan Afirmasi. Segera lengkapi berkas dan daftarkan diri Anda sebelum batas waktu.
                        </p>
                        <div class="flex flex-wrap gap-3">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn-primary text-sm">
                                    Daftar Sekarang
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            @endif
                            <a href="#" class="btn-outline border-amber-300 text-amber-900 bg-transparent hover:bg-amber-100 text-sm">
                                Unduh Panduan
                            </a>
                        </div>
                    </div>
                    <div class="hidden lg:flex items-center justify-center bg-amber-400/20 p-10"
                         data-aos="fade-left" data-aos-delay="200">
                        <div class="text-center">
                            <div class="text-7xl mb-4">🎓</div>
                            <div class="grid grid-cols-3 gap-4 text-center">
                                @foreach([['30 Mei','Batas Daftar'],['10 Jun','Seleksi'],['25 Jun','Pengumuman']] as [$d,$l])
                                    <div class="bg-white/70 rounded-xl p-3">
                                        <div class="text-sm font-extrabold text-amber-700">{{ $d }}</div>
                                        <div class="text-[10px] text-amber-600 font-medium mt-0.5">{{ $l }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             CARD: INFORMASI UMUM SMA
        ═══════════════════════════════════════════════════ --}}
        <section id="profil" class="mb-10">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach([
                    ['🏫', 'Berdiri Sejak', '1985',       'Terakreditasi A'],
                    ['🎓', 'Total Siswa',   '1.240',      'Aktif tahun ini'],
                    ['👨‍🏫', 'Tenaga Pendidik','86',         'Guru bersertifikat'],
                    ['🏆', 'Prestasi',      '200+',       'Tingkat nasional'],
                ] as [$icon, $label, $val, $sub])
                    <div class="fi-card fi-card-hover p-5 text-center"
                         data-aos="zoom-in" data-aos-delay="{{ $loop->index * 80 }}">
                        <div class="text-3xl mb-2">{{ $icon }}</div>
                        <div class="text-xl font-extrabold text-amber-600">{{ $val }}</div>
                        <div class="text-xs font-semibold mt-0.5" style="color:var(--text)">{{ $label }}</div>
                        <div class="text-[11px] mt-0.5" style="color:var(--muted)">{{ $sub }}</div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             SECTION: SAMBUTAN KEPALA SEKOLAH
        ═══════════════════════════════════════════════════ --}}
        <section id="sambutan" class="mb-12 border-t pt-12" style="border-color:var(--border)">
            <div class="fi-label mb-2" data-aos="fade-up">Sambutan</div>
            <h2 class="text-2xl font-bold mb-8" style="color:var(--text)" data-aos="fade-up" data-aos-delay="50">Sambutan Kepala Sekolah</h2>

            <div class="fi-card p-7 lg:p-10" data-aos="fade-up" data-aos-delay="100">
                <div class="grid lg:grid-cols-3 gap-8 items-start">
                    {{-- Photo placeholder --}}
                    <div class="flex flex-col items-center text-center" data-aos="fade-right" data-aos-delay="150">
                        <div class="w-32 h-32 rounded-2xl bg-linear-to-br from-amber-400 to-amber-600 flex items-center justify-center text-5xl shadow-lg mb-4">👨‍💼</div>
                        <div class="font-bold text-sm" style="color:var(--text)">Drs. H. Ahmad Fauzi, M.Pd.</div>
                        <div class="text-xs mt-0.5" style="color:var(--muted)">Kepala Sekolah</div>
                        <div class="mt-3 fi-badge">NIP. 197003012005011001</div>
                    </div>
                    {{-- Message --}}
                    <div class="lg:col-span-2" data-aos="fade-left" data-aos-delay="150">
                        <svg class="w-8 h-8 text-amber-300 mb-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                        </svg>
                        <p class="text-sm leading-relaxed mb-4" style="color:var(--muted)">
                            Assalamu'alaikum Warahmatullahi Wabarakatuh. Puji syukur kepada Allah SWT atas segala nikmat dan karunia-Nya sehingga {{ config('app.name') }} terus berkembang menjadi lembaga pendidikan yang unggul dan terpercaya.
                        </p>
                        <p class="text-sm leading-relaxed mb-4" style="color:var(--muted)">
                            Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi yang tidak hanya mencerdaskan akal, tetapi juga membentuk karakter mulia. Dengan dukungan tenaga pendidik profesional dan fasilitas modern, kami yakin setiap siswa dapat meraih potensi terbaiknya.
                        </p>
                        <p class="text-sm leading-relaxed" style="color:var(--muted)">
                            Selamat datang dan bergabunglah bersama keluarga besar {{ config('app.name') }}. Mari bersama-sama kita wujudkan generasi penerus bangsa yang cerdas, berkarakter, dan berakhlak mulia.
                        </p>
                        <div class="mt-6 pt-5 border-t flex items-center gap-3" style="border-color:var(--border)">
                            <div class="text-xs" style="color:var(--muted)">Wassalamu'alaikum Wr. Wb.</div>
                            <div class="ml-auto text-xs font-semibold text-amber-600">Drs. H. Ahmad Fauzi, M.Pd.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             SECTION: CTA SPMB (TAHAPAN)
        ═══════════════════════════════════════════════════ --}}
        <section class="mb-12 border-t pt-12" style="border-color:var(--border)">
            <div class="text-center mb-10" data-aos="fade-up">
                <div class="fi-label mb-2">Cara Mendaftar</div>
                <h2 class="text-2xl font-bold" style="color:var(--text)">Tahapan SPMB 2026/2027</h2>
                <p class="mt-2 text-sm" style="color:var(--muted)">Ikuti langkah-langkah berikut untuk mendaftarkan diri sebagai calon peserta didik baru.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['01', '📝', 'Isi Formulir', 'Isi formulir pendaftaran online secara lengkap dan benar melalui portal SPMB.'],
                    ['02', '📁', 'Upload Berkas', 'Upload dokumen yang dipersyaratkan: ijazah/SHUN, rapor, dan pas foto terbaru.'],
                    ['03', '✅', 'Verifikasi', 'Berkas diverifikasi oleh panitia. Pantau status pendaftaran melalui akun Anda.'],
                    ['04', '🎉', 'Pengumuman', 'Hasil seleksi diumumkan pada tanggal 25 Juni 2026 melalui portal resmi sekolah.'],
                ] as [$num, $icon, $title, $desc])
                    <div class="fi-card fi-card-hover p-6 relative"
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="absolute top-4 right-4 text-3xl font-black text-amber-100 select-none">{{ $num }}</div>
                        <div class="text-2xl mb-3">{{ $icon }}</div>
                        <div class="font-bold text-sm mb-2" style="color:var(--text)">{{ $title }}</div>
                        <p class="text-xs leading-relaxed" style="color:var(--muted)">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 text-center">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">
                        Mulai Pendaftaran
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                @endif
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             SECTION: KEGIATAN SEKOLAH
        ═══════════════════════════════════════════════════ --}}
        <section id="kegiatan" class="mb-12 border-t pt-12" style="border-color:var(--border)">
            <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
                <div>
                    <div class="fi-label mb-2">Ekstrakurikuler & Acara</div>
                    <h2 class="text-2xl font-bold" style="color:var(--text)">Kegiatan Sekolah</h2>
                </div>
                <a href="#" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 shrink-0">
                    Lihat Semua <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach([
                    ['⚽', 'Sepak Bola',          'Ekskul',    'Latihan setiap Selasa & Jumat. Juara provinsi 3 tahun berturut-turut.',          'amber'],
                    ['🎭', 'Seni & Drama',          'Ekskul',    'Pentas seni tahunan dan kompetisi teater tingkat nasional.',                     'purple'],
                    ['🔬', 'Karya Ilmiah Remaja',   'Akademik',  'KIR aktif mengikuti lomba riset sains dan teknologi tingkat nasional.',          'blue'],
                    ['🥋', 'Pencak Silat',          'Ekskul',    'Bela diri tradisional. Berprestasi di tingkat nasional dan internasional.',      'green'],
                    ['💻', 'Coding & Robotika',     'Teknologi', 'Workshop coding, IoT, dan robotika untuk generasi digital Indonesia.',            'amber'],
                    ['🎵', 'Paduan Suara',          'Seni',      'Koor terbaik tingkat kota. Tampil di berbagai acara nasional.',                  'purple'],
                ] as [$icon, $name, $tag, $desc, $color])
                    @php
                        $tc = [
                            'amber'  => 'bg-amber-50 text-amber-700 border-amber-200',
                            'blue'   => 'bg-blue-50 text-blue-700 border-blue-200',
                            'purple' => 'bg-purple-50 text-purple-700 border-purple-200',
                            'green'  => 'bg-green-50 text-green-700 border-green-200',
                        ][$color];
                    @endphp
                    <div class="fi-card fi-card-hover p-5 flex gap-4"
                         data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                        <div class="text-3xl shrink-0">{{ $icon }}</div>
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="font-semibold text-sm" style="color:var(--text)">{{ $name }}</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md border {{ $tc }}">{{ $tag }}</span>
                            </div>
                            <p class="text-xs leading-relaxed" style="color:var(--muted)">{{ $desc }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             SECTION: GALERI
        ═══════════════════════════════════════════════════ --}}
        <section id="galeri" class="mb-12 border-t pt-12" style="border-color:var(--border)">
            <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
                <div>
                    <div class="fi-label mb-2">Foto & Video</div>
                    <h2 class="text-2xl font-bold" style="color:var(--text)">Galeri Sekolah</h2>
                </div>
                <a href="#" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 shrink-0">
                    Semua Foto <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            <div class="masonry">
                @foreach([
                    ['upacara',      'Upacara Bendera',    '176px'],
                    ['computer-lab', 'Lab Komputer',       '260px'],
                    ['sports-field', 'Lapangan Olahraga',  '200px'],
                    ['stage-drama',  'Pentas Seni',        '240px'],
                    ['graduation',   'Wisuda & Kelulusan', '176px'],
                    ['science-lab',  'Laboratorium IPA',   '220px'],
                    ['library',      'Perpustakaan',       '196px'],
                    ['classroom',    'Ruang Kelas',        '210px'],
                    ['school-hall',  'Aula Sekolah',       '180px'],
                ] as [$seed, $caption, $h])
                    <div class="masonry-item group" style="height:{{ $h }}"
                         data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}" data-aos-duration="500">
                        {{-- Ganti src dengan URL media dari admin setelah upload --}}
                        <img src="https://picsum.photos/seed/{{ $seed }}/800/600"
                             alt="{{ $caption }}"
                             loading="lazy"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        {{-- Caption overlay — selalu terlihat tipis, lebih tebal saat hover --}}
                        <div class="absolute inset-0 bg-linear-to-t from-black/60 via-black/10 to-transparent flex items-end">
                            <div class="w-full px-3 py-2.5 text-white text-xs font-semibold
                                        translate-y-1 group-hover:translate-y-0
                                        opacity-70 group-hover:opacity-100
                                        transition-all duration-200">
                                {{ $caption }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- ═══════════════════════════════════════════════════
             SECTION: BLOG
        ═══════════════════════════════════════════════════ --}}
        <section id="blog" class="mb-12 border-t pt-12" style="border-color:var(--border)">
            <div class="flex items-end justify-between gap-4 mb-8" data-aos="fade-up">
                <div>
                    <div class="fi-label mb-2">Berita & Artikel</div>
                    <h2 class="text-2xl font-bold" style="color:var(--text)">Blog Sekolah</h2>
                    <p class="mt-1 text-sm" style="color:var(--muted)">Informasi terkini, prestasi, dan cerita inspiratif dari komunitas sekolah.</p>
                </div>
                <a href="{{ route('blog.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700 flex items-center gap-1 shrink-0">
                    Semua Artikel <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($posts->isNotEmpty())
                @php $featured = $posts->first(); @endphp

                {{-- Featured post --}}
                <article class="fi-card overflow-hidden mb-5" data-aos="fade-up" data-aos-delay="50">
                    <div class="grid lg:grid-cols-5">
                        <a href="{{ route('blog.show', $featured->slug) }}"
                           class="lg:col-span-2 h-52 lg:h-auto relative overflow-hidden block group">
                            <img src="{{ $featured->thumbnail_url }}"
                                 alt="{{ $featured->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent"></div>
                        </a>
                        <div class="lg:col-span-3 p-6 flex flex-col justify-center">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded-md bg-amber-50 text-amber-700 border border-amber-200">{{ $featured->category }}</span>
                                <span class="text-xs" style="color:var(--muted)">{{ $featured->formatted_date }} · {{ $featured->read_time }} menit baca</span>
                            </div>
                            <h3 class="text-lg font-bold leading-snug mb-2" style="color:var(--text)">
                                <a href="{{ route('blog.show', $featured->slug) }}" class="hover:text-amber-700 transition-colors">
                                    {{ $featured->title }}
                                </a>
                            </h3>
                            <p class="text-sm leading-relaxed mb-5 line-clamp-3" style="color:var(--muted)">
                                {{ $featured->excerpt }}
                            </p>
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $featured->author_initials }}</div>
                                    <span class="text-xs font-medium" style="color:var(--muted)">{{ $featured->author }}</span>
                                </div>
                                <a href="{{ route('blog.show', $featured->slug) }}" class="btn-primary text-xs">
                                    Baca Selengkapnya
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Post grid --}}
                @if($posts->count() > 1)
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($posts->skip(1) as $post)
                            <article class="fi-card fi-card-hover group flex flex-col overflow-hidden"
                                     data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                                <a href="{{ route('blog.show', $post->slug) }}" class="relative h-44 block overflow-hidden">
                                    <img src="{{ $post->thumbnail_url }}"
                                         alt="{{ $post->title }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute top-3 left-3">
                                        <span class="text-[11px] font-semibold px-2.5 py-1 rounded-md border backdrop-blur-sm bg-white/80 text-amber-700 border-amber-200">{{ $post->category }}</span>
                                    </div>
                                </a>
                                <div class="p-5 flex flex-col flex-1">
                                    <span class="text-[11px] mb-2 block" style="color:var(--muted)">{{ $post->formatted_date }}</span>
                                    <h3 class="font-semibold text-sm leading-snug mb-2 line-clamp-2 hover:text-amber-700 transition-colors" style="color:var(--text)">
                                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    <p class="text-xs leading-relaxed flex-1 line-clamp-3" style="color:var(--muted)">{{ $post->excerpt }}</p>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="mt-4 text-xs font-semibold text-amber-600 hover:underline inline-flex items-center gap-1">
                                        Baca Selengkapnya
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center py-16 fi-card">
                    <div class="text-5xl mb-4">📰</div>
                    <p class="text-sm font-medium" style="color:var(--muted)">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @endif

            <div class="mt-8 text-center">
                <a href="{{ route('blog.index') }}" class="btn-outline">Lihat Semua Artikel</a>
            </div>
        </section>

    </main>

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
                        <div class="w-9 h-9 rounded-xl bg-amber-500 shadow flex items-center justify-center">
                            <span class="text-white font-extrabold text-base">{{ strtoupper(substr(config('app.name','S'),0,1)) }}</span>
                        </div>
                        <div>
                            <div class="font-bold text-sm" style="color:var(--text)">{{ config('app.name', 'SMA Negeri 1') }}</div>
                            <div class="text-[10px] text-amber-600 font-semibold uppercase tracking-wider">Unggul · Berkarakter</div>
                        </div>
                    </div>
                    <p class="text-xs leading-relaxed mb-4" style="color:var(--muted)">Mencetak generasi penerus bangsa yang cerdas, berkarakter mulia, dan siap menghadapi tantangan masa depan.</p>
                    <div class="flex gap-2">
                        @foreach(['FB','IG','YT','WA'] as $s)
                            <a href="#" class="w-8 h-8 rounded-xl border flex items-center justify-center text-[10px] font-bold transition-all hover:bg-amber-50 hover:border-amber-300 hover:text-amber-600"
                               style="border-color:var(--border); color:var(--muted)">{{ $s }}</a>
                        @endforeach
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
                        <li class="flex gap-2"><span>📍</span><span>Jl. Pendidikan No. 1, Kec. Sukamaju, Kota Bandung 40111</span></li>
                        <li class="flex gap-2"><span>📞</span><span>(022) 1234-5678</span></li>
                        <li class="flex gap-2"><span>✉️</span><span>info@sman1.sch.id</span></li>
                        <li class="flex gap-2"><span>🕐</span><span>Senin–Jumat, 07.00–15.30 WIB</span></li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t flex flex-col sm:flex-row items-center justify-between gap-3" style="border-color:var(--border)">
                <span class="text-xs" style="color:var(--muted)">© {{ date('Y') }} {{ config('app.name','SMA Negeri 1') }}. Hak cipta dilindungi.</span>
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
