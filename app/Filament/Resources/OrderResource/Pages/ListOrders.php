<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Widgets\OrderStatsWidget as WidgetsOrderStatsWidget;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;




class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            WidgetsOrderStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->Label("Tambah Pesanan"),
        ];
    }
}
