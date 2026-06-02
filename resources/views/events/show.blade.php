@extends('layouts.public')

@section('content')

{{-- ── Breadcrumb ──────────────────────────────────────────── --}}
<div style="background:var(--bg-alt);border-bottom:1px solid var(--border)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <nav class="flex items-center gap-2 text-sm" style="color:var(--muted)">
            <a href="{{ route('home') }}" class="hover:opacity-75 transition-opacity">Beranda</a>
            <span>/</span>
            <a href="{{ route('events.index') }}" class="hover:opacity-75 transition-opacity">Kegiatan</a>
            <span>/</span>
            <span class="font-medium line-clamp-1" style="color:var(--text)">{{ $event->title }}</span>
        </nav>
    </div>
</div>

{{-- ── Hero Image ───────────────────────────────────────────── --}}
<div class="relative h-64 sm:h-80 lg:h-96 overflow-hidden">
    <img src="{{ $event->thumbnail_url }}" alt="{{ $event->title }}"
         class="w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,.7),rgba(0,0,0,.2))"></div>
    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10">
        <div class="max-w-4xl mx-auto">
            @if($event->category)
            <span class="inline-block text-xs font-bold px-3 py-1 rounded-full mb-3"
                  style="background:rgba(8,72,74,.85);color:#fff">{{ $event->category }}</span>
            @endif
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                {{ $event->title }}
            </h1>
        </div>
    </div>
</div>

{{-- ── Content ──────────────────────────────────────────────── --}}
<section class="py-14 sm:py-20" style="background:var(--bg)">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Meta --}}
        <div class="fi-card p-6 mb-10 grid sm:grid-cols-3 gap-5" data-aos="fade-up">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:rgba(8,72,74,.1)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold mb-0.5" style="color:var(--muted)">Tanggal Mulai</div>
                    <div class="text-sm font-bold" style="color:var(--text)">
                        {{ $event->starts_at->translatedFormat('l, d F Y') }}<br>
                        <span class="font-normal text-xs" style="color:var(--muted)">{{ $event->starts_at->format('H:i') }} WIB</span>
                    </div>
                </div>
            </div>
            @if($event->ends_at)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:rgba(8,72,74,.1)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold mb-0.5" style="color:var(--muted)">Selesai</div>
                    <div class="text-sm font-bold" style="color:var(--text)">
                        {{ $event->ends_at->translatedFormat('d F Y') }}<br>
                        <span class="font-normal text-xs" style="color:var(--muted)">{{ $event->ends_at->format('H:i') }} WIB</span>
                    </div>
                </div>
            </div>
            @endif
            @if($event->location)
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:rgba(8,72,74,.1)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-semibold mb-0.5" style="color:var(--muted)">Lokasi</div>
                    <div class="text-sm font-bold" style="color:var(--text)">{{ $event->location }}</div>
                </div>
            </div>
            @endif
        </div>

        {{-- YouTube Video --}}
        @if($event->youtube_embed_url)
        <div class="mb-10" data-aos="fade-up">
            <div class="rounded-2xl overflow-hidden shadow-lg" style="aspect-ratio:16/9">
                <iframe
                    src="{{ $event->youtube_embed_url }}"
                    title="{{ $event->title }}"
                    class="w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        @endif

        {{-- Content --}}
        @if($event->content)
        <div class="article-prose" data-aos="fade-up">
            {!! $event->content !!}
        </div>
        @endif

        {{-- Back --}}
        <div class="mt-10">
            <a href="{{ route('events.index') }}" class="btn-outline">← Lihat Semua Kegiatan</a>
        </div>
    </div>
</section>

{{-- ── Related ──────────────────────────────────────────────── --}}
@if($related->isNotEmpty())
<section class="py-14" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-extrabold mb-8" style="color:var(--text)">Kegiatan Lainnya</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($related as $rel)
            <article class="fi-card fi-card-hover group flex flex-col overflow-hidden">
                <a href="{{ route('events.show', $rel) }}" class="relative h-40 block overflow-hidden">
                    <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->title }}"
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
                <div class="p-5">
                    <p class="text-xs mb-1.5" style="color:var(--muted)">{{ $rel->formatted_date }}</p>
                    <h3 class="font-bold text-sm line-clamp-2" style="color:var(--text)">
                        <a href="{{ route('events.show', $rel) }}" class="hover:opacity-70 transition-opacity">{{ $rel->title }}</a>
                    </h3>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
