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
                    ->label("To'liq ISM")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone_number')
                    ->label("Telefon raqami")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label("Elektron pochta")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label("Yaratilgan vaqti")
                    ->dateTime()
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