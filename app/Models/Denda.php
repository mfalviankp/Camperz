<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    protected $primaryKey = 'denda_id';

    protected $fillable = [
        'reservation_id',
        'denda_keterlambatan',
        'denda_kerusakan',
        'total_denda',
        'keterangan',
    ];

    // Relasi ke reservasi
    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'reservation_id');
    }
}
