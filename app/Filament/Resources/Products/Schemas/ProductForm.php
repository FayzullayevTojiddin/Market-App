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
                    ->label("Nomi")
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('barcode')
                    ->label('Barcode')
                    ->required()
                    ->maxLength(9)
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
                    ->helperText('9 xonali raqam. Faqat yaratishda generatsiya qilinadi.'),

                TextInput::make('purchase_price')
                    ->label("Kelgan narxi")
                    ->numeric()
                    ->readOnly()
                    ->default(0)
                    ->prefix('UZS'),

                TextInput::make('selling_price')
                    ->label("Sotuv narxi")
                    ->numeric()
                    ->readOnly()
                    ->default(0)
                    ->prefix('UZS'),
            ]);
    }
}