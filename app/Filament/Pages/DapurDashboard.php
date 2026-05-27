<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClockWidget;
use App\Filament\Widgets\DashboardOverview;
use App\Filament\Widgets\KetersediaanMenuOverview;
use App\Filament\Widgets\OrderDapurStatsWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Page;

class DapurDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string $view = 'filament.pages.dapur-dashboard';

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'dapur';
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ClockWidget::class,
            KetersediaanMenuOverview::class,
            OrderDapurStatsWidget::class,
        ];
    }
}
