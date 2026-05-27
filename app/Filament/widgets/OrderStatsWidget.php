<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class OrderStatsWidget extends BaseWidget
{

  protected function getStats(): array
  {
    $chartPending = collect(range(1, 0))
      ->map(function ($daysAgo) {
        return Order::where('status', 'pending')
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
    $chartSelesai = collect(range(1, 0)) // 6 hari lalu sampai hari ini
      ->map(function ($daysAgo) {
        return Order::where('status', 'selesai')
          ->whereDate('created_at', Carbon::today()->subDays($daysAgo))
          ->count();
      })
      ->toArray();
    $chartBatal = collect(range(1, 0))
      ->map(function ($daysAgo) {
        return Order::where('status', 'dibatalkan')
          ->whereDate('created_at', Carbon::today()->subDays($daysAgo))
          ->count();
      })
      ->toArray();

    $totalPesananDiprosesHariIni = Order::where('status', 'diproses')->whereDate('created_at', today())->count();
    $totalPesananSelesaiHariIni = Order::where('status', 'selesai')
      ->whereDate('created_at', today())
      ->count();
    $totalPesananPendingHariIni = Order::where('status', 'pending')
      ->whereDate('created_at', today())
      ->count();
    $totalPesananDibatalkanHariIni = Order::where('status', 'dibatalkan')
      ->whereDate('created_at', today())
      ->count();

    return [
      Stat::make('Pesanan Selesai', $totalPesananSelesaiHariIni)
        ->description('Total Pesanan Selesai')
        ->descriptionIcon('heroicon-o-check-circle')
        ->color('success'),

      Stat::make('Pesanan Pending',$totalPesananPendingHariIni)
        ->description('Total Pesanan Pending')
        ->descriptionIcon('heroicon-o-clock')
        ->color('warning'),

      Stat::make('Pesanan Diproses', $totalPesananDiprosesHariIni)
        ->description('Total Pesanan Diproses')
        ->descriptionIcon('heroicon-o-fire')
        ->color('info'),

      Stat::make('Pesanan Dibatalkan',$totalPesananDibatalkanHariIni)
        ->description('Total Pesanan Dibatalkan')
        ->descriptionIcon('heroicon-o-x-circle')
        ->color('danger'),
    ];
  }
}
