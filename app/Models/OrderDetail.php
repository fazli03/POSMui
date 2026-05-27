<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    //
    protected $fillable = [
        'quantity',
        'catatan',
        'harga',
        'order_id',
        'menu_id',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
