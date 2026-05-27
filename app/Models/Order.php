<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    //
    protected $fillable = [
        'kode_pesanan',
        'nama',
        'no_meja',
        'tipe_order',
        'metode_bayar',
        'status',
        'quantity',
        'total',
        'jumlah_uang_diberikan',
        'kembalian',
    ];


    public static function generateUniqueKodePesanan()
    {
        $prefix = 'MUI';
        do {
            $randomString = $prefix . mt_rand(1000, 9999);
        } while (self::where('kode_pesanan', $randomString)->exists());
        return $randomString;
    }

    public static function hitungPembayaran(string $metode, int $total): array
    {
        if ($metode === 'non_tunai') {
            return [
                'jumlah_uang_diberikan' => $total,
                'kembalian' => 0,
            ];
        }

        // Untuk metode tunai, anggap pelanggan kasih uang lebih
        $jumlah_uang_diberikan = $total + rand(1000, 10000);
        $kembalian = $jumlah_uang_diberikan - $total;

        return [
            'jumlah_uang_diberikan' => $jumlah_uang_diberikan,
            'kembalian' => $kembalian,
        ];
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
