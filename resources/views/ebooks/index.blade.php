@extends('layouts.public')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'CollectionPage',
    'name'        => $seo['title'],
    'description' => $seo['description'],
    'url'         => $seo['canonical'],
    'publisher'   => [
        '@type' => 'EducationalOrganization',
        'name'  => setting('site_name', config('app.name')),
        'url'   => url('/'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@php
    /**
     * URL halaman ini dengan filter aktif, ditimpa $overrides. Nilai kosong
     * (atau urutan bawaan) dibuang agar URL tetap bersih; halaman paginasi
     * sengaja tidak dibawa sehingga ganti filter kembali ke halaman 1.
     */
    $filterUrl = function (array $overrides = []) use ($q, $category, $format, $sort): string {
        $params = array_merge(compact('q', 'category', 'format', 'sort'), $overrides);

        if (($params['sort'] ?? '') === 'default') {
            $params['sort'] = '';
        }

        return route('ebooks.index', array_filter($params, fn ($value): bool => $value !== null && $value !== ''));
    };
@endphp

@section('content')

{{-- ══════════════════════════════════════════
     HERO
══════════════════════════════════════════ --}}
<section class="page-band -mt-17 pt-32 pb-16 sm:pt-36 sm:pb-20 relative overflow-hidden"
         style="background:linear-gradient(135deg,var(--primary-900) 0%,var(--primary) 55%,var(--primary-700) 100%)">
    <div class="absolute inset-0 pointer-events-none"
         style="background:
            radial-gradient(ellipse 55% 70% at 10% 60%,rgba(255,255,255,.06) 0%,transparent 55%),
            radial-gradient(ellipse 35% 50% at 90% 10%,rgba(255,255,255,.04) 0%,transparent 55%)">
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 text-xs font-bold px-3.5 py-1.5 rounded-full mb-5"
                 style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.18)">
                📖 E-Book
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-3 leading-tight">
                Perpustakaan Digital
            </h1>
            <p class="text-sm sm:text-base leading-relaxed mb-8" style="color:rgba(255,255,255,.68)">
                Buku pelajaran, modul, kitab, dan bacaan bermanfaat dari {{ setting('site_name', config('app.name')) }} — bisa dibaca langsung tanpa biaya.
            </p>

            {{-- ── Search bar inside hero ── --}}
            <form method="GET" action="{{ route('ebooks.index') }}" role="search">
                {{-- preserve active filters when searching --}}
                @if($category) <input type="hidden" name="category" value="{{ $category }}"> @endif
                @if($format) <input type="hidden" name="format" value="{{ $format }}"> @endif
                @if($sort !== 'default') <input type="hidden" name="sort" value="{{ $sort }}"> @endif

                <div class="relative flex items-center">
                    <svg class="absolute left-4 w-5 h-5 pointer-events-none" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24" style="color:rgba(0,0,0,.4)" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="search" name="q" value="{{ $q }}"
                           placeholder="Cari judul, penulis, atau deskripsi…"
                           aria-label="Cari e-book"
                           autocomplete="off"
                           class="w-full pl-12 pr-32 py-3.5 rounded-2xl text-sm font-medium outline-none transition-shadow focus:shadow-lg"
                           style="background:rgba(255,255,255,.96);color:var(--text);box-shadow:0 4px 24px rgba(0,0,0,.18)">
                    <button type="submit"
                            class="absolute right-1.5 px-5 py-2.5 rounded-xl text-sm font-bold transition-colors"
                            style="background:var(--primary);color:#fff">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════
     MAIN — sidebar + content
══════════════════════════════════════════ --}}
<div x-data="{ filterOpen: false }" @keydown.escape.window="filterOpen = false"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- ── Mobile filter toggle bar ── --}}
    <div class="flex items-center gap-3 mb-6 lg:hidden">
        <button type="button" @click="filterOpen = true"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all border relative"
                style="background:var(--card);color:var(--text);border-color:var(--border)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            Filter
            @if($activeFilters > 0)
                <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full text-xs font-bold text-white flex items-center justify-center"
                      style="background:var(--primary)">{{ $activeFilters }}</span>
            @endif
        </button>

        {{-- Sort select — mobile --}}
        <select onchange="window.location.href = this.value" aria-label="Urutkan e-book"
                class="flex-1 w-full px-3 py-2.5 rounded-xl text-sm font-medium border outline-none"
                style="background:var(--card);color:var(--text);border-color:var(--border)">
            @foreach($sorts as $value => $label)
                <option value="{{ $filterUrl(['sort' => $value]) }}" @selected($sort === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    {{-- ── Mobile filter drawer (di atas navbar) ── --}}
    <div x-show="filterOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="filterOpen = false"
         class="fixed inset-0 bg-black/40 lg:hidden"
         style="display:none;z-index:60"></div>

    <div x-show="filterOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 overflow-y-auto rounded-r-3xl shadow-2xl p-6 lg:hidden"
         style="display:none;z-index:61;width:min(18rem,85vw);background:var(--bg)"
         role="dialog" aria-modal="true" aria-label="Filter e-book">
        <div class="flex items-center justify-between mb-6">
            <span class="font-extrabold text-base" style="color:var(--text)">Filter</span>
            <button type="button" @click="filterOpen = false" aria-label="Tutup filter"
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors hover:bg-gray-100"
                    style="color:var(--muted)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        @include('ebooks.partials.filters')
    </div>

    <div class="flex gap-8">

        {{-- ── Sidebar (desktop) ── --}}
        <aside class="w-64 shrink-0 hidden lg:block" aria-label="Filter e-book">
            @include('ebooks.partials.filters')
        </aside>

        {{-- ════════════════════════════════════
             CONTENT AREA
        ════════════════════════════════════ --}}
        <div class="flex-1 min-w-0">

            {{-- ── Results info ── --}}
            <p class="text-sm font-medium mb-5" style="color:var(--muted)" data-aos="fade-up">
                Menampilkan
                <strong style="color:var(--text)">{{ $ebooks->firstItem() ?? 0 }}–{{ $ebooks->lastItem() ?? 0 }}</strong>
                dari <strong style="color:var(--text)">{{ $ebooks->total() }}</strong> e-book
                @if($q)
                    untuk <em style="color:var(--primary)">"{{ $q }}"</em>
                @endif
            </p>

            {{-- ── Active filter chips ── --}}
            @if($activeFilters > 0)
            <div class="flex flex-wrap gap-2 mb-5" data-aos="fade-up">
                @foreach(array_filter([
                    'q'        => $q ? '🔍 "'.Str::limit($q, 20).'"' : null,
                    'category' => $category ? '📂 '.$category : null,
                    'format'   => $format ? '📄 '.($formats[$format] ?? $format) : null,
                ]) as $param => $chip)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border"
                      style="background:var(--primary-50);color:var(--primary-800);border-color:var(--primary-200)">
                    {{ $chip }}
                    <a href="{{ $filterUrl([$param => '']) }}" class="hover:opacity-70 transition-opacity ml-0.5"
                       aria-label="Hapus filter">✕</a>
                </span>
                @endforeach
                <a href="{{ route('ebooks.index') }}"
                   class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold transition-colors"
                   style="color:var(--muted)">
                    Reset semua ✕
                </a>
            </div>
            @endif

            {{-- ── E-book grid ── --}}
            @if($ebooks->isNotEmpty())
            <div class="grid gap-4 grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
                @foreach($ebooks as $ebook)
                    <x-ebook-card :ebook="$ebook" heading-tag="h2"
                                  data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 50 }}" />
                @endforeach
            </div>

            {{-- Pagination --}}
            @if($ebooks->hasPages())
            <div class="mt-10">
                {{ $ebooks->links() }}
            </div>
            @endif

            @else
            {{-- Empty state --}}
            <div class="fi-card text-center py-20 px-6" data-aos="fade-up">
                <div class="text-6xl mb-4">{{ $activeFilters > 0 ? '🔍' : '📖' }}</div>
                <h2 class="text-lg font-extrabold mb-2" style="color:var(--text)">
                    {{ $activeFilters > 0 ? 'E-book tidak ditemukan' : 'Belum ada e-book' }}
                </h2>
                <p class="text-sm mb-6 max-w-xs mx-auto" style="color:var(--muted)">
                    @if($q)
                        Tidak ada e-book yang cocok dengan pencarian <strong>"{{ $q }}"</strong>.
                    @elseif($activeFilters > 0)
                        Tidak ada e-book yang cocok dengan filter yang dipilih.
                    @else
                        Koleksi e-book akan segera hadir. Silakan kembali lagi nanti.
                    @endif
                </p>
                @if($activeFilters > 0)
                <a href="{{ route('ebooks.index') }}" class="btn-outline text-sm">
                    Hapus Semua Filter
                </a>
                @endif
            </div>
            @endif

        </div>
        {{-- end content --}}
    </div>
    {{-- end flex --}}
</div>

@endsection
