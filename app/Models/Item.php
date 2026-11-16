<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'nama_item',
        'deskripsi',
        'kategori',
        'harga_sewa',
        'jumlah_stok',
        'foto',
        'status',
    ];

    // Relasi: item punya banyak reservasi
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'item_id', 'item_id');
    }
}
