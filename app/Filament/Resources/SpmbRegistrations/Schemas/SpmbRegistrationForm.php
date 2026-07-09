<?php

namespace App\Filament\Resources\SpmbRegistrations\Schemas;

use App\Models\SpmbRegistration;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SpmbRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Data Pribadi Calon Peserta')
                ->icon(Heroicon::OutlinedUser)
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('full_name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('phone')
                            ->label('No. HP / WhatsApp')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(100),

                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                    ]),

                    Grid::make(2)->schema([
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir')
                            ->maxLength(100),

                        Select::make('jalur')
                            ->label('Jalur Pendaftaran')
                            ->options(SpmbRegistration::jalurOptions())
                            ->required()
                            ->native(false),
                    ]),

                    Textarea::make('address')
                        ->label('Alamat Lengkap')
                        ->rows(2)
                        ->columnSpanFull(),
                ]),

            Section::make('Asal Sekolah')
                ->icon(Heroicon::OutlinedAcademicCap)
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('previous_school')
                            ->label('Nama Sekolah Asal')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('previous_school_city')
                            ->label('Kota / Kabupaten')
                            ->maxLength(100),
                    ]),
                ]),

            Section::make('Data Orang Tua / Wali')
                ->icon(Heroicon::OutlinedUsers)
                ->schema([
                    Grid::make(2)->schema([
                        TextInput::make('parent_name')
                            ->label('Nama Orang Tua / Wali')
                            ->maxLength(100),

                        TextInput::make('parent_phone')
                            ->label('No. HP Orang Tua / Wali')
                            ->tel()
                            ->maxLength(20),
                    ]),
                ]),

            Section::make('Catatan & Status')
                ->icon(Heroicon::OutlinedClipboardDocumentCheck)
                ->schema([
                    Grid::make(2)->schema([
                        Select::make('status')
                            ->label('Status Pendaftaran')
                            ->options(SpmbRegistration::statusOptions())
                            ->required()
                            ->native(false),

                        DatePicker::make('verified_at')
                            ->label('Tanggal Verifikasi')
                            ->native(false)
                            ->displayFormat('d/m/Y H:i'),
                    ]),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
