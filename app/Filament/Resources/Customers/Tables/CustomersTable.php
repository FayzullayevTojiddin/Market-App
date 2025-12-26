<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use App\Models\DebtTransaction;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->label("ID"),

                TextColumn::make('full_name')
                    ->label("Полное имя")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone_number')
                    ->label("Номер телефона")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('remaining_debt')
                    ->label('Оставшийся долг')
                    ->money('UZS')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label("Дата создания")
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Action::make('payDebt')
                    ->button()
                    ->label('Оплатить долг')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->remaining_debt > 0)
                    ->modalHeading('Оплата долга')
                    ->modalSubmitActionLabel('Сохранить')
                    ->form([
                        TextInput::make('amount')
                            ->label('Сумма оплаты')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make('notes')
                            ->label('Примечание')
                            ->default('Долг оплачен'),
                    ])
                    ->action(function (array $data, $record) {
                        DebtTransaction::create([
                            'customer_id' => $record->id,
                            'type'        => 'decrease',
                            'amount'      => $data['amount'],
                            'notes'       => $data['notes'] ?? null,
                        ]);
                    })
                    ->successNotificationTitle('Долг успешно оплачен'),

                EditAction::make()->button()
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}