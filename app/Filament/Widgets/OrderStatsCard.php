<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use App\Models\Order;
use Carbon\Carbon;

class OrderStatsCard extends Widget
{
    use InteractsWithForms;

    protected string $view = 'filament.widgets.order-stats-card';

    public $date;

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date')
                ->label('Sana tanlang')
                ->default(now())
                ->reactive(),
        ];
    }

    public function getStats(): array
    {
        $date = $this->date ? Carbon::parse($this->date) : Carbon::today();
        $orders = Order::whereDate('created_at', $date)->get();

        return [
            'totalOrders' => $orders->count(),
            'totalAmount' => $orders->sum(fn($o) => $o->cash + $o->card + $o->debt),
            'totalCash'   => $orders->sum('cash'),
            'totalCard'   => $orders->sum('card'),
            'totalDebt'   => $orders->sum('debt'),
        ];
    }
}