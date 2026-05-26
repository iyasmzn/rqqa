<?php

namespace App\Filament\Resources\Stories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten Cerita')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Cerita')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set(
                                'slug',
                                Str::slug($state ?? ''),
                            ))
                            ->columnSpanFull(),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),

                        TextInput::make('excerpt')
                            ->label('Ringkasan')
                            ->maxLength(300)
                            ->hint('Digunakan untuk preview di halaman daftar cerita.')
                            ->columnSpanFull(),

                        RichEditor::make('content')
                            ->label('Isi Cerita')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('stories/attachments')
                            ->fileAttachmentsVisibility('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Penulis / Santri')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('author_name')
                                    ->label('Nama Santri')
                                    ->required()
                                    ->maxLength(150),

                                TextInput::make('author_class')
                                    ->label('Kelas / Tingkat')
                                    ->maxLength(100)
                                    ->hint('Contoh: Kelas 3, Kelas Ulya'),

                                TextInput::make('author_year')
                                    ->label('Angkatan / Tahun')
                                    ->maxLength(10)
                                    ->hint('Contoh: 2024'),
                            ]),

                        FileUpload::make('author_photo')
                            ->label('Foto Santri')
                            ->image()
                            ->disk('public')
                            ->directory('stories/photos')
                            ->visibility('public')
                            ->avatar()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Gambar & Publikasi')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar Utama Cerita')
                            ->image()
                            ->disk('public')
                            ->directory('stories/images')
                            ->visibility('public')
                            ->automaticallyCropImagesToAspectRatio('16:9')
                            ->automaticallyResizeImagesMode('cover')
                            ->automaticallyResizeImagesToWidth('1200')
                            ->automaticallyResizeImagesToHeight('675')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Publikasikan')
                                    ->default(false)
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, bool $state): void {
                                        if ($state) {
                                            $set('published_at', now()->format('Y-m-d H:i:s'));
                                        }
                                    }),

                                DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi')
                                    ->native(false)
                                    ->seconds(false),
                            ]),

                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
