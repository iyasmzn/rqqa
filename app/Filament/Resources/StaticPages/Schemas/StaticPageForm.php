<?php

namespace App\Filament\Resources\StaticPages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Halaman')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Halaman')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'slug',
                                Str::slug($state ?? ''),
                            ))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(200)
                            ->unique(ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->hint('Digunakan sebagai URL halaman. Harus unik dan hanya boleh huruf, angka, dan tanda hubung.')
                            ->helperText(fn (?string $state): string => $state ? '/page/'.$state : '')
                            ->columnSpanFull(),

                        TextInput::make('meta_description')
                            ->label('Meta Deskripsi')
                            ->maxLength(300)
                            ->hint('Maksimal 300 karakter. Digunakan untuk SEO.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Konten')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Isi Halaman')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('pages/attachments')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ]),

                Section::make('Konten Tambahan')
                    ->description('Tambahkan blok gambar opsional yang ditampilkan di bawah konten utama.')
                    ->icon(Heroicon::OutlinedPhoto)
                    ->schema([
                        Repeater::make('blocks')
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
                                FileUpload::make('image')
                                    ->label('Gambar')
                                    ->image()
                                    ->disk('public')
                                    ->directory('pages/blocks')
                                    ->visibility('public')
                                    ->automaticallyResizeImagesToWidth('1400')
                                    ->hint('Lebar optimal 1400px atau lebih.')
                                    ->visible(fn (Get $get): bool => $get('type') === 'image_cover')
                                    ->columnSpanFull(),

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
                                        FileUpload::make('image')
                                            ->label('Gambar')
                                            ->image()
                                            ->disk('public')
                                            ->directory('pages/blocks')
                                            ->visibility('public')
                                            ->required()
                                            ->columnSpanFull(),

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
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Pengaturan')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(9999)
                                    ->default(0)
                                    ->hint('Angka lebih kecil ditampilkan lebih awal.'),

                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }
}
