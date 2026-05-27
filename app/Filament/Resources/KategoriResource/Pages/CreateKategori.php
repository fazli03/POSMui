<?php

namespace App\Filament\Resources\KategoriResource\Pages;

use App\Filament\Resources\KategoriResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateKategori extends CreateRecord
{
    protected static string $resource = KategoriResource::class;
    protected static ?string $title = 'Tambah Kategori';
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
