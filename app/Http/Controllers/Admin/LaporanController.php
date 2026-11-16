<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Denda;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Halaman laporan
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        // Total pemasukan bulan ini (payment yang sudah paid)
        $totalPemasukan = Payment::where('status', 'paid')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_bayar');

        // Total pemasukan sewa
        $pemasukanSewa = Payment::where('status', 'paid')
            ->where('jenis', 'sewa')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_bayar');

        // Total pemasukan denda
        $pemasukanDenda = Payment::where('status', 'paid')
            ->where('jenis', 'denda')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_bayar');

        // Jumlah transaksi selesai
        $transaksiSelesai = Reservation::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        // Total denda bulan ini
        $totalDenda = Denda::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->sum('total_denda');

        // Data per minggu untuk tabel statistik
        $dataMingguan = [];
        $tanggalAwal = Carbon::create($tahun, $bulan, 1);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        for ($minggu = 1; $minggu <= 5; $minggu++) {
            $mulai = $tanggalAwal->copy()->addWeeks($minggu - 1);
            if ($mulai->gt($tanggalAkhir)) break;

            $selesai = $mulai->copy()->addDays(6);
            if ($selesai->gt($tanggalAkhir)) $selesai = $tanggalAkhir->copy();

            $pendapatan = Payment::where('status', 'paid')
                ->whereBetween('tanggal_bayar', [$mulai->startOfDay(), $selesai->endOfDay()])
                ->sum('jumlah_bayar');

            $jumlahTransaksi = Reservation::whereBetween('created_at', [$mulai->startOfDay(), $selesai->endOfDay()])
                ->count();

            $dataMingguan[] = [
                'minggu' => $minggu,
                'periode' => $mulai->format('d/m') . ' - ' . $selesai->format('d/m'),
                'pendapatan' => $pendapatan,
                'jumlah_transaksi' => $jumlahTransaksi,
            ];
        }

        // Daftar transaksi bulan ini
        $transaksis = Reservation::with(['user', 'item', 'payments'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.laporan.index', compact(
            'totalPemasukan',
            'pemasukanSewa',
            'pemasukanDenda',
            'transaksiSelesai',
            'totalDenda',
            'dataMingguan',
            'transaksis',
            'bulan',
            'tahun'
        ));
    }

    // Cetak PDF laporan
    public function cetakPdf(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $totalPemasukan = Payment::where('status', 'paid')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_bayar');

        $pemasukanSewa = Payment::where('status', 'paid')
            ->where('jenis', 'sewa')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_bayar');

        $pemasukanDenda = Payment::where('status', 'paid')
            ->where('jenis', 'denda')
            ->whereMonth('tanggal_bayar', $bulan)
            ->whereYear('tanggal_bayar', $tahun)
            ->sum('jumlah_bayar');

        $transaksiSelesai = Reservation::where('status', 'selesai')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->count();

        $transaksis = Reservation::with(['user', 'item', 'payments'])
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at', 'desc')
            ->get();

        $namaBulan = Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y');

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'totalPemasukan',
            'pemasukanSewa',
            'pemasukanDenda',
            'transaksiSelesai',
            'transaksis',
            'namaBulan'
        ));

        return $pdf->stream('laporan-camperz-' . $bulan . '-' . $tahun . '.pdf');
    }
}
