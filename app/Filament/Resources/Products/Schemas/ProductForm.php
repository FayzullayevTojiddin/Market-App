<?php

namespace App\Filament\Resources\Products\Schemas;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use App\Models\Product;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label("Имя")
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('barcode')
                    ->label('Штрих-код')
                    ->required()
                    ->numeric()
                    ->disabled(fn (string $operation) => $operation === 'edit')
                    ->suffixAction(
                        fn (string $operation) => $operation === 'create'
                            ? Action::make('generate')
                                ->icon('heroicon-m-sparkles')
                                ->action(function ($set) {
                                    do {
                                        $barcode = str_pad(
                                            mt_rand(1, 999999999),
                                            9,
                                            '0',
                                            STR_PAD_LEFT
                                        );
                                    } while (\App\Models\Product::where('barcode', $barcode)->exists());

                                    $set('barcode', $barcode);
                                })
                            : null
                    )
                    ->helperText('9-значное число. Генерируется только при создании.'),

                TextInput::make('purchase_price')
                    ->label("Цена при поступлении")
                    ->numeric()
                    ->readOnly()
                    ->default(0)
                    ->prefix('сум'),

                TextInput::make('selling_price')
                    ->label("Цена продажи")
                    ->numeric()
                    ->readOnly()
                    ->default(0)
                    ->prefix('сум'),
            ]);
    }
}