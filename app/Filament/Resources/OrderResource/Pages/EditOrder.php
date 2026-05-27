<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;
    protected static ?string $title = 'Pesanan';



    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()->Label("Kembali"),
        ];
    }
}
