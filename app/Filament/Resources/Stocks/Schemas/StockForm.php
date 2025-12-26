<?php

namespace App\Filament\Resources\Stocks\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use App\Models\Dealer;
use App\Models\Product;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\HtmlString;

class StockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('dealer_id')
                    ->label('Diler')
                    ->options(Dealer::all()->pluck('full_name', 'id'))
                    ->required()
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('full_name')
                            ->label('To\'liq ism')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->label('Telefon')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Textarea::make('notes')
                            ->label('Izoh')
                            ->rows(3),
                    ])
                    ->createOptionUsing(function (array $data) {
                        return Dealer::create($data)->id;
                    }),

                    Grid::make(3)
                        ->schema([
                            Placeholder::make('total_purchase')
                                ->label('')
                                ->content(function ($get) {
                                    $items = $get('stockProducts') ?? [];
                                    $total = collect($items)->sum(fn($item) => 
                                        (int) ($item['purchase_price'] ?? 0) * (int) ($item['count'] ?? 0)
                                    );
                                    return new HtmlString('
                                        <div style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: white;">
                                            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; opacity: 0.9; margin-bottom: 0.5rem;">
                                                Xarid summasi
                                            </div>
                                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">
                                                ' . number_format($total, 0, '.', ' ') . '
                                            </div>
                                            <div style="font-size: 0.875rem; opacity: 0.9;">
                                                UZS
                                            </div>
                                        </div>
                                    ');
                                }),
                            
                            Placeholder::make('total_selling')
                                ->label('')
                                ->content(function ($get) {
                                    $items = $get('stockProducts') ?? [];
                                    $total = collect($items)->sum(fn($item) => 
                                        (int) ($item['selling_price'] ?? 0) * (int) ($item['count'] ?? 0)
                                    );
                                    return new HtmlString('
                                        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: white;">
                                            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; opacity: 0.9; margin-bottom: 0.5rem;">
                                                Sotish summasi
                                            </div>
                                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">
                                                ' . number_format($total, 0, '.', ' ') . '
                                            </div>
                                            <div style="font-size: 0.875rem; opacity: 0.9;">
                                                UZS
                                            </div>
                                        </div>
                                    ');
                                }),
                            
                            Placeholder::make('total_profit')
                                ->label('')
                                ->content(function ($get) {
                                    $items = $get('stockProducts') ?? [];
                                    $purchase = collect($items)->sum(fn($item) => 
                                        (int) ($item['purchase_price'] ?? 0) * (int) ($item['count'] ?? 0)
                                    );
                                    $selling = collect($items)->sum(fn($item) => 
                                        (int) ($item['selling_price'] ?? 0) * (int) ($item['count'] ?? 0)
                                    );
                                    $profit = $selling - $purchase;
                                    return new HtmlString('
                                        <div style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: white;">
                                            <div style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; opacity: 0.9; margin-bottom: 0.5rem;">
                                                Foyda summasi
                                            </div>
                                            <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.25rem;">
                                                ' . number_format($profit, 0, '.', ' ') . '
                                            </div>
                                            <div style="font-size: 0.875rem; opacity: 0.9;">
                                                UZS
                                            </div>
                                        </div>
                                    ');
                                }),
                        ]),

                Textarea::make('notes')
                    ->label('Izoh')
                    ->rows(3)
                    ->columnSpanFull(),

                Repeater::make('stockProducts')
                    ->label('Mahsulotlar')
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label('Mahsulot')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nomi')
                                    ->required()
                                    ->maxLength(255),
                                
                                TextInput::make('barcode')
                                    ->label('Barcode')
                                    ->required()
                                    ->numeric()
                                    ->suffixAction(
                                        Action::make('generate')
                                            ->icon('heroicon-m-sparkles')
                                            ->action(function ($set) {
                                                do {
                                                    $barcode = str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT);
                                                } while (Product::where('barcode', $barcode)->exists());
                                                $set('barcode', $barcode);
                                            })
                                    )
                                    ->helperText('9 xonali raqam.'),

                                TextInput::make('purchase_price')
                                    ->hidden()
                                    ->numeric()
                                    ->default(0)
                                    ->dehydrated(true)
                                    ->prefix('UZS'),

                                TextInput::make('selling_price')
                                    ->hidden()
                                    ->numeric()
                                    ->default(0)
                                    ->dehydrated(true)
                                    ->prefix('UZS'),

                                TextInput::make('count')
                                    ->hidden()
                                    ->numeric()
                                    ->default(0)
                                    ->dehydrated(true),
                            ])
                            ->createOptionUsing(function (array $data) {
                                return Product::create($data)->id;
                            }),

                        TextInput::make('purchase_price')
                            ->label('Kelgan narxi')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->minValue(0)
                            ->prefix('UZS'),

                        TextInput::make('selling_price')
                            ->label('Sotish narxi')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->minValue(fn ($get) => (int) $get('purchase_price'))
                            ->rules([
                                fn ($get) => function ($attribute, $value, $fail) use ($get) {
                                    if ((int) $value < (int) $get('purchase_price')) {
                                        $fail('Sotish narxi kirish narxidan kichik bo‘lishi mumkin emas.');
                                    }
                                },
                            ])
                            ->prefix('UZS'),

                        TextInput::make('count')
                            ->label('Soni')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->default(1)
                            ->minValue(1),
                    ])
                    ->columns(4)
                    ->addActionLabel('➕ Mahsulot qo‘shish')
                    ->defaultItems(1)
                    ->live()
                    
                    ->columnSpanFull(),
            ]);
    }
}