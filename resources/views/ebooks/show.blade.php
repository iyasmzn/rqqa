@extends('layouts.public')

@push('structured-data')
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context'            => 'https://schema.org',
    '@type'               => 'Book',
    'name'                => $ebook->title,
    'description'         => $seo['description'],
    'url'                 => $seo['canonical'],
    'image'               => $ebook->cover_url,
    'bookFormat'          => 'https://schema.org/EBook',
    'author'              => $ebook->author ? ['@type' => 'Person', 'name' => $ebook->author] : null,
    'publisher'           => $ebook->publisher ? ['@type' => 'Organization', 'name' => $ebook->publisher] : null,
    'datePublished'       => $ebook->published_year ? (string) $ebook->published_year : null,
    'isbn'                => $ebook->isbn,
    'numberOfPages'       => $ebook->pages,
    'inLanguage'          => $ebook->language,
    'genre'               => $ebook->category,
    'isAccessibleForFree' => true,
]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

{{-- ── BreadcrumbList Schema ──── --}}
<script type="application/ld+json">
{!! json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'E-Book',  'item' => route('ebooks.index')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $ebook->title, 'item' => $seo['canonical']],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@php
    $meta = array_filter([
        'Penerbit'     => $ebook->publisher,
        'Tahun Terbit' => $ebook->published_year,
        'Halaman'      => $ebook->pages ? $ebook->pages.' hal' : null,
        'Bahasa'       => $ebook->language,
        'ISBN'         => $ebook->isbn,
        'Format'       => $ebook->format_label.($ebook->isPdf() && $ebook->file_size_label ? ' · '.$ebook->file_size_label : ''),
    ]);
@endphp

@section('content')

{{-- ── Breadcrumb ──────────────────────────────────────────── --}}
<div class="page-band -mt-17" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0c1a14 100%);border-bottom:1px solid rgba(255,255,255,.08)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-5 sm:pt-28 sm:pb-6">
        <nav class="flex items-center gap-2 text-sm" style="color:rgba(255,255,255,.6)" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:opacity-75 transition-opacity">Beranda</a>
            <span>/</span>
            <a href="{{ route('ebooks.index') }}" class="hover:opacity-75 transition-opacity">E-Book</a>
            <span>/</span>
            <span class="font-medium line-clamp-1" style="color:#fff">{{ $ebook->title }}</span>
        </nav>
    </div>
</div>

{{--
    pdfInline: browser bisa menampilkan PDF di dalam halaman. Penampil PDF
    bawaan tidak ada di Chrome Android — di sana iframe PDF malah memicu
    unduhan — jadi PDF dibuka di tab baru saja. Browser lama yang belum
    mengenal navigator.pdfViewerEnabled dinilai dari lebar layarnya.
--}}
<div x-data="{ pdfInline: navigator.pdfViewerEnabled ?? window.matchMedia('(min-width: 640px)').matches }">

{{-- ── Detail ──────────────────────────────────────────────── --}}
<section class="py-16 sm:py-20" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-10 lg:gap-16">

            {{-- Cover --}}
            <div class="lg:col-span-2" data-aos="fade-right">
                <div class="sticky top-28">
                    <x-ebook-cover :ebook="$ebook" eager style="max-width:340px;margin:0 auto" />
                </div>
            </div>

            {{-- Info --}}
            <div class="lg:col-span-3" data-aos="fade-left">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if($ebook->category)
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full"
                          style="background:var(--primary-50);color:var(--primary)">
                        {{ $ebook->category }}
                    </span>
                    @endif
                    <span class="inline-block text-xs font-bold px-3 py-1 rounded-full border"
                          style="border-color:var(--primary-200);color:var(--primary-800)">
                        📖 {{ $ebook->format_label }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold leading-tight mb-3" style="color:var(--text)">
                    {{ $ebook->title }}
                </h1>

                @if($ebook->author)
                <p class="text-base mb-6" style="color:var(--muted)">oleh <strong style="color:var(--text)">{{ $ebook->author }}</strong></p>
                @endif

                {{-- Meta --}}
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8 p-5 rounded-xl" style="background:var(--bg-alt)">
                    @foreach($meta as $label => $value)
                    <div>
                        <dt class="text-xs font-semibold mb-0.5" style="color:var(--muted)">{{ $label }}</dt>
                        <dd class="text-sm font-medium" style="color:var(--text)">{{ $value }}</dd>
                    </div>
                    @endforeach
                </dl>

                {{-- CTA --}}
                <div class="flex flex-wrap gap-3 mb-3">
                    @if($ebook->isLink())
                        <a href="{{ $ebook->external_url }}" target="_blank" rel="noopener" class="btn-primary">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Buka E-Book
                        </a>
                    @elseif($ebook->hasReader())
                        <a href="#baca" class="btn-primary"
                           @if($ebook->isPdf())
                           @click="if (! pdfInline) { $event.preventDefault(); window.open(@js($ebook->file_url), '_blank', 'noopener'); }"
                           @endif>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Baca Sekarang
                        </a>
                    @endif

                    @if($ebook->canBeDownloaded())
                        <a href="{{ $ebook->file_url }}" download="{{ $ebook->download_filename }}" class="btn-outline">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh PDF
                        </a>
                    @endif

                    <a href="{{ route('ebooks.index') }}" class="btn-outline">← Kembali</a>
                </div>

                @if($ebook->isLink() && $ebook->external_host)
                <p class="text-xs" style="color:var(--muted)">
                    Dibuka di tab baru — <span class="font-semibold">{{ $ebook->external_host }}</span>
                </p>
                @endif

                {{-- Description --}}
                @if($ebook->description)
                <div class="mt-10">
                    <h2 class="text-lg font-bold mb-3" style="color:var(--text)">Tentang E-Book Ini</h2>
                    <p class="text-base leading-relaxed" style="color:var(--muted)">{!! nl2br(e($ebook->description)) !!}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ── Ruang Baca (PDF / embed) ─────────────────────────────── --}}
