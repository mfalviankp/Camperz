<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'user_id',
        'item_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'total_harga',
        'biaya_layanan',
        'status',
        'qr_pickup',
        'qr_return',
        'tanggal_pengambilan_aktual',
        'tanggal_pengembalian_aktual',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_pengambilan_aktual' => 'datetime',
        'tanggal_pengembalian_aktual' => 'datetime',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relasi ke item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    // Relasi ke payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'reservation_id', 'reservation_id');
    }

    // Relasi ke denda
    public function denda()
    {
        return $this->hasOne(Denda::class, 'reservation_id', 'reservation_id');
    }
}
