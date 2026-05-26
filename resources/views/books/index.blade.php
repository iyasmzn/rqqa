@extends('layouts.public')

@push('head')
<style>
    .books-hero {
        background: linear-gradient(135deg, #082828 0%, #08484A 60%, #0a6060 100%);
        position: relative;
        overflow: hidden;
    }
    .books-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 60% at 20% 50%, rgba(255,255,255,.06) 0%, transparent 55%),
            radial-gradient(ellipse 40% 40% at 80% 20%, rgba(255,255,255,.04) 0%, transparent 50%);
    }
    .book-card { transition: transform .3s ease, box-shadow .3s ease; }
    .book-card:hover { transform: translateY(-4px); }
</style>
@endpush

@section('content')

{{-- ── Hero ──────────────────────────────────────────────── --}}
<section class="books-hero py-20 sm:py-28">
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-1.5 rounded-full mb-6"
             style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.9);border:1px solid rgba(255,255,255,.2)">
            📚 Toko Buku
        </div>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-4 leading-tight">
            Koleksi Buku Pilihan
        </h1>
        <p class="text-lg max-w-xl mx-auto" style="color:rgba(255,255,255,.75)">
            Kitab, buku agama, dan referensi pendidikan berkualitas dari {{ setting('site_name', config('app.name')) }}.
        </p>
    </div>
</section>

{{-- ── Filter & Grid ─────────────────────────────────────── --}}
<section class="py-16 sm:py-20" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Category filter --}}
        @if($categories->isNotEmpty())
        <div class="flex flex-wrap gap-2 mb-10" data-aos="fade-up">
            <a href="{{ route('books.index') }}"
               class="cat-pill {{ !$category ? 'active-pill' : '' }}"
               style="{{ !$category ? 'background:var(--primary);color:#fff;border-color:var(--primary)' : '' }}">
                Semua
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('books.index', ['category' => $cat]) }}"
               class="cat-pill {{ $category === $cat ? 'active-pill' : '' }}"
               style="{{ $category === $cat ? 'background:var(--primary);color:#fff;border-color:var(--primary)' : '' }}">
                {{ $cat }}
            </a>
            @endforeach
        </div>
        @endif

        @if($books->isNotEmpty())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($books as $book)
            <div class="book-card fi-card fi-card-hover flex flex-col overflow-hidden"
                 data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 60 }}">
                {{-- Cover --}}
                <a href="{{ route('books.show', $book) }}" class="block relative overflow-hidden"
                   style="padding-top:133%">
                    <img src="{{ $book->cover_url }}"
                         alt="{{ $book->title }}"
                         loading="lazy"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @if(!$book->is_available || $book->stock === 0)
                    <div class="absolute top-3 left-3">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                              style="background:rgba(239,68,68,.9);color:#fff">Habis</span>
                    </div>
                    @endif
                </a>

                {{-- Info --}}
                <div class="p-5 flex flex-col flex-1">
                    @if($book->category)
                    <span class="text-xs font-bold mb-2 block" style="color:var(--primary)">{{ $book->category }}</span>
                    @endif

                    <h3 class="font-bold text-sm leading-snug mb-1 line-clamp-2 flex-1" style="color:var(--text)">
                        <a href="{{ route('books.show', $book) }}" class="hover:opacity-70 transition-opacity">
                            {{ $book->title }}
                        </a>
                    </h3>

                    @if($book->author)
                    <p class="text-xs mb-3" style="color:var(--muted)">{{ $book->author }}</p>
                    @endif

                    <div class="flex items-center justify-between gap-2 mt-auto pt-3"
                         style="border-top:1px solid var(--border)">
                        <span class="font-extrabold text-sm" style="color:var(--primary)">
                            {{ $book->formatted_price }}
                        </span>
                        <a href="{{ route('books.show', $book) }}"
                           class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                           style="background:var(--primary);color:#fff">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($books->hasPages())
        <div class="mt-12">
            {{ $books->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-24 fi-card">
            <div class="text-6xl mb-4">📚</div>
            <p class="text-base font-medium" style="color:var(--muted)">
                {{ $category ? "Belum ada buku dalam kategori \"{$category}\"." : 'Belum ada buku yang tersedia.' }}
            </p>
            @if($category)
            <a href="{{ route('books.index') }}" class="btn-outline mt-6 inline-flex">Lihat Semua Buku</a>
            @endif
        </div>
        @endif

    </div>
</section>

@endsection
