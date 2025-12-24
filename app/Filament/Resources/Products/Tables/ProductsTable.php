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
                    ->label("Nomi")
                    ->searchable()
                    ->limit(40)
                    ->sortable(),

                TextColumn::make('barcode')
                    ->label("Barcode")
                    ->searchable(),

                TextColumn::make('purchase_price')
                    ->label("Kelgan narxi")
                    ->disabled()
                    ->money('UZS')
                    ->sortable(),

                TextColumn::make('selling_price')
                    ->label("Sotish narxi")
                    ->disabled()
                    ->money('UZS')
                    ->sortable(),

                TextColumn::make('count')
                    ->label("Omborda")
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label("Yaratilgan vaqti")
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->label("Tahrirlash")
                    ->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}