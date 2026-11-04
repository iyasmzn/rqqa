<?php

namespace App\Filament\Resources\Ebooks;

use App\Filament\Concerns\IndicatesFeatureStatus;
use App\Filament\Resources\Ebooks\Pages\CreateEbook;
use App\Filament\Resources\Ebooks\Pages\EditEbook;
use App\Filament\Resources\Ebooks\Pages\ListEbooks;
use App\Filament\Resources\Ebooks\Schemas\EbookForm;
use App\Filament\Resources\Ebooks\Tables\EbooksTable;
use App\Models\Ebook;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EbookResource extends Resource
{
    use IndicatesFeatureStatus;

    protected static string $feature = 'ebook';

    protected static ?string $model = Ebook::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Media & Unduhan';

    protected static ?string $navigationLabel = 'E-Book';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'E-Book';

    protected static ?string $pluralModelLabel = 'E-Book';

    public static function form(Schema $schema): Schema
    {
        return EbookForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EbooksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEbooks::route('/'),
            'create' => CreateEbook::route('/create'),
            'edit' => EditEbook::route('/{record}/edit'),
        ];
    }
}
