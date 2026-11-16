<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Denda;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    // Tampilkan form input denda
    public function inputForm($reservation_id)
    {
        $reservasi = Reservation::with(['user', 'item'])->findOrFail($reservation_id);

        // Hitung keterlambatan
        $hariTerlambat = 0;
        $dendaKeterlambatan = 0;

        if ($reservasi->tanggal_pengembalian_aktual && $reservasi->tanggal_pengembalian_aktual->gt($reservasi->tanggal_selesai)) {
            $hariTerlambat = $reservasi->tanggal_pengembalian_aktual->diffInDays($reservasi->tanggal_selesai);
            // Denda keterlambatan = harga sewa per hari × jumlah hari terlambat × 1.5
            $dendaKeterlambatan = $reservasi->item->harga_sewa * $hariTerlambat * 1.5;
        }

        return view('admin.denda_input', compact('reservasi', 'hariTerlambat', 'dendaKeterlambatan'));
    }

    // Simpan denda
    public function simpanDenda(Request $request, $reservation_id)
    {
        $request->validate([
            'denda_keterlambatan' => 'required|numeric|min:0',
            'denda_kerusakan' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $reservasi = Reservation::with('item')->findOrFail($reservation_id);

        $totalDenda = $request->denda_keterlambatan + $request->denda_kerusakan;

        // Simpan record denda
        Denda::create([
            'reservation_id' => $reservation_id,
            'denda_keterlambatan' => $request->denda_keterlambatan,
            'denda_kerusakan' => $request->denda_kerusakan,
            'total_denda' => $totalDenda,
            'keterangan' => $request->keterangan,
        ]);

        // Buat payment denda
        Payment::create([
            'reservation_id' => $reservation_id,
            'jumlah_bayar' => $totalDenda,
            'jenis' => 'denda',
            'status' => 'pending',
        ]);

        // Update status reservasi
        $reservasi->update(['status' => 'menunggu_pelunasan_denda']);

        // Notifikasi ke user
        Notifikasi::create([
            'user_id' => $reservasi->user_id,
            'judul' => 'Denda Dikenakan',
            'pesan' => 'Anda dikenakan denda sebesar Rp ' . number_format($totalDenda, 0, ',', '.') . ' untuk "' . $reservasi->item->nama_item . '". Silakan lunasi denda untuk menyelesaikan transaksi.',
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Denda berhasil disimpan. Menunggu pelunasan dari user.');
    }
}
