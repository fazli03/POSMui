<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Kategori;


class ProdukController extends Controller
{

    public function index(Request $request)
    {
        $tipe = $request->query('tipe');
        if ($tipe) {
            session(['tipe_order' => $tipe]);
        }

        $kategoriId = $request->query('kategori');

        $kategori = Kategori::withCount('menus')->get();

        $produk = Menu::select('id', 'nama', 'harga', 'gambar', 'kategoris_id', 'is_tersedia')
            ->with('kategori:id,nama')
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('kategoris_id', $kategoriId);
            })
            ->paginate(20);

        $cart = session('cart', []);
        $totalItems = array_sum(array_column($cart, 'qty'));

        return view('pelanggan.menu', compact('produk', 'kategori', 'totalItems'));
    }

    public function show($id)
    {
        $produk = Menu::select('id', 'nama', 'harga', 'gambar', 'deskripsi', 'is_tersedia', 'kategoris_id')
            ->with('kategori:id,nama')
            ->findOrFail($id);

        return view('pelanggan.product-information', compact('produk'));
    }
}
