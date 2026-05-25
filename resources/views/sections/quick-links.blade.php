@php
    $quickLinks = collect(json_decode(setting('quick_links', ''), true) ?: [
        ['icon' => '📋', 'label' => 'SPMB',      'url' => '#spmb',       'is_active' => true],
        ['icon' => '📥', 'label' => 'Unduhan',    'url' => '/unduhan',    'is_active' => true],
        ['icon' => '📅', 'label' => 'Jadwal',     'url' => '#jadwal',     'is_active' => true],
        ['icon' => '🏆', 'label' => 'Prestasi',   'url' => '#prestasi',   'is_active' => true],
        ['icon' => '👥', 'label' => 'Alumni',     'url' => '#alumni',     'is_active' => true],
        ['icon' => '📞', 'label' => 'Kontak',     'url' => '#kontak',     'is_active' => true],
    ])->where('is_active', true)->values();
@endphp

@if($quickLinks->isNotEmpty())
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-2 pb-6">
    <div class="fi-card p-2"
         data-aos="fade-up" data-aos-duration="600">
        <div class="grid grid-cols-3 sm:grid-cols-{{ min($quickLinks->count(), 6) }} lg:grid-cols-{{ min($quickLinks->count(), 8) }} gap-1">
            @foreach($quickLinks as $item)
                <a href="{{ str_starts_with($item['url'], '#') ? '/'.$item['url'] : $item['url'] }}"
                   class="flex flex-col items-center gap-2.5 py-5 px-2 rounded-2xl transition-all duration-200 hover:scale-105 group"
                   style="hover:background:var(--color-amber-50);"
                   onmouseenter="this.style.background='var(--color-amber-50)'"
                   onmouseleave="this.style.background=''"
                   data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    @if(!empty($item['icon']))
                        <span class="text-3xl leading-none transition-transform duration-200 group-hover:scale-110">{{ $item['icon'] }}</span>
                    @endif
                    <span class="text-xs font-semibold text-center leading-tight transition-colors duration-200 group-hover:text-amber-700"
                          style="color:var(--muted)">
                        {{ $item['label'] }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
