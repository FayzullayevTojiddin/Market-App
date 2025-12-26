<?php

namespace App\Filament\Resources\Dealers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class DealerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make("Данные дилера")
                    ->schema([
                        TextInput::make('full_name')
                            ->label("Полное имя")
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone_number')
                            ->label("Номер телефона")
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        TextInput::make('email')
                            ->label("Электронная почта")
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),

                Textarea::make('notes')
                    ->label("Примечание")
                    ->maxLength(65535)
                    ->rows(12),
            ]);
    }
}