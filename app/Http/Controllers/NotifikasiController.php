<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // Tampilkan semua notifikasi user
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', session('user_id'))
            ->orderBy('created_at', 'desc')
            ->get();

        // Tandai semua sebagai sudah dibaca
        Notifikasi::where('user_id', session('user_id'))
            ->where('dibaca', false)
            ->update(['dibaca' => true]);

        return view('user.notifikasi', compact('notifikasis'));
    }

    // Hitung notifikasi belum dibaca (untuk badge di navbar)
    public static function hitungBelumDibaca()
    {
        if (!session('user_id')) return 0;

        return Notifikasi::where('user_id', session('user_id'))
            ->where('dibaca', false)
            ->count();
    }
}
