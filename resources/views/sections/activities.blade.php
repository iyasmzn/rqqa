<section id="kegiatan" class="py-20 sm:py-28" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Section header --}}
        <div class="flex items-end justify-between gap-6 mb-14" data-aos="fade-up">
            <div>
                <div class="fi-label mb-3">Ekstrakurikuler & Acara</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">Kegiatan Sekolah</h2>
                <p class="mt-2 text-base max-w-md leading-relaxed" style="color:var(--muted)">
                    Berbagai kegiatan unggulan yang membentuk karakter dan prestasi siswa.
                </p>
            </div>
            <a href="#" class="shrink-0 inline-flex items-center gap-1.5 text-sm font-semibold transition-colors hover:opacity-75"
               style="color:var(--primary)">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['⚽', 'Sepak Bola',          'Ekskul',    'Latihan setiap Selasa & Jumat. Juara provinsi 3 tahun berturut-turut.',        'amber'],
                ['🎭', 'Seni & Drama',          'Ekskul',    'Pentas seni tahunan dan kompetisi teater tingkat nasional.',                   'purple'],
                ['🔬', 'Karya Ilmiah Remaja',   'Akademik',  'KIR aktif mengikuti lomba riset sains dan teknologi tingkat nasional.',        'blue'],
                ['🥋', 'Pencak Silat',          'Ekskul',    'Bela diri tradisional. Berprestasi di tingkat nasional dan internasional.',    'green'],
                ['💻', 'Coding & Robotika',     'Teknologi', 'Workshop coding, IoT, dan robotika untuk generasi digital Indonesia.',          'amber'],
                ['🎵', 'Paduan Suara',          'Seni',      'Koor terbaik tingkat kota. Tampil di berbagai acara nasional.',                'purple'],
            ] as [$icon, $name, $tag, $desc, $color])
                @php
                    $tagStyle = match($color) {
                        'blue'   => 'background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe',
                        'purple' => 'background:#faf5ff;color:#7e22ce;border-color:#e9d5ff',
                        'green'  => 'background:#f0fdf4;color:#15803d;border-color:#bbf7d0',
                        default  => 'background:var(--color-amber-50);color:var(--color-amber-800);border-color:var(--color-amber-200)',
                    };
                    $iconBg = match($color) {
                        'blue'   => 'background:#dbeafe',
                        'purple' => 'background:#ede9fe',
                        'green'  => 'background:#dcfce7',
                        default  => 'background:var(--color-amber-100)',
                    };
                @endphp
                <div class="fi-card fi-card-hover p-7"
                     data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                    <div class="flex gap-5">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shrink-0" style="{{ $iconBg }}">
                            {{ $icon }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="font-bold text-base" style="color:var(--text)">{{ $name }}</span>
                            </div>
                            <span class="inline-flex items-center text-xs font-semibold px-2.5 py-0.5 rounded-full border mb-2.5"
                                  style="{{ $tagStyle }}">
                                {{ $tag }}
                            </span>
                            <p class="text-sm leading-relaxed" style="color:var(--muted)">{{ $desc }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
