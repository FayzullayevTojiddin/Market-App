<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OrderStatsWidget;
use Filament\Pages\Page;
use BackedEnum;

class Dashboard extends Page
{
    protected static ?string $navigationLabel = 'Bosh sahifa';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected string $view = 'filament.pages.dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            OrderStatsWidget::class
        ];
    }
}
