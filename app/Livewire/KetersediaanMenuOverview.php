<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;

class KetersediaanMenuOverview extends Component
{
    public $tersedia;
    public $tidakTersedia;

    public function mount()
    {
        $this->tersedia = Menu::where('is_tersedia', true)->count();
        $this->tidakTersedia = Menu::where('is_tersedia', false)->count();
    }

    public function render()
    {
        return view('livewire.ketersediaan-menu-overview');
    }
}
