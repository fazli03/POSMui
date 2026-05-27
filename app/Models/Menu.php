<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'kategoris_id',
        'is_tersedia',
        'gambar',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',           
            'nama' => 'string',          
            'deskripsi' => 'string',
            'harga' => 'integer',        
            'kategoris_id' => 'string',
            'is_tersedia' => 'boolean',  
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Scope untuk menu yang tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('is_tersedia', true);
    }

    /**
     * Scope untuk menu berdasarkan kategori
     */
    // public function scopeByKategori($query, string $kategoris_id)
    // {
    //     return $query->where('kategoris_id', $kategoris_id);
    // }

    /**
     * Accessor untuk format harga
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('nama', 'like', "%{$search}%")
            ->orWhere('deskripsi', 'like', "%{$search}%");
    }

    /**
     * Relasi dengan OrderDetail
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategoris_id');
    }
}
