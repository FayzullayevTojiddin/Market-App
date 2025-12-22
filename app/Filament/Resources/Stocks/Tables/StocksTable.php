<?php

namespace App\Filament\Resources\Stocks\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

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
                    ->label('Diler')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Umumiy summa')
                    ->money('UZS')
                    ->getStateUsing(function ($record) {
                        return $record->stockProducts->sum(function ($item) {
                            return $item->purchase_price * $item->count;
                        });
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Yaratilgan')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}