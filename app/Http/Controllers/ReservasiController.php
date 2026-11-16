<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservasiController extends Controller
{
    // Tambah item ke keranjang (session)
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Cek stok masih tersedia
        if ($item->jumlah_stok <= 0) {
            return back()->with('error', 'Maaf, stok item ini sedang habis.');
        }

        // Hitung jumlah hari
        $mulai = \Carbon\Carbon::parse($request->tanggal_mulai);
        $selesai = \Carbon\Carbon::parse($request->tanggal_selesai);
        $jumlahHari = $mulai->diffInDays($selesai);

        if ($jumlahHari < 1) {
            $jumlahHari = 1;
        }

        // Ambil keranjang dari session
        $keranjang = session('keranjang', []);

        // Tambah item ke keranjang
        $keranjang[] = [
            'item_id' => $item->item_id,
            'nama_item' => $item->nama_item,
            'foto' => $item->foto,
            'harga_sewa' => $item->harga_sewa,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jumlah_hari' => $jumlahHari,
            'subtotal' => $item->harga_sewa * $jumlahHari,
        ];

        session(['keranjang' => $keranjang]);

        return redirect()->route('keranjang')->with('success', 'Item berhasil ditambahkan ke keranjang!');
    }

    // Tampilkan keranjang
    public function keranjang()
    {
        $keranjang = session('keranjang', []);
        $total = collect($keranjang)->sum('subtotal');
        $biayaLayanan = $total * 0.05; // Biaya layanan 5%
        $grandTotal = $total + $biayaLayanan;

        return view('user.keranjang', compact('keranjang', 'total', 'biayaLayanan', 'grandTotal'));
    }

    // Hapus item dari keranjang
    public function hapusDariKeranjang($index)
    {
        $keranjang = session('keranjang', []);

        if (isset($keranjang[$index])) {
            unset($keranjang[$index]);
            $keranjang = array_values($keranjang); // Re-index array
            session(['keranjang' => $keranjang]);
        }

        return redirect()->route('keranjang')->with('success', 'Item dihapus dari keranjang.');
    }

    // Tampilkan halaman checkout
    public function checkout()
    {
        $keranjang = session('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('katalog')->with('error', 'Keranjang kosong. Silakan pilih item terlebih dahulu.');
        }

        $user = User::find(session('user_id'));
        $total = collect($keranjang)->sum('subtotal');
        $biayaLayanan = $total * 0.05;
        $grandTotal = $total + $biayaLayanan;

        return view('user.checkout', compact('keranjang', 'user', 'total', 'biayaLayanan', 'grandTotal'));
    }

    // Proses checkout — buat reservasi dan payment
    public function prosesCheckout(Request $request)
    {
        $keranjang = session('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('katalog')->with('error', 'Keranjang kosong.');
        }

        // Cek profil lengkap
        $user = User::find(session('user_id'));
        if (!$user->nama || !$user->no_hp || !$user->alamat) {
            return redirect()->route('profil')->with('error', 'Lengkapi profil Anda terlebih dahulu (nama, no HP, alamat).');
        }

        // Proses setiap item di keranjang
        foreach ($keranjang as $cart) {
            $item = Item::find($cart['item_id']);

            // Cek stok masih ada
            if (!$item || $item->jumlah_stok <= 0) {
                return back()->with('error', 'Stok "' . ($item->nama_item ?? 'Item') . '" sudah habis.');
            }

            $subtotal = $cart['subtotal'];
            $biayaLayanan = $subtotal * 0.05;
            $totalBayar = $subtotal + $biayaLayanan;

            // Buat record reservasi
            $reservasi = Reservation::create([
                'user_id' => session('user_id'),
                'item_id' => $cart['item_id'],
                'tanggal_mulai' => $cart['tanggal_mulai'],
                'tanggal_selesai' => $cart['tanggal_selesai'],
                'jumlah_hari' => $cart['jumlah_hari'],
                'total_harga' => $subtotal,
                'biaya_layanan' => $biayaLayanan,
                'status' => 'belum_bayar',
            ]);

            // Buat record payment
            Payment::create([
                'reservation_id' => $reservasi->reservation_id,
                'jumlah_bayar' => $totalBayar,
                'jenis' => 'sewa',
                'status' => 'pending',
            ]);
        }

        // Kosongkan keranjang
        session()->forget('keranjang');

        return redirect()->route('transaksi.index')->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }
}
