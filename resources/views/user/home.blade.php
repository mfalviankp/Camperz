@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
<!-- HERO SECTION -->
<section class="hero">
    <div class="container">
        <h1>⛺ Sewa Alat Camping, Mudah & Terpercaya</h1>
        <p>Temukan perlengkapan camping terlengkap untuk petualangan tak terlupakan bersama Camperz.</p>
        <div style="margin-top:1.5rem;display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('katalog') }}" class="btn btn-oranye btn-lg">🔍 Lihat Katalog</a>
            @if(!session('user_id'))
            <a href="{{ route('register') }}" class="btn btn-lg" style="background:rgba(255,255,255,.15);color:white;border:2px solid rgba(255,255,255,.4)">Daftar Gratis</a>
            @endif
        </div>
    </div>
</section>

<!-- STATISTIK USER -->
@if(session('user_id'))
<section style="background:white;padding:1.5rem 0;border-bottom:1px solid var(--border)">
    <div class="container">
        <div class="grid-2" style="max-width:500px">
            <div style="text-align:center;padding:1rem;border-radius:12px;background:var(--krem)">
                <div style="font-size:2rem;font-weight:800;color:var(--hijau)">{{ $totalTransaksi }}</div>
                <div style="font-size:.82rem;color:var(--teks-abu);font-weight:600">Total Transaksi</div>
            </div>
            <div style="text-align:center;padding:1rem;border-radius:12px;background:var(--krem)">
                <div style="font-size:2rem;font-weight:800;color:var(--oranye-tua)">{{ $transaksiAktif }}</div>
                <div style="font-size:.82rem;color:var(--teks-abu);font-weight:600">Sedang Disewa</div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- ITEM POPULER -->
<section class="section">
    <div class="container">
        <div class="d-flex justify-between align-center mb-3">
            <div>
                <h2 style="font-size:1.6rem;color:var(--hijau-tua)">🔥 Item Populer</h2>
                <p class="text-abu">Perlengkapan camping paling dicari</p>
            </div>
            <a href="{{ route('katalog') }}" class="btn btn-outline">Lihat Semua →</a>
        </div>

        @if($itemPopuler->count() > 0)
        <div class="katalog-grid">
            @foreach($itemPopuler as $item)
            <div class="item-card">
                <div class="item-card-img">
                    @if($item->foto)
                        <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_item }}">
                    @else
                        @php
                        $icons = ['tenda'=>'⛺','sleeping_bag'=>'🛌','carrier'=>'🎒','kompor'=>'🔥','matras'=>'🟫','senter'=>'🔦'];
                        $icon = $icons[strtolower($item->kategori)] ?? '🏕';
                        @endphp
                        {{ $icon }}
                    @endif
                </div>
                <div class="item-card-body">
                    <div class="mb-1">
                        <span class="badge badge-hijau">{{ ucfirst($item->kategori) }}</span>
                    </div>
                    <div class="item-card-title">{{ $item->nama_item }}</div>
                    <div class="item-card-price">
                        Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                        <span>/hari</span>
                    </div>
                    <div style="font-size:.82rem;color:var(--teks-abu);margin-top:4px">
                        Stok: {{ $item->jumlah_stok }} unit
                    </div>
                </div>
                <div class="item-card-footer">
                    <a href="{{ route('katalog.detail', $item->item_id) }}" class="btn btn-primary btn-block">
                        Sewa Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="icon">🏕</div>
            <h3>Belum ada item tersedia</h3>
            <p class="text-abu">Admin sedang menambahkan produk baru.</p>
        </div>
        @endif
    </div>
</section>

<!-- FITUR UNGGULAN -->
<section style="background:var(--hijau-tua);padding:4rem 0;color:white">
    <div class="container">
        <h2 class="text-center" style="font-size:1.8rem;margin-bottom:2.5rem">Mengapa Pilih Camperz?</h2>
        <div class="grid-3" style="text-align:center">
            <div style="padding:1.5rem">
                <div style="font-size:3rem;margin-bottom:1rem">✅</div>
                <h3 style="margin-bottom:.5rem">Terpercaya</h3>
                <p style="opacity:.8;font-size:.9rem">Semua alat dicek kualitasnya sebelum dan sesudah disewa.</p>
            </div>
            <div style="padding:1.5rem">
                <div style="font-size:3rem;margin-bottom:1rem">📱</div>
                <h3 style="margin-bottom:.5rem">Mudah Dipakai</h3>
                <p style="opacity:.8;font-size:.9rem">Pesan online, bayar digital, ambil dengan scan QR.</p>
            </div>
            <div style="padding:1.5rem">
                <div style="font-size:3rem;margin-bottom:1rem">💰</div>
                <h3 style="margin-bottom:.5rem">Harga Terjangkau</h3>
                <p style="opacity:.8;font-size:.9rem">Harga sewa per hari yang kompetitif untuk semua kalangan.</p>
            </div>
        </div>
    </div>
</section>
@endsection
