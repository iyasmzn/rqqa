{{--
    Kartu artikel di grid — dipakai seksi artikel halaman depan dan halaman
    blog, agar keduanya selalu tampil sama.
--}}
@props([
    /** @var \App\Models\Post */
    'post',
    /** Tag judul: h2 di halaman blog (di bawah h1 hero), h3 di seksi halaman depan. */
    'headingTag' => 'h3',
])

@php
    $headingTag = in_array($headingTag, ['h2', 'h3', 'h4'], true) ? $headingTag : 'h3';
@endphp

<article {{ $attributes->merge(['class' => 'fi-card fi-card-hover group flex flex-col overflow-hidden']) }}
         itemscope itemtype="https://schema.org/Article">
    <meta itemprop="author" content="{{ $post->author }}">
    <meta itemprop="datePublished" content="{{ $post->published_at?->toIso8601String() }}">

    <a href="{{ route('blog.show', $post->slug) }}" class="relative h-48 block overflow-hidden">
        <img src="{{ $post->thumbnail_url }}"
             alt="{{ $post->title }}"
             loading="lazy"
             itemprop="image"
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        <div class="absolute top-4 left-4">
            <span class="text-xs font-bold px-2.5 py-1 rounded-full backdrop-blur-md border"
                  style="background:rgba(255,255,255,.88);color:var(--primary-800);border-color:var(--primary-200)">
                {{ $post->category }}
            </span>
        </div>
    </a>

    <div class="p-6 flex flex-col flex-1">
        <span class="text-xs mb-2.5 flex items-center gap-1.5" style="color:var(--muted)">
            <time datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->formatted_date }}</time>
            <span aria-hidden="true">·</span>
            <span class="inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                {{ $post->formatted_views }}x dibaca
            </span>
        </span>
        <{{ $headingTag }} class="font-bold text-base leading-snug mb-2.5 line-clamp-2 flex-1 hover:opacity-70 transition-opacity"
            style="color:var(--text)" itemprop="headline">
            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
        </{{ $headingTag }}>
        <p class="text-sm leading-relaxed line-clamp-2 mb-5" style="color:var(--muted)" itemprop="description">{{ $post->excerpt }}</p>
        <a href="{{ route('blog.show', $post->slug) }}"
           class="inline-flex items-center gap-1.5 text-sm font-semibold hover:opacity-75 transition-opacity"
           style="color:var(--primary)">
            Baca Selengkapnya
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</article>
