<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use App\Models\Product;
use App\Models\Customer;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Mijoz')
                    ->relationship('customer', 'full_name')
                    ->searchable()
                    ->required()

                    // ➕ Yangi mijozni shu yerda yaratish
                    ->createOptionForm([
                        TextInput::make('full_name')
                            ->label('To‘liq ismi')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->label('Telefon raqami')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])

                    // 🪟 Modal sarlavhasi (BOR va ISHLAYDI)
                    ->createOptionModalHeading('Yangi mijoz qo‘shish'),

                TextInput::make('status')
                    ->label('Holati')
                    ->default('new')
                    ->readOnly()
                    ->required(),

                Repeater::make('products')
                    ->relationship()
                    ->label('Mahsulotlar')
                    ->schema([
                        Select::make('product_id')
                            ->label('Mahsulot')
                            ->options(
                                Product::query()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $product = Product::find($state);

                                if ($product) {
                                    $set('price_summ', $product->selling_price);
                                }
                            }),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                                self::recalculatePrice($get, $set)
                            ),

                        TextInput::make('discount')
                            ->label('Chegirma (%)')
                            ->numeric()
                            ->default(0)
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                                self::recalculatePrice($get, $set)
                            ),

                        TextInput::make('price_summ')
                            ->label('Jami summa')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(4)
                    ->defaultItems(1)
                    ->addActionLabel('Mahsulot qo‘shish')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }

    protected static function recalculatePrice(callable $get, callable $set): void
    {
        $productId = $get('product_id');
        $count     = (int) ($get('count') ?? 1);
        $discount  = (float) ($get('discount') ?? 0);

        $product = Product::find($productId);

        if (! $product) {
            return;
        }

        $price = $product->selling_price * $count;
        $price -= ($price * $discount / 100);

        $set('price_summ', round($price, 2));
    }
}