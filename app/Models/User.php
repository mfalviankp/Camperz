<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    // Primary key custom
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'no_hp',
        'alamat',
        'foto_profil',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi: user punya banyak reservasi
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id', 'user_id');
    }

    // Relasi: user punya banyak notifikasi
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'user_id', 'user_id');
    }
}
