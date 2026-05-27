<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\KasirDashboard;
use App\Filament\Pages\OwnerDashboard;
use App\Filament\Pages\DapurDashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;



class MuiPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('mui')
            ->path('mui')
            ->login()
            ->homeUrl(fn() => $this->getHomeUrl())
            ->colors([
                'primary' => Color::Lime,

            ])

            ->resources([
                \App\Filament\Resources\OrderResource::class,
                \App\Filament\Resources\MenuResource::class,
                \App\Filament\Resources\LaporanResource::class,
                \App\Filament\Resources\KategoriResource::class,
                \App\Filament\Resources\OrderDapurResource::class,
                \App\Filament\Resources\KetersediaanMenuResource::class,
            ])
            ->pages([
                KasirDashboard::class,
                OwnerDashboard::class,
                DapurDashboard::class,
            ])

            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\OrderDapurStatsWidget::class,
                \App\Filament\Widgets\TotalPendapatan::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets',
            )

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
    public function getHomeUrl(): string
    {
        if (!Auth::check()) {
            return route('filament.mui.auth.login');
        }

        $role = Auth::user()?->role;

        return match ($role) {
            'kasir' => KasirDashboard::getUrl(),
            'owner' => OwnerDashboard::getUrl(),
            'dapur' => DapurDashboard::getUrl(),
            default => route('filament.auth.login'),
        };
    }
}
