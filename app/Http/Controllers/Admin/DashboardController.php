<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dashboard admin — ringkasan data
    public function index()
    {
        // Hitung jumlah per status
        $perluDiambil = Reservation::where('status', 'menunggu_pengambilan')->count();
        $aktif = Reservation::where('status', 'aktif')->count();

        // Hitung yang terlambat (tanggal_selesai sudah lewat tapi masih aktif)
        $terlambat = Reservation::where('status', 'aktif')
            ->where('tanggal_selesai', '<', now()->toDateString())
            ->count();

        $menungguInspeksi = Reservation::where('status', 'menunggu_inspeksi')->count();
        $menungguDenda = Reservation::where('status', 'menunggu_pelunasan_denda')->count();

        // Total pemasukan bulan ini
        $pemasukanBulanIni = Payment::where('status', 'paid')
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('jumlah_bayar');

        // Transaksi terbaru
        $transaksiTerbaru = Reservation::with(['user', 'item'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Total item tersedia
        $totalItem = Item::where('status', 'tersedia')->sum('jumlah_stok');

        return view('admin.dashboard', compact(
            'perluDiambil',
            'aktif',
            'terlambat',
            'menungguInspeksi',
            'menungguDenda',
            'pemasukanBulanIni',
            'transaksiTerbaru',
            'totalItem'
        ));
    }
}
