<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label("ID"),

                TextColumn::make('name')
                    ->label("Имя"),

                TextColumn::make('role')
                    ->label('Роль')
                    ->color(fn ($record) => match($record->role) {
                        'admin' => 'success',
                        'super' => 'danger',
                        default => 'secondary',
                    }),

                TextColumn::make('email')
                    ->label("Электронная почта"),

                TextColumn::make('created_at')
                    ->label("Дата добавления")
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
