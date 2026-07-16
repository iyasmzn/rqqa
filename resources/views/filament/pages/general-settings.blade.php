<x-filament-panels::page>
    {{-- Load selectable fonts so the dropdown options and live preview render in their real typeface --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ $this->googleFontsHref() }}">

    {{-- Custom font preview loads its own stylesheet inside the preview card (see ThemeSettings::customFontPreview) --}}
    {{ $this->form }}
</x-filament-panels::page>
