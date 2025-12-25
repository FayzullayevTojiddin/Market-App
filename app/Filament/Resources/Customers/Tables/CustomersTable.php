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
                    ->label("To'liq ISM")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone_number')
                    ->label("Telefon raqami")
                    ->searchable()
                    ->sortable(),

                TextColumn::make('remaining_debt')
                    ->label('Qolgan qarz')
                    ->money('UZS')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label("Yaratilgan vaqti")
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                EditAction::make()->button(),
                Action::make('payDebt')
                    ->button()
                    ->label('Qarz to‘lash')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->visible(fn ($record) => $record->remaining_debt > 0)
                    ->modalHeading('Qarz to‘lash')
                    ->modalSubmitActionLabel('Saqlash')
                    ->form([
                        TextInput::make('amount')
                            ->label('To‘lanayotgan summa')
                            ->numeric()
                            ->required()
                            ->minValue(1),

                        TextInput::make('notes')
                            ->label('Izoh')
                            ->default('Qarz to‘landi'),
                    ])
                    ->action(function (array $data, $record) {
                        DebtTransaction::create([
                            'customer_id' => $record->id,
                            'type'        => 'decrease',
                            'amount'      => $data['amount'],
                            'notes'       => $data['notes'] ?? null,
                        ]);
                    })
                    ->successNotificationTitle('Qarz muvaffaqiyatli to‘landi'),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}