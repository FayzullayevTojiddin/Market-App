<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\DebtTransaction;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $order = $this->record;

        if ($order->debt > 0) {
            $transaction = DebtTransaction::create([
                'customer_id' => $order->customer_id,
                'type'        => 'increase',
                'amount'      => $order->debt,
                'notes'       => 'Savdo bo‘yicha qarz',
            ]);
        }
    }
}
