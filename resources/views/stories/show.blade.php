@extends('layouts.public')

@push('head')
<style>
    .article-prose h2 { font-size:1.35rem;font-weight:800;margin-top:2.25rem;margin-bottom:.75rem;color:var(--text) }
    .article-prose h3 { font-size:1.1rem;font-weight:700;margin-top:1.75rem;margin-bottom:.5rem;color:var(--text) }
    .article-prose p  { line-height:1.85;color:var(--muted);margin-bottom:1.25rem }
    .article-prose ul,.article-prose ol { padding-left:1.5rem;margin-bottom:1.25rem;color:var(--muted) }
    .article-prose li { margin-bottom:.4rem;line-height:1.75 }
    .article-prose img { border-radius:.75rem;max-width:100%;height:auto;margin:1.5rem 0 }
    .article-prose a  { color:var(--primary);text-decoration:underline }
    .article-prose blockquote {
        border-left:4px solid var(--primary);
        padding:.75rem 1.25rem;
        margin:1.5rem 0;
        background:rgba(8,72,74,.05);
        border-radius:0 .5rem .5rem 0;
        font-style:italic;
    }
</style>
@endpush

@section('content')

{{-- ── Breadcrumb ──────────────────────────────────────────── --}}
<div style="background:var(--bg-alt);border-bottom:1px solid var(--border)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <nav class="flex items-center gap-2 text-sm" style="color:var(--muted)">
            <a href="{{ route('home') }}" class="hover:opacity-75 transition-opacity">Beranda</a>
            <span>/</span>
            <a href="{{ route('stories.index') }}" class="hover:opacity-75 transition-opacity">Cerita Santri</a>
            <span>/</span>
            <span class="font-medium line-clamp-1" style="color:var(--text)">{{ $story->title }}</span>
        </nav>
    </div>
</div>

{{-- ── Hero Image ───────────────────────────────────────────── --}}
<div class="relative h-64 sm:h-80 lg:h-96 overflow-hidden">
    <img src="{{ $story->thumbnail_url }}" alt="{{ $story->title }}"
         class="w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,.78),rgba(0,0,0,.2))"></div>
    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight mb-4">
                {{ $story->title }}
            </h1>
            {{-- Author --}}
            <div class="flex items-center gap-3">
                <img src="{{ $story->author_photo_url }}"
                     alt="{{ $story->author_name }}"
                     class="w-11 h-11 rounded-full object-cover ring-2"
                     style="ring-color:rgba(255,255,255,.4)">
                <div>
                    <div class="text-sm font-semibold text-white">{{ $story->author_name }}</div>
                    <div class="text-xs" style="color:rgba(255,255,255,.7)">
                        {{ implode(' · ', array_filter([$story->author_class, $story->author_year, $story->formatted_date])) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Content ──────────────────────────────────────────────── --}}
<section class="py-14 sm:py-20" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($story->excerpt)
        <p class="text-xl leading-relaxed mb-8 font-medium italic" style="color:var(--muted)" data-aos="fade-up">
            "{{ $story->excerpt }}"
        </p>
        <hr style="border-color:var(--border);margin-bottom:2.5rem">
        @endif

        @if($story->content)
        <div class="article-prose" data-aos="fade-up">
            {!! $story->content !!}
        </div>
        @endif

        {{-- Author Card --}}
        <div class="mt-12 fi-card p-6 flex items-center gap-5">
            <img src="{{ $story->author_photo_url }}"
                 alt="{{ $story->author_name }}"
                 class="w-16 h-16 rounded-full object-cover ring-2 shrink-0"
                 style="--tw-ring-color:var(--primary)">
            <div>
                <div class="font-extrabold text-base mb-0.5" style="color:var(--text)">{{ $story->author_name }}</div>
                <div class="text-sm" style="color:var(--muted)">
                    {{ implode(' · ', array_filter([$story->author_class, $story->author_year])) ?: 'Santri' }}
                </div>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('stories.index') }}" class="btn-outline">← Semua Cerita</a>
        </div>
    </div>
</section>

{{-- ── Related ──────────────────────────────────────────────── --}}
@if($related->isNotEmpty())
<section class="py-14" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-extrabold mb-8" style="color:var(--text)">Cerita Lainnya</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($related as $rel)
            <article class="fi-card fi-card-hover group flex flex-col overflow-hidden">
                <a href="{{ route('stories.show', $rel) }}" class="relative h-44 block overflow-hidden">
                    <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->title }}"
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <h3 class="font-bold text-sm line-clamp-2 mb-3 flex-1" style="color:var(--text)">
                        <a href="{{ route('stories.show', $rel) }}" class="hover:opacity-70 transition-opacity">{{ $rel->title }}</a>
                    </h3>
                    <div class="flex items-center gap-2.5 pt-3" style="border-top:1px solid var(--border)">
                        <img src="{{ $rel->author_photo_url }}" alt="{{ $rel->author_name }}"
                             class="w-7 h-7 rounded-full object-cover">
                        <span class="text-xs font-medium" style="color:var(--muted)">{{ $rel->author_name }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
