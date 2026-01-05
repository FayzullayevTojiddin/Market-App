<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OrderStatsWidget;
use Filament\Pages\Page;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Pages\Dashboard\Actions\FilterAction;

class Dashboard extends Page
{

    use HasFiltersForm;

    protected static ?string $navigationLabel = 'Главная';
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected string $view = 'filament.pages.dashboard';

    

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->schema([
                    DatePicker::make('startDate'),
                    DatePicker::make('endDate'),
                    // ...
                ]),
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            OrderStatsWidget::class
        ];
    }
}
