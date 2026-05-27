<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderDapurStats extends Component
{
    public $totalSelesai;
    public $totalProses;

    public function mount()
    {
        $this->totalSelesai = Order::where('status', 'selesai')->count();
        $this->totalProses = Order::where('status', 'proses')->count();
    }

    public function render()
    {
        return view('livewire.order-dapur-stats');
    }
}
