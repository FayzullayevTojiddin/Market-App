<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label("Название")
                    ->searchable()
                    ->limit(40)
                    ->sortable(),

                TextColumn::make('barcode')
                    ->label("Штрихкод")
                    ->searchable(),

                TextColumn::make('purchase_price')
                    ->label("Закупочная цена")
                    ->disabled()
                    ->money('UZS')
                    ->sortable(),

                TextColumn::make('selling_price')
                    ->label("Цена продажи")
                    ->disabled()
                    ->money('UZS')
                    ->sortable(),

                TextColumn::make('count')
                    ->label("На складе")
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label("Дата создания")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}