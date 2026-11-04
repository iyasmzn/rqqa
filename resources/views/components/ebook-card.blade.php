{{--
    Kartu e-book di grid — dipakai halaman e-book, seksi e-book halaman depan,
    dan daftar "E-Book Lainnya", agar ketiganya selalu tampil sama.
--}}
@props([
    /** @var \App\Models\Ebook */
    'ebook',
    /** Tag judul: h2 di halaman e-book (di bawah h1 hero), h3 di seksi lain. */
    'headingTag' => 'h3',
])

@php
    $headingTag = in_array($headingTag, ['h2', 'h3', 'h4'], true) ? $headingTag : 'h3';
    $url = route('ebooks.show', $ebook);
@endphp

@once
<style>
    .ebook-card__shelf {
        position: relative;
        display: block;
        padding: 1.75rem 1.25rem 1.5rem;
        background: linear-gradient(180deg, var(--primary-50), var(--card));
    }
    .ebook-card__shelf .ebook-cover {
        width: 72%;
        max-width: 11rem;
        margin-inline: auto;
        transition: transform .35s ease, box-shadow .35s ease;
    }
    .ebook-card:hover .ebook-card__shelf .ebook-cover {
        transform: translateY(-4px) rotate(-1.5deg);
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 22px 36px -14px rgba(0,0,0,.5);
    }
    .ebook-card__badge {
        position: absolute;
        top: .875rem;
        left: .875rem;
        z-index: 1;
        padding: .2rem .625rem;
        border-radius: var(--radius-pill);
        border: 1px solid var(--primary-200);
        background: rgba(255,255,255,.92);
        color: var(--primary-800);
        font-size: .6875rem;
        font-weight: 700;
    }
</style>
@endonce

<article {{ $attributes->merge(['class' => 'ebook-card fi-card fi-card-hover group flex flex-col overflow-hidden']) }}
         itemscope itemtype="https://schema.org/Book">
    <link itemprop="bookFormat" href="https://schema.org/EBook">
    <link itemprop="url" href="{{ $url }}">

    <a href="{{ $url }}" class="ebook-card__shelf">
        <span class="ebook-card__badge">{{ $ebook->format_label }}</span>
        <x-ebook-cover :ebook="$ebook" />
    </a>

    <div class="p-5 pt-4 flex flex-col flex-1">
        @if($ebook->category)
        <span class="text-xs font-bold uppercase tracking-wider mb-1.5" style="color:var(--primary)" itemprop="genre">
            {{ $ebook->category }}
        </span>
        @endif

        <{{ $headingTag }} class="font-bold text-sm leading-snug line-clamp-2" style="color:var(--text)" itemprop="name">
            <a href="{{ $url }}" class="hover:opacity-70 transition-opacity">{{ $ebook->title }}</a>
        </{{ $headingTag }}>

        @if($ebook->author)
        <p class="text-xs mt-1 line-clamp-1" style="color:var(--muted)" itemprop="author">{{ $ebook->author }}</p>
        @endif

        <div class="mt-auto pt-4">
            <a href="{{ $url }}"
               class="w-full text-xs font-bold py-2.5 rounded-xl transition-all flex items-center justify-center gap-1.5 hover:opacity-80"
               style="background:var(--primary-50);color:var(--primary-800);border:1px solid var(--primary-200)">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Baca E-Book
            </a>
        </div>
    </div>
</article>
