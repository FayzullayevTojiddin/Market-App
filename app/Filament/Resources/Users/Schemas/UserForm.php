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
                    ->label("Имя пользователя")
                    ->string(),

                TextInput::make('email')
                    ->label(label: "Электронная почта")
                    ->email(),

                TextInput::make('password')
                    ->label("Пароль")
                    ->password(),

                Select::make('role')
                    ->label("Роль")
                    ->options([
                        'admin' => "Admin",
                        'super' => "Super Admin"
                    ]),
            ]);
    }
}
