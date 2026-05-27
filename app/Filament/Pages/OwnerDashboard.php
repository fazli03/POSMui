<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClockWidget;
use Illuminate\Support\Facades\Auth;
use Filament\Pages\Page;

class OwnerDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static string $view = 'filament.pages.owner-dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            ClockWidget::class,
            \App\Filament\Widgets\TotalPendapatan::class,
            \App\Filament\Widgets\ChartPendapatan::class, // 👈 ini ditambah
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::check() && Auth::user()?->role === 'owner';
    }
}
