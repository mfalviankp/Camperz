@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
<div class="container section" style="padding-top:2rem;max-width:700px">
    <a href="{{ route('transaksi.index') }}" class="btn btn-outline btn-sm mb-3">← Kembali</a>

    <div class="card mb-2">
        <div class="card-header" style="display:flex;justify-content:space-between;align-items:center">
            <span>📋 Detail Transaksi #{{ $reservasi->reservation_id }}</span>
            <span class="badge badge-abu status-{{ $reservasi->status }}">
                {{ str_replace('_',' ',ucfirst($reservasi->status)) }}
            </span>
        </div>
        <div class="card-body">
            <div class="order-row"><span class="text-abu">Item</span><span class="fw-bold">{{ $reservasi->item->nama_item }}</span></div>
            <div class="order-row">
                <span class="text-abu">Periode</span>
                <span>{{ $reservasi->tanggal_mulai->format('d M Y') }} – {{ $reservasi->tanggal_selesai->format('d M Y') }}</span>
            </div>
            <div class="order-row"><span class="text-abu">Durasi</span><span>{{ $reservasi->jumlah_hari }} hari</span></div>
            <div class="order-row"><span class="text-abu">Biaya Sewa</span><span>Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</span></div>
            <div class="order-row"><span class="text-abu">Biaya Layanan</span><span>Rp {{ number_format($reservasi->biaya_layanan, 0, ',', '.') }}</span></div>
        </div>
    </div>

    <!-- PEMBAYARAN -->
    @foreach($reservasi->payments as $p)
    <div class="card mb-2">
        <div class="card-body">
            <div style="font-weight:700;margin-bottom:.5rem">
                💳 Pembayaran {{ ucfirst($p->jenis) }}
                <span class="badge {{ $p->status === 'paid' ? 'badge-hijau' : 'badge-oranye' }}">{{ ucfirst($p->status) }}</span>
            </div>
            <div class="order-row"><span class="text-abu">Jumlah</span><span class="fw-bold text-hijau">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span></div>
            @if($p->metode_bayar)<div class="order-row"><span class="text-abu">Metode</span><span>{{ str_replace('_',' ',ucfirst($p->metode_bayar)) }}</span></div>@endif
            @if($p->tanggal_bayar)<div class="order-row"><span class="text-abu">Tanggal Bayar</span><span>{{ $p->tanggal_bayar->format('d M Y H:i') }}</span></div>@endif
        </div>
    </div>
    @endforeach

    <!-- DENDA (jika ada) -->
    @if($reservasi->denda)
    <div class="card mb-2" style="border-left:4px solid #EF4444">
        <div class="card-body">
            <div style="font-weight:700;color:#DC2626;margin-bottom:.5rem">⚠️ Denda</div>
            <div class="order-row"><span class="text-abu">Denda Keterlambatan</span><span>Rp {{ number_format($reservasi->denda->denda_keterlambatan, 0, ',', '.') }}</span></div>
            <div class="order-row"><span class="text-abu">Denda Kerusakan</span><span>Rp {{ number_format($reservasi->denda->denda_kerusakan, 0, ',', '.') }}</span></div>
            <div class="order-row fw-bold"><span>Total Denda</span><span class="text-merah">Rp {{ number_format($reservasi->denda->total_denda, 0, ',', '.') }}</span></div>
            @if($reservasi->denda->keterangan)
            <div style="margin-top:.5rem;font-size:.85rem;color:var(--teks-abu)">{{ $reservasi->denda->keterangan }}</div>
            @endif
        </div>
    </div>
    @endif

    <!-- AKSI -->
    <div style="display:flex;gap:.75rem">
        @if($reservasi->status === 'belum_bayar')
            <a href="{{ route('pembayaran', $reservasi->reservation_id) }}" class="btn btn-oranye">💳 Bayar Sekarang</a>
        @elseif($reservasi->status === 'menunggu_pengambilan')
            <a href="{{ route('etiket', $reservasi->reservation_id) }}" class="btn btn-primary">🎫 Lihat E-Tiket</a>
        @elseif($reservasi->status === 'menunggu_pelunasan_denda')
            <a href="{{ route('bayar.denda', $reservasi->reservation_id) }}" class="btn btn-danger">⚠️ Bayar Denda</a>
        @endif
    </div>
</div>
@endsection
