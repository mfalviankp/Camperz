@extends('layouts.admin')
@section('title', 'Input Denda')
@section('page-title', 'Input Denda')

@section('content')
<div style="max-width:600px">
    <div class="card mb-2" style="border-top:4px solid #EF4444">
        <div class="card-header" style="background:#FEF2F2">⚠️ Data Pelanggaran</div>
        <div class="card-body">
            <div class="order-row"><span class="text-abu">Penyewa</span><span class="fw-bold">{{ $reservasi->user->nama }}</span></div>
            <div class="order-row"><span class="text-abu">Item</span><span class="fw-bold">{{ $reservasi->item->nama_item }}</span></div>
            <div class="order-row"><span class="text-abu">Keterlambatan</span>
                <span class="{{ $hariTerlambat > 0 ? 'text-merah fw-bold' : '' }}">
                    {{ $hariTerlambat > 0 ? $hariTerlambat . ' hari' : 'Tidak terlambat' }}
                </span>
            </div>
            @if($hariTerlambat > 0)
            <div class="order-row">
                <span class="text-abu">Estimasi Denda Terlambat</span>
                <span class="text-merah fw-bold">Rp {{ number_format($dendaKeterlambatan, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">💰 Form Input Denda</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.denda.simpan', $reservasi->reservation_id) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Denda Keterlambatan (Rp)</label>
                    <input type="number" name="denda_keterlambatan" class="form-control"
                           value="{{ old('denda_keterlambatan', $dendaKeterlambatan) }}" min="0" required>
                    <div style="font-size:.8rem;color:var(--teks-abu);margin-top:4px">
                        Otomatis dihitung: harga/hari × hari terlambat × 1.5
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Denda Kerusakan (Rp)</label>
                    <input type="number" name="denda_kerusakan" class="form-control" value="{{ old('denda_kerusakan', 0) }}" min="0" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan <span class="text-abu">(opsional)</span></label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi kerusakan, dll...">{{ old('keterangan') }}</textarea>
                </div>
                <button type="submit" class="btn btn-danger btn-block btn-lg">
                    ⚠️ Simpan & Kirim Tagihan ke User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
