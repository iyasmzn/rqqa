{{--
    Sampul e-book berproporsi buku (3:4) dengan bayangan punggung di sisi
    kiri. Tanpa gambar sampul, sampul digambar dari judul & penulis memakai
    warna tema — dipakai kartu e-book, halaman detail, dan seksi halaman depan.
--}}
@props([
    /** @var \App\Models\Ebook */
    'ebook',
    /** Muat gambar segera (sampul utama halaman detail), bukan lazy. */
    'eager' => false,
])

@once
<style>
    .ebook-cover {
        position: relative;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        border-radius: .375rem .75rem .75rem .375rem;
        background: var(--primary-100);
        box-shadow: 0 1px 2px rgba(0,0,0,.12), 0 14px 28px -12px rgba(0,0,0,.45);
        container-type: inline-size;
    }
    .ebook-cover > img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    /* Punggung buku: bayangan & kilap tipis di sisi kiri. */
    .ebook-cover::after {
        content: '';
        position: absolute;
        inset: 0 auto 0 0;
        width: 7%;
        background: linear-gradient(90deg, rgba(0,0,0,.28), rgba(255,255,255,.2) 45%, rgba(0,0,0,.06));
        pointer-events: none;
    }
    .ebook-cover__generated {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        padding: 13% 10% 11% 16%;
        color: #fff;
        background:
            radial-gradient(circle at 88% 10%, rgba(255,255,255,.16) 0 17%, transparent 17.5%),
            radial-gradient(circle at 105% 102%, rgba(255,255,255,.08) 0 38%, transparent 38.5%),
            linear-gradient(160deg, var(--ebook-cover-from), var(--ebook-cover-to));
    }
    .ebook-cover--tone-0 { --ebook-cover-from: var(--primary-700); --ebook-cover-to: var(--primary-900); }
    .ebook-cover--tone-1 { --ebook-cover-from: var(--primary-500); --ebook-cover-to: var(--primary-800); }
    .ebook-cover--tone-2 { --ebook-cover-from: var(--primary-800); --ebook-cover-to: #111827; }
    .ebook-cover--tone-3 { --ebook-cover-from: var(--primary); --ebook-cover-to: var(--primary-700); }
    .ebook-cover__label {
        font-size: .5625rem;
        font-size: clamp(.5rem, 5cqw, .75rem);
        font-weight: 700;
        letter-spacing: .2em;
        text-transform: uppercase;
        opacity: .7;
    }
    .ebook-cover__rule {
        width: 26%;
        height: 2px;
        margin: 7% 0;
        background: currentColor;
        opacity: .45;
    }
    .ebook-cover__title {
        font-size: 1rem;
        font-size: clamp(.75rem, 10cqw, 2rem);
        font-weight: 800;
        line-height: 1.15;
        overflow-wrap: anywhere;
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ebook-cover__author {
        margin-top: auto;
        font-size: .6875rem;
        font-size: clamp(.5625rem, 5.5cqw, .9375rem);
        opacity: .8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endonce

<div {{ $attributes->merge(['class' => 'ebook-cover']) }}>
    @if($ebook->cover_url)
        <img src="{{ $ebook->cover_url }}"
             alt="Sampul {{ $ebook->title }}"
             loading="{{ $eager ? 'eager' : 'lazy' }}">
    @else
        <div class="ebook-cover__generated ebook-cover--tone-{{ ((int) $ebook->id) % 4 }}"
             role="img" aria-label="Sampul {{ $ebook->title }}">
            <span class="ebook-cover__label" aria-hidden="true">E-Book</span>
            <span class="ebook-cover__rule" aria-hidden="true"></span>
            <span class="ebook-cover__title" aria-hidden="true">{{ $ebook->title }}</span>
            @if($ebook->author)
                <span class="ebook-cover__author" aria-hidden="true">{{ $ebook->author }}</span>
            @endif
        </div>
    @endif
</div>
