<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\OrderStatsWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Page;

class KasirDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string $view = 'filament.pages.kasir-dashboard';

protected function getHeaderWidgets(): array
{
    return [
           
            \App\Filament\Widgets\ClockWidget::class,
            OrderStatsWidget::class,
    ];
}


    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'kasir';
    }
}
