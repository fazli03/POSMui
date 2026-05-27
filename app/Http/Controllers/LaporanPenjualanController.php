<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanPenjualanController extends Controller
{
    public function export(Request $request)
    {
        // Validasi input (opsional tapi recommended)
        $request->validate([
            'status' => 'nullable|string',
            'metode_bayar' => 'nullable|string',
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal'
        ]);

        $query = Order::query();

        // Terapkan filter jika dikirim dari Filament atau request lain
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->input('metode_bayar'));
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->input('tanggal_awal'));
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->input('tanggal_akhir'));
        }

        // Load relasi jika diperlukan untuk laporan
        $orders = $query->with(['orderItems', 'user'])->orderBy('created_at', 'desc')->get();

        // Hitung statistik untuk laporan
        $totalOrders = $orders->count();
        $statusCounts = $orders->groupBy('status')->map->count();
        $metodeBayarCounts = $orders->groupBy('metode_bayar')->map->count();

        // Data untuk filter info di PDF
        $filterInfo = [
            'status' => $request->input('status'),
            'metode_bayar' => $request->input('metode_bayar'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        $pdf = Pdf::loadView('exports.laporan-pdf', compact(
            'orders',
            'totalOrders',
            'statusCounts',
            'metodeBayarCounts',
            'filterInfo'
        ));

        // Set paper size dan orientasi
        $pdf->setPaper('A4', 'portrait');

        // Generate filename dengan timestamp
        $filename = 'laporan-penjualan-' . now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }

    // Method untuk preview laporan sebelum export
    public function preview(Request $request)
    {
        $request->validate([
            'status' => 'nullable|string',
            'metode_bayar' => 'nullable|string',
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date|after_or_equal:tanggal_awal'
        ]);

        $query = Order::query();

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->input('metode_bayar'));
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->input('tanggal_awal'));
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->input('tanggal_akhir'));
        }

        $orders = $query->with(['orderItems', 'user'])->orderBy('created_at', 'desc')->get();

        $totalOrders = $orders->count();
        $statusCounts = $orders->groupBy('status')->map->count();
        $metodeBayarCounts = $orders->groupBy('metode_bayar')->map->count();

        $filterInfo = [
            'status' => $request->input('status'),
            'metode_bayar' => $request->input('metode_bayar'),
            'tanggal_awal' => $request->input('tanggal_awal'),
            'tanggal_akhir' => $request->input('tanggal_akhir'),
            'generated_at' => now()->format('d/m/Y H:i:s')
        ];

        return view('exports.laporan-pdf', compact(
            'orders',
            'totalOrders',
            'statusCounts',
            'metodeBayarCounts',
            'filterInfo'
        ));
    }
}
