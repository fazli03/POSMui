<?php

namespace App\Filament\Resources\OrderResource\Pages;


use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\Page;
use App\Models\Order;

class PrintStruk extends Page
{
  protected static string $resource = OrderResource::class;

  protected static string $view = 'struk'; 

  public Order $order;

  public function mount($record): void
  {
    $this->order = Order::with('orderDetails.menu')->findOrFail($record);
  }
}
