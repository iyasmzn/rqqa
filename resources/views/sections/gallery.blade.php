<section id="galeri" class="py-20 sm:py-28" style="background:var(--bg)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex items-end justify-between gap-6 mb-14" data-aos="fade-up">
            <div>
                <div class="fi-label mb-3">Foto & Video</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">Galeri Sekolah</h2>
                <p class="mt-2 text-base max-w-md leading-relaxed" style="color:var(--muted)">
                    Momen-momen berharga dari kehidupan sekolah kami.
                </p>
            </div>
            <a href="#" class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-75"
               style="color:var(--primary)">
                Semua Foto
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="masonry">
            @foreach([
                ['upacara',      'Upacara Bendera',    '176px'],
                ['computer-lab', 'Lab Komputer',       '260px'],
                ['sports-field', 'Lapangan Olahraga',  '200px'],
                ['stage-drama',  'Pentas Seni',        '240px'],
                ['graduation',   'Wisuda & Kelulusan', '176px'],
                ['science-lab',  'Laboratorium IPA',   '220px'],
                ['library',      'Perpustakaan',       '196px'],
                ['classroom',    'Ruang Kelas',        '210px'],
                ['school-hall',  'Aula Sekolah',       '180px'],
            ] as [$seed, $caption, $h])
                <div class="masonry-item group" style="height:{{ $h }}"
                     data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 80 }}" data-aos-duration="500">
                    <img src="https://picsum.photos/seed/{{ $seed }}/800/600"
                         alt="{{ $caption }}"
                         loading="lazy"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-107">
                    <div class="absolute inset-0 flex items-end transition-opacity duration-300"
                         style="background:linear-gradient(to top,rgba(0,0,0,.7) 0%,rgba(0,0,0,.1) 50%,transparent 100%);opacity:.7;group-hover:opacity:1">
                        <div class="w-full px-4 py-3 text-white text-xs font-semibold translate-y-1 group-hover:translate-y-0 transition-transform duration-300">
                            {{ $caption }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
