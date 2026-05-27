<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
    protected static ?string $title = 'Tambah Menu';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('Tambah'),
            $this->getCreateAnotherFormAction(),
            $this->getCancelFormAction()->Label("Kembali"),
        ];
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Data berhasil ditambah')
            ->success();
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
