<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Models\StaticPage;
use App\Services\MediaLibraryService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class PrincipalSettings extends Page
{
    protected string $view = 'filament.pages.general-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Kepala Sekolah';

    protected static ?string $title = 'Pengaturan Seksi Kepala Sekolah';

    protected static ?int $navigationSort = 4;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'principal_name' => Setting::get('principal_name'),
            'principal_nip' => Setting::get('principal_nip'),
            'principal_title' => Setting::get('principal_title', 'Kepala Sekolah'),
            'principal_photo' => Setting::get('principal_photo'),
            'principal_excerpt' => Setting::get('principal_excerpt'),
            'principal_page' => Setting::get('principal_page', 'sambutan-kepala-sekolah'),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Profil Kepala Sekolah')
                ->description('Data ini ditampilkan pada section Kepala Sekolah di halaman depan website.')
                ->icon(Heroicon::OutlinedUserCircle)
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('principal_name')
                            ->label('Nama Lengkap')
                            ->maxLength(150)
                            ->placeholder('Drs. Ahmad Fauzi, M.Pd.')
                            ->required(),

                        TextInput::make('principal_nip')
                            ->label('NIP')
                            ->maxLength(30)
                            ->placeholder('197601012005011001'),
                    ]),

                    TextInput::make('principal_title')
                        ->label('Jabatan')
                        ->maxLength(100)
                        ->default('Kepala Sekolah')
                        ->placeholder('Kepala Sekolah')
                        ->columnSpanFull(),

                    FileUpload::make('principal_photo')
                        ->label('Foto Kepala Sekolah')
                        ->image()
                        ->disk('public')
                        ->directory('principal')
                        ->visibility('public')
                        ->automaticallyResizeImagesToWidth('600')
                        ->automaticallyResizeImagesToHeight('700')
                        ->hint('Foto formal, rasio portrait 3:4 disarankan.')
                        ->columnSpanFull(),
                ]),

            Section::make('Tampilan di Halaman Depan')
                ->description('Kutipan singkat yang muncul di homepage, bukan konten lengkap sambutan.')
                ->icon(Heroicon::OutlinedHome)
                ->schema([
                    Textarea::make('principal_excerpt')
                        ->label('Kutipan / Sambutan Singkat')
                        ->rows(4)
                        ->maxLength(500)
                        ->hint('Maks 500 karakter. Tampil di homepage sebagai preview sambutan.')
                        ->placeholder('Kami berkomitmen memberikan pendidikan terbaik untuk mencetak generasi yang beriman, berilmu, dan berdaya saing...')
                        ->columnSpanFull(),

                    Select::make('principal_page')
                        ->label('Halaman Sambutan Lengkap')
                        ->options(fn () => StaticPage::active()->ordered()->pluck('title', 'slug'))
                        ->native(false)
                        ->searchable()
                        ->hint('Tombol "Baca Selengkapnya" di homepage akan mengarah ke halaman ini.')
                        ->placeholder('Pilih halaman statis...')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::setMany($data);

        if (! blank($data['principal_photo'] ?? null)) {
            app(MediaLibraryService::class)->sync($data['principal_photo']);
        }

        Notification::make()
            ->success()
            ->title('Pengaturan kepala sekolah berhasil disimpan')
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
