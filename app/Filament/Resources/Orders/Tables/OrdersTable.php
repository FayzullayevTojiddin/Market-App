<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label("ID")
                    ->searchable(),

                TextColumn::make('customer.full_name')
                    ->label("Xarid qiluvchi"),

                TextColumn::make('debt')
                    ->label("Qarz"),

                TextColumn::make('cash')
                    ->label("Naqd"),
                
                TextColumn::make('card')
                    ->label("Karta"),

                TextColumn::make('created_at')
                    ->label("Savdo Vaqti")
                    ->dateTime(),
                    
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label("Ko'rish")->button()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
