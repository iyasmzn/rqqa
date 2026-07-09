@extends('layouts.public')

@push('head')
<style>
    .article-prose h2 { font-size:1.35rem;font-weight:800;margin-top:2.25rem;margin-bottom:.75rem;color:var(--text) }
    .article-prose h3 { font-size:1.1rem;font-weight:700;margin-top:1.75rem;margin-bottom:.5rem;color:var(--text) }
    .article-prose p  { line-height:1.8;color:var(--muted);margin-bottom:1.25rem }
    .article-prose ul,.article-prose ol { padding-left:1.5rem;margin-bottom:1.25rem;color:var(--muted) }
    .article-prose li { margin-bottom:.4rem;line-height:1.7 }
    .article-prose img { border-radius:.75rem;max-width:100%;height:auto;margin:1.5rem 0 }
    .article-prose a  { color:var(--primary);text-decoration:underline }
</style>
@endpush

@section('content')

{{-- ── Breadcrumb ──────────────────────────────────────────── --}}
<div style="background:var(--bg-alt);border-bottom:1px solid var(--border)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5">
        <nav class="flex items-center gap-2 text-sm" style="color:var(--muted)">
            <a href="{{ route('home') }}" class="hover:opacity-75 transition-opacity">Beranda</a>
            <span>/</span>
            <a href="{{ route('programs.index') }}" class="hover:opacity-75 transition-opacity">Program</a>
            <span>/</span>
            <span class="font-medium line-clamp-1" style="color:var(--text)">{{ $program->title }}</span>
        </nav>
    </div>
</div>

{{-- ── Hero Image ───────────────────────────────────────────── --}}
<div class="relative h-64 sm:h-80 lg:h-96 overflow-hidden">
    <img src="{{ $program->thumbnail_url }}" alt="{{ $program->title }}"
         class="w-full h-full object-cover">
    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,.75),rgba(0,0,0,.2))"></div>
    <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center gap-3 mb-3">
                @if($program->icon)
                <span class="text-4xl">{{ $program->icon }}</span>
                @endif
                @if($program->category)
                <span class="inline-block text-xs font-bold px-3 py-1 rounded-full"
                      style="background:rgba(8,72,74,.85);color:#fff">{{ $program->category }}</span>
                @endif
            </div>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                {{ $program->title }}
            </h1>
        </div>
    </div>
</div>

{{-- ── Content ──────────────────────────────────────────────── --}}
<section class="py-14 sm:py-20" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($program->excerpt)
        <p class="text-xl leading-relaxed mb-8 font-medium" style="color:var(--muted)" data-aos="fade-up">
            {{ $program->excerpt }}
        </p>
        <hr style="border-color:var(--border);margin-bottom:2rem">
        @endif

        @if($program->content)
        <div class="article-prose" data-aos="fade-up">
            {!! $program->content !!}
        </div>
        @endif

        <div class="mt-10">
            <a href="{{ route('programs.index') }}" class="btn-outline">← Semua Program</a>
        </div>
    </div>
</section>

{{-- ── Related ──────────────────────────────────────────────── --}}
@if($related->isNotEmpty())
<section class="py-14" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-extrabold mb-8" style="color:var(--text)">Program Lainnya</h2>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($related as $rel)
            <a href="{{ route('programs.show', $rel) }}"
               class="fi-card fi-card-hover group flex flex-col overflow-hidden">
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ $rel->thumbnail_url }}" alt="{{ $rel->title }}"
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @if($rel->icon)
                    <div class="absolute bottom-3 left-3 text-2xl">{{ $rel->icon }}</div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-sm line-clamp-2" style="color:var(--text)">{{ $rel->title }}</h3>
                    @if($rel->excerpt)
                    <p class="text-xs mt-1.5 line-clamp-2" style="color:var(--muted)">{{ $rel->excerpt }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
