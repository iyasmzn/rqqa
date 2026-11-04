{{--
    E-book unggulan — atau e-book terbaru selama belum ada yang ditandai
    unggulan (lihat Ebook::featuredOrLatest). Seksi tidak dirender sama sekali
    sebelum ada e-book yang dipublikasikan.
--}}
@if(isset($featuredEbooks) && $featuredEbooks->isNotEmpty())
<section id="ebook" class="py-20 sm:py-28" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        @php
            $eyebrow  = setting('section_ebooks_eyebrow', 'Perpustakaan Digital');
            $subtitle = setting('section_ebooks_subtitle', 'Buku pelajaran, modul, dan bacaan bermanfaat yang bisa dibaca langsung secara gratis.');
        @endphp
        <div class="flex items-end justify-between gap-6 mb-14" data-aos="fade-up">
            <div>
                @if($eyebrow)
                    <div class="fi-label mb-3">{{ $eyebrow }}</div>
                @endif
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">{{ setting('section_ebooks_title') ?: 'E-Book Pilihan' }}</h2>
                @if($subtitle)
                    <p class="mt-2 text-base max-w-md leading-relaxed" style="color:var(--muted)">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            <a href="{{ route('ebooks.index') }}"
               class="shrink-0 hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-75"
               style="color:var(--primary)">
                Lihat Semua E-Book
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="grid gap-4 sm:gap-5 grid-cols-2 md:grid-cols-4">
            @foreach($featuredEbooks as $ebook)
                <x-ebook-card :ebook="$ebook"
                              data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 70 }}" />
            @endforeach
        </div>

        <div class="mt-12 text-center" data-aos="fade-up">
            <a href="{{ route('ebooks.index') }}" class="btn-outline">
                Jelajahi Perpustakaan Digital
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

    </div>
</section>
@endif
