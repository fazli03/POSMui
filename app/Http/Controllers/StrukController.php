<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StrukController extends Controller
{
    public function showHtml(Order $order)
    {
        $order->load(['orderDetails.menu', 'user']);

        $company = [
            'name' => 'Kedai Martabak Ulama India',
            'address' => 'Jl. Marco No.01 Kec. Bebek Standing dan Terbang Kel. Karapan Sapi',
            'phone' => '0123-456-789',
        ];

        return view('struk', compact('order', 'company'));
    }
}

