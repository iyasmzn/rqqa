@extends('layouts.public')

@push('head')
<style>
    /* ── Hero ─────────────────────────────────────────────── */
    .page-hero {
        position: relative;
        background: linear-gradient(135deg, var(--primary-900) 0%, var(--primary-800) 40%, var(--primary-700) 70%, var(--primary) 100%);
        padding: 6rem 0 3.5rem;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 15% 50%, rgba(255,255,255,.06) 0%, transparent 50%),
            radial-gradient(circle at 85% 20%, rgba(255,255,255,.05) 0%, transparent 40%);
    }
    .page-hero-circle {
        position: absolute;
        border-radius: 9999px;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }

    /* ── Prose typography ─────────────────────────────────── */
    .page-prose { line-height: 1.85; color: #374151; font-size: .9375rem; }

    .page-prose h2 {
        font-size: 1.3rem;
        font-weight: 800;
        color: #111827;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
        padding-bottom: .625rem;
        border-bottom: 2px solid var(--primary-200);
        display: inline-block;
    }
    .page-prose h3 {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1f2937;
        margin-top: 2rem;
        margin-bottom: .75rem;
    }
    .page-prose p { margin-bottom: 1.25rem; }
    .page-prose ul, .page-prose ol {
        padding-left: 1.75rem;
        margin-bottom: 1.25rem;
    }
    .page-prose ul  { list-style: disc; }
    .page-prose ol  { list-style: decimal; }
    .page-prose li  { margin-bottom: .5rem; line-height: 1.75; }
    .page-prose blockquote {
        border-left: 4px solid var(--primary);
        padding: .875rem 1.25rem;
        margin: 2rem 0;
        background: var(--primary-50);
        border-radius: 0 .75rem .75rem 0;
        color: var(--primary-900);
        font-style: italic;
        font-size: .95rem;
    }
    .page-prose a     { color: var(--primary); text-decoration: underline; text-underline-offset: 3px; font-weight: 500; }
    .page-prose a:hover { color: var(--primary-700); }
    .page-prose strong { color: #111827; font-weight: 700; }
    .page-prose img    { border-radius: .75rem; margin: 1.75rem 0; max-width: 100%; }
    .page-prose code   { background: #f3f4f6; padding: .125rem .4rem; border-radius: .25rem; font-size: .875em; color: var(--primary); }
    .page-prose table  { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; font-size: .875rem; }
    .page-prose th     { background: var(--primary-50); color: var(--primary-800); font-weight: 700; padding: .625rem 1rem; text-align: left; border-bottom: 2px solid var(--primary-200); }
    .page-prose td     { padding: .625rem 1rem; border-bottom: 1px solid #f3f4f6; vertical-align: top; }
    .page-prose tr:last-child td { border-bottom: none; }

    /* ── Sidebar TOC ──────────────────────────────────────── */
    .sidebar-sticky   { position: sticky; top: 5.5rem; }
    .toc-item {
        display: flex;
        align-items: flex-start;
        gap: .625rem;
        padding: .35rem .5rem;
        border-radius: .375rem;
        font-size: .75rem;
        color: #6b7280;
        line-height: 1.5;
        transition: background .15s, color .15s;
        cursor: pointer;
    }
    .toc-item:hover { background: var(--primary-50); color: var(--primary-700); }
    .toc-num { flex-shrink: 0; font-weight: 700; color: var(--primary-400); font-size: .6875rem; min-width: 1.25rem; }

    /* ── Other pages list ─────────────────────────────────── */
    .page-link {
        display: flex;
        align-items: center;
        gap: .625rem;
        padding: .625rem .75rem;
        border-radius: .5rem;
        font-size: .8125rem;
        color: #6b7280;
        transition: background .15s, color .15s;
        line-height: 1.4;
    }
    .page-link:hover { background: var(--primary-50); color: var(--primary-700); }
    .page-link svg   { flex-shrink: 0; }

    /* ── Blocks ───────────────────────────────────────────── */
    .block-label {
        display: inline-flex; align-items: center; gap: .4rem;
        font-size: .6875rem; font-weight: 700; letter-spacing: .07em;
        text-transform: uppercase; color: var(--primary); margin-bottom: .75rem;
    }
    .block-label::before {
        content: ''; display: inline-block; width: 1rem; height: 2px;
        background: var(--primary); border-radius: 1px;
    }

    /* Cover Image */
    .block-cover { margin: 2rem 0; }
    .block-cover img {
        width: 100%; border-radius: .875rem; object-fit: cover;
        max-height: 520px; display: block;
        box-shadow: 0 8px 32px rgba(0,0,0,.12);
    }
    .block-cover figcaption {
        text-align: center; font-size: .8rem; color: #9ca3af;
        margin-top: .625rem; font-style: italic;
    }

    /* Carousel */
    .block-carousel { margin: 2rem 0; border-radius: .875rem; overflow: hidden; position: relative; }
    .block-carousel img { width: 100%; max-height: 520px; object-fit: cover; display: block; }
    .carousel-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 2.5rem; height: 2.5rem; border-radius: 9999px;
        background: rgba(255,255,255,.9); backdrop-filter: blur(4px);
        border: 1px solid rgba(255,255,255,.6);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background .15s, transform .15s;
        box-shadow: 0 2px 8px rgba(0,0,0,.15);
    }
    .carousel-btn:hover { background: #fff; transform: translateY(-50%) scale(1.08); }
    .carousel-btn svg   { width: 1rem; height: 1rem; color: #374151; flex-shrink: 0; }

    /* Gallery */
    .block-gallery { margin: 2rem 0; }
    .gallery-item {
        position: relative; overflow: hidden; border-radius: .75rem;
        cursor: zoom-in; aspect-ratio: 4/3;
    }
    .gallery-item img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform .5s ease;
    }
    .gallery-item:hover img { transform: scale(1.06); }
    .gallery-caption {
        position: absolute; inset-x: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,.65), transparent);
        padding: .75rem .875rem .625rem;
        transform: translateY(100%); transition: transform .3s ease;
    }
    .gallery-item:hover .gallery-caption { transform: translateY(0); }
    .gallery-caption p { color: #fff; font-size: .75rem; line-height: 1.4; }

    /* Lightbox */
    .lightbox-overlay {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,.92); backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        padding: 1.5rem;
    }
    .lightbox-img {
        max-width: 100%; max-height: 90vh;
        border-radius: .75rem; object-fit: contain;
        box-shadow: 0 24px 80px rgba(0,0,0,.6);
    }
</style>
@endpush

@section('content')

{{-- ═══════════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════════ --}}
<div class="page-hero">
    {{-- Decorative circles --}}
    <div class="page-hero-circle" style="width:380px;height:380px;top:-120px;right:-80px;"></div>
    <div class="page-hero-circle" style="width:220px;height:220px;bottom:-60px;left:10%;"></div>
    <div class="page-hero-circle" style="width:80px;height:80px;top:30%;right:25%;background:rgba(255,255,255,.07)"></div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-xs mb-6" style="color:color-mix(in srgb,var(--primary-200) 70%,transparent)" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Beranda
            </a>
            <svg class="w-3 h-3 opacity-40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="font-medium truncate" style="color:var(--primary-100)">{{ $page->title }}</span>
        </nav>

        {{-- Label --}}
        <div class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest mb-3" style="color:var(--primary-300)">
            <span class="w-4 h-px inline-block" style="background:var(--primary-400)"></span>
            Halaman Informasi
        </div>

        {{-- Title --}}
        <h1 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight tracking-tight mb-4">
            {{ $page->title }}
        </h1>

        {{-- Meta description --}}
        @if($page->meta_description)
            <p class="text-sm sm:text-base leading-relaxed max-w-2xl mb-6" style="color:color-mix(in srgb,var(--primary-100) 80%,transparent)">
                {{ $page->meta_description }}
            </p>
        @endif

        {{-- Date badge --}}
        <div class="inline-flex items-center gap-2 text-xs bg-white/5 border border-white/10 rounded-full px-3 py-1.5" style="color:color-mix(in srgb,var(--primary-200) 60%,transparent)">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Diperbarui {{ $page->updated_at->translatedFormat('d F Y') }}
        </div>
    </div>
</div>

{{-- Amber accent bar --}}
<div style="height:4px;background:linear-gradient(90deg,var(--primary),var(--primary-300) 50%,var(--primary-100) 100%)"></div>

{{-- ═══════════════════════════════════════════════════
     CONTENT BODY
═══════════════════════════════════════════════════ --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid lg:grid-cols-4 gap-10">

        {{-- ── Main content ────────────────────────────────── --}}
        <article class="lg:col-span-3" data-aos="fade-up" data-aos-duration="500">
            <div class="fi-card overflow-hidden">
                {{-- Card amber top accent --}}
                <div style="height:3px;background:linear-gradient(90deg,var(--primary),var(--primary-300) 60%,transparent)"></div>
                <div class="p-6 sm:p-10">
                    @if($page->content)
                        <div class="page-prose">
                            {!! $page->content !!}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl" style="background:var(--primary-50);border:1px solid var(--primary-100)">📄</div>
                            <p class="text-sm font-medium text-gray-500">Konten halaman ini belum tersedia.</p>
                        </div>
                    @endif

                    {{-- ══ BLOCKS ══════════════════════════════════════════ --}}
                    @if($page->blocks)
                        @foreach($page->blocks as $block)
                            @php $type = $block['type'] ?? ''; @endphp

                            {{-- ── Cover Image ─────────────────────────────── --}}
                            @if($type === 'image_cover' && !empty($block['image']))
                                <figure class="block-cover">
                                    <div class="block-label">Cover Image</div>
                                    <img src="{{ asset('storage/' . $block['image']) }}"
                                         alt="{{ $block['caption'] ?? $page->title }}"
                                         loading="lazy">
                                    @if(!empty($block['caption']))
                                        <figcaption>{{ $block['caption'] }}</figcaption>
                                    @endif
                                </figure>
                            @endif

                            {{-- ── Carousel ─────────────────────────────────── --}}
                            @if($type === 'image_carousel' && !empty($block['images']))
                                @php $slides = array_values(array_filter($block['images'], fn($i) => !empty($i['image']))); @endphp
                                @if(count($slides) > 0)
                                    <div class="my-8">
                                        <div class="block-label">Carousel</div>
                                        <div class="block-carousel"
                                             x-data="{
                                                 slide: 0,
                                                 total: {{ count($slides) }},
                                                 next() { this.slide = (this.slide + 1) % this.total },
                                                 prev() { this.slide = (this.slide - 1 + this.total) % this.total }
                                             }">
                                            @foreach($slides as $i => $img)
                                                <div x-show="slide === {{ $i }}"
                                                     x-transition:enter="transition-opacity duration-500 ease-in-out"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     x-transition:leave="transition-opacity duration-300 ease-in-out"
                                                     x-transition:leave-start="opacity-100"
                                                     x-transition:leave-end="opacity-0"
                                                     class="relative">
                                                    <img src="{{ asset('storage/' . $img['image']) }}"
                                                         alt="{{ $img['caption'] ?? '' }}"
                                                         loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                                    @if(!empty($img['caption']))
                                                        <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-black/65 to-transparent px-5 py-4">
                                                            <p class="text-white text-sm leading-snug">{{ $img['caption'] }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach

                                            @if(count($slides) > 1)
                                                {{-- Prev / Next --}}
                                                <button @click="prev()" class="carousel-btn" style="left:.75rem" aria-label="Sebelumnya">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                                    </svg>
                                                </button>
                                                <button @click="next()" class="carousel-btn" style="right:.75rem" aria-label="Selanjutnya">
                                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>

                                                {{-- Dots --}}
                                                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-10">
                                                    @foreach($slides as $i => $img)
                                                        <button @click="slide = {{ $i }}"
                                                                :class="slide === {{ $i }} ? 'w-5 bg-white' : 'w-2 bg-white/50 hover:bg-white/80'"
                                                                class="h-2 rounded-full transition-all duration-300"
                                                                aria-label="Slide {{ $i + 1 }}">
                                                        </button>
                                                    @endforeach
                                                </div>

                                                {{-- Counter --}}
                                                <div class="absolute top-3 right-3 text-xs font-semibold text-white bg-black/40 backdrop-blur-sm rounded-full px-2.5 py-1">
                                                    <span x-text="slide + 1"></span>/{{ count($slides) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endif

                            {{-- ── Gallery ──────────────────────────────────── --}}
                            @if($type === 'image_gallery' && !empty($block['images']))
                                @php
                                    $imgs    = array_values(array_filter($block['images'], fn($i) => !empty($i['image'])));
                                    $cols    = $block['columns'] ?? '3';
                                    $gridCls = match($cols) {
                                        '2'     => 'grid-cols-2',
                                        '4'     => 'grid-cols-2 sm:grid-cols-4',
                                        default => 'grid-cols-2 sm:grid-cols-3',
                                    };
                                @endphp
                                @if(count($imgs) > 0)
                                    <div class="block-gallery"
                                         x-data="{
                                             lightbox: false,
                                             current: '',
                                             currentAlt: '',
                                             open(src, alt) { this.current = src; this.currentAlt = alt; this.lightbox = true; },
                                             close() { this.lightbox = false; }
                                         }">
                                        <div class="block-label">Galeri Foto</div>

                                        <div class="grid {{ $gridCls }} gap-3">
                                            @foreach($imgs as $img)
                                                <div class="gallery-item"
                                                     @click="open('{{ asset('storage/' . $img['image']) }}', '{{ $img['caption'] ?? '' }}')">
                                                    <img src="{{ asset('storage/' . $img['image']) }}"
                                                         alt="{{ $img['caption'] ?? '' }}"
                                                         loading="lazy">
                                                    @if(!empty($img['caption']))
                                                        <div class="gallery-caption">
                                                            <p>{{ $img['caption'] }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- Lightbox --}}
                                        <div x-show="lightbox"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100"
                                             x-transition:leave-end="opacity-0"
                                             @click.self="close()"
                                             @keydown.escape.window="close()"
                                             class="lightbox-overlay"
                                             x-trap.inert="lightbox">
                                            <img :src="current" :alt="currentAlt" class="lightbox-img">
                                            <button @click="close()"
                                                    class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-colors"
                                                    aria-label="Tutup">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                            <p x-show="currentAlt" x-text="currentAlt"
                                               class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white/70 text-sm bg-black/40 backdrop-blur-sm rounded-full px-4 py-1.5 max-w-sm text-center"></p>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        @endforeach
                    @endif
                    {{-- ══ END BLOCKS ══════════════════════════════════════ --}}
                </div>
            </div>

            {{-- Back navigation --}}
            <div class="mt-8 flex items-center justify-between">
                <a href="{{ url()->previous() === url()->current() ? route('home') : url()->previous() }}"
                   class="btn-outline group text-sm">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    Kembali
                </a>

                {{-- Share --}}
                <div class="flex items-center gap-1.5">
                    <span class="text-xs text-gray-400 mr-1">Bagikan:</span>
                    <a href="https://wa.me/?text={{ urlencode($page->title.' — '.route('page.show', $page->slug)) }}"
                       target="_blank" rel="noopener"
                       class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold transition-opacity hover:opacity-80"
                       style="background:#25d366" title="Bagikan via WhatsApp">
                        WA
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('page.show', $page->slug)) }}"
                       target="_blank" rel="noopener"
                       class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-xs font-bold transition-opacity hover:opacity-80"
                       style="background:#1877f2" title="Bagikan via Facebook">
                        FB
                    </a>
                    <button onclick="navigator.clipboard.writeText('{{ route('page.show', $page->slug) }}').then(()=>{ this.textContent='✓'; setTimeout(()=>{ this.textContent='🔗'; },2000); })"
                            class="w-8 h-8 rounded-lg border flex items-center justify-center text-sm transition-colors"
                            style="border-color:var(--border)" onmouseover="this.style.background='var(--primary-50)';this.style.borderColor='var(--primary-300)'" onmouseout="this.style.background='';this.style.borderColor='var(--border)'" title="Salin link">
                        🔗
                    </button>
                </div>
            </div>
        </article>

        {{-- ── Sidebar ──────────────────────────────────────── --}}
        <aside class="lg:col-span-1" data-aos="fade-up" data-aos-delay="100" data-aos-duration="500">
            <div class="sidebar-sticky space-y-4">

                {{-- Table of contents --}}
                @php
                    preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/is', $page->content ?? '', $headings);
                @endphp
                @if(count($headings[1]) > 1)
                    <div class="fi-card p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:var(--primary-50)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest" style="color:var(--primary)">Daftar Isi</span>
                        </div>
                        <ol class="space-y-0.5">
                            @foreach($headings[1] as $i => $heading)
                                <li class="toc-item">
                                    <span class="toc-num">{{ $i + 1 }}.</span>
                                    <span>{{ strip_tags($heading) }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Other pages --}}
                @if($otherPages->isNotEmpty())
                    <div class="fi-card p-5">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-5 h-5 rounded-md flex items-center justify-center" style="background:var(--primary-50)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary)">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-widest" style="color:var(--primary)">Halaman Lainnya</span>
                        </div>
                        <ul class="space-y-0.5">
                            @foreach($otherPages as $other)
                                <li>
                                    <a href="{{ route('page.show', $other->slug) }}" class="page-link">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary-400)">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        {{ $other->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Back to home --}}
                <a href="{{ route('home') }}" class="btn-primary w-full justify-center text-xs group">
                    <svg class="w-3.5 h-3.5 transition-transform group-hover:-translate-x-0.5"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Halaman Utama
                </a>
            </div>
        </aside>

    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     OTHER PAGES — bottom strip
═══════════════════════════════════════════════════ --}}
@if($otherPages->isNotEmpty())
    <section class="border-t py-12" style="border-color:#f3f4f6;background:#f9fafb">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-4 h-px inline-block" style="background:var(--primary)"></span>
                <span class="text-[11px] font-bold uppercase tracking-widest" style="color:var(--primary)">Informasi Lainnya</span>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach($otherPages as $other)
                    <a href="{{ route('page.show', $other->slug) }}"
                       class="fi-card fi-card-hover flex items-center gap-4 p-4 group"
                       data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors" style="background:var(--primary-50);border:1px solid var(--primary-100)">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary-500)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-semibold text-sm truncate" style="color:var(--text)">{{ $other->title }}</div>
                            <div class="text-xs mt-0.5" style="color:var(--muted)">/page/{{ $other->slug }}</div>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 shrink-0 transition-all group-hover:translate-x-0.5"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
