@extends('layouts.admin')
@section('title', 'Inspeksi Barang')
@section('page-title', 'Inspeksi Kondisi Barang')

@section('content')
<div style="max-width:700px">
    <div class="card mb-2">
        <div class="card-header">📋 Detail Pengembalian</div>
        <div class="card-body">
            <div class="order-row"><span class="text-abu">Penyewa</span><span class="fw-bold">{{ $reservasi->user->nama }}</span></div>
            <div class="order-row"><span class="text-abu">Item</span><span class="fw-bold">{{ $reservasi->item->nama_item }}</span></div>
            <div class="order-row"><span class="text-abu">Tanggal Selesai Sewa</span><span>{{ $reservasi->tanggal_selesai->format('d M Y') }}</span></div>
            <div class="order-row">
                <span class="text-abu">Waktu Pengembalian</span>
                <span>{{ $reservasi->tanggal_pengembalian_aktual?->format('d M Y H:i') }}</span>
            </div>

            @if($terlambat)
            <div class="alert alert-danger" style="margin-top:1rem">
                ⚠️ <strong>TERLAMBAT {{ $hariTerlambat }} hari!</strong>
                Akan dikenakan denda keterlambatan.
            </div>
            @else
            <div class="alert alert-success" style="margin-top:1rem">
                ✅ Pengembalian tepat waktu.
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">🔍 Form Inspeksi Kondisi</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.inspeksi.proses', $reservasi->reservation_id) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Kondisi Barang *</label>
                    <div style="display:flex;gap:1rem;margin-top:.5rem">
                        <label class="payment-method" style="flex:1;justify-content:center">
                            <input type="radio" name="kondisi" value="baik" required>
                            <span style="font-size:1.5rem">✅</span>
                            <div>
                                <div class="fw-bold">Baik</div>
                                <div style="font-size:.8rem;color:var(--teks-abu)">Tidak ada kerusakan</div>
                            </div>
                        </label>
                        <label class="payment-method" style="flex:1;justify-content:center;border-color:#FCA5A5">
                            <input type="radio" name="kondisi" value="rusak">
                            <span style="font-size:1.5rem">❌</span>
                            <div>
                                <div class="fw-bold" style="color:#DC2626">Rusak</div>
                                <div style="font-size:.8rem;color:var(--teks-abu)">Ada kerusakan</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="alert alert-info">
                    ℹ️ Jika kondisi baik dan tidak terlambat → status <strong>Selesai</strong>.
                    Jika ada masalah → lanjut ke form <strong>input denda</strong>.
                </div>

                <button type="submit" class="btn btn-primary btn-lg">
                    ✅ Proses Inspeksi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
