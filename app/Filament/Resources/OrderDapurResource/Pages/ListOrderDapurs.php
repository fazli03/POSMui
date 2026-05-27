<?php

namespace App\Filament\Resources\OrderDapurResource\Pages;

use App\Filament\Resources\OrderDapurResource;
use App\Filament\Dapur\Widgets\OrderDapurStatsWidget;
use App\Filament\Widgets\OrderDapurStatsWidget as WidgetsOrderDapurStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderDapurs extends ListRecords
{
    protected static string $resource = OrderDapurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tidak ada action create karena ini hanya untuk menampilkan pesanan yang sudah dikonfirmasi
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsOrderDapurStatsWidget::class,
        ];
    }
}
