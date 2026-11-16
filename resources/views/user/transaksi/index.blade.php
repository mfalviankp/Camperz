@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>📋 Riwayat Transaksi</h1>
    </div>
</div>

<div class="container section" style="padding-top:1.5rem">
    <!-- TAB STATUS -->
    <div class="tabs">
        @foreach([
            'semua' => 'Semua',
            'belum_bayar' => 'Belum Bayar',
            'menunggu_pengambilan' => 'Menunggu Ambil',
            'aktif' => 'Aktif',
            'menunggu_pelunasan_denda' => 'Ada Denda',
            'selesai' => 'Selesai',
        ] as $key => $label)
        <a href="{{ route('transaksi.index', ['status' => $key]) }}"
           class="tab-btn {{ $status == $key ? 'active' : '' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>

    @if($transaksis->count() > 0)
    <div style="display:flex;flex-direction:column;gap:1rem">
        @foreach($transaksis as $t)
        <div class="card">
            <div class="card-body" style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
                <!-- Foto item -->
                <div style="width:70px;height:70px;border-radius:10px;overflow:hidden;flex-shrink:0;background:var(--krem);display:flex;align-items:center;justify-content:center;font-size:2rem">
                    @if($t->item && $t->item->foto)
                        <img src="{{ asset('storage/'.$t->item->foto) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        🏕
                    @endif
                </div>
                <!-- Info -->
                <div style="flex:1">
                    <div class="fw-bold">{{ $t->item->nama_item ?? 'Item dihapus' }}</div>
                    <div style="font-size:.82rem;color:var(--teks-abu)">
                        {{ $t->tanggal_mulai->format('d M Y') }} – {{ $t->tanggal_selesai->format('d M Y') }}
                        ({{ $t->jumlah_hari }} hari)
                    </div>
                    <div class="fw-bold text-hijau">
                        Rp {{ number_format($t->total_harga + $t->biaya_layanan, 0, ',', '.') }}
                    </div>
                </div>
                <!-- Status & aksi -->
                <div style="text-align:right">
                    <span class="badge badge-abu status-{{ $t->status }}" style="display:block;margin-bottom:8px">
                        {{ str_replace('_', ' ', ucfirst($t->status)) }}
                    </span>
                    @if($t->status === 'belum_bayar')
                        <a href="{{ route('pembayaran', $t->reservation_id) }}" class="btn btn-oranye btn-sm">Bayar</a>
                    @elseif($t->status === 'menunggu_pengambilan')
                        <a href="{{ route('etiket', $t->reservation_id) }}" class="btn btn-primary btn-sm">E-Tiket</a>
                    @elseif($t->status === 'menunggu_pelunasan_denda')
                        <a href="{{ route('bayar.denda', $t->reservation_id) }}" class="btn btn-danger btn-sm">Bayar Denda</a>
                    @endif
                    <a href="{{ route('transaksi.detail', $t->reservation_id) }}" class="btn btn-outline btn-sm" style="margin-top:4px">Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div class="icon">📋</div>
        <h3>Belum ada transaksi</h3>
        <p class="text-abu">Mulai sewa alat camping sekarang!</p>
        <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">Lihat Katalog</a>
    </div>
    @endif
</div>
@endsection
