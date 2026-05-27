<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function indexKasir()
    {
        $orders = DB::table('orders')->orderByDesc('created_at')->get();

        return view('kasir.orders', compact('orders'));
    }

    public function konfirmasi($id)
    {
        DB::table('orders')->where('id', $id)->update([
            'status' => 'selesai',
            'updated_at' => now(),
        ]);

        return redirect()->route('kasir.orders')->with('success', 'Pesanan berhasil dikonfirmasi.');
    }

    public function showPembayaranForm()
    {
        return view('pelanggan.pembayaran');
    }

    public function prosesPembayaran(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_meja' => 'required|integer|between:0,20',
            'metode_bayar' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.formPembayaran')->with('error', 'Keranjang kosong');
        }

        $datePart = now()->format('ymd');
        $prefix = Order::generateUniqueKodePesanan();
        $orderCount = Order::whereDate('created_at', now())->count() + 1;
        $number = str_pad($orderCount, 3, '0', STR_PAD_LEFT);
        $kodePesanan = $prefix;
        $totalQty = array_sum(array_column($cart, 'qty'));
        $totalHarga = array_sum(array_column($cart, 'subtotal'));

        DB::beginTransaction();
        try {
            $orderId = DB::table('orders')->insertGetId([
                'kode_pesanan' => $kodePesanan,
                'nama' => $request->nama,
                'no_meja' => $request->no_meja,
                'tipe_order' => session('tipe_order'),
                'metode_bayar' => $request->metode_bayar,
                'status' => 'pending',
                'quantity' => $totalQty,
                'total' => $totalHarga,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cart as $item) {
                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'menu_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'catatan' => $item['note'] ?? null,
                    'harga' => $item['harga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();
            session()->forget('cart');
            session()->put('payment_success', true);
            session()->put('order_id', $orderId);

            // Simpan order_id ke array pesanan milik session ini
            session()->push('my_order_ids', $orderId);

            return redirect()->route('order.finish')->with('success', 'Pesanan berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan pesanan. ' . $e->getMessage());
        }
    }

    public function pesananSaya()
    {
        // Ambil order_id dari session
        $orderId = session('order_id');
        $myOrderIds = session('my_order_ids', []);

        if (!$orderId || empty($myOrderIds)) {
            return redirect()->back()->with('error', 'Tidak ada pesanan yang aktif saat ini.');
        }

        // Ambil pesanan yang ID-nya ada di session dan masih aktif
        $orders = Order::whereIn('id', $myOrderIds)
            ->whereIn('status', ['pending', 'diproses'])
            ->orderByDesc('created_at')
            ->get();

        $selectedOrder = $orders->firstWhere('id', $orderId);

        if (!$selectedOrder) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan atau sudah selesai.');
        }

        $details = OrderDetail::with('menu')
            ->where('order_id', $selectedOrder->id)
            ->get();

        return view('order-finish', [
            'orders' => $orders,
            'order' => $selectedOrder,
            'orderDetails' => $details,
        ]);
    }

    public function pesananTertentu($id)
    {
        $myOrderIds = session('my_order_ids', []);

        // Pastikan ID pesanan ada di session user ini
        if (!in_array($id, $myOrderIds)) {
            return redirect()->route('order.finish')
                ->with('error', 'Pesanan tidak valid.');
        }

        $orders = Order::whereIn('id', $myOrderIds)
            ->whereIn('status', ['pending', 'diproses'])
            ->orderByDesc('created_at')
            ->get();

        $order = $orders->firstWhere('id', $id);

        if (!$order) {
            return redirect()->route('order.finish')
                ->with('error', 'Pesanan tidak ditemukan atau sudah selesai.');
        }

        session()->put('order_id', $order->id);

        $details = OrderDetail::with('menu')
            ->where('order_id', $order->id)
            ->get();

        return view('order-finish', [
            'orders' => $orders,
            'order' => $order,
            'orderDetails' => $details,
        ]);
    }
}
