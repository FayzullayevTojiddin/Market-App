<?php

namespace App\Filament\Resources\Dealers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;

class DealersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label("ID")
                    ->searchable(),
                    
                TextColumn::make('full_name')
                    ->label("Полное имя")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone_number')
                    ->label("Номер телефона")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label("Электронная почта")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('stocks_count')
                    ->label("Количество продаж")
                    ->counts('stocks')
                    ->sortable(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}