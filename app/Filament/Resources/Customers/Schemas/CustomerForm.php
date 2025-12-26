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
                Section::make('Данные клиента')
                    ->description('Введите основные данные клиента')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Полное имя')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Например: Ахмадов Сардор'),

                        TextInput::make('phone_number')
                            ->label('Номер телефона')
                            ->tel()
                            ->required()
                            ->maxLength(20)
                            ->placeholder('90 123 45 67')
                            ->prefix('+998'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}