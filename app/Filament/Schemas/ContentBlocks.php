<?php

namespace App\Filament\Schemas;

use App\Filament\Concerns\InteractsWithImagePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

/**
 * Shared "Konten Tambahan" image blocks repeater used by static pages. Every
 * image upload uses the media image picker (choose from library or upload a
 * new file).
 */
class ContentBlocks
{
    use InteractsWithImagePicker;

    /**
     * @var list<string>
     */
    private const ACCEPTED = ['image/jpeg', 'image/png', 'image/webp'];

    public static function make(string $directory): Repeater
    {
        return Repeater::make('blocks')
            ->label('')
            ->schema([
                Select::make('type')
                    ->label('Jenis Blok')
                    ->options([
                        'image_cover' => '🖼️  Cover Image — satu gambar penuh lebar',
                        'image_carousel' => '🎠  Carousel — slider beberapa gambar',
                        'image_gallery' => '🖼️  Galeri — grid beberapa gambar',
                    ])
                    ->required()
                    ->live()
                    ->native(false)
                    ->columnSpanFull(),

                // ── Cover Image ──────────────────────────────
                self::imagePicker(
                    key: 'image',
                    label: 'Gambar',
                    hint: 'Lebar optimal 1400px atau lebih.',
                    accepted: self::ACCEPTED,
                    width: 1400,
                    directory: $directory,
                    withMeta: false,
                )->visible(fn (Get $get): bool => $get('type') === 'image_cover'),

                TextInput::make('caption')
                    ->label('Keterangan Gambar')
                    ->maxLength(200)
                    ->placeholder('Opsional — keterangan singkat di bawah gambar')
                    ->visible(fn (Get $get): bool => $get('type') === 'image_cover')
                    ->columnSpanFull(),

                // ── Carousel & Gallery — shared images repeater ──
                Repeater::make('images')
                    ->label('Daftar Gambar')
                    ->schema([
                        self::imagePicker(
                            key: 'image',
                            label: 'Gambar',
                            hint: 'Lebar optimal 1400px atau lebih.',
                            accepted: self::ACCEPTED,
                            width: 1600,
                            directory: $directory,
                            withMeta: false,
                        ),

                        TextInput::make('caption')
                            ->label('Keterangan')
                            ->maxLength(200)
                            ->placeholder('Opsional')
                            ->columnSpanFull(),
                    ])
                    ->addActionLabel('+ Tambah Gambar')
                    ->minItems(1)
                    ->defaultItems(1)
                    ->reorderable()
                    ->collapsed(false)
                    ->itemLabel(fn (array $state): string => $state['caption'] ?: 'Gambar')
                    ->visible(fn (Get $get): bool => in_array($get('type'), ['image_carousel', 'image_gallery']))
                    ->columnSpanFull(),

                // ── Gallery columns selector ──────────────────
                Select::make('columns')
                    ->label('Jumlah Kolom')
                    ->options(['2' => '2 Kolom', '3' => '3 Kolom', '4' => '4 Kolom'])
                    ->default('3')
                    ->native(false)
                    ->visible(fn (Get $get): bool => $get('type') === 'image_gallery'),
            ])
            ->addActionLabel('+ Tambah Blok')
            ->reorderable()
            ->collapsible()
            ->collapsed()
            ->defaultItems(0)
            ->itemLabel(fn (array $state): string => match ($state['type'] ?? '') {
                'image_cover' => '🖼️  Cover Image',
                'image_carousel' => '🎠  Carousel — '.count($state['images'] ?? []).' gambar',
                'image_gallery' => '🖼️🖼️  Galeri — '.count($state['images'] ?? []).' gambar',
                default => 'Blok Baru',
            })
            ->columnSpanFull();
    }
}
