@php
    $spmbYear       = setting('spmb_year', '2026/2027');
    $cardTitle      = setting('spmb_card_title', 'SPMB Tahun Ajaran {year} Dibuka!');
    $cardTitle      = str_replace('{year}', $spmbYear, $cardTitle);
    $cardDesc       = setting('spmb_card_description', 'Pendaftaran peserta didik baru resmi dibuka. Tersedia jalur Prestasi, Zonasi, dan Afirmasi. Segera lengkapi berkas dan daftarkan diri Anda sebelum batas waktu.');
    $ctaLabel       = setting('spmb_card_cta_label', 'Daftar Sekarang');
    $ctaUrl         = setting('spmb_card_cta_url', '/ppdb');
    $secondaryLabel = setting('spmb_card_secondary_label', 'Info Selengkapnya');
@endphp

<section id="spmb" class="py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl overflow-hidden shadow-2xl border"
             style="background:linear-gradient(135deg,var(--primary-50) 0%,var(--primary-100) 50%,var(--primary-200) 100%);border-color:var(--primary-200);box-shadow:0 20px 60px color-mix(in oklab,var(--primary) 15%,transparent)"
             data-aos="fade-up">
            <div class="grid lg:grid-cols-2">

                {{-- Left: copy --}}
                <div class="p-10 lg:p-14" data-aos="fade-right" data-aos-delay="80">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-5"
                         style="border:1px solid var(--primary-300);background:color-mix(in oklab,var(--primary-100) 60%,transparent);color:var(--primary-800)">
                        <span class="w-1.5 h-1.5 rounded-full animate-pulse inline-block" style="background:var(--primary)"></span>
                        Penerimaan Peserta Didik Baru
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight tracking-tight mb-4" style="color:var(--primary-900)">
                        {!! nl2br(e($cardTitle)) !!}
                    </h2>
                    <p class="text-base leading-relaxed mb-8" style="color:var(--primary-800);opacity:.85">
                        {{ $cardDesc }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $ctaUrl }}" class="btn-primary text-base">
                            {{ $ctaLabel }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <a href="{{ route('ppdb.index') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl border-2 font-semibold bg-transparent transition-all text-base"
                           style="border-color:var(--primary-300);color:var(--primary-900)"
                           onmouseover="this.style.background='var(--primary-100)'"
                           onmouseout="this.style.background='transparent'">
                            {{ $secondaryLabel }}
                        </a>
                    </div>
                </div>

                {{-- Right: dates panel --}}
                <div class="hidden lg:flex items-center justify-center px-10 py-14"
                     style="background:color-mix(in oklab,var(--primary-400) 15%,transparent)"
                     data-aos="fade-left" data-aos-delay="160">
                    <div class="text-center w-full">
                        <div class="text-8xl mb-6">🎓</div>
                        <div class="grid grid-cols-3 gap-4 text-center">
                            @foreach([
                                [setting('spmb_deadline', '30 Mei'), 'Batas Daftar'],
                                [setting('spmb_select', '10 Juni'), 'Seleksi'],
                                [setting('spmb_announce', '25 Juni'), 'Pengumuman'],
                            ] as [$d, $l])
                                <div class="bg-white/70 backdrop-blur-sm rounded-2xl p-4 shadow-sm">
                                    <div class="text-sm font-extrabold" style="color:var(--primary-700)">{{ $d }}</div>
                                    <div class="text-[11px] font-semibold mt-1" style="color:var(--primary-600)">{{ $l }}</div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('ppdb.index') }}"
                           class="mt-6 inline-flex items-center gap-1.5 text-sm font-bold transition-opacity hover:opacity-70"
                           style="color:var(--primary-700)">
                            Lihat semua info PPDB
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
