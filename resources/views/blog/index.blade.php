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
        'name'  => config('app.name'),
        'url'   => url('/'),
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('breadcrumb')
    <a href="/" class="hover:text-amber-600 transition-colors">Beranda</a>
    <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-amber-600 font-medium">Blog</span>
@endpush

@section('content')

    {{-- ── Page Header ──────────────────────────────────────── --}}
    <section class="border-b py-10" style="background:linear-gradient(135deg,#fffbeb,#fef3c7 60%,#fff);border-color:#fde68a">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Breadcrumb mobile --}}
            <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs mb-4" style="color:#6b7280">
                <a href="/" class="hover:text-amber-600">Beranda</a>
                <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-amber-600 font-medium">Blog</span>
            </nav>

            <div class="fi-label mb-2">Blog & Berita</div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-amber-900 mb-2">
                {{ $category ? "Kategori: {$category}" : 'Semua Artikel' }}
            </h1>
            <p class="text-amber-800/70 text-sm max-w-xl">
                Informasi terkini, prestasi siswa, dan cerita inspiratif dari komunitas {{ config('app.name') }}.
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- ── Category Filter ─────────────────────────────── --}}
        @if($categories->isNotEmpty())
            <div class="flex flex-wrap gap-2 mb-8">
                <a href="{{ route('blog.index') }}"
                   class="text-xs font-semibold px-3 py-1.5 rounded-full border transition-colors
                          {{ !$category ? 'bg-amber-500 border-amber-500 text-white' : 'border-gray-200 text-gray-500 hover:border-amber-400 hover:text-amber-700 bg-white' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat]) }}"
                       class="text-xs font-semibold px-3 py-1.5 rounded-full border transition-colors
                              {{ $category === $cat ? 'bg-amber-500 border-amber-500 text-white' : 'border-gray-200 text-gray-500 hover:border-amber-400 hover:text-amber-700 bg-white' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- ── Post Grid ───────────────────────────────────── --}}
        @if($posts->isEmpty())
            <div class="text-center py-20">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-gray-500">Belum ada artikel untuk kategori ini.</p>
                <a href="{{ route('blog.index') }}" class="mt-4 btn-outline inline-flex">Lihat Semua Artikel</a>
            </div>
        @else
            {{-- Featured (first post) --}}
            @php $featured = $posts->first(); @endphp
            <article class="fi-card overflow-hidden mb-8 group" itemscope itemtype="https://schema.org/Article">
                <meta itemprop="author" content="{{ $featured->author }}">
                <meta itemprop="datePublished" content="{{ $featured->published_at?->toIso8601String() }}">
                <div class="grid lg:grid-cols-5">
                    <a href="{{ route('blog.show', $featured->slug) }}" class="lg:col-span-2 block h-56 lg:h-auto relative overflow-hidden">
                        <img src="{{ $featured->thumbnail_url }}"
                             alt="{{ $featured->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="eager"
                             itemprop="image">
                        <div class="absolute inset-0 bg-linear-to-t from-black/30 to-transparent"></div>
                    </a>
                    <div class="lg:col-span-3 p-7 flex flex-col justify-center">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                {{ $featured->category }}
                            </span>
                            <time class="text-xs" style="color:#6b7280" datetime="{{ $featured->published_at?->toIso8601String() }}">
                                {{ $featured->formatted_date }}
                            </time>
                            <span class="text-xs" style="color:#6b7280">· {{ $featured->read_time }} menit baca</span>
                        </div>
                        <h2 class="text-xl font-extrabold leading-snug mb-2" style="color:#030712" itemprop="headline">
                            <a href="{{ route('blog.show', $featured->slug) }}" class="hover:text-amber-700 transition-colors">
                                {{ $featured->title }}
                            </a>
                        </h2>
                        <p class="text-sm leading-relaxed mb-5 line-clamp-3" style="color:#6b7280" itemprop="description">
                            {{ $featured->excerpt }}
                        </p>
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ $featured->author_initials }}
                                </div>
                                <span class="text-xs font-medium" style="color:#6b7280">{{ $featured->author }}</span>
                            </div>
                            <a href="{{ route('blog.show', $featured->slug) }}" class="btn-primary text-xs">
                                Baca Selengkapnya
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </article>

            {{-- Remaining grid --}}
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts->skip(1) as $post)
                    <article class="fi-card fi-card-hover group flex flex-col overflow-hidden"
                             itemscope itemtype="https://schema.org/Article">
                        <meta itemprop="author" content="{{ $post->author }}">
                        <meta itemprop="datePublished" content="{{ $post->published_at?->toIso8601String() }}">

                        <a href="{{ route('blog.show', $post->slug) }}" class="relative h-44 block overflow-hidden">
                            <img src="{{ $post->thumbnail_url }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                 loading="lazy"
                                 itemprop="image">
                            <div class="absolute top-3 left-3">
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-md bg-white/85 backdrop-blur-sm text-amber-700 border border-amber-200">
                                    {{ $post->category }}
                                </span>
                            </div>
                        </a>

                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-2 text-[11px]" style="color:#6b7280">
                                <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->formatted_date }}</time>
                                <span>·</span>
                                <span>{{ $post->read_time }} menit baca</span>
                            </div>

                            <h2 class="font-bold text-sm leading-snug mb-2 line-clamp-2 hover:text-amber-700 transition-colors" style="color:#030712" itemprop="headline">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>

                            <p class="text-xs leading-relaxed flex-1 line-clamp-3" style="color:#6b7280" itemprop="description">
                                {{ $post->excerpt }}
                            </p>

                            <div class="mt-4 flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] font-bold shrink-0">
                                        {{ $post->author_initials }}
                                    </div>
                                    <span class="text-[11px] truncate" style="color:#6b7280">{{ $post->author }}</span>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   class="text-xs font-semibold text-amber-600 hover:text-amber-700 hover:underline shrink-0 flex items-center gap-1">
                                    Baca
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- ── Pagination ─────────────────────────────── --}}
            @if($posts->hasPages())
                <div class="mt-10 flex justify-center">
                    {{ $posts->links('pagination::simple-tailwind') }}
                </div>
            @endif
        @endif

    </div>

@endsection
