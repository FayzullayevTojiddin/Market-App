<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Mijoz Ma\'lumotlari')
                    ->description('Mijozning asosiy ma\'lumotlarini kiriting')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('To\'liq Ism')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Masalan: Ahmadov Sardor')
                            ->columnSpanFull(),

                        TextInput::make('phone_number')
                            ->label('Telefon Raqami')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('90 123 45 67')
                            ->prefix('+998')
                            ->columnSpanFull(),
                    ])
                    ->columns(1),
            ]);
    }
}