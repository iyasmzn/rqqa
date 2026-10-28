@extends('layouts.public')

{{-- ── Structured Data (JSON-LD) ─────────────────────────── --}}
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


@push('head')
<style>
    .blog-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0c1a14 100%);
        position: relative;
        overflow: hidden;
    }
    .blog-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 70% 70% at 10% 50%, rgba(217,119,6,.22) 0%, transparent 55%),
            radial-gradient(ellipse 50% 50% at 90% 10%, rgba(251,191,36,.1) 0%, transparent 50%);
    }

    /* Category filter pills */
    .cat-pill {
        font-size: .8125rem;
        font-weight: 600;
        padding: .5rem 1.125rem;
        border-radius: 9999px;
        border: 1.5px solid var(--border);
        color: var(--muted);
        background: var(--card);
        transition: all .2s;
        white-space: nowrap;
    }
    .cat-pill:hover {
        border-color: var(--primary-300);
        color: var(--primary-800);
        background: var(--primary-50);
    }
    .cat-pill.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #fff;
    }

    /* Sort toggle (Terbaru / Terpopuler) */
    .sort-toggle {
        display: inline-flex;
        align-self: flex-start;
        flex-shrink: 0;
        gap: .25rem;
        padding: .1875rem;
        border-radius: 9999px;
        border: 1.5px solid var(--border);
        background: var(--card);
    }
    .sort-toggle a {
        display: inline-flex;
        align-items: center;
        gap: .375rem;
        font-size: .8125rem;
        font-weight: 600;
        padding: .375rem .875rem;
        border-radius: 9999px;
        color: var(--muted);
        transition: all .2s;
        white-space: nowrap;
    }
    .sort-toggle a:hover {
        color: var(--primary-800);
        background: var(--primary-50);
    }
    .sort-toggle a.active {
        background: var(--primary);
        color: #fff;
    }
</style>
@endpush

@section('content')

    {{-- ── Page Hero ──────────────────────────────────────────── --}}
    <section class="blog-hero page-band -mt-17 pt-30 pb-14 sm:pt-36 sm:pb-20">
        <x-hero-geo />
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Mobile breadcrumb --}}
            <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs mb-5 text-white/50">
                <a href="/" class="hover:text-white transition-colors">Beranda</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-amber-400 font-medium">Blog</span>
            </nav>

            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest text-amber-400 mb-3" data-aos="fade-up">
                <span class="w-4 h-px bg-amber-400 inline-block"></span>
                Blog & Berita
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-white leading-tight mb-3" data-aos="fade-up" data-aos-delay="60">
                {{ $category ? "Kategori: {$category}" : 'Semua Artikel' }}
            </h1>
            <p class="text-white/60 text-sm sm:text-base max-w-lg leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Informasi terkini, prestasi siswa, dan cerita inspiratif dari komunitas {{ setting('site_name', config('app.name')) }}.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Category Filter & Sort ──────────────────────── --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:justify-between mb-8" data-aos="fade-up">
            @if($categories->isNotEmpty())
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('blog.index', array_filter(['sort' => $sort])) }}" class="cat-pill {{ !$category ? 'active' : '' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('blog.index', array_filter(['category' => $cat, 'sort' => $sort])) }}"
                           class="cat-pill {{ $category === $cat ? 'active' : '' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            @endif

            <nav class="sort-toggle sm:ml-auto" aria-label="Urutkan artikel">
                <a href="{{ route('blog.index', array_filter(['category' => $category])) }}"
                   class="{{ !$sort ? 'active' : '' }}" @if(!$sort) aria-current="true" @endif>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Terbaru
                </a>
                <a href="{{ route('blog.index', array_filter(['category' => $category, 'sort' => 'popular'])) }}"
                   class="{{ $sort ? 'active' : '' }}" @if($sort) aria-current="true" @endif>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                    Terpopuler
                </a>
            </nav>
        </div>

        {{-- ── Post Grid ───────────────────────────────────── --}}
        @if($posts->isEmpty())
            <div class="text-center py-24" data-aos="fade-up">
                <div class="w-20 h-20 rounded-2xl bg-amber-50 flex items-center justify-center mx-auto mb-5 text-4xl">📭</div>
                <p class="text-base font-semibold text-gray-700 mb-1">Belum ada artikel</p>
                <p class="text-sm text-gray-400 mb-5">Belum ada artikel untuk kategori ini.</p>
                <a href="{{ route('blog.index') }}" class="btn-outline inline-flex">Lihat Semua Artikel</a>
            </div>
        @else
            {{-- Featured (first post) --}}
            @php $featured = $posts->first(); @endphp
            <x-post-featured :post="$featured"
                             heading-tag="h2"
                             :eager="true"
                             :label="$sort && $posts->onFirstPage() ? '🔥 Terpopuler' : '✦ Unggulan'"
                             class="mb-8"
                             data-aos="fade-up" data-aos-delay="50" />

            {{-- Remaining grid --}}
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts->skip(1) as $post)
                    <x-post-card :post="$post"
                                 heading-tag="h2"
                                 data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}" />
                @endforeach
            </div>

            {{-- ── Pagination ─────────────────────────────── --}}
            @if($posts->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $posts->links('pagination::simple-tailwind') }}
                </div>
            @endif
        @endif

        {{-- ── Ajakan menjadi author (tamu) ───────────── --}}
        <div class="mt-16">
            <x-author-cta />
        </div>

    </div>

@endsection
