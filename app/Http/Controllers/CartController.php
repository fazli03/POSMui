<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function store(Request $request)
    {
        
        $cart = session()->get('cart', []);

        $productId = $request->id;
        $request->input('quantity');

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] += $request->qty;
        } else {
            $cart[$productId] = [
                'id' => $productId,
                'nama' => $request->nama,
                'harga' => $request->harga,
                'gambar' => $request->gambar,
                'qty' => $request->qty,
                'subtotal' => $request->qty * $request->harga,
                'note' => $request->note ?? '',
            ];
        }

        session()->put('cart', $cart);

        session()->flash('success', 'Menu berhasil dimasukkan ke keranjang');

        return redirect()->route('dine.index');
        try {
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat memasukkan produk ke keranjang');
            return redirect()->back();
        }

    }


    public function index()
    {
        $cart = session()->get('cart', []);

        $totalPrice = array_sum(array_column($cart, 'subtotal'));

        return view('pelanggan.cart', compact('cart', 'totalPrice'));
    }


    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->id;

        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $request->qty;
            $cart[$productId]['subtotal'] = $cart[$productId]['qty'] * $cart[$productId]['harga'];
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }
    
    


    public function updateNote(Request $request)
    {
        $cart = session()->get('cart', []);
        $productId = $request->id;

        if (isset($cart[$productId])) {
            $cart[$productId]['note'] = $request->note;
            session()->put('cart', $cart);
        }

        return redirect()->back();
    }


}
