@extends('layouts.admin')
@section('title', 'Scan Pickup')
@section('page-title', 'Scan QR Pickup')

@section('content')
<div style="max-width:700px">
    <!-- FORM SCAN -->
    <div class="card mb-3">
        <div class="card-header">📤 Masukkan Kode QR Pickup</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.scan.pickup.proses') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kode QR / Kode Manual</label>
                    <input type="text" name="qr_code" class="form-control"
                           placeholder="Contoh: PICK-12-ABCD"
                           value="{{ old('qr_code') }}"
                           autofocus required
                           style="font-family:monospace;font-size:1.1rem;letter-spacing:2px">
                    <div style="font-size:.82rem;color:var(--teks-abu);margin-top:4px">
                        Scan QR dari E-Tiket user atau ketik kode manual
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">🔍 Cari Reservasi</button>
            </form>
        </div>
    </div>

    <!-- HASIL SCAN (jika ada) -->
    @isset($reservasi)
    <div class="card" style="border-top:4px solid var(--hijau)">
        <div class="card-header" style="background:#ECFDF5">
            ✅ Reservasi Ditemukan — Konfirmasi Pickup
        </div>
        <div class="card-body">
            <div class="order-row"><span class="text-abu">Penyewa</span><span class="fw-bold">{{ $reservasi->user->nama }}</span></div>
            <div class="order-row"><span class="text-abu">No. HP</span><span>{{ $reservasi->user->no_hp }}</span></div>
            <div class="order-row"><span class="text-abu">Item</span><span class="fw-bold">{{ $reservasi->item->nama_item }}</span></div>
            <div class="order-row">
                <span class="text-abu">Periode Sewa</span>
                <span>{{ $reservasi->tanggal_mulai->format('d M Y') }} – {{ $reservasi->tanggal_selesai->format('d M Y') }}</span>
            </div>
            <div class="order-row"><span class="text-abu">Durasi</span><span>{{ $reservasi->jumlah_hari }} hari</span></div>
            <div class="order-row"><span class="text-abu">Kode Pickup</span><span style="font-family:monospace;font-weight:700;color:var(--hijau)">{{ $reservasi->qr_pickup }}</span></div>

            <div class="alert alert-info" style="margin-top:1rem">
                ℹ️ Pastikan identitas penyewa sudah diverifikasi sebelum konfirmasi.
            </div>

            <form method="POST" action="{{ route('admin.scan.pickup.konfirmasi', $reservasi->reservation_id) }}">
                @csrf
                <button type="submit" class="btn btn-primary btn-lg">
                    ✅ Konfirmasi — Barang Sudah Diambil
                </button>
            </form>
        </div>
    </div>
    @endisset
</div>
@endsection
