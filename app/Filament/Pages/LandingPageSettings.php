<?php

namespace App\Filament\Pages;

use App\Filament\Resources\Books\BookResource;
use App\Filament\Resources\Events\EventResource;
use App\Filament\Resources\Greetings\GreetingResource;
use App\Filament\Resources\Media\MediaResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Filament\Resources\Slides\SlideResource;
use App\Filament\Resources\Stats\StatResource;
use App\Filament\Resources\Testimonials\TestimonialResource;
use App\Models\Setting;
use App\Support\HomeSections;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Resources\Resource as FilamentResource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use UnitEnum;

class LandingPageSettings extends Page
{
    use HasPageShield;

    protected string $view = 'filament.pages.general-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Halaman Depan';

    protected static ?string $title = 'Pengaturan Halaman Depan';

    protected static ?int $navigationSort = 3;

    /**
     * Menu panel tempat isi tiap seksi dikelola — slide, daftar program,
     * rekening donasi, dan seterusnya — agar kartunya bisa menautkan ke sana.
     *
     * @var array<string, class-string<FilamentResource>|class-string<Page>>
     */
    private const CONTENT_SOURCES = [
        'section_hero' => SlideResource::class,
        'section_quick_links' => QuickLinksSettings::class,
        'section_spmb' => SpmbSettings::class,
        'section_stats' => StatResource::class,
        'section_principal' => GreetingResource::class,
        'section_spmb_steps' => SpmbSettings::class,
        'section_programs' => ProgramResource::class,
        'section_events' => EventResource::class,
        'section_books' => BookResource::class,
        'section_gallery' => MediaResource::class,
        'section_blog' => PostResource::class,
        'section_testimonials' => TestimonialResource::class,
        'section_donasi' => GeneralSettings::class,
        'section_contact' => GeneralSettings::class,
    ];

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /**
     * Seksi yang teks judulnya bisa diubah di sini, beserta teks bawaannya.
     * Kunci tiap teks sekaligus akhiran setting-nya (`section_programs_title`)
     * dan teks bawaannya harus sama dengan default di partial Blade-nya.
     *
     * @return array<string, array{eyebrow: string, title: string, subtitle: string, title_highlight?: string}>
     */
    private static function contentSections(): array
    {
        return [
            'section_programs' => [
                'eyebrow' => 'Keunggulan Kami',
                'title' => 'Program Unggulan',
                'subtitle' => 'Berbagai program yang dirancang untuk membentuk santri berprestasi dan berakhlak mulia.',
            ],
            'section_events' => [
                'eyebrow' => 'Agenda Pesantren',
                'title' => 'Kegiatan Akan Datang',
                'subtitle' => 'Pengajian, seminar, dan berbagai kegiatan menarik yang segera diselenggarakan.',
            ],
            'section_books' => [
                'eyebrow' => 'Toko Buku',
                'title' => 'Koleksi Buku Pilihan',
                'subtitle' => 'Kitab, buku agama, dan referensi pendidikan berkualitas tersedia untuk dipesan.',
            ],
            'section_gallery' => [
                'eyebrow' => 'Foto & Video',
                'title' => 'Galeri Sekolah',
                'subtitle' => 'Momen-momen berharga dari kehidupan sekolah kami.',
            ],
            'section_blog' => [
                'eyebrow' => 'Berita & Artikel',
                'title' => 'Artikel',
                'subtitle' => 'Blog inspiratif dari berbagai sumber.',
            ],
            'section_testimonials' => [
                'eyebrow' => 'Kesan & Pesan',
                'title' => 'Apa Kata Alumni',
                'subtitle' => 'Cerita dan harapan dari para alumni yang telah menempuh pendidikan bersama kami.',
            ],
            'section_principal' => [
                'eyebrow' => 'Sambutan',
                'title' => 'Sambutan Para Tokoh',
                'subtitle' => '',
            ],
            'section_contact' => [
                'eyebrow' => 'Hubungi Kami',
                'title' => 'Kami Siap Membantu Anda',
                'subtitle' => 'Punya pertanyaan seputar SPMB, akademik, atau kegiatan sekolah? Jangan ragu untuk menghubungi kami.',
            ],
            'section_donasi' => [
                'eyebrow' => 'Program Donasi',
                'title' => 'Bersama Wujudkan',
                'title_highlight' => 'Pendidikan Berkualitas',
                'subtitle' => 'Setiap kontribusi Anda sangat berarti bagi perkembangan pendidikan santri. Donasi Anda akan digunakan untuk pengadaan sarana belajar, beasiswa, dan program-program pesantren.',
            ],
        ];
    }

    /**
     * @return array<string, string>|null
     */
    private static function contentConfig(?string $key): ?array
    {
        return self::contentSections()[$key] ?? null;
    }

