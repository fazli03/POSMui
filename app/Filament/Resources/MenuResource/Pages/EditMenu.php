<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [

            $this->getSaveFormAction(),
            $this->getCancelFormAction()->Label("Kembali"),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->title('Data berhasil diubah')
            ->success();
    }

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function hasFormHtml5Validation(): bool
    {
        return false;
    }
}
