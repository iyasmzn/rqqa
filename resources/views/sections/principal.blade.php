@php
    $principalName    = setting('principal_name');
    $principalTitle   = setting('principal_title', 'Kepala Yayasan');
    $principalNip     = setting('principal_nip');
    $principalPhoto   = setting('principal_photo');
    $principalExcerpt = setting('principal_excerpt');
    $principalPage    = setting('principal_page');
    $detailUrl        = $principalPage ? route('page.show', $principalPage) : null;
@endphp

<section id="sambutan" class="py-20 sm:py-28" style="background:var(--bg-alt)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-14" data-aos="fade-up">
            <div class="fi-label mb-3">Sambutan</div>
            <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight" style="color:var(--text)">
                Sambutan {{ $principalTitle }}
            </h2>
        </div>

        <div class="fi-card p-8 lg:p-12" data-aos="fade-up" data-aos-delay="80">
            <div class="grid lg:grid-cols-3 gap-10 items-start">

                {{-- Foto / Avatar --}}
                <div class="flex flex-col items-center text-center" data-aos="fade-right" data-aos-delay="120">
                    @if($principalPhoto)
                        <div class="relative mb-5">
                            <img src="{{ asset('storage/' . $principalPhoto) }}"
                                 alt="Foto {{ $principalName ?? $principalTitle }}"
                                 class="w-44 h-56 rounded-3xl object-cover shadow-2xl ring-4 ring-amber-100">
                            <div class="absolute -bottom-3 -right-3 w-10 h-10 rounded-2xl flex items-center justify-center shadow-lg"
                                 style="background:var(--primary)">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z"/>
                                </svg>
                            </div>
                        </div>
                    @else
                        <div class="w-36 h-36 rounded-3xl flex items-center justify-center text-5xl shadow-xl mb-5"
                             style="background:linear-gradient(135deg,var(--color-amber-100),var(--color-amber-200))">
                            👨‍💼
                        </div>
                    @endif

                    @if($principalName)
                        <div class="font-bold text-base mb-1" style="color:var(--text)">{{ $principalName }}</div>
                    @endif
                    <div class="text-sm" style="color:var(--muted)">{{ $principalTitle }}</div>

                    @if($principalNip)
                        <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                             style="background:var(--color-amber-50);color:var(--color-amber-800);border-color:var(--color-amber-200)">
                            {{ $principalNip }}
                        </div>
                    @endif
                </div>

                {{-- Sambutan teks --}}
                <div class="lg:col-span-2" data-aos="fade-left" data-aos-delay="120">
                    <svg class="w-10 h-10 mb-5" fill="currentColor" viewBox="0 0 24 24"
                         style="color:var(--color-amber-300)">
                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                    </svg>

                    <div class="space-y-4">
                        @if($principalExcerpt)
                            @foreach(array_filter(array_map('trim', explode("\n", $principalExcerpt))) as $paragraph)
                                <p class="text-base leading-relaxed" style="color:var(--muted)">{{ $paragraph }}</p>
                            @endforeach
                        @else
                            <p class="text-base leading-relaxed" style="color:var(--muted)">
                                Assalamu'alaikum Warahmatullahi Wabarakatuh. Puji syukur kepada Allah SWT atas segala nikmat dan karunia-Nya sehingga {{ setting('site_name', config('app.name')) }} terus berkembang menjadi lembaga pendidikan yang unggul dan terpercaya.
                            </p>
                            <p class="text-base leading-relaxed" style="color:var(--muted)">
                                Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi yang tidak hanya mencerdaskan akal, tetapi juga membentuk karakter mulia.
                            </p>
                        @endif
                    </div>

                    <div class="mt-8 pt-6 border-t flex flex-wrap items-center gap-4"
                         style="border-color:var(--border)">
                        <div class="text-sm" style="color:var(--muted)">Wassalamu'alaikum Wr. Wb.</div>

                        @if($principalName)
                            <div class="ml-auto text-sm font-bold" style="color:var(--primary)">
                                {{ $principalName }}
                            </div>
                        @endif

                        @if($detailUrl)
                            <a href="{{ $detailUrl }}" class="btn-outline text-sm w-full sm:w-auto justify-center mt-1 sm:mt-0">
                                Baca Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
