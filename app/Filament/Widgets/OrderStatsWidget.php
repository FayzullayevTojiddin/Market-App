<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\DatePicker;
use App\Models\Order;
use Carbon\Carbon;

class OrderStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Статистика';

    public $date;

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date')
                ->reactive()
                ->label('Выберите дату')
                ->default(now())
        ];
    }

    protected function getStats(): array
    {
        $date = $this->date ? Carbon::parse($this->date) : Carbon::today();

        $orders = Order::whereDate('created_at', $date)->get();

        $totalOrders = $orders->count();
        $totalAmount = $orders->sum(fn($o) => $o->cash + $o->card + $o->debt);
        $totalCash   = $orders->sum('cash');
        $totalCard   = $orders->sum('card');
        $totalDebt   = $orders->sum('debt');

        return [
            Stat::make('Количество заказов', $totalOrders)
                ->color('primary')
                ->description('Общее количество заказов'),

            Stat::make('Общая сумма продаж', $totalAmount)
                ->color('success')
                ->description('Общая сумма продаж')
                ->formatStateUsing(fn ($state) => number_format($state) . ' сом'),

            Stat::make('Наличный платеж', $totalCash)
                ->color('success')
                ->description('Наличные платежи')
                ->formatStateUsing(fn ($state) => number_format($state) . ' сом'),

            Stat::make('Оплата картой', $totalCard)
                ->color('info')
                ->description('Платежи по карте')
                ->formatStateUsing(fn ($state) => number_format($state) . ' сом'),

            Stat::make('Долг', $totalDebt)
                ->color('danger')
                ->description('Выданные долги')
                ->formatStateUsing(fn ($state) => number_format($state) . ' сом'),
        ];
    }
}