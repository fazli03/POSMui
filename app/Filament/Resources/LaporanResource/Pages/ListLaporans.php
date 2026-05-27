<?php

namespace App\Filament\Resources\LaporanResource\Pages;

use App\Filament\Resources\LaporanResource;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use App\Filament\Widgets\TotalPendapatan;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;
    public function getTitle(): string
    {
        return 'Laporan Penjualan'; // Ganti sesuai kebutuhan
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cetak_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->action('CetakLaporan'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalPendapatan::class, // kalau widget class tetap kamu pakai
        ];
    }

    public function CetakLaporan()
    {
        // Ambil data dari table dengan filter aktif
        $filteredData = $this->getFilteredTableQuery()->get();

        // Kirim ke view dan export sebagai PDF
        $pdf = PDF::loadView('exports.laporan-pdf', [
            'orders' => $filteredData
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'laporan-penjualan.pdf'
        );
    }
}
