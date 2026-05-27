<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class TotalPendapatan extends BaseWidget
{
  protected static ?string $pollingInterval = null;

  protected function getStats(): array
  {
    $now = Carbon::now();
    $startOfMonth = $now->copy()->startOfMonth();
    $daysInMonth = $now->day; // hanya sampai hari ini

    // Total bulanan (angka besar)
    $totalPendapatan = Order::where('status', 'selesai')
      ->whereMonth('created_at', $now->month)
      ->whereYear('created_at', $now->year)
      ->sum('total');

    $pesananSelesai = Order::where('status', 'selesai')
      ->whereMonth('created_at', $now->month)
      ->whereYear('created_at', $now->year)
      ->distinct('kode_pesanan')
      ->count('kode_pesanan');

    $pesananBatal = Order::where('status', 'dibatalkan')
      ->whereMonth('created_at', $now->month)
      ->whereYear('created_at', $now->year)
      ->count();

    // Chart harian: hanya sampai hari ini
    $chartPendapatan = [];
    $chartPelanggan = [];
    $chartBatal = [];

    for ($i = 0; $i < $daysInMonth; $i++) {
      $date = $startOfMonth->copy()->addDays($i)->toDateString();

      $chartPendapatan[] = Order::where('status', 'selesai')
        ->whereDate('created_at', $date)
        ->sum('total');

      $chartPelanggan[] = Order::where('status', 'selesai')
        ->whereDate('created_at', $date)
        ->distinct('kode_pesanan')
        ->count('kode_pesanan');

      $chartBatal[] = Order::where('status', 'dibatalkan')
        ->whereDate('created_at', $date)
        ->count();
    }

    return [
      Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
        ->descriptionIcon('heroicon-m-banknotes')
        ->color('success')
        ->chart($chartPendapatan)
        ->chartColor('success')
        ->description('Total Pendapatan Di Bulan ' . $now->translatedFormat('F')),

      Stat::make('Total Pelanggan', number_format($pesananSelesai))
        ->descriptionIcon('heroicon-m-user-circle')
        ->color('success')
        ->chart($chartPelanggan)
        ->chartColor('success')
        ->description('Total Pelanggan Di Bulan ' . $now->translatedFormat('F')),

      Stat::make('Pesanan Dibatalkan', number_format($pesananBatal))
        ->descriptionIcon('heroicon-m-x-circle')
        ->color('danger')
        ->chart($chartBatal)
        ->chartColor('danger')
        ->description('Total Pesanan Batal Di Bulan ' . $now->translatedFormat('F')),
    ];
  }
}
