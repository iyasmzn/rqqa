<?php

namespace App\Filament\Resources\Ebooks\Pages;

use App\Filament\Concerns\InteractsWithImagePicker;
use App\Filament\Resources\Ebooks\EbookResource;
use App\Filament\Resources\Ebooks\Schemas\EbookForm;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditEbook extends EditRecord
{
    use InteractsWithImagePicker;

    protected static string $resource = EbookResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return EbookForm::prepareSourceData(self::applyImagePickers($data, ['cover_image']));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewPublic')
                ->label('Lihat Halaman')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url(fn (): string => route('ebooks.show', $this->getRecord()))
                ->openUrlInNewTab()
                ->visible(fn (): bool => $this->getRecord()->is_published && feature_enabled('ebook')),

            DeleteAction::make(),
        ];
    }
}
