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
                    ->label('Клиент')
                    ->relationship('customer', 'full_name')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        TextInput::make('full_name')
                            ->label('Полное имя')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->label('Номер телефона')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                    ])
                    ->createOptionModalHeading('Добавить нового клиента'),

                TextInput::make('status')
                    ->label('Статус')
                    ->default('new')
                    ->readOnly()
                    ->required(),

                Repeater::make('products')
                    ->relationship()
                    ->label('Продукты')
                    ->schema([
                        Select::make('product_id')
                            ->label('Продукт')
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
                            ->label('Число')
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                                self::recalculatePrice($get, $set)
                            ),

                        TextInput::make('discount')
                            ->label('Скидка (%)')
                            ->numeric()
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $get, callable $set) =>
                                self::recalculatePrice($get, $set)
                            ),

                        TextInput::make('price_summ')
                            ->label('Общая сумма')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(4)
                    ->defaultItems(1)
                    ->addActionLabel('Добавить товар')
                    ->columnSpanFull()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        self::updateTotalAmount($get, $set);
                    }),

                Section::make('Платежная информация')
                    ->schema([
                        TextInput::make('total_amount')
                            ->label('Общая сумма')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->suffix('сум')
                            ->extraAttributes(['class' => 'font-bold text-lg']),

                        TextInput::make('cash')
                            ->label('Наличные')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 500)
                            ->suffix('сум')
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $totalAmount = (float) ($get('total_amount') ?? 0);
                                $cash = (float) ($state ?? 0);
                                $card = (float) ($get('card') ?? 0);
                                
                                $debt = $totalAmount - ($cash + $card);
                                $debt = max(0, $debt);
                                
                                $set('debt', round($debt, 2));
                            }),

                        TextInput::make('card')
                            ->label('По карте')
                            ->numeric()
                            ->default(0)
                            ->live(debounce: 500)
                            ->suffix('сум')
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $totalAmount = (float) ($get('total_amount') ?? 0);
                                $cash = (float) ($get('cash') ?? 0);
                                $card = (float) ($state ?? 0);
                                
                                $debt = $totalAmount - ($cash + $card);
                                $debt = max(0, $debt);
                                
                                $set('debt', round($debt, 2));
                            }),

                        TextInput::make('debt')
                            ->label('Долг')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->suffix('сум')
                            ->helperText('Задолженность рассчитывается автоматически.')
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