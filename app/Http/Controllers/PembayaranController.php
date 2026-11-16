<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use App\Models\Item;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PembayaranController extends Controller
{
    // Tampilkan halaman pembayaran
    public function show($reservation_id)
    {
        $reservasi = Reservation::with(['item', 'payments'])
            ->where('user_id', session('user_id'))
            ->findOrFail($reservation_id);

        // Ambil payment sewa yang pending
        $payment = $reservasi->payments()->where('jenis', 'sewa')->where('status', 'pending')->first();

        if (!$payment) {
            return redirect()->route('transaksi.index')->with('error', 'Pembayaran tidak ditemukan atau sudah lunas.');
        }

        return view('user.pembayaran', compact('reservasi', 'payment'));
    }

    // Konfirmasi pembayaran manual (simulasi webhook)
    public function konfirmasiManual(Request $request, $payment_id)
    {
        $request->validate([
            'metode_bayar' => 'required|string',
        ]);

        $payment = Payment::findOrFail($payment_id);
        $reservasi = Reservation::findOrFail($payment->reservation_id);

        // Pastikan ini milik user yang login
        if ($reservasi->user_id != session('user_id')) {
            return redirect()->route('transaksi.index')->with('error', 'Akses ditolak.');
        }

        // Update status payment
        $payment->update([
            'status' => 'paid',
            'metode_bayar' => $request->metode_bayar,
            'tanggal_bayar' => now(),
        ]);

        // Update status reservasi
        $reservasi->update([
            'status' => 'menunggu_pengambilan',
        ]);

        // Kurangi stok item
        $item = Item::find($reservasi->item_id);
        $item->decrement('jumlah_stok');

        // Generate kode QR pickup
        $kodePickup = 'PICK-' . $reservasi->reservation_id . '-' . strtoupper(Str::random(4));
        $reservasi->update(['qr_pickup' => $kodePickup]);

        // Buat notifikasi untuk user
        Notifikasi::create([
            'user_id' => $reservasi->user_id,
            'judul' => 'Pembayaran Berhasil',
            'pesan' => 'Pembayaran untuk "' . $item->nama_item . '" berhasil! Tunjukkan E-Tiket saat pengambilan barang.',
        ]);

        return redirect()->route('etiket', $reservasi->reservation_id)
            ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    // Tampilkan E-Tiket
    public function etiket($reservation_id)
    {
        $reservasi = Reservation::with(['item', 'user', 'payments'])
            ->where('user_id', session('user_id'))
            ->findOrFail($reservation_id);

        return view('user.etiket', compact('reservasi'));
    }

    // Bayar denda
    public function showBayarDenda($reservation_id)
    {
        $reservasi = Reservation::with(['item', 'denda', 'payments'])
            ->where('user_id', session('user_id'))
            ->findOrFail($reservation_id);

        $paymentDenda = $reservasi->payments()->where('jenis', 'denda')->where('status', 'pending')->first();

        if (!$paymentDenda) {
            return redirect()->route('transaksi.index')->with('error', 'Tidak ada denda yang perlu dibayar.');
        }

        return view('user.bayar_denda', compact('reservasi', 'paymentDenda'));
    }

    // Konfirmasi bayar denda
    public function konfirmasiBayarDenda(Request $request, $payment_id)
    {
        $request->validate([
            'metode_bayar' => 'required|string',
        ]);

        $payment = Payment::findOrFail($payment_id);
        $reservasi = Reservation::findOrFail($payment->reservation_id);

        if ($reservasi->user_id != session('user_id')) {
            return redirect()->route('transaksi.index')->with('error', 'Akses ditolak.');
        }

        // Update payment denda
        $payment->update([
            'status' => 'paid',
            'metode_bayar' => $request->metode_bayar,
            'tanggal_bayar' => now(),
        ]);

        // Update status reservasi jadi selesai
        $reservasi->update(['status' => 'selesai']);

        // Kembalikan stok item
        $item = Item::find($reservasi->item_id);
        $item->increment('jumlah_stok');

        // Notifikasi
        Notifikasi::create([
            'user_id' => $reservasi->user_id,
            'judul' => 'Denda Lunas',
            'pesan' => 'Pembayaran denda untuk "' . $item->nama_item . '" berhasil. Transaksi selesai.',
        ]);

        return redirect()->route('transaksi.detail', $reservasi->reservation_id)
            ->with('success', 'Pembayaran denda berhasil!');
    }
}
