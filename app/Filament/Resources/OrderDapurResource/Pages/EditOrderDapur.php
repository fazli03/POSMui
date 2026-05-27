<?php

namespace App\Filament\Resources\OrderDapurResource\Pages;

use App\Filament\Resources\OrderDapurResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditOrderDapur extends EditRecord
{
    protected static string $resource = OrderDapurResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    public function getTitle(): string
    {
        return 'Detail Pesanan'; // Ganti sesuai kebutuhan
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('selesai')
                ->label('Selesai')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pesanan Selesai')
                ->modalDescription(fn() => "Apakah Anda yakin pesanan {$this->record->kode_pesanan} sudah selesai?")
                ->action(function () {
                    $this->record->update(['status' => 'selesai']);

                    Notification::make()
                        ->title('Pesanan Selesai')
                        ->body("Pesanan {$this->record->kode_pesanan} telah diselesaikan.")
                        ->success()
                        ->send();

                    return redirect('/mui/order-dapurs');
                })
                ->visible(fn() => $this->record->status === 'diproses'),

            Actions\Action::make('kembali')
                ->label('Kembali ke Daftar')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(OrderDapurResource::getUrl('index')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Nonaktifkan save karena ini hanya untuk view
    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()->hidden();
    }

    protected function getSaveAndContinueFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveAndContinueFormAction()->hidden();
    }
}
