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
                    ->label("Покупатель"),

                TextColumn::make('debt')
                    ->label("Долг"),

                TextColumn::make('cash')
                    ->label("Наличные"),
                
                TextColumn::make('card')
                    ->label("Карта"),

                TextColumn::make('created_at')
                    ->label("Время продажи")
                    ->dateTime(),
                    
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label("Просмотр")->button()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}