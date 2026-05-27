<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    //
    protected $guarded = [];

    public function menus()
    {
        return $this->hasMany(Menu::class, 'kategoris_id');
    }

}
