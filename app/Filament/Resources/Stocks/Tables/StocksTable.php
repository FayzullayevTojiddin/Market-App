<?php

namespace App\Filament\Resources\Stocks\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class StocksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                    
                TextColumn::make('dealer.full_name')
                    ->label('Название дилера')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Общая сумма')
                    ->money('UZS')
                    ->getStateUsing(function ($record) {
                        return $record->stockProducts->sum(function ($item) {
                            return $item->purchase_price * $item->count;
                        });
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make()->label("Просмотр")->button()
            ])
            ->bulkActions([
                //
            ]);
    }
}