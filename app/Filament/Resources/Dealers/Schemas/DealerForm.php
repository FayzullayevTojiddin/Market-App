<?php

namespace App\Filament\Resources\Dealers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DealerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('full_name')
                    ->label("To'liq ISM")
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone_number')
                    ->label("Telefon raqami")
                    ->tel()
                    ->required()
                    ->maxLength(20),

                TextInput::make('email')
                    ->label("Elektron pochta")
                    ->email()
                    ->maxLength(255),

                Textarea::make('notes')
                    ->label("Izoh")
                    ->maxLength(65535)
                    ->rows(3),
            ]);
    }
}