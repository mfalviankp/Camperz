<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Reservation;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Halaman beranda user
    public function home()
    {
        // Ambil 6 item populer yang tersedia
        $itemPopuler = Item::where('status', 'tersedia')
            ->where('jumlah_stok', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Hitung statistik untuk user yang login
        $totalTransaksi = 0;
        $transaksiAktif = 0;

        if (session('user_id')) {
            $totalTransaksi = Reservation::where('user_id', session('user_id'))->count();
            $transaksiAktif = Reservation::where('user_id', session('user_id'))
                ->whereIn('status', ['menunggu_pengambilan', 'aktif'])
                ->count();
        }

        return view('user.home', compact('itemPopuler', 'totalTransaksi', 'transaksiAktif'));
    }
}
