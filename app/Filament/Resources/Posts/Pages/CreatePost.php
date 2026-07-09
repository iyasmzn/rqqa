<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Concerns\InteractsWithImagePicker;
use App\Filament\Resources\Posts\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    use InteractsWithImagePicker;

    protected static string $resource = PostResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return self::applyImagePickers($data, ['image']);
    }
}
