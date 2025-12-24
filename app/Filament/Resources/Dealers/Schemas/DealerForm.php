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
                Section::make("Diler ma'lumotlari")
                    ->schema([
                        TextInput::make('full_name')
                            ->label("To'liq ism")
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone_number')
                            ->label("Telefon raqam")
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        TextInput::make('email')
                            ->label("Email manzil")
                            ->email()
                            ->required()
                            ->maxLength(255),
                    ]),

                Textarea::make('notes')
                    ->label("Izoh")
                    ->maxLength(65535)
                    ->rows(12),
            ]);
    }
}