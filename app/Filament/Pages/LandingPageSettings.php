<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class LandingPageSettings extends Page
{
    protected string $view = 'filament.pages.general-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Halaman Depan';

    protected static ?string $title = 'Pengaturan Halaman Depan';

    protected static ?int $navigationSort = 3;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    /** @return array<int, array{key: string, label: string, visible: bool}> */
    private static function defaultSections(): array
    {
        return [
            ['key' => 'section_hero',        'label' => '🖼️  Hero Image Slider',        'visible' => true],
            ['key' => 'section_quick_links', 'label' => '🔗  Tautan Cepat',              'visible' => true],
            ['key' => 'section_spmb',        'label' => '📋  Kartu SPMB',               'visible' => true],
            ['key' => 'section_stats',       'label' => '📊  Statistik Sekolah',         'visible' => true],
            ['key' => 'section_principal',   'label' => '👨‍💼  Sambutan Para Tokoh',     'visible' => true],
            ['key' => 'section_spmb_steps',  'label' => '📝  Tahapan SPMB',             'visible' => true],
            ['key' => 'section_activities',  'label' => '⚽  Kegiatan & Ekskul',         'visible' => true],
            ['key' => 'section_gallery',     'label' => '🖼️  Galeri Foto',              'visible' => true],
            ['key' => 'section_blog',        'label' => '📰  Blog & Berita',             'visible' => true],
            ['key' => 'section_contact',     'label' => '📞  Kontak Kami',               'visible' => true],
        ];
    }

    public function mount(): void
    {
        $saved = Setting::get('section_order');
        $sections = $saved
            ? (json_decode($saved, true) ?: self::defaultSections())
            : self::defaultSections();

        // Ensure label is always present (in case old data lacked it)
        $labelMap = collect(self::defaultSections())->keyBy('key');
        $sections = array_map(function (array $section) use ($labelMap): array {
            $section['label'] = $labelMap->get($section['key'])['label'] ?? $section['key'];

            return $section;
        }, $sections);

        $this->form->fill(['sections' => $sections]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Urutan & Visibilitas Seksi')
                ->description('Drag dan drop untuk mengatur urutan tampilan. Aktifkan atau nonaktifkan setiap seksi.')
                ->icon(Heroicon::OutlinedQueueList)
                ->schema([
                    Repeater::make('sections')
                        ->label('')
                        ->addable(false)
                        ->deletable(false)
                        ->reorderableWithDragAndDrop(true)
                        ->schema([
                            TextInput::make('key')
                                ->hiddenLabel()
                                ->disabled()
                                ->dehydrated(true)
                                ->extraInputAttributes(['class' => 'hidden'])
                                ->columnSpan(0),

                            TextInput::make('label')
                                ->label('Seksi')
                                ->disabled()
                                ->dehydrated(false)
                                ->columnSpan(3),

                            Toggle::make('visible')
                                ->label('Tampilkan')
                                ->onColor('success')
                                ->columnSpan(1),
                        ])
                        ->columns(4),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // array_values preserves drag-and-drop order from Repeater
        $sections = array_values($data['sections'] ?? []);

        Setting::set('section_order', json_encode($sections));

        Notification::make()
            ->success()
            ->title('Urutan dan visibilitas seksi disimpan')
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan')
                ->icon(Heroicon::OutlinedCheckCircle)
                ->action('save'),
        ];
    }
}
