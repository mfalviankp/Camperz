@extends('layouts.app')
@section('title', 'E-Tiket Sewa')

@section('content')
<div class="container section" style="padding-top:2rem">
    <div class="etiket-card">
        <div class="etiket-header">
            <div style="font-size:2rem">🏕</div>
            <h2 style="margin:.5rem 0 .25rem">Camperz</h2>
            <p style="opacity:.85;font-size:.9rem">E-Tiket Pengambilan Barang</p>
        </div>
        <div class="etiket-body">
            <p style="font-size:.85rem;color:var(--teks-abu);margin-bottom:1rem">
                Tunjukkan tiket ini kepada admin saat pengambilan
            </p>

            <!-- QR CODE -->
            <div class="etiket-qr">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(180)->generate($reservasi->qr_pickup) !!}
            </div>

            <!-- KODE MANUAL -->
            <div class="etiket-kode">{{ $reservasi->qr_pickup }}</div>

            <!-- INFO RESERVASI -->
            <div class="etiket-info">
                <div class="etiket-info-row">
                    <span class="label">Item</span>
                    <span class="value">{{ $reservasi->item->nama_item }}</span>
                </div>
                <div class="etiket-info-row">
                    <span class="label">Penyewa</span>
                    <span class="value">{{ $reservasi->user->nama }}</span>
                </div>
                <div class="etiket-info-row">
                    <span class="label">Mulai Sewa</span>
                    <span class="value">{{ $reservasi->tanggal_mulai->format('d M Y') }}</span>
                </div>
                <div class="etiket-info-row">
                    <span class="label">Selesai Sewa</span>
                    <span class="value">{{ $reservasi->tanggal_selesai->format('d M Y') }}</span>
                </div>
                <div class="etiket-info-row">
                    <span class="label">Durasi</span>
                    <span class="value">{{ $reservasi->jumlah_hari }} hari</span>
                </div>
                <div class="etiket-info-row">
                    <span class="label">Total Bayar</span>
                    <span class="value text-hijau">Rp {{ number_format($reservasi->payments->sum('jumlah_bayar'), 0, ',', '.') }}</span>
                </div>
                <div class="etiket-info-row">
                    <span class="label">Status</span>
                    <span class="badge badge-biru">Menunggu Pengambilan</span>
                </div>
            </div>

            <div class="d-flex gap-1" style="margin-top:1.5rem">
                <a href="{{ route('transaksi.index') }}" class="btn btn-outline btn-block">📋 Transaksi</a>
                <button onclick="window.print()" class="btn btn-primary btn-block">🖨 Cetak Tiket</button>
            </div>
        </div>
    </div>
</div>
@endsection
