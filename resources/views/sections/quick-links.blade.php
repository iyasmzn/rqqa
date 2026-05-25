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
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-2 pb-10">
    <div class="fi-card p-1 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-{{ min($quickLinks->count(), 8) }} gap-1"
         data-aos="fade-up" data-aos-duration="500">
        @foreach($quickLinks as $item)
            <a href="{{ str_starts_with($item['url'], '#') ? '/'.$item['url'] : $item['url'] }}"
               class="flex flex-col items-center gap-1.5 py-4 px-3 rounded-xl transition-colors hover:bg-amber-50 group"
               data-aos="fade-up" data-aos-delay="{{ $loop->index * 60 }}">
                @if(!empty($item['icon']))
                    <span class="text-2xl">{{ $item['icon'] }}</span>
                @endif
                <span class="text-xs font-semibold group-hover:text-amber-700 transition-colors" style="color:var(--muted)">
                    {{ $item['label'] }}
                </span>
            </a>
        @endforeach
    </div>
</section>
@endif
