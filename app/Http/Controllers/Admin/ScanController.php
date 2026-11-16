<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Item;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScanController extends Controller
{
    // Halaman scan pickup
    public function showPickup()
    {
        return view('admin.scan.pickup');
    }

    // Proses scan QR pickup
    public function prosesPickup(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Cari reservasi berdasarkan kode QR pickup
        $reservasi = Reservation::with(['user', 'item'])
            ->where('qr_pickup', $request->qr_code)
            ->where('status', 'menunggu_pengambilan')
            ->first();

        if (!$reservasi) {
            return back()->with('error', 'Kode QR tidak valid atau reservasi sudah diambil.');
        }

        // Tampilkan detail untuk konfirmasi
        return view('admin.scan.pickup', compact('reservasi'));
    }

    // Konfirmasi pickup
    public function konfirmasiPickup($id)
    {
        $reservasi = Reservation::findOrFail($id);

        // Update status jadi aktif
        $reservasi->update([
            'status' => 'aktif',
            'tanggal_pengambilan_aktual' => now(),
        ]);

        // Generate kode QR return
        $kodeReturn = 'RET-' . $reservasi->reservation_id . '-' . strtoupper(Str::random(4));
        $reservasi->update(['qr_return' => $kodeReturn]);

        // Notifikasi ke user
        Notifikasi::create([
            'user_id' => $reservasi->user_id,
            'judul' => 'Barang Diambil',
            'pesan' => 'Barang "' . $reservasi->item->nama_item . '" sudah diambil. Selamat berkemah! Jangan lupa kembalikan tepat waktu.',
        ]);

        return redirect()->route('admin.scan.pickup')
            ->with('success', 'Pickup berhasil dikonfirmasi! Kode return: ' . $kodeReturn);
    }

    // Halaman scan return
    public function showReturn()
    {
        return view('admin.scan.return');
    }

    // Proses scan QR return
    public function prosesReturn(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
        ]);

        // Cari reservasi berdasarkan kode QR return
        $reservasi = Reservation::with(['user', 'item'])
            ->where('qr_return', $request->qr_code)
            ->where('status', 'aktif')
            ->first();

        if (!$reservasi) {
            return back()->with('error', 'Kode QR tidak valid atau barang sudah dikembalikan.');
        }

        // Update status menunggu inspeksi
        $reservasi->update([
            'status' => 'menunggu_inspeksi',
            'tanggal_pengembalian_aktual' => now(),
        ]);

        // Redirect ke halaman inspeksi
        return redirect()->route('admin.inspeksi', $reservasi->reservation_id)
            ->with('success', 'Barang diterima. Silakan lakukan inspeksi.');
    }

    // Halaman inspeksi barang
    public function inspeksi($id)
    {
        $reservasi = Reservation::with(['user', 'item'])->findOrFail($id);

        // Cek apakah terlambat
        $terlambat = false;
        $hariTerlambat = 0;
        if ($reservasi->tanggal_pengembalian_aktual && $reservasi->tanggal_pengembalian_aktual->gt($reservasi->tanggal_selesai)) {
            $terlambat = true;
            $hariTerlambat = $reservasi->tanggal_pengembalian_aktual->diffInDays($reservasi->tanggal_selesai);
        }

        return view('admin.inspeksi', compact('reservasi', 'terlambat', 'hariTerlambat'));
    }

    // Proses inspeksi
    public function prosesInspeksi(Request $request, $id)
    {
        $request->validate([
            'kondisi' => 'required|in:baik,rusak',
        ]);

        $reservasi = Reservation::with('item')->findOrFail($id);

        // Cek apakah terlambat
        $terlambat = false;
        if ($reservasi->tanggal_pengembalian_aktual && $reservasi->tanggal_pengembalian_aktual->gt($reservasi->tanggal_selesai)) {
            $terlambat = true;
        }

        // Jika kondisi baik DAN tepat waktu → selesai
        if ($request->kondisi === 'baik' && !$terlambat) {
            $reservasi->update(['status' => 'selesai']);

            // Kembalikan stok
            $item = Item::find($reservasi->item_id);
            $item->increment('jumlah_stok');

            // Notifikasi
            Notifikasi::create([
                'user_id' => $reservasi->user_id,
                'judul' => 'Pengembalian Selesai',
                'pesan' => 'Barang "' . $reservasi->item->nama_item . '" sudah dikembalikan dengan baik. Terima kasih telah menggunakan Camperz!',
            ]);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Inspeksi selesai. Barang dalam kondisi baik dan tepat waktu.');
        }

        // Jika ada masalah (terlambat atau rusak) → redirect ke form denda
        return redirect()->route('admin.denda.input', $reservasi->reservation_id)
            ->with('info', 'Terdapat masalah — silakan input denda.');
    }
}
