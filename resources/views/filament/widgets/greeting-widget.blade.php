<x-filament-widgets::widget>
    <x-filament::section>
        <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
            @if ($avatarUrl)
                <img
                    src="{{ $avatarUrl }}"
                    alt="{{ $name }}"
                    style="width:4rem;height:4rem;border-radius:9999px;object-fit:cover;flex-shrink:0;box-shadow:0 0 0 3px rgba(8,72,74,.15);"
                />
            @endif

            <div style="flex:1;min-width:12rem;">
                <p class="fi-color-primary" style="font-size:.875rem;font-weight:600;margin:0;">
                    {{ $greeting }},
                </p>

                <h2 style="font-size:1.5rem;font-weight:700;line-height:1.2;margin:.125rem 0 .5rem;">
                    {{ $name }}
                </h2>

                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;">
                    @foreach ($roles as $role)
                        <x-filament::badge color="primary">{{ $role }}</x-filament::badge>
                    @endforeach

                    <span style="font-size:.8125rem;opacity:.7;">{{ $date }}</span>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
