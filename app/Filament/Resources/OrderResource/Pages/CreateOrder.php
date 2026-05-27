<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
    protected static ?string $title = 'Tambah Pesanan';

    protected function getFormActions(): array
    {
        return [
            // $this->getCreateFormAction()->label('Tambah'),
            // $this->getCreateAnotherFormAction(),
            $this->getCancelFormAction()->Label("Kembali"),
        ];
    }
}
