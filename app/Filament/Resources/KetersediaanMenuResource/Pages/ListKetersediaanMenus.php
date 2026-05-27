<?php

namespace App\Filament\Resources\KetersediaanMenuResource\Pages;

use App\Filament\Resources\KetersediaanMenuResource;
use App\Livewire\KetersediaanMenuOverview;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\View\LegacyComponents\Widget;

class ListKetersediaanMenus extends ListRecords
{
    protected static string $resource = KetersediaanMenuResource::class;

    public function getTitle(): string
    {
        return 'Ketersediaan Menu'; // Ganti sesuai kebutuhan
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\KetersediaanMenuOverview::class,
        ];
    }
}
