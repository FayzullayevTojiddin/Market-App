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
        $day = $this->date ? Carbon::parse($this->date) : Carbon::today();

        $orders = Order::whereDate('created_at', $day)->get();

        $totalOrders = $orders->count();
        $totalAmount = $orders->sum(fn($o) => $o->cash + $o->card + $o->debt);
        $totalCash = $orders->sum('cash');
        $totalCard = $orders->sum('card');
        $totalDebt = $orders->sum('debt');
        $totalDiscount = $orders->sum(function ($order) {
            return $order->products->sum(function ($product) {
                return $product->discount * $product->count;
            });
        });

        $totalRevenue = $totalAmount - $totalDiscount;

        return [
            'totalOrders' => $totalOrders,
            'totalAmount' => $totalAmount,
            'totalCash'   => $totalCash,
            'totalCard'   => $totalCard,
            'totalDebt'   => $totalDebt,
            'totalDiscount' => $totalDiscount,
            'totalRevenue' => $totalRevenue,
        ];
    }
}