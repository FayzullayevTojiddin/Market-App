<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use Carbon\Carbon;

class OrderStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $today = Carbon::today();

        $orders = Order::whereDate('created_at', $today)->get();

        $totalOrders = $orders->count();
        $totalAmount = $orders->sum(fn($o) => $o->cash + $o->card + $o->debt);
        $totalCash   = $orders->sum('cash');
        $totalCard   = $orders->sum('card');
        $totalDebt   = $orders->sum('debt');

        return [
            Stat::make('Bugungi orderlar', $totalOrders)
                ->color('primary')
                ->description('Bugungi jami orderlar soni'),

            Stat::make('Bugungi savdo jami', $totalAmount)
                ->color('success')
                ->description('Bugungi jami savdo')
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
                ->description('Bugun berilgan qarzlar')
                ->formatStateUsing(fn ($state) => number_format($state) . ' so\'m'),
        ];
    }
}
