<?php

namespace App\Filament\Resources\Ebooks\Tables;

use App\Models\Category;
use App\Models\Ebook;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class EbooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Sampul')
                    ->disk('public')
                    ->height(64)
                    ->width(48),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn (Ebook $record): ?string => $record->author),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('primary')
                    ->toggleable(),

                TextColumn::make('source_type')
                    ->label('Sumber Isi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Ebook::sourceTypeOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        Ebook::SOURCE_LINK => 'info',
                        Ebook::SOURCE_EMBED => 'warning',
                        default => 'success',
                    }),

                // ToggleColumn tidak memeriksa policy sendiri — kunci bagi yang tak boleh mengubah.
                ToggleColumn::make('is_featured')
                    ->label('Unggulan')
                    ->disabled(fn (Ebook $record): bool => ! (auth()->user()?->can('update', $record) ?? false)),

                IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),

                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options(fn () => Category::optionsForType(Category::TYPE_EBOOK)),

                SelectFilter::make('source_type')
                    ->label('Sumber Isi')
                    ->options(Ebook::sourceTypeOptions()),

                TernaryFilter::make('is_featured')
                    ->label('Unggulan'),

                TernaryFilter::make('is_published')
                    ->label('Status Publik'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
