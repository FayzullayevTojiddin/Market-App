<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\DatePicker;
use App\Models\Order;
use Carbon\Carbon;

class OrderStatsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Orderlar Statistikasi';

    public $date;

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date')
                ->reactive()
                ->label('Sana tanlang')
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
            Stat::make('Orderlar soni', $totalOrders)
                ->color('primary')
                ->description('Jami orderlar soni'),

            Stat::make('Savdo jami', $totalAmount)
                ->color('success')
                ->description('Jami savdo')
                ->formatStateUsing(fn ($state) => number_format($state) . ' so\'m'),

            Stat::make('Naqd to‘lov', $totalCash)
                ->color('success')
                ->description('Naqd to‘lovlar')
                ->formatStateUsing(fn ($state) => number_format($state) . ' so\'m'),

            Stat::make('Karta orqali', $totalCard)
                ->color('info')
                ->description('Karta orqali to‘lovlar')
                ->formatStateUsing(fn ($state) => number_format($state) . ' so\'m'),

            Stat::make('Qarz', $totalDebt)
                ->color('danger')
                ->description('Berilgan qarzlar')
                ->formatStateUsing(fn ($state) => number_format($state) . ' so\'m'),
        ];
    }
}
