@extends('layouts.app')
@section('title', 'Bayar Denda')

@section('content')
<div class="container section" style="padding-top:2rem;max-width:600px">
    <div class="card" style="border-top:4px solid #EF4444">
        <div class="card-header">⚠️ Pembayaran Denda</div>
        <div class="card-body">
            <div class="order-row"><span class="text-abu">Item</span><span class="fw-bold">{{ $reservasi->item->nama_item }}</span></div>
            @if($reservasi->denda)
            <div class="order-row"><span class="text-abu">Denda Keterlambatan</span><span>Rp {{ number_format($reservasi->denda->denda_keterlambatan, 0, ',', '.') }}</span></div>
            <div class="order-row"><span class="text-abu">Denda Kerusakan</span><span>Rp {{ number_format($reservasi->denda->denda_kerusakan, 0, ',', '.') }}</span></div>
            @if($reservasi->denda->keterangan)
            <div class="alert alert-warning" style="margin:1rem 0">{{ $reservasi->denda->keterangan }}</div>
            @endif
            @endif
            <div class="order-total">
                <span>Total Denda</span>
                <span class="text-merah">Rp {{ number_format($paymentDenda->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('bayar.denda.konfirmasi', $paymentDenda->payment_id) }}" style="margin-top:1.5rem">
        @csrf
        <div class="card">
            <div class="card-header">🏦 Metode Pembayaran</div>
            <div class="card-body">
                @foreach([
                    ['value'=>'transfer_bca','label'=>'Transfer BCA','icon'=>'🏦'],
                    ['value'=>'gopay','label'=>'GoPay','icon'=>'💚'],
                    ['value'=>'dana','label'=>'DANA','icon'=>'💙'],
                    ['value'=>'tunai','label'=>'Tunai di Tempat','icon'=>'💵'],
                ] as $m)
                <label class="payment-method">
                    <input type="radio" name="metode_bayar" value="{{ $m['value'] }}" required>
                    <span style="font-size:1.4rem">{{ $m['icon'] }}</span>
                    <span class="fw-bold">{{ $m['label'] }}</span>
                </label>
                @endforeach
                <button type="submit" class="btn btn-danger btn-block btn-lg" style="margin-top:1rem">
                    ✅ Konfirmasi Bayar Denda
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