    public function mount(): void
    {
        $this->form->fill([
            'sections' => array_map(
                fn (array $section): array => self::sectionItem($section['key'], $section['visible']),
                HomeSections::order(),
            ),
            'home_meta_title' => Setting::get('home_meta_title', ''),
            'home_meta_description' => Setting::get('home_meta_description', ''),
        ]);
    }

    /**
     * Satu kartu repeater: kunci & saklar tampil seksi, plus teks judulnya
     * yang sedang tayang — teks bawaan dipakai bila belum pernah diubah agar
     * admin melihat apa yang live.
     *
     * @return array<string, mixed>
     */
    private static function sectionItem(string $key, bool $visible): array
    {
        $item = ['key' => $key, 'visible' => $visible];

        foreach (self::contentConfig($key) ?? [] as $field => $default) {
            $item[$field] = Setting::get("{$key}_{$field}", $default);
        }

        return $item;
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Susunan Halaman Depan')
                ->description(fn (Get $get): string => self::arrangementSummary($get('sections') ?? []))
                ->icon(Heroicon::OutlinedQueueList)
                ->schema([
                    Repeater::make('sections')
                        ->hiddenLabel()
                        ->addable(false)
                        ->deletable(false)
                        ->reorderableWithDragAndDrop()
                        ->collapsible()
                        ->collapsed()
                        ->itemLabel(fn (array $state): ?HtmlString => self::itemLabel($state))
                        ->extraItemActions([self::restoreDefaultTextAction()])
                        ->schema(self::sectionItemSchema()),
                ]),

            Section::make('SEO Halaman Depan')
                ->description('Judul dan deskripsi meta khusus halaman depan untuk mesin pencari (Google) dan berbagi ke media sosial.')
                ->icon(Heroicon::OutlinedGlobeAlt)
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('home_meta_title')
                        ->label('Meta Title')
                        ->maxLength(70)
                        ->live(debounce: 500)
                        ->hint(fn (?string $state): string => self::lengthHint($state, 70))
                        ->hintColor(fn (?string $state): string => self::lengthHintColor($state, 50, 60))
                        ->placeholder(setting('site_name', config('app.name')).' — '.setting('site_tagline', 'Membentuk Generasi Qurani yang Berilmu dan Berakhlak'))
                        ->helperText('Judul di tab browser & hasil pencarian. Ideal 50–60 karakter. Kosongkan untuk memakai Nama Sekolah + Tagline.')
                        ->columnSpanFull(),

                    Textarea::make('home_meta_description')
                        ->label('Meta Description')
                        ->rows(3)
                        ->maxLength(160)
                        ->live(debounce: 500)
                        ->hint(fn (?string $state): string => self::lengthHint($state, 160))
                        ->hintColor(fn (?string $state): string => self::lengthHintColor($state, 150, 160))
                        ->placeholder(fn (): ?string => setting('site_description'))
                        ->helperText('Ringkasan yang tampil di hasil pencarian Google. Ideal 150–160 karakter. Kosongkan untuk memakai Deskripsi Singkat dari Pengaturan Umum.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    /**
     * Isi kartu: saklar tampil, asal isi & anchor seksi, lalu kolom teks
     * judulnya. Kolom teks memakai satu set nama untuk semua seksi —
     * placeholder dan visibilitasnya menyesuaikan kunci seksi di baris itu.
     *
     * @return array<int, mixed>
     */
    private static function sectionItemSchema(): array
    {
        $isEditable = fn (Get $get): bool => self::contentConfig($get('key')) !== null;
        $defaultText = fn (string $field): Closure => fn (Get $get): ?string => self::contentConfig($get('key'))[$field] ?? null;

        return [
            Hidden::make('key'),

            Toggle::make('visible')
                ->label('Tampilkan seksi ini di halaman depan')
                ->onColor('success')
                ->offColor('danger')
                ->live()
                ->helperText(fn (Get $get): ?HtmlString => self::featureWarning($get('key')))
                ->columnSpanFull(),

            Grid::make(2)->schema([
                TextEntry::make('content_source')
                    ->label('Isi Seksi')
                    ->state(fn (Get $get): HtmlString => self::contentSourceHint($get('key'))),

                TextEntry::make('anchor_hint')
                    ->label('ID Seksi (Anchor)')
                    ->state(fn (Get $get): HtmlString => self::anchorHint($get('key'))),
            ]),

            Grid::make(2)
                ->visible($isEditable)
                ->schema([
                    TextInput::make('eyebrow')
                        ->label('Label Kecil')
                        ->maxLength(60)
                        ->placeholder($defaultText('eyebrow'))
                        ->helperText('Teks kecil di atas judul. Kosongkan untuk menyembunyikannya.'),

                    TextInput::make('title')
                        ->label('Judul')
                        ->maxLength(120)
                        ->placeholder($defaultText('title'))
                        ->helperText('Kosongkan untuk memakai judul bawaan.'),

                    TextInput::make('title_highlight')
                        ->label('Judul — Bagian Disorot')
                        ->maxLength(120)
                        ->visible(fn (Get $get): bool => isset(self::contentConfig($get('key'))['title_highlight']))
                        ->placeholder($defaultText('title_highlight'))
                        ->helperText('Baris kedua judul dengan warna aksen. Kosongkan untuk menyembunyikannya.')
                        ->columnSpanFull(),

                    Textarea::make('subtitle')
                        ->label('Deskripsi')
                        ->rows(2)
                        ->maxLength(300)
                        ->placeholder($defaultText('subtitle'))
                        ->helperText('Kosongkan untuk menyembunyikan deskripsi.')
                        ->columnSpanFull(),
                ]),
        ];
    }

    /**
     * Tombol di kepala kartu untuk mengisi ulang teks seksi dengan teks
     * bawaannya — jalan pintas setelah kolom dikosongkan atau diubah.
     */
    private static function restoreDefaultTextAction(): Action
    {
        $itemKey = fn (array $arguments, Repeater $component): ?string => $component->getState()[$arguments['item'] ?? '']['key'] ?? null;

        return Action::make('restoreDefaultText')
            ->label('Pakai teks bawaan')
            ->tooltip('Pakai teks bawaan')
            ->icon(Heroicon::OutlinedArrowUturnLeft)
            ->iconButton()
            ->color('gray')
            ->visible(fn (array $arguments, Repeater $component): bool => self::contentConfig($itemKey($arguments, $component)) !== null)
            ->action(function (array $arguments, Repeater $component) use ($itemKey): void {
                $defaults = self::contentConfig($itemKey($arguments, $component));

                if ($defaults === null) {
                    return;
                }

                $items = $component->getState();
                $items[$arguments['item']] = [...$items[$arguments['item']], ...$defaults];
                $component->state($items);

                Notification::make()
                    ->success()
                    ->title('Teks bawaan dipasang')
                    ->body('Klik "Simpan Pengaturan" untuk menerapkannya ke halaman depan.')
                    ->send();
            });
    }

    /**
     * Ringkasan di bawah judul: berapa seksi yang benar-benar tampil,
     * memperhitungkan saklar tampil dan fitur yang sedang dimatikan.
     *
     * @param  array<array-key, array<string, mixed>>  $sections
     */
    private static function arrangementSummary(array $sections): string
    {
        $shown = collect($sections)
            ->filter(fn (array $section): bool => (bool) ($section['visible'] ?? true)
                && HomeSections::disabledFeatureLabel((string) ($section['key'] ?? '')) === null)
            ->count();

        return 'Seret kartu untuk mengubah urutan, lalu buka kartunya untuk menampilkan/menyembunyikan seksi dan mengubah teksnya. '
            ."Saat ini {$shown} dari ".count($sections).' seksi tampil di halaman depan.';
    }

    /**
     * Judul kartu: nama seksi dan anchor-nya, plus badge bila seksi itu
     * disembunyikan atau fiturnya sedang dimatikan.
     *
     * @param  array<string, mixed>  $state
     */
    private static function itemLabel(array $state): ?HtmlString
    {
        $key = $state['key'] ?? null;

        if (! is_string($key) || ! HomeSections::exists($key)) {
            return null;
        }

        $label = e(HomeSections::label($key))
            .'<span style="margin-inline-start:.5rem;font-weight:400;font-size:.8125rem;opacity:.5">#'.e(HomeSections::anchor($key)).'</span>';

        $disabledFeature = HomeSections::disabledFeatureLabel($key);

        if (! ($state['visible'] ?? true)) {
            $label .= self::badge('Disembunyikan', 'gray');
        } elseif ($disabledFeature !== null) {
            $label .= self::badge("Fitur {$disabledFeature} nonaktif", 'warning');
        }

        return new HtmlString($label);
    }

    private static function badge(string $label, string $color): string
    {
        return Blade::render(
            '<x-filament::badge :color="$color" size="sm" style="display:inline-flex;margin-inline-start:.5rem;vertical-align:middle">{{ $label }}</x-filament::badge>',
            ['label' => $label, 'color' => $color],
        );
    }

    /**
     * Peringatan di bawah saklar bila seksi ini tetap padam karena fiturnya
     * dimatikan di menu Fitur Website.
     */
    private static function featureWarning(?string $key): ?HtmlString
    {
        $feature = HomeSections::disabledFeatureLabel((string) $key);

        if ($feature === null) {
            return null;
        }

        return new HtmlString(
            '⚠️ Fitur <strong>'.e($feature).'</strong> sedang dimatikan di menu '.self::menuLink(FeatureSettings::class)
            .', jadi seksi ini tetap tidak tampil meski saklarnya menyala.'
        );
    }

    /**
     * Asal isi seksi beserta tautan ke menu pengelolanya.
     */
    private static function contentSourceHint(?string $key): HtmlString
    {
        $source = self::CONTENT_SOURCES[$key] ?? null;

        if ($source === null) {
            return new HtmlString('Isinya diambil dari menu datanya masing-masing.');
        }

        $menu = self::menuLink($source);

        return new HtmlString(self::contentConfig($key) === null
            ? "Tidak ada teks yang bisa diubah di sini — seluruh isinya diatur di menu {$menu}."
            : "Isinya diambil dari menu {$menu}; teks judulnya bisa diubah di bawah.");
    }

    /**
     * Nama menu sebagai tautan bila admin yang sedang login boleh membukanya,
     * atau teks tebal biasa bila tidak.
     *
     * @param  class-string<FilamentResource>|class-string<Page>  $target
     */
    private static function menuLink(string $target): string
    {
        $isResource = is_subclass_of($target, FilamentResource::class);
        $label = $target::getNavigationLabel();

        if (! ($isResource ? $target::canViewAny() : $target::canAccess())) {
            return '<strong>'.e($label).'</strong>';
        }

        return Blade::render(
            '<x-filament::link :href="$url">{{ $label }}</x-filament::link>',
            ['url' => $isResource ? $target::getUrl('index') : $target::getUrl(), 'label' => $label],
        );
    }

    /**
     * Chip ID anchor seksi beserta cara memakainya sebagai tujuan tautan.
     */
    private static function anchorHint(?string $key): HtmlString
    {
        $anchor = e(HomeSections::anchor((string) $key));

        // Latar & garis chip diracik dari warna teks yang berlaku, sehingga
        // tetap terbaca baik di tema terang maupun gelap.
        return new HtmlString(
            '<code style="background:color-mix(in oklab, currentColor 12%, transparent);'
            .'border:1px solid color-mix(in oklab, currentColor 20%, transparent);'
            .'border-radius:.375rem;padding:.125rem .375rem;font-weight:600">#'.$anchor.'</code>'
            .'<div style="margin-top:.35rem;font-size:.8125rem;opacity:.75">'
            ."Pakai sebagai URL menu atau tautan cepat. Dari halaman lain, tulis <code>/#{$anchor}</code>."
            .'</div>'
        );
    }

    private static function lengthHint(?string $state, int $max): string
    {
        return mb_strlen((string) $state).' / '.$max.' karakter';
    }

    /**
     * Hijau di rentang panjang ideal, kuning di luar rentang itu, abu-abu saat
     * kosong (halaman depan memakai teks cadangan).
     */
    private static function lengthHintColor(?string $state, int $idealMin, int $idealMax): string
    {
        $length = mb_strlen((string) $state);

        return match (true) {
            $length === 0 => 'gray',
            $length >= $idealMin && $length <= $idealMax => 'success',
            default => 'warning',
        };
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $payload = [
            'home_meta_title' => $data['home_meta_title'] ?? '',
            'home_meta_description' => $data['home_meta_description'] ?? '',
        ];
        $order = [];

        // array_values preserves drag-and-drop order from Repeater
        foreach (array_values($data['sections'] ?? []) as $section) {
            $key = $section['key'] ?? null;

            if (! is_string($key) || ! HomeSections::exists($key) || isset($order[$key])) {
                continue;
            }

            $order[$key] = ['key' => $key, 'visible' => (bool) ($section['visible'] ?? true)];
            $payload = [...$payload, ...self::contentPayload($key, $section)];
        }

        $payload['section_order'] = json_encode(array_values($order));

        Setting::setMany($payload);

        Notification::make()
            ->success()
            ->title('Pengaturan halaman depan disimpan')
            ->send();
    }

    /**
     * Teks satu seksi, dipetakan balik ke kunci setting datar yang dibaca
     * partialnya (`section_programs_title`, dst.). Kolom kosong disimpan
     * sebagai string kosong — bukan null — agar partial benar-benar
     * menyembunyikannya; judul yang kosong kembali ke teks bawaan.
     *
     * @param  array<string, mixed>  $section
     * @return array<string, string>
     */
    private static function contentPayload(string $key, array $section): array
    {
        $payload = [];

        foreach (array_keys(self::contentConfig($key) ?? []) as $field) {
            $payload["{$key}_{$field}"] = (string) ($section[$field] ?? '');
        }

        return $payload;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->action('save'),

            Action::make('viewHomePage')
                ->label('Lihat Halaman Depan')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(route('home'))
                ->openUrlInNewTab(),
        ];
    }
}
