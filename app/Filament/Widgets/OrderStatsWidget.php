<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Forms\Components\DatePicker;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class OrderStatsWidget extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Статистика';

    public $period, $date;

    protected function getStats(): array
    {
        $date = $this->date ? Carbon::parse($this->date) : Carbon::today();
        $startDate = $this->pageFilters['startDate'] ?? null;
        $endDate = $this->pageFilters['endDate'] ?? null;

        $ordersQuery = Order::with('products.product');

        if ($startDate) {
            $ordersQuery->whereDate('created_at', '>=', Carbon::parse($startDate)); // Start date dan katta yoki teng bo'lgan buyurtmalar
        }   

        if ($endDate) {
            $ordersQuery->whereDate('created_at', '<=', Carbon::parse($endDate)); // End date dan kichik yoki teng bo'lgan buyurtmalar
        }

        $orders = $ordersQuery->get();

        $totalOrders = $orders->count();      // Buyurtmalar soni
        $totalSales = 0;                      // Sotish summasi
        $totalCost  = 0;                      // Kelgan narx summasi
        $totalDiscount = 0;                   // Chegirma summasi
        $totalProfit = 0;                     // Daromad (profit)
        $totalCash = 0;                       // Naqdga savdo summasi
        $totalCard = 0;                       // Kartaga savdo summasi

        // Har bir buyurtma va mahsulot uchun hisoblash
        foreach ($orders as $order) {
            foreach ($order->products as $item) {

                // Sotish summasi: price_summ bu umumiy narx (price * count)
                $saleSum = $item->price_summ;

                // Kelgan narx: productning purchase_price * count
                $costSum = $item->product->purchase_price * $item->count;

                // Chegirma
                $discount = $item->discount ?? 0;

                // Daromad: Sotish summasidan kelgan narxni ayirib, chegirmani ham olib tashlaymiz
                $profit = ($saleSum - $costSum) - $discount;

                // Umumiy summalar
                $totalSales += $saleSum;
                $totalCost += $costSum;
                $totalDiscount += $discount;
                $totalProfit += $profit;
            }

            // Naqdga va kartaga savdolarni hisoblash
            $totalCash += $order->cash;
            $totalCard += $order->card;
        }

        // Natijani qaytarish
        return [
            Stat::make('Заказы', $totalOrders)  // Bu yerga $totalOrders qiymatini qo'shdik
                ->color('primary'),

            Stat::make('Продажи', number_format($totalSales) . ' сом')
                ->color('success'),

            Stat::make('Себестоимость', number_format($totalCost) . ' сом')
                ->color('gray'),

            Stat::make('Скидки', number_format($totalDiscount) . ' сом')
                ->color('warning'),

            Stat::make('💰 Чистая прибыль', number_format($totalProfit) . ' сом')
                ->color('success')
                ->description('Продажа − себестоимость − скидка'),

            // Naqdga savdo
            Stat::make('На наличный расчет', number_format($totalCash) . ' сом')
                ->color('danger')
                ->description('Общая сумма на наличный расчет'),

            // Kartaga savdo
            Stat::make('Оплата картой', number_format($totalCard) . ' сом')
                ->color('info')
                ->description('Общая сумма оплат по карте'),
        ];
    }
}
