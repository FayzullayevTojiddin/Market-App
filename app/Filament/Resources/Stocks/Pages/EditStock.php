<?php

namespace App\Filament\Resources\Stocks\Pages;

use App\Filament\Resources\Stocks\StockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStock extends EditRecord
{
    protected static string $resource = StockResource::class;

    public static function canDelete(): bool
    {
        return false;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
