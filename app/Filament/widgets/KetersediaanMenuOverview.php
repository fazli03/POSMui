<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Menu;
use Illuminate\Support\Number;

class KetersediaanMenuOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $tersedia = Menu::where('is_tersedia', true)->count();
        $habis = Menu::where('is_tersedia', false)->count();
        $total = $tersedia + $habis;

        return [
            Stat::make('Menu Tersedia', Number::format($tersedia))
                ->description("{$tersedia} dari {$total} menu tersedia")
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')->chart([7, 2, 10, 3, 15, 4, 17])
                ->chartColor('success'),

            Stat::make('Menu Tidak Tersedia', Number::format($habis))
                ->description("{$habis} menu tidak tersedia")
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger')->chart([7, 2, 10, 3, 15, 4, 4])
                ->chartColor('danger'),
        ];
    }
}
