<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;


class OrderDapurStatsWidget extends BaseWidget
{

  protected function getStats(): array
  {
    $chartSelesai = collect(range(1, 0)) // 1 hari lalu sampai hari ini
      ->map(function ($daysAgo) {
        return Order::where('status', 'selesai')
          ->whereDate('created_at', Carbon::today()->subDays($daysAgo))
          ->count();
      })
      ->toArray();
    $chartDiproses = collect(range( 1, 0))
      ->map(function ($daysAgo) {
        return Order::where('status', 'diproses')
          ->whereDate('created_at', Carbon::today()->subDays($daysAgo))
          ->count();
      })
      ->toArray();

    $totalPesananBaru = Order::where('status', 'diproses')->count();
    $totalPesananSelesaiHariIni = Order::where('status', 'selesai')
      ->whereDate('created_at', today())
      ->count();

    return [
      Stat::make('Pesanan Baru', $totalPesananBaru)
        ->description('Pesanan yang perlu diproses')
        ->descriptionIcon('heroicon-m-clock')
        ->color('warning')
        ->chart($chartDiproses)
        ->chartColor('warning'),  

      Stat::make('Total Selesai', $totalPesananSelesaiHariIni)
        ->description('Pesanan selesai hari ini')
        ->descriptionIcon('heroicon-m-check-circle')
        ->color('success')
        ->chart($chartSelesai)
        ->chartColor('success'),

    ];
  }

  protected function getPollingInterval(): ?string
  {
    return '1s'; // Update statistik setiap 1 detik
  }
  public static function canView(): bool
  {
    return Auth::check() && Auth::user()?->role === 'dapur';
  }
}
