<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label("Foydalanuvchi nomi")
                    ->string(),

                TextInput::make('email')
                    ->label("Email")
                    ->email(),

                TextInput::make('password')
                    ->label("Parol")
                    ->password(),

                Select::make('role')
                    ->label("Roli")
                    ->options([
                        'admin' => "Admin",
                        'super' => "Super Admin"
                    ]),
            ]);
    }
}
