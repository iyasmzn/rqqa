<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class PopularPostsWidget extends TableWidget
{
    use HasWidgetShield;

    private const LIMIT = 5;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Artikel Terpopuler')
            ->description('Artikel tayang dengan pembaca terbanyak')
            ->query(fn (): Builder => Post::query()
                ->published()
                ->where('views_count', '>', 0)
                ->popular()
                ->latest('published_at')
                ->limit(self::LIMIT)
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('title')
                    ->label('Artikel')
                    ->limit(60)
                    ->wrap()
                    ->description(fn (Post $record): ?string => $record->category),

                TextColumn::make('views_count')
                    ->label('Dibaca')
                    ->numeric(decimalPlaces: 0, thousandsSeparator: '.')
                    ->icon(Heroicon::OutlinedEye)
                    ->alignEnd(),
            ])
            ->headerActions([
                Action::make('lihat_semua')
                    ->label('Lihat Semua')
                    ->url(fn (): string => PostResource::getUrl('index', ['sort' => 'views_count:desc']))
                    ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                    ->color('gray')
                    ->size('sm')
                    ->visible(fn (): bool => PostResource::canViewAny()),
            ])
            ->recordUrl(fn (Post $record): ?string => PostResource::canEdit($record)
                ? PostResource::getUrl('edit', ['record' => $record])
                : null)
            ->recordActions([
                Action::make('buka_artikel')
                    ->label('Buka artikel')
                    ->iconButton()
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->color('gray')
                    ->url(fn (Post $record): string => route('blog.show', $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->emptyStateHeading('Belum ada data pembaca')
            ->emptyStateDescription('Jumlah pembaca tercatat otomatis saat artikel dibuka pengunjung.')
            ->emptyStateIcon(Heroicon::OutlinedEye)
            ->paginated(false);
    }
}
