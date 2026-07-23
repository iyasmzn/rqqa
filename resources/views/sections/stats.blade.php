<section id="profil" class="py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($stats->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-{{ min($stats->count(), 4) }} gap-4 sm:gap-5">
                @foreach($stats as $stat)
                    @php
                        $hasLink = filled($stat->url);
                        $tag = $hasLink ? 'a' : 'div';
                        $cardClass = 'fi-card fi-card-hover p-7 sm:p-8 text-center'.($hasLink ? ' block cursor-pointer' : '');
                        $linkAttrs = $hasLink
                            ? 'href="'.e($stat->url).'"'.($stat->open_in_new_tab ? ' target="_blank" rel="noopener"' : '')
                            : '';
                    @endphp
                    <{{ $tag }} class="{{ $cardClass }}" {!! $linkAttrs !!}
                         data-aos="zoom-in" data-aos-delay="{{ $loop->index * 80 }}">
                        @if($url = icon_url($stat->icon_image))
                            <img src="{{ $url }}" alt="{{ $stat->label }}" loading="lazy"
                                 class="w-10 h-10 mx-auto mb-3 object-contain">
                        @else
                            <div class="text-4xl mb-3">{{ $stat->icon }}</div>
                        @endif
                        <div class="text-3xl sm:text-4xl font-black tracking-tight" style="color:var(--primary)">{{ $stat->value }}</div>
                        <div class="text-sm font-semibold mt-2" style="color:var(--text)">{{ $stat->label }}</div>
                        @if($stat->sub)
                            <div class="text-xs mt-1 leading-relaxed" style="color:var(--muted)">{{ $stat->sub }}</div>
                        @endif
                    </{{ $tag }}>
                @endforeach
            </div>
        @endif
    </div>
</section>
