<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                    ->createOptionForm([
                        TextInput::make('full_name')
                            ->label('To\'liq ismi')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->label('Telefon raqami')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])
                    ->createOptionModalHeading('Yangi mijoz qo\'shish'),

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
                            ->searchable()
                            ->required()
                            ->getSearchResultsUsing(function (string $search) {
                                return Product::query()
                                    ->where('name', 'like', "%{$search}%")
                                    ->orWhere('barcode', 'like', "%{$search}%")
                                    ->limit(20)
                                    ->pluck('name', 'id');
                            })
                            ->getOptionLabelUsing(fn ($value) => Product::find($value)?->name)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $product = Product::find($state);

                                if (! $product) {
                                    return;
                                }

                                $count = (int) ($get('count') ?? 1);
                                $discount = (float) ($get('discount') ?? 0);

                                $price = $product->selling_price * $count;
                                $price -= ($price * $discount / 100);

                                $set('price_summ', round($price, 2));
                            }),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                                self::recalculatePrice($get, $set)
                            ),

                        TextInput::make('discount')
                            ->label('Chegirma (%)')
                            ->numeric()
                            ->default(0)
                            ->live(onBlur: true)
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
                    ->addActionLabel('Mahsulot qo\'shish')
                    ->columnSpanFull()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        self::updateTotalAmount($get, $set);
                    }),

                Section::make('To\'lov ma\'lumotlari')
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Umumiy summa')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->suffix('so\'m')
                            ->extraAttributes(['class' => 'font-bold text-lg']),

                        TextInput::make('cash')
                            ->label('Naqd pul')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 500)
                            ->suffix('so\'m')
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $totalAmount = (float) ($get('total_amount') ?? 0);
                                $cash = (float) ($state ?? 0);
                                $card = (float) ($get('card') ?? 0);
                                
                                $debt = $totalAmount - ($cash + $card);
                                $debt = max(0, $debt);
                                
                                $set('debt', round($debt, 2));
                            }),

                        TextInput::make('card')
                            ->label('Karta orqali')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 500)
                            ->suffix('so\'m')
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $totalAmount = (float) ($get('total_amount') ?? 0);
                                $cash = (float) ($get('cash') ?? 0);
                                $card = (float) ($state ?? 0);
                                
                                $debt = $totalAmount - ($cash + $card);
                                $debt = max(0, $debt);
                                
                                $set('debt', round($debt, 2));
                            }),

                        TextInput::make('debt')
                            ->label('Qarz')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->suffix('so\'m')
                            ->helperText('Qarz avtomatik hisoblanadi')
                            ->extraAttributes(['class' => 'font-bold text-lg']),
                    ])
                    ->columns(4)
                    ->columnSpanFull()
                    ->collapsible(),
            ]);
    }

    protected static function recalculatePrice(callable $get, callable $set): void
    {
        $productId = $get('product_id');
        $count     = (int) ($get('count') ?? 1);
        $discount  = (float) ($get('discount') ?? 0);

        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        $price = $product->selling_price * $count;
        $price -= ($price * $discount / 100);

        $set('price_summ', round($price, 2));
        
        self::updateTotalAmount($get, $set);
    }

    protected static function updateTotalAmount(callable $get, callable $set): void
    {
        $products = $get('../../products') ?? [];
        $total = 0;

        foreach ($products as $product) {
            if (isset($product['price_summ']) && is_numeric($product['price_summ'])) {
                $total += (float) $product['price_summ'];
            }
        }

        $set('../../total_amount', round($total, 2));
        
        $totalAmount = round($total, 2);
        $cash = (float) ($get('../../cash') ?? 0);
        $card = (float) ($get('../../card') ?? 0);
        
        $debt = $totalAmount - ($cash + $card);
        $debt = max(0, $debt);
        
        $set('../../debt', round($debt, 2));
    }
}