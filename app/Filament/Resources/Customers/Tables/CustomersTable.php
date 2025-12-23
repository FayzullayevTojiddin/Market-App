<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->label("ID"),

                TextColumn::make('full_name')
                    ->label("To'liq ISM")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone_number')
                    ->label("Telefon raqami")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('remaining_debt')
                    ->label('Qolgan qarz')
                    ->money('UZS')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label("Yaratilgan vaqti")
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()->button(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}