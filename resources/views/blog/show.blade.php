@extends('layouts.public')

{{-- ── JSON-LD Article Schema ──────────────────────────────── --}}
@push('structured-data')
<script type="application/ld+json">
{!! json_encode([
    '@context'      => 'https://schema.org',
    '@type'         => 'Article',
    'headline'      => $post->title,
    'description'   => $post->meta_description,
    'image'         => $post->thumbnail_url,
    'url'           => $post->canonical_url,
    'datePublished' => $post->published_at?->toIso8601String(),
    'dateModified'  => $post->updated_at?->toIso8601String(),
    'author'        => ['@type' => 'Person', 'name' => $post->author],
    'publisher'     => [
        '@type' => 'EducationalOrganization',
        'name'  => config('app.name'),
        'url'   => url('/'),
    ],
    'articleSection' => $post->category,
    'wordCount'      => str_word_count(strip_tags($post->content)),
    'inLanguage'     => 'id-ID',
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

{{-- ── BreadcrumbList Schema ──── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',    'item' => route('blog.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $post->title, 'item' => $post->canonical_url],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@push('breadcrumb')
    <a href="/" class="hover:text-amber-600 transition-colors">Beranda</a>
    <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <a href="{{ route('blog.index') }}" class="hover:text-amber-600 transition-colors">Blog</a>
    <svg class="w-3 h-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="text-amber-600 font-medium truncate max-w-48">{{ $post->title }}</span>
@endpush

@section('content')

    {{-- ── Hero Image ───────────────────────────────────────── --}}
    <div class="relative h-72 sm:h-96 lg:h-112 overflow-hidden bg-gray-200">
        <img src="{{ $post->thumbnail_url }}"
             alt="{{ $post->title }}"
             class="w-full h-full object-cover"
             loading="eager">
        <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,.7) 0%, rgba(0,0,0,.2) 60%, transparent 100%)"></div>

        {{-- Overlay content --}}
        <div class="absolute bottom-0 inset-x-0 p-6 sm:p-10">
            <div class="max-w-4xl mx-auto">
                {{-- Mobile breadcrumb --}}
                <nav aria-label="Breadcrumb" class="md:hidden flex items-center gap-1.5 text-xs text-white/70 mb-3">
                    <a href="/" class="hover:text-white">Beranda</a>
                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('blog.index') }}" class="hover:text-white">Blog</a>
                </nav>
                <span class="inline-block text-[11px] font-bold px-2.5 py-1 rounded-md bg-amber-500 text-white mb-3">{{ $post->category }}</span>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white leading-snug tracking-tight">
                    {{ $post->title }}
                </h1>
            </div>
        </div>
    </div>

    {{-- ── Article Body ─────────────────────────────────────── --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid lg:grid-cols-4 gap-10">

            {{-- Main content --}}
            <div class="lg:col-span-3">

                {{-- Meta bar --}}
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 pb-6 mb-8 border-b" style="border-color:#e5e7eb">
                    <div class="flex items-center gap-2">
                        <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center text-sm font-bold shrink-0">
                            {{ $post->author_initials }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold" style="color:#030712">{{ $post->author }}</div>
                            <div class="text-[11px]" style="color:#6b7280">Penulis</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-xs" style="color:#6b7280">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->formatted_date }}</time>
                    </div>
                    <div class="flex items-center gap-1 text-xs" style="color:#6b7280">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ $post->read_time }} menit baca</span>
                    </div>
                </div>

                {{-- Excerpt --}}
                @if($post->excerpt)
                    <p class="text-base font-medium leading-relaxed mb-8 p-4 rounded-xl border-l-4 border-amber-400 bg-amber-50" style="color:#44403c">
                        {{ $post->excerpt }}
                    </p>
                @endif

                {{-- Article content --}}
                <div class="prose" itemprop="articleBody">
                    {!! $post->content !!}
                </div>

                {{-- Share buttons --}}
                <div class="mt-10 pt-8 border-t" style="border-color:#e5e7eb">
                    <p class="text-sm font-semibold mb-3" style="color:#6b7280">Bagikan artikel ini:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([
                            ['WhatsApp',  'bg-green-500',  "https://wa.me/?text=".urlencode($post->title.' - '.$post->canonical_url)],
                            ['Facebook',  'bg-blue-600',   "https://www.facebook.com/sharer/sharer.php?u=".urlencode($post->canonical_url)],
                            ['X / Twitter','bg-gray-900',  "https://twitter.com/intent/tweet?text=".urlencode($post->title)."&url=".urlencode($post->canonical_url)],
                            ['Copy Link', 'bg-amber-500',  '#copy'],
                        ] as [$label, $bg, $href])
                            <a href="{{ $href }}"
                               @if($href !== '#copy') target="_blank" rel="noopener noreferrer" @endif
                               class="{{ $bg }} text-white text-xs font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition-opacity flex items-center gap-1.5">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1 space-y-6">

                {{-- Category info --}}
                <div class="fi-card p-5">
                    <div class="fi-label mb-3">Kategori</div>
                    <a href="{{ route('blog.index', ['category' => $post->category]) }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-amber-700 hover:underline">
                        📂 {{ $post->category }}
                    </a>
                </div>

                {{-- Table of contents (simple) --}}
                @php
                    preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $post->content, $headings);
                @endphp
                @if(count($headings[1]) > 0)
                    <div class="fi-card p-5">
                        <div class="fi-label mb-3">Daftar Isi</div>
                        <ol class="space-y-2">
                            @foreach($headings[1] as $i => $heading)
                                <li class="text-xs text-amber-700 hover:underline cursor-pointer flex gap-2">
                                    <span class="shrink-0 font-bold text-amber-400">{{ $i + 1 }}.</span>
                                    <span>{{ strip_tags($heading) }}</span>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- Back to blog --}}
                <a href="{{ route('blog.index') }}" class="btn-outline w-full justify-center text-xs">
                    ← Semua Artikel
                </a>
            </aside>
        </div>
    </div>

    {{-- ── Related Articles ─────────────────────────────────── --}}
    @if($related->isNotEmpty())
        <section class="border-t py-12" style="border-color:#e5e7eb;background:#f9fafb">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="fi-label mb-2">Selanjutnya</div>
                <h2 class="text-xl font-extrabold mb-8" style="color:#030712">Artikel Terkait</h2>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($related as $rel)
                        <article class="fi-card fi-card-hover group flex flex-col overflow-hidden">
                            <a href="{{ route('blog.show', $rel->slug) }}" class="relative h-40 block overflow-hidden">
                                <img src="{{ $rel->thumbnail_url }}"
                                     alt="{{ $rel->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                     loading="lazy">
                                <div class="absolute top-3 left-3">
                                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-md bg-white/85 backdrop-blur-sm text-amber-700 border border-amber-200">
                                        {{ $rel->category }}
                                    </span>
                                </div>
                            </a>
                            <div class="p-4 flex flex-col flex-1">
                                <time class="text-[11px] mb-1.5 block" style="color:#6b7280" datetime="{{ $rel->published_at?->toIso8601String() }}">
                                    {{ $rel->formatted_date }}
                                </time>
                                <h3 class="font-bold text-sm leading-snug line-clamp-2 flex-1 hover:text-amber-700 transition-colors" style="color:#030712">
                                    <a href="{{ route('blog.show', $rel->slug) }}">{{ $rel->title }}</a>
                                </h3>
                                <a href="{{ route('blog.show', $rel->slug) }}"
                                   class="mt-3 text-xs font-semibold text-amber-600 hover:underline flex items-center gap-1">
                                    Baca
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
