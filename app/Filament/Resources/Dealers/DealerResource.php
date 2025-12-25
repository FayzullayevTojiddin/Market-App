<?php

namespace App\Filament\Resources\Dealers;

use App\Filament\Resources\Dealers\Pages\EditDealer;
use App\Filament\Resources\Dealers\Pages\ListDealers;
use App\Filament\Resources\Dealers\Schemas\DealerForm;
use App\Filament\Resources\Dealers\Tables\DealersTable;
use App\Models\Dealer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Dealers\RelationManagers\StocksRelationManager;

class DealerResource extends Resource
{
    protected static ?string $model = Dealer::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Dilerlar';

    protected static ?string $modelLabel = 'Diler';

    protected static ?string $pluralModelLabel = 'Dilerlar';

    public static function getNavigationGroup(): ?string
    {
        return "Muloqotlar";
    }

    public static function form(Schema $schema): Schema
    {
        return DealerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DealersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            // StocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDealers::route('/'),
            // 'edit'  => EditDealer::route('/{record}/edit'),
        ];
    }
}