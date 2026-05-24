<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ── Core SEO ────────────────────────────────────────── --}}
    <title>{{ $seo['title'] ?? config('app.name') }}</title>
    <meta name="description" content="{{ $seo['description'] ?? '' }}">
    <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
    <meta name="robots" content="{{ $seo['robots'] ?? 'index, follow' }}">

    {{-- ── Open Graph ──────────────────────────────────────── --}}
    <meta property="og:type"        content="{{ $seo['og_type'] ?? 'website' }}">
    <meta property="og:title"       content="{{ $seo['title'] ?? config('app.name') }}">
    <meta property="og:description" content="{{ $seo['description'] ?? '' }}">
    <meta property="og:url"         content="{{ $seo['canonical'] ?? url()->current() }}">
    <meta property="og:site_name"   content="{{ config('app.name') }}">
    @if(!empty($seo['og_image']))
        <meta property="og:image"       content="{{ $seo['og_image'] }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height"content="630">
        <meta property="og:image:alt"   content="{{ $seo['title'] ?? config('app.name') }}">
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
    <meta name="twitter:title"       content="{{ $seo['title'] ?? config('app.name') }}">
    <meta name="twitter:description" content="{{ $seo['description'] ?? '' }}">
    @if(!empty($seo['og_image']))
        <meta name="twitter:image" content="{{ $seo['og_image'] }}">
    @endif

    {{-- ── Structured Data (JSON-LD) ──────────────────────── --}}
    @stack('structured-data')

    {{-- ── Fonts & Assets ─────────────────────────────────── --}}
    @fonts

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --bg:     #f9fafb; --card:   #ffffff; --border: #e5e7eb;
            --text:   #030712; --muted:  #6b7280; --amber:  #d97706;
        }
        body { background:var(--bg); color:var(--text); font-family:'Instrument Sans',ui-sans-serif,system-ui,sans-serif; }
        .fi-card { background:var(--card); border:1px solid var(--border); border-radius:.75rem; box-shadow:0 1px 2px 0 rgb(0 0 0/.05); transition:box-shadow .15s,border-color .15s; }
        .fi-card-hover:hover { box-shadow:0 6px 20px rgb(0 0 0/.1); border-color:#fcd34d; }
        .btn-primary { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.25rem; border-radius:.5rem; font-size:.875rem; font-weight:600; background:#d97706; color:#fff; transition:background .15s; }
        .btn-primary:hover { background:#b45309; }
        .btn-outline { display:inline-flex; align-items:center; gap:.5rem; padding:.6rem 1.25rem; border-radius:.5rem; font-size:.875rem; font-weight:600; border:1px solid var(--border); color:var(--text); background:var(--card); transition:border-color .15s,color .15s; }
        .btn-outline:hover { border-color:#d97706; color:#d97706; }
        .fi-label { font-size:.6875rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:#d97706; }
        .amber-bar { height:3px; background:linear-gradient(90deg,#d97706,#fbbf24 60%,transparent); }

        /* Prose content */
        .prose h2 { font-size:1.25rem; font-weight:700; margin-top:1.75rem; margin-bottom:.75rem; color:var(--text); }
        .prose h3 { font-size:1.1rem; font-weight:600; margin-top:1.5rem; margin-bottom:.5rem; color:var(--text); }
        .prose p  { margin-bottom:1rem; line-height:1.8; color:var(--muted); }
        .prose ul { list-style:disc; padding-left:1.5rem; margin-bottom:1rem; color:var(--muted); }
        .prose ol { list-style:decimal; padding-left:1.5rem; margin-bottom:1rem; color:var(--muted); }
        .prose li { margin-bottom:.4rem; line-height:1.7; }
        .prose blockquote { border-left:3px solid #d97706; padding-left:1rem; margin:1.5rem 0; color:var(--muted); font-style:italic; }
        .prose a  { color:#d97706; text-decoration:underline; text-underline-offset:2px; }
        .prose strong { color:var(--text); }
    </style>

    @stack('head')
</head>

<body class="min-h-screen antialiased">

    {{-- ── Navbar ──────────────────────────────────────────── --}}
    <header class="border-b" style="background:var(--card);border-color:var(--border)">
        <div class="amber-bar"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between gap-4">
            <a href="/" class="flex items-center gap-2.5 shrink-0">
                <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center shadow-sm">
                    <span class="text-white font-bold text-sm">{{ strtoupper(substr(config('app.name','S'),0,1)) }}</span>
                </div>
                <div class="hidden sm:block leading-tight">
                    <div class="font-bold text-sm" style="color:var(--text)">{{ config('app.name') }}</div>
                    <div class="text-[10px] text-amber-600 font-semibold uppercase tracking-wider">Unggul · Berkarakter</div>
                </div>
            </a>

            {{-- Breadcrumb --}}
            <nav aria-label="Breadcrumb" class="hidden md:flex items-center gap-1.5 text-xs" style="color:var(--muted)">
                @stack('breadcrumb')
            </nav>

            <div class="flex items-center gap-2">
                <a href="/" class="text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-amber-50 hover:text-amber-700 transition-colors" style="color:var(--muted)">Beranda</a>
                <a href="/blog" class="text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-amber-50 hover:text-amber-700 transition-colors" style="color:var(--muted)">Blog</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary text-xs">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline text-xs">Masuk</a>
                    @endauth
                @endif
            </div>
        </div>
    </header>

    {{-- ── Page Content ─────────────────────────────────────── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── Footer ───────────────────────────────────────────── --}}
    <footer class="border-t mt-16" style="background:var(--card);border-color:var(--border)">
        <div class="amber-bar"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-amber-500 flex items-center justify-center">
                    <span class="text-white font-bold text-xs">{{ strtoupper(substr(config('app.name','S'),0,1)) }}</span>
                </div>
                <span class="text-sm font-semibold" style="color:var(--text)">{{ config('app.name') }}</span>
            </div>
            <p class="text-xs" style="color:var(--muted)">© {{ date('Y') }} {{ config('app.name') }}. Semua hak dilindungi.</p>
            <nav class="flex gap-4">
                <a href="/" class="text-xs hover:text-amber-600 transition-colors" style="color:var(--muted)">Beranda</a>
                <a href="/blog" class="text-xs hover:text-amber-600 transition-colors" style="color:var(--muted)">Blog</a>
                <a href="/#kontak" class="text-xs hover:text-amber-600 transition-colors" style="color:var(--muted)">Kontak</a>
            </nav>
        </div>
    </footer>

</body>
</html>
