<section id="blog" class="py-20 sm:py-28" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        @php
            $eyebrow  = setting('section_blog_eyebrow', 'Berita & Artikel');
            $subtitle = setting('section_blog_subtitle', 'Blog inspiratif dari berbagai sumber.');
        @endphp
        <div class="flex items-end justify-between gap-6 mb-14" data-aos="fade-up">
            <div>
                @if($eyebrow)
                    <div class="fi-label mb-3">{{ $eyebrow }}</div>
                @endif
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">{{ setting('section_blog_title') ?: 'Artikel' }}</h2>
                @if($subtitle)
                    <p class="mt-2 text-base max-w-md leading-relaxed" style="color:var(--muted)">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            <a href="{{ route('blog.index') }}"
               class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-75"
               style="color:var(--primary)">
                Semua Artikel
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($posts->isNotEmpty())
            @php $featured = $posts->first(); @endphp

            {{-- Featured post --}}
            <x-post-featured :post="$featured" class="mb-6" data-aos="fade-up" data-aos-delay="60" />

            {{-- Post grid --}}
            @if($posts->count() > 1)
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($posts->skip(1) as $post)
                        <x-post-card :post="$post" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}" />
                    @endforeach
                </div>
            @endif
        @else
            <div class="text-center py-20 fi-card">
                <div class="text-5xl mb-4">📰</div>
                <p class="text-base font-medium" style="color:var(--muted)">Belum ada artikel yang dipublikasikan.</p>
            </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('blog.index') }}" class="btn-outline">Lihat Semua Artikel</a>
        </div>
    </div>
</section>
