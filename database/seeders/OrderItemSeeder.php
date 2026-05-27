<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Menu;
use Illuminate\Support\Carbon;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        if (Menu::count() === 0) {
            $this->command->warn('Tabel menus kosong. Seeder ini membutuhkan data menu.');
            return;
        }

        foreach (range(1, 100) as $i) {
            // Ambil 1-3 menu acak
            $menuItems = Menu::inRandomOrder()->take(rand(1, 3))->get();
            $totalQuantity = 0;
            $totalHarga = 0;
            $orderDetails = [];

            foreach ($menuItems as $menu) {
                $qty = rand(1, 5);
                $totalQuantity += $qty;
                $totalHarga += $qty * $menu->harga;

                $orderDetails[] = [
                    'menu_id' => $menu->id,
                    'quantity' => $qty,
                    'harga' => $menu->harga,
                    'catatan' => fake()->optional()->sentence(),
                ];
            }

            // Pilih metode bayar
            $metode = fake()->randomElement(['tunai', 'non_tunai']);

            // Gunakan helper dari model Order
            $pembayaran = Order::hitungPembayaran($metode, $totalHarga);

            // Tanggal acak antara Juni - Desember 2025
            $tanggal = Carbon::create(2025, rand(1, 8), rand(1, 28), rand(8, 21), rand(0, 59));

            // Buat Order
            $order = Order::create([
                'kode_pesanan' => Order::generateUniqueKodePesanan(),
                'nama' => fake()->name(),
                'no_meja' => fake()->optional()->numberBetween(1, 20),
                'tipe_order' => fake()->randomElement(['dine_in', 'takeaway']),
                'metode_bayar' => $metode,
                'status' => fake()->randomElement(['diproses', 'selesai', 'dibatalkan']),
                'quantity' => $totalQuantity,
                'total' => $totalHarga,
                'jumlah_uang_diberikan' => $pembayaran['jumlah_uang_diberikan'],
                'kembalian' => $pembayaran['kembalian'],
                'created_at' => $tanggal,
                'updated_at' => $tanggal,
            ]);

            // Buat detail dari Order
            foreach ($orderDetails as $detail) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_id' => $detail['menu_id'],
                    'quantity' => $detail['quantity'],
                    'harga' => $detail['harga'],
                    'catatan' => $detail['catatan'],
                    'created_at' => $tanggal,
                    'updated_at' => $tanggal,
                ]);
            }
        }
    }
}
