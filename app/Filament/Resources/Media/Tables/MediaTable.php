<?php

namespace App\Filament\Resources\Media\Tables;

use App\Models\Media;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    // Thumbnail
                    ImageColumn::make('path')
                        ->label('')
                        ->disk('public')
                        ->height(160)
                        ->width('100%')
                        ->extraAttributes([
                            'style' => 'width:100%;height:160px;display:block;',
                        ])
                        ->extraImgAttributes([
                            'style' => 'width:100%;height:100%;object-fit:cover;display:block;',
                        ])
                        ->defaultImageUrl(fn (Media $record): string => $record->is_image
                            ? ''
                            : 'data:image/svg+xml,'.rawurlencode(
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80" fill="#d97706">'
                                .'<rect width="80" height="80" rx="8" fill="#fffbeb"/>'
                                .'<text x="40" y="50" font-size="32" text-anchor="middle">📄</text>'
                                .'</svg>'
                            )
                        ),

                    // Meta below thumbnail
                    Stack::make([
                        TextColumn::make('name')
                            ->weight(FontWeight::SemiBold)
                            ->size(TextSize::Small)
                            ->limit(26)
                            ->searchable()
                            ->extraAttributes(['style' => 'overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:100%;display:block;']),

                        TextColumn::make('size_formatted')
                            ->label('Ukuran')
                            ->color('gray')
                            ->size(TextSize::ExtraSmall),

                        TextColumn::make('mime_type')
                            ->label('Tipe')
                            ->color('gray')
                            ->size(TextSize::ExtraSmall)
                            ->limit(30),
                    ])
                        ->space(1)
                        ->extraAttributes(['class' => 'px-3 py-2.5 min-w-0 overflow-hidden']),
                ])
                    ->extraAttributes(['class' => 'overflow-hidden']),
            ])
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'xl' => 4,
                '2xl' => 5,
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([12, 24, 48, 96])
            ->searchable()
            ->filters([
                TernaryFilter::make('show_in_gallery')
                    ->label('Status Galeri')
                    ->trueLabel('Ditampilkan di Galeri')
                    ->falseLabel('Tidak di Galeri'),

                SelectFilter::make('type')
                    ->label('Tipe File')
                    ->options([
                        'image' => '🖼️  Gambar',
                        'pdf' => '📄  PDF',
                        'other' => '📎  Lainnya',
                    ])
                    ->query(function ($query, array $data): void {
                        if (blank($data['value'])) {
                            return;
                        }
                        match ($data['value']) {
                            'image' => $query->where('mime_type', 'like', 'image/%'),
                            'pdf' => $query->where('mime_type', 'application/pdf'),
                            'other' => $query->where('mime_type', 'not like', 'image/%')
                                ->where('mime_type', '!=', 'application/pdf'),
                        };
                    }),
            ])
            ->recordActions([
                Action::make('copy_url')
                    ->label('Salin URL')
                    ->icon(Heroicon::OutlinedClipboard)
                    ->color('gray')
                    ->action(function (Media $record): void {
                        // URL is returned to the frontend via a notification containing it
                    })
                    ->successNotification(null)
                    ->after(fn (Media $record) => Notification::make()
                        ->title('URL disalin ke clipboard')
                        ->body($record->url)
                        ->success()
                        ->send()
                    ),

                DeleteAction::make()
                    ->after(function (Media $record): void {
                        Storage::disk($record->disk)->delete($record->path);
                    }),
            ])
            ->bulkActions([
                BulkAction::make('delete_selected')
                    ->label('Hapus Terpilih')
                    ->icon(Heroicon::OutlinedTrash)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        foreach ($records as $record) {
                            Storage::disk($record->disk)->delete($record->path);
                            $record->delete();
                        }

                        Notification::make()
                            ->success()
                            ->title('File berhasil dihapus')
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }
}
