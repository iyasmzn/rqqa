<?php

namespace App\Filament\Resources\Stats\Schemas;

use App\Filament\Support\IconUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class StatForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Isi Kartu Statistik')
                ->description('Data yang ditampilkan pada kartu statistik di halaman utama.')
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('icon')
                            ->label('Ikon (Emoji)')
                            ->required()
                            ->maxLength(20)
                            ->hint('Salin emoji dari EmojiPedia atau tekan Win + . di Windows.')
                            ->placeholder('🏆'),

                        TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->hint('Angka kecil tampil lebih dulu.'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('value')
                            ->label('Angka / Nilai')
                            ->required()
                            ->maxLength(50)
                            ->placeholder('200+'),

                        TextInput::make('label')
                            ->label('Label Utama')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('Prestasi'),
                    ]),

                    TextInput::make('sub')
                        ->label('Keterangan Tambahan')
                        ->maxLength(150)
                        ->placeholder('Tingkat nasional')
                        ->columnSpanFull(),

                    TextInput::make('url')
                        ->label('Tautan (URL / Path)')
                        ->maxLength(500)
                        ->placeholder('https://contoh.sch.id/prestasi atau /prestasi')
                        ->hint('Opsional — kartu bisa diklik dan mengarah ke tautan ini.')
                        ->live(onBlur: true)
                        ->columnSpanFull(),

                    Toggle::make('open_in_new_tab')
                        ->label('Buka di Tab Baru')
                        ->default(false)
                        ->visible(fn (Get $get): bool => filled($get('url')))
                        ->columnSpanFull(),

                    IconUpload::make()
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
