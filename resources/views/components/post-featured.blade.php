{{--
    Kartu artikel utama (lebar, gambar di kiri) — dipakai seksi artikel halaman
    depan dan halaman blog, agar keduanya selalu tampil sama.
--}}
@props([
    /** @var \App\Models\Post */
    'post',
    /** Tag judul: h2 di halaman blog (di bawah h1 hero), h3 di seksi halaman depan. */
    'headingTag' => 'h3',
    /** Label kecil di pojok gambar, mis. "✦ Unggulan". */
    'label' => null,
    /** Muat gambar segera — untuk kartu yang tampil di layar pertama. */
    'eager' => false,
])

@php
    $headingTag = in_array($headingTag, ['h2', 'h3', 'h4'], true) ? $headingTag : 'h3';
@endphp

<article {{ $attributes->merge(['class' => 'fi-card overflow-hidden']) }}
         itemscope itemtype="https://schema.org/Article">
    <meta itemprop="author" content="{{ $post->author }}">
    <meta itemprop="datePublished" content="{{ $post->published_at?->toIso8601String() }}">

    <div class="grid lg:grid-cols-5">
        <a href="{{ route('blog.show', $post->slug) }}"
           class="lg:col-span-2 h-56 lg:h-auto relative overflow-hidden block group">
            <img src="{{ $post->thumbnail_url }}"
                 alt="{{ $post->title }}"
                 loading="{{ $eager ? 'eager' : 'lazy' }}"
                 itemprop="image"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
            <div class="absolute inset-0"
                 style="background:linear-gradient(to top,rgba(0,0,0,.4),transparent)"></div>
            @if(filled($label))
                <div class="absolute top-4 left-4">
                    <span class="text-[10px] font-bold px-2.5 py-1.5 rounded-lg bg-amber-500 text-white uppercase tracking-wider">
                        {{ $label }}
                    </span>
                </div>
            @endif
        </a>
        <div class="lg:col-span-3 p-8 lg:p-10 flex flex-col justify-center">
            <div class="flex flex-wrap items-center gap-2.5 mb-4">
                <span class="text-xs font-bold px-3 py-1 rounded-full border"
                      style="background:var(--primary-50);color:var(--primary-800);border-color:var(--primary-200)">
                    {{ $post->category }}
                </span>
                <time class="text-xs" style="color:var(--muted)" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->formatted_date }}</time>
                <span class="text-xs inline-flex items-center gap-1" style="color:var(--muted)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $post->read_time }} mnt baca
                </span>
                <span class="text-xs inline-flex items-center gap-1" style="color:var(--muted)">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ $post->formatted_views }}x dibaca
                </span>
            </div>
            <{{ $headingTag }} class="text-xl sm:text-2xl font-extrabold leading-snug tracking-tight mb-3"
                style="color:var(--text)" itemprop="headline">
                <a href="{{ route('blog.show', $post->slug) }}" class="hover:opacity-75 transition-opacity">
                    {{ $post->title }}
                </a>
            </{{ $headingTag }}>
            <p class="text-base leading-relaxed mb-6 line-clamp-3" style="color:var(--muted)" itemprop="description">
                {{ $post->excerpt }}
            </p>
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                         style="background:var(--primary)">
                        {{ $post->author_initials }}
                    </div>
                    <span class="text-sm font-medium" style="color:var(--muted)">{{ $post->author }}</span>
                </div>
                <a href="{{ route('blog.show', $post->slug) }}" class="btn-primary text-sm">
                    Baca Selengkapnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</article>
