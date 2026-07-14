<?php

namespace App\Filament\Resources\Comments\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Komentar Pengguna')
                    ->schema([
                        Placeholder::make('user.name')
                            ->label('Pengguna')
                            ->content(fn ($record) => $record?->user?->name ?? '—'),

                        Placeholder::make('post.title')
                            ->label('Artikel')
                            ->content(fn ($record) => $record?->post?->title ?? '—'),

                        Placeholder::make('body')
                            ->label('Isi Komentar')
                            ->content(fn ($record) => $record?->body)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Balasan Admin')
                    ->schema([
                        Textarea::make('admin_reply')
                            ->label('Balasan')
                            ->rows(5)
                            ->columnSpanFull(),

                        Toggle::make('is_approved')
                            ->label('Tampilkan ke Publik')
                            ->default(true)
                            ->helperText('Nonaktifkan untuk menyembunyikan komentar ini dari halaman artikel.'),
                    ]),
            ]);
    }
}
