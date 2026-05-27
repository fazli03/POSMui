<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Menu;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends BaseWidget
{
  protected function getStats(): array
  {
    $chartSelesai = collect(range(1, 0)) // 6 hari lalu sampai hari ini
      ->map(function ($daysAgo) {
        return Order::where('status', 'selesai')
          ->whereDate('created_at', Carbon::today()->subDays($daysAgo))
          ->count();
      })
      ->toArray();
    $chartDiproses = collect(range(1, 0))
      ->map(function ($daysAgo) {
        return Order::where('status', 'diproses')
          ->whereDate('created_at', Carbon::today()->subDays($daysAgo))
          ->count();
      })
      ->toArray();
    $totalMenu = Menu::count();
    $tersedia = Menu::where('is_tersedia', true)->count();
    $tidakTersedia = $totalMenu - $tersedia;

    $pesananBaru = Order::where('status', 'pending')->count();
    $pesananSelesai = Order::whereDate('updated_at', now())
      ->where('status', 'selesai')
      ->count();

    return [
      Stat::make('Pesanan Baru', $pesananBaru)
        ->description('Pesanan yang perlu diproses')
        ->color('warning')
        ->descriptionIcon('heroicon-o-clock')
        ->chart($chartDiproses)
        ->chartColor('warning'),

      Stat::make('Total Selesai', $pesananSelesai)
        ->description('Pesanan selesai hari ini')
        ->color('success')
        ->descriptionIcon('heroicon-o-check-circle')
        ->chart($chartSelesai)
        ->chartColor('success'),

      Stat::make('Menu Tersedia', $tersedia)
        ->description("{$tersedia} dari {$totalMenu} menu tersedia")
        ->color('success')
        ->descriptionIcon('heroicon-o-check-badge'),

      Stat::make('Menu Tidak Tersedia', $tidakTersedia)
        ->description("{$tidakTersedia} menu tidak tersedia")
        ->color('danger')
        ->descriptionIcon('heroicon-o-x-circle'),
    ];
  }
}
