<?php

namespace App\Filament\Resources\Ebooks\Pages;

use App\Filament\Concerns\InteractsWithImagePicker;
use App\Filament\Resources\Ebooks\EbookResource;
use App\Filament\Resources\Ebooks\Schemas\EbookForm;
use Filament\Resources\Pages\CreateRecord;

class CreateEbook extends CreateRecord
{
    use InteractsWithImagePicker;

    protected static string $resource = EbookResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return EbookForm::prepareSourceData(self::applyImagePickers($data, ['cover_image']));
    }
}
