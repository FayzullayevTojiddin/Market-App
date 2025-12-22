<?php

namespace App\Filament\Resources\Stocks\Pages;

use App\Filament\Resources\Stocks\StockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStock extends CreateRecord
{
    protected static string $resource = StockResource::class;

    protected function afterCreate(): void
    {
        $stock = $this->record;
        $stock->stockProducts()->with('product')->get()->each(function ($stockProduct) {

            if ($stockProduct->product) {
                $stockProduct->product->update([
                    'purchase_price' => $stockProduct->purchase_price,
                    'selling_price'  => $stockProduct->selling_price,
                ]);
            }

        });
    }
}