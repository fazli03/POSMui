<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Order;

class ChartPendapatan extends ChartWidget
{
    protected static ?string $heading = 'Grafik Pendapatan per Bulan';
    protected static string $color = 'success'; // warna garis

    protected function getData(): array
    {
        $data = Order::selectRaw("MONTH(created_at) as bulan, SUM(total) as total")
            ->where('status', 'selesai')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'bulan');

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $data->values(),
                    'borderColor' => 'rgb(34,197,94)', // green-500
                    'backgroundColor' => 'rgba(34,197,94,0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->keys()->map(function ($bulan) {
                return date('M', mktime(0, 0, 0, $bulan, 1));
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line'; // line, bar, pie
    }

    protected function getHeight(): ?int
    {
        return 300;
    }
}
