<?php

namespace App\Filament\Resources\KetersediaanMenuResource\Pages;

use App\Filament\Resources\KetersediaanMenuResource;
use Filament\Resources\Pages\EditRecord;

class EditKetersediaanMenu extends EditRecord
{
    protected static string $resource = KetersediaanMenuResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Hanya izinkan update field tertentu
        return [
            'is_tersedia' => $data['is_tersedia'],
        ];
    }
}
