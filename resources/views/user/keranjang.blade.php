@extends('layouts.app')
@section('title', 'Keranjang Sewa')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>🛒 Keranjang Sewa</h1>
    </div>
</div>

<div class="container section" style="padding-top:1.5rem">
    @if(count($keranjang) > 0)
    <div class="grid-2" style="align-items:start;gap:2rem">
        <!-- ITEM KERANJANG -->
        <div class="card">
            <div class="card-header">
                Item Sewa ({{ count($keranjang) }})
            </div>
            @foreach($keranjang as $index => $cart)
            <div class="cart-item">
                <div class="cart-img">
                    @if($cart['foto'])
                        <img src="{{ asset('storage/' . $cart['foto']) }}" alt="{{ $cart['nama_item'] }}">
                    @else
                        🏕
                    @endif
                </div>
                <div style="flex:1">
                    <div class="fw-bold">{{ $cart['nama_item'] }}</div>
                    <div style="font-size:.82rem;color:var(--teks-abu)">
                        {{ \Carbon\Carbon::parse($cart['tanggal_mulai'])->format('d M Y') }}
                        → {{ \Carbon\Carbon::parse($cart['tanggal_selesai'])->format('d M Y') }}
                        ({{ $cart['jumlah_hari'] }} hari)
                    </div>
                    <div class="text-hijau fw-bold" style="margin-top:4px">
                        Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}
                    </div>
                </div>
                <form method="POST" action="{{ route('keranjang.hapus', $index) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger no-loading" title="Hapus">🗑</button>
                </form>
            </div>
            @endforeach
        </div>

        <!-- RINGKASAN ORDER -->
        <div>
            <div class="order-summary card card-body">
                <h3 style="margin-bottom:1rem;font-size:1.1rem">Ringkasan Pesanan</h3>
                <div class="order-row">
                    <span class="text-abu">Subtotal Sewa</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="order-row">
                    <span class="text-abu">Biaya Layanan (5%)</span>
                    <span>Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span>
                </div>
                <div class="order-total">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('checkout') }}" class="btn btn-primary btn-block btn-lg" style="margin-top:1.5rem">
                    Lanjut Checkout →
                </a>
                <a href="{{ route('katalog') }}" class="btn btn-outline btn-block" style="margin-top:.75rem">
                    + Tambah Item
                </a>
            </div>
        </div>
    </div>
    @else
    <div class="empty-state">
        <div class="icon">🛒</div>
        <h3>Keranjang Anda Kosong</h3>
        <p class="text-abu">Pilih alat camping dari katalog kami.</p>
        <a href="{{ route('katalog') }}" class="btn btn-primary mt-2">Lihat Katalog</a>
    </div>
    @endif
</div>
@endsection
