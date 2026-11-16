@extends('layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>📋 Konfirmasi Pesanan</h1>
    </div>
</div>

<div class="container section" style="padding-top:1.5rem">
    @if(!$user->nama || !$user->no_hp || !$user->alamat)
    <div class="alert alert-warning">
        ⚠️ Lengkapi profil Anda terlebih dahulu sebelum checkout.
        <a href="{{ route('profil') }}" class="fw-bold">Lengkapi Profil →</a>
    </div>
    @endif

    <div class="grid-2" style="align-items:start;gap:2rem">
        <!-- DETAIL PESANAN -->
        <div>
            <div class="card mb-2">
                <div class="card-header">📦 Item yang Dipesan</div>
                @foreach($keranjang as $cart)
                <div class="cart-item">
                    <div class="cart-img">
                        @if($cart['foto'])<img src="{{ asset('storage/'.$cart['foto']) }}" alt="">@else 🏕 @endif
                    </div>
                    <div style="flex:1">
                        <div class="fw-bold">{{ $cart['nama_item'] }}</div>
                        <div style="font-size:.82rem;color:var(--teks-abu)">
                            {{ \Carbon\Carbon::parse($cart['tanggal_mulai'])->format('d M Y') }}
                            – {{ \Carbon\Carbon::parse($cart['tanggal_selesai'])->format('d M Y') }}
                            ({{ $cart['jumlah_hari'] }} hari)
                        </div>
                        <div class="text-hijau fw-bold">Rp {{ number_format($cart['subtotal'], 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="card">
                <div class="card-header">👤 Data Penyewa</div>
                <div class="card-body">
                    <div class="order-row"><span class="text-abu">Nama</span><span class="fw-bold">{{ $user->nama }}</span></div>
                    <div class="order-row"><span class="text-abu">Email</span><span>{{ $user->email }}</span></div>
                    <div class="order-row"><span class="text-abu">No. HP</span><span>{{ $user->no_hp ?? '-' }}</span></div>
                    <div class="order-row"><span class="text-abu">Alamat</span><span>{{ $user->alamat ?? '-' }}</span></div>
                </div>
            </div>
        </div>

        <!-- RINGKASAN & SUBMIT -->
        <div>
            <div class="order-summary card card-body">
                <h3 style="margin-bottom:1rem;font-size:1.1rem">💰 Ringkasan Pembayaran</h3>
                <div class="order-row"><span class="text-abu">Subtotal</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
                <div class="order-row"><span class="text-abu">Biaya Layanan (5%)</span><span>Rp {{ number_format($biayaLayanan, 0, ',', '.') }}</span></div>
                <div class="order-total">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>

                <form method="POST" action="{{ route('checkout.proses') }}" style="margin-top:1.5rem">
                    @csrf
                    @if($user->nama && $user->no_hp && $user->alamat)
                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            ✅ Buat Pesanan
                        </button>
                    @else
                        <button type="button" class="btn btn-block btn-lg" disabled style="background:#D1FAE5;color:#6B7280">
                            Lengkapi Profil Dulu
                        </button>
                    @endif
                </form>

                <a href="{{ route('keranjang') }}" class="btn btn-outline btn-block" style="margin-top:.75rem">
                    ← Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
