@extends('layouts.public')

@push('head')
<style>
    .gallery-hero {
        background: linear-gradient(135deg, #082828 0%, #08484A 60%, #0a5a5c 100%);
        position: relative;
        overflow: hidden;
    }
    .gallery-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 60% at 80% 20%, rgba(255,255,255,.06) 0%, transparent 55%),
            radial-gradient(ellipse 40% 40% at 10% 80%, rgba(255,255,255,.04) 0%, transparent 50%);
    }
    .album-pill {
        font-size: .8125rem;
        font-weight: 600;
        padding: .45rem 1.1rem;
        border-radius: 9999px;
        border: 1.5px solid var(--border);
        color: var(--muted);
        background: var(--card);
        transition: all .2s;
        white-space: nowrap;
    }
    .album-pill:hover, .album-pill.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }
    .gallery-grid {
        columns: 2;
        column-gap: .75rem;
    }
    @media (min-width: 640px) { .gallery-grid { columns: 3; } }
    @media (min-width: 1024px) { .gallery-grid { columns: 4; } }
    .gallery-item {
        break-inside: avoid;
        margin-bottom: .75rem;
        border-radius: .75rem;
        overflow: hidden;
        position: relative;
        cursor: zoom-in;
        background: var(--card);
        border: 1.5px solid var(--border);
    }
    .gallery-item img {
        width: 100%;
        display: block;
        transition: transform .4s ease;
    }
    .gallery-item:hover img { transform: scale(1.04); }
    .gallery-item-overlay {
        position: absolute;
        inset: 0;
        background: rgba(8,40,40,.6);
        opacity: 0;
        transition: opacity .25s;
        display: flex;
        align-items: flex-end;
        padding: .75rem;
    }
    .gallery-item:hover .gallery-item-overlay { opacity: 1; }

    /* Lightbox */
    #lightbox {
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(0,0,0,.92);
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    #lightbox.open { display: flex; }
    #lightbox img {
        max-width: 92vw;
        max-height: 88vh;
        object-fit: contain;
        border-radius: .75rem;
        box-shadow: 0 25px 60px rgba(0,0,0,.6);
    }
    #lightbox-close {
        position: absolute;
        top: 1rem; right: 1rem;
        color: #fff;
        background: rgba(255,255,255,.15);
        border: none;
        border-radius: 9999px;
        width: 2.5rem; height: 2.5rem;
        font-size: 1.25rem;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background .2s;
    }
    #lightbox-close:hover { background: rgba(255,255,255,.3); }
    #lightbox-caption {
        position: absolute;
        bottom: 1.5rem; left: 0; right: 0;
        text-align: center;
        color: rgba(255,255,255,.8);
        font-size: .875rem;
    }
</style>
@endpush

@section('content')

{{-- ── Hero ──────────────────────────────────────────────── --}}
<section class="gallery-hero py-20 sm:py-28">
    <x-hero-geo />
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.2)">
            🖼️ Galeri Foto
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 leading-tight">
            Galeri & Dokumentasi
        </h1>
        <p class="text-lg max-w-xl mx-auto" style="color:rgba(255,255,255,.75)">
            Foto kegiatan, fasilitas, dan momen berharga dari {{ setting('site_name', config('app.name')) }}.
        </p>
    </div>
</section>

{{-- ── Gallery ──────────────────────────────────────────── --}}
<section class="py-16 sm:py-20" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Album Filter --}}
        @if($albums->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-10 overflow-x-auto pb-1" data-aos="fade-up">
            <a href="{{ route('gallery.index') }}"
               class="album-pill {{ is_null($album) ? 'active' : '' }}">
                Semua
            </a>
            @foreach($albums as $a)
            <a href="{{ route('gallery.index', ['album' => $a]) }}"
               class="album-pill {{ $album === $a ? 'active' : '' }}">
                {{ $a }}
            </a>
            @endforeach
        </div>
        @endif

        @if($images->isNotEmpty())
        <div class="gallery-grid" data-aos="fade-up">
            @foreach($images as $image)
            <div class="gallery-item"
                 onclick="openLightbox('{{ $image->url }}', '{{ e($image->alt ?? $image->name) }}')"
                 title="{{ $image->alt ?? $image->name }}">
                <img src="{{ $image->url }}"
                     alt="{{ $image->alt ?? $image->name }}"
                     loading="lazy">
                <div class="gallery-item-overlay">
                    @if($image->alt || $image->description)
                    <p class="text-xs text-white font-medium line-clamp-2">
                        {{ $image->alt ?? $image->description }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($images->hasPages())
        <div class="mt-12">
            {{ $images->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-24 fi-card" data-aos="fade-up">
            <div class="text-6xl mb-4">🖼️</div>
            <p class="text-base font-medium" style="color:var(--muted)">
                @if($album)
                    Belum ada foto di album <strong>{{ $album }}</strong>.
                @else
                    Belum ada foto di galeri.
                @endif
            </p>
        </div>
        @endif

    </div>
</section>

{{-- Lightbox --}}
<div id="lightbox" onclick="closeLightbox()">
    <button id="lightbox-close" onclick="closeLightbox()" aria-label="Tutup">✕</button>
    <img id="lightbox-img" src="" alt="">
    <p id="lightbox-caption"></p>
</div>

@push('scripts')
<script>
function openLightbox(src, caption) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox-caption').textContent = caption || '';
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.getElementById('lightbox-img').src = '';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>
@endpush

@endsection
