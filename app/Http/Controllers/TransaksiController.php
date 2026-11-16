<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    // Tampilkan daftar transaksi user
    public function index(Request $request)
    {
        $userId = session('user_id');
        $status = $request->get('status', 'semua');

        $query = Reservation::with(['item', 'payments'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status tab
        if ($status !== 'semua') {
            $query->where('status', $status);
        }

        $transaksis = $query->get();

        return view('user.transaksi.index', compact('transaksis', 'status'));
    }

    // Detail transaksi
    public function detail($id)
    {
        $reservasi = Reservation::with(['item', 'user', 'payments', 'denda'])
            ->where('user_id', session('user_id'))
            ->findOrFail($id);

        return view('user.transaksi.detail', compact('reservasi'));
    }
}
