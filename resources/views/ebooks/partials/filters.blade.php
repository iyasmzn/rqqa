{{--
    Daftar filter halaman e-book — dipakai sidebar desktop dan laci filter
    mobile. Membutuhkan $filterUrl dari ebooks/index.blade.php.
--}}
@php
    $filterGroups = [
        [
            'title' => 'Kategori',
            'param' => 'category',
            'current' => $category,
            'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
            'options' => [
                ['value' => '', 'label' => 'Semua Kategori'],
                ...$categories->map(fn (string $name): array => ['value' => $name, 'label' => $name])->all(),
            ],
        ],
        [
            'title' => 'Format',
            'param' => 'format',
            'current' => $format,
            'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
            'options' => [
                ['value' => '', 'label' => 'Semua Format'],
                ...collect($formats)->map(fn (string $label, string $value): array => ['value' => $value, 'label' => $label])->values()->all(),
            ],
        ],
        [
            'title' => 'Urutkan',
            'param' => 'sort',
            'current' => $sort,
            'icon' => 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12',
            'options' => collect($sorts)->map(fn (string $label, string $value): array => ['value' => $value, 'label' => $label])->values()->all(),
        ],
    ];
@endphp

@foreach($filterGroups as $group)
<div class="fi-card p-5 mb-4">
    <h3 class="font-extrabold text-sm mb-4 flex items-center gap-2" style="color:var(--text)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--primary)" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $group['icon'] }}"/>
        </svg>
        {{ $group['title'] }}
    </h3>
    <div class="space-y-2">
        @foreach($group['options'] as $option)
        @php $isActive = (string) $group['current'] === (string) $option['value']; @endphp
        <a href="{{ $filterUrl([$group['param'] => $option['value']]) }}"
           class="flex items-center gap-2.5 text-sm font-medium transition-colors hover:opacity-75"
           style="color:{{ $isActive ? 'var(--primary)' : 'var(--muted)' }}"
           @if($isActive) aria-current="true" @endif>
            <span class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0"
                  style="border-color:{{ $isActive ? 'var(--primary)' : 'var(--border)' }}">
                @if($isActive)
                <span class="w-2 h-2 rounded-full" style="background:var(--primary)"></span>
                @endif
            </span>
            {{ $option['label'] }}
        </a>
        @endforeach
    </div>
</div>
@endforeach

@if($activeFilters > 0)
<a href="{{ route('ebooks.index') }}"
   class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-sm font-semibold transition-colors border"
   style="background:var(--card);color:var(--muted);border-color:var(--border)">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
    </svg>
    Reset Semua Filter
</a>
@endif