@if($ebook->hasReader())
<section id="baca" class="pb-16 sm:pb-20" style="background:var(--bg);scroll-margin-top:5rem">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="fi-card overflow-hidden" style="border-radius:1.25rem">
            <div class="amber-bar"></div>

            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4 border-b" style="border-color:var(--border)">
                <div class="min-w-0">
                    <div class="fi-label mb-0.5">Ruang Baca</div>
                    <h2 class="font-extrabold text-base line-clamp-1" style="color:var(--text)">{{ $ebook->title }}</h2>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ $ebook->read_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold border transition-colors hover:opacity-80"
                       style="background:var(--card);color:var(--text);border-color:var(--border)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                        </svg>
                        Layar Penuh
                    </a>
                    @if($ebook->canBeDownloaded())
                    <a href="{{ $ebook->file_url }}" download="{{ $ebook->download_filename }}"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-colors hover:opacity-90"
                       style="background:var(--primary);color:#fff">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                        Unduh{{ $ebook->file_size_label ? ' ('.$ebook->file_size_label.')' : '' }}
                    </a>
                    @endif
                </div>
            </div>

            @if($ebook->isPdf())
                <template x-if="pdfInline">
                    <iframe src="{{ $ebook->file_url }}#view=FitH"
                            title="Baca {{ $ebook->title }}"
                            class="block w-full" style="height:85vh;min-height:640px;border:0"
                            loading="lazy"></iframe>
                </template>
                <div x-show="! pdfInline" class="px-6 py-12 text-center">
                    <div class="text-5xl mb-3" aria-hidden="true">📄</div>
                    <p class="text-sm mb-6 max-w-sm mx-auto" style="color:var(--muted)">
                        PDF ini paling nyaman dibaca dengan penampil PDF di perangkat Anda.
                    </p>
                    <a href="{{ $ebook->file_url }}" target="_blank" rel="noopener" class="btn-primary">Buka PDF</a>
                </div>
            @else
                <iframe src="{{ $ebook->embed_url }}"
                        title="Baca {{ $ebook->title }}"
                        class="block w-full" style="height:80vh;min-height:520px;border:0"
                        loading="lazy" allow="fullscreen" allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
            @endif
        </div>

        <p class="text-xs text-center mt-3" style="color:var(--muted)">
            {{ $ebook->isPdf() ? 'PDF tidak tampil?' : 'Penampil tidak tampil?' }}
            <a href="{{ $ebook->read_url }}" target="_blank" rel="noopener" class="font-semibold underline" style="color:var(--primary)">Buka di tab baru</a>.
        </p>
    </div>
</section>
@endif

</div>

{{-- ── Related ──────────────────────────────────────────────── --}}
@if($related->isNotEmpty())
<section class="py-16" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-extrabold mb-8" style="color:var(--text)">E-Book Lainnya</h2>
        <div class="grid gap-4 grid-cols-2 md:grid-cols-4">
            @foreach($related as $rel)
                <x-ebook-card :ebook="$rel" />
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
