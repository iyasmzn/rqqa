<?php

namespace App\Filament\Resources\Ebooks\Schemas;

use App\Filament\Concerns\InteractsWithImagePicker;
use App\Models\Category;
use App\Models\Ebook;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EbookForm
{
    use InteractsWithImagePicker;

    /** Batas ukuran PDF (KB) — sejalan dengan batas unggah sementara di config/livewire.php. */
    public const PDF_MAX_KB = 51200;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi E-Book')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul E-Book')
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

                        Grid::make(2)
                            ->schema([
                                TextInput::make('author')
                                    ->label('Penulis / Pengarang')
                                    ->maxLength(150),

                                TextInput::make('publisher')
                                    ->label('Penerbit')
                                    ->maxLength(150),

                                TextInput::make('published_year')
                                    ->label('Tahun Terbit')
                                    ->numeric()
                                    ->minValue(1800)
                                    ->maxValue(now()->year),

                                TextInput::make('isbn')
                                    ->label('ISBN')
                                    ->maxLength(30),

                                TextInput::make('language')
                                    ->label('Bahasa')
                                    ->maxLength(50)
                                    ->datalist(['Indonesia', 'Arab', 'Inggris', 'Jawa', 'Sunda']),

                                TextInput::make('pages')
                                    ->label('Jumlah Halaman')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('hal'),
                            ])
                            ->columnSpanFull(),

                        Select::make('category')
                            ->label('Kategori')
                            ->options(fn () => Category::optionsForType(Category::TYPE_EBOOK))
                            ->native(false)
                            ->helperText('Daftar kategori diatur di menu Kategori (tab E-Book).'),

                        Textarea::make('description')
                            ->label('Deskripsi / Sinopsis')
                            ->rows(5)
                            ->maxLength(5000)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Gambar Sampul')
                    ->schema([
                        self::imagePicker(
                            key: 'cover_image',
                            label: 'Gambar Sampul',
                            hint: 'Opsional. Rasio 3:4 (potret), di-resize ke 600×800. Tanpa sampul, website membuat sampul otomatis dari judul.',
                            accepted: ['image/jpeg', 'image/png', 'image/webp'],
                            width: 600,
                            height: 800,
                            directory: 'ebooks/covers',
                            aspectRatio: '3:4',
                        )->columnSpanFull(),
                    ]),

                Section::make('Isi E-Book')
                    ->description('Pilih dari mana pengunjung membaca e-book ini.')
                    ->schema(self::sourceSchema()),

                Section::make('Tampilan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Toggle::make('is_published')
                                    ->label('Tampilkan ke Publik')
                                    ->default(true),

                                Toggle::make('is_featured')
                                    ->label('E-Book Unggulan')
                                    ->helperText('Tampil di seksi E-Book halaman depan. Selama belum ada yang ditandai, seksi itu menampilkan e-book terbaru.'),

                                TextInput::make('sort_order')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('Angka kecil tampil lebih dulu.'),
                            ]),
                    ]),
            ]);
    }

    /**
     * Pilihan sumber isi beserta kolom milik tiap sumber. Hanya kolom sumber
     * terpilih yang tampil dan divalidasi; sisanya dikosongkan saat disimpan
     * oleh {@see self::prepareSourceData()}.
     *
     * @return array<int, mixed>
     */
    private static function sourceSchema(): array
    {
        $isSource = fn (string $type): Closure => fn (Get $get): bool => $get('source_type') === $type;

        return [
            ToggleButtons::make('source_type')
                ->label('Sumber Isi')
                ->options(Ebook::sourceTypeOptions())
                ->icons([
                    Ebook::SOURCE_PDF => Heroicon::OutlinedDocumentArrowUp,
                    Ebook::SOURCE_LINK => Heroicon::OutlinedLink,
                    Ebook::SOURCE_EMBED => Heroicon::OutlinedCodeBracketSquare,
                ])
                ->default(Ebook::SOURCE_PDF)
                ->required()
                ->in(array_keys(Ebook::sourceTypeOptions()))
                ->inline()
                ->live()
                ->helperText(fn (Get $get): string => match ($get('source_type')) {
                    Ebook::SOURCE_LINK => 'E-book tersimpan di situs lain; tombol "Baca" membuka tautannya di tab baru.',
                    Ebook::SOURCE_EMBED => 'Penampil dari situs lain (Google Drive, flipbook, dll.) ditampilkan langsung di halaman e-book.',
                    default => 'PDF diunggah ke website dan bisa dibaca langsung di halaman e-book.',
                })
                ->columnSpanFull(),

            FileUpload::make('file_path')
                ->label('File PDF')
                ->disk('public')
                ->directory(Ebook::FILE_DIRECTORY)
                ->visibility('public')
                // Model ikut menghapus PDF lama saat diganti, jadi path kiriman
                // klien wajib sama dengan milik record (atau unggahan baru).
                ->preventFilePathTampering()
                ->acceptedFileTypes(['application/pdf'])
                ->maxSize(self::PDF_MAX_KB)
                ->storeFileNamesIn('original_filename')
                ->openable()
                ->downloadable()
                ->required($isSource(Ebook::SOURCE_PDF))
                ->visible($isSource(Ebook::SOURCE_PDF))
                ->columnSpanFull(),

            Toggle::make('allow_download')
                ->label('Tampilkan tombol Unduh PDF')
                ->default(true)
                ->helperText('Matikan bila e-book cukup dibaca di halaman. Catatan: penampil PDF bawaan browser tetap bisa menyimpan berkasnya.')
                ->visible($isSource(Ebook::SOURCE_PDF)),

            TextInput::make('external_url')
                ->label('URL E-Book')
                ->url()
                ->rule('url:http,https')
                ->maxLength(Ebook::URL_MAX_LENGTH)
                ->placeholder('https://')
                ->helperText('Misalnya tautan Google Drive, Google Play Books, atau situs perpustakaan digital.')
                ->required($isSource(Ebook::SOURCE_LINK))
                ->visible($isSource(Ebook::SOURCE_LINK))
                ->columnSpanFull(),

            Textarea::make('embed_url')
                ->label('URL atau Kode Sematan')
                ->rows(3)
                ->maxLength(5000)
                ->placeholder('https://drive.google.com/file/d/…/view   atau   <iframe src="…"></iframe>')
                ->helperText('Tempel URL penampil atau kode <iframe> dari Google Drive, Heyzine, FlipHTML5, AnyFlip, dan sejenisnya. Tautan berbagi Google Drive/Docs otomatis diubah ke mode pratinjau.')
                ->rules([
                    fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        $url = Ebook::normalizeEmbedUrl(is_string($value) ? $value : null);

                        if ($url === null) {
                            $fail('Masukkan URL (https://…) atau kode <iframe> yang valid.');
                        } elseif (mb_strlen($url) > Ebook::URL_MAX_LENGTH) {
                            $fail('URL sematan terlalu panjang (maksimal '.Ebook::URL_MAX_LENGTH.' karakter).');
                        }
                    },
                ])
                ->required($isSource(Ebook::SOURCE_EMBED))
                ->visible($isSource(Ebook::SOURCE_EMBED))
                ->columnSpanFull(),
        ];
    }

    /**
     * Rapikan kolom sumber isi sebelum disimpan: kolom milik sumber yang tidak
     * dipilih dikosongkan (PDF lamanya ikut dihapus oleh model), dan ukuran
     * PDF dibaca dari berkas yang tersimpan.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareSourceData(array $data): array
    {
        $type = $data['source_type'] ?? Ebook::SOURCE_PDF;
        $disk = Storage::disk('public');

        if ($type === Ebook::SOURCE_PDF) {
            $path = $data['file_path'] ?? null;

            $data['file_size'] = is_string($path) && $path !== '' && $disk->exists($path)
                ? $disk->size($path)
                : null;
        } else {
            $data['file_path'] = null;
            $data['original_filename'] = null;
            $data['file_size'] = null;
        }

        if ($type !== Ebook::SOURCE_LINK) {
            $data['external_url'] = null;
        }

        if ($type !== Ebook::SOURCE_EMBED) {
            $data['embed_url'] = null;
        }

        return $data;
    }
}
