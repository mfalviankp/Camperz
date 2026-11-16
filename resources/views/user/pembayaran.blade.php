@extends('layouts.app')
@section('title', 'Pembayaran')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>💳 Pembayaran</h1>
    </div>
</div>

<div class="container section" style="padding-top:1.5rem;max-width:700px">
    <!-- COUNTDOWN -->
    <div class="countdown-box">
        <div class="countdown-label">⏰ Selesaikan pembayaran dalam</div>
        <div id="countdown">23:59:59</div>
        <div style="font-size:.8rem;color:var(--teks-abu);margin-top:4px">Pesanan akan otomatis dibatalkan jika tidak dibayar</div>
    </div>

    <div class="card mb-2">
        <div class="card-header">📦 Detail Pesanan</div>
        <div class="card-body">
            <div class="order-row"><span class="text-abu">Item</span><span class="fw-bold">{{ $reservasi->item->nama_item }}</span></div>
            <div class="order-row">
                <span class="text-abu">Periode Sewa</span>
                <span>{{ $reservasi->tanggal_mulai->format('d M Y') }} – {{ $reservasi->tanggal_selesai->format('d M Y') }}</span>
            </div>
            <div class="order-row"><span class="text-abu">Durasi</span><span>{{ $reservasi->jumlah_hari }} hari</span></div>
            <div class="order-row"><span class="text-abu">Biaya Sewa</span><span>Rp {{ number_format($reservasi->total_harga, 0, ',', '.') }}</span></div>
            <div class="order-row"><span class="text-abu">Biaya Layanan</span><span>Rp {{ number_format($reservasi->biaya_layanan, 0, ',', '.') }}</span></div>
            <div class="order-total">
                <span>Total Bayar</span>
                <span class="text-hijau">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- PILIH METODE BAYAR -->
    <form method="POST" action="{{ route('pembayaran.konfirmasi', $payment->payment_id) }}">
        @csrf
        <div class="card">
            <div class="card-header">🏦 Pilih Metode Pembayaran</div>
            <div class="card-body">
                @foreach([
                    ['value'=>'transfer_bca','label'=>'Transfer BCA','icon'=>'🏦','info'=>'BCA 1234567890 a.n. Camperz Indonesia'],
                    ['value'=>'transfer_mandiri','label'=>'Transfer Mandiri','icon'=>'🏦','info'=>'Mandiri 0987654321 a.n. Camperz Indonesia'],
                    ['value'=>'gopay','label'=>'GoPay','icon'=>'💚','info'=>'08123456789 (QR tersedia di kasir)'],
                    ['value'=>'dana','label'=>'DANA','icon'=>'💙','info'=>'08123456789 (QR tersedia di kasir)'],
                    ['value'=>'qris','label'=>'QRIS','icon'=>'📱','info'=>'Scan QR saat pengambilan barang'],
                ] as $m)
                <label class="payment-method" onclick="this.classList.toggle('selected')">
                    <input type="radio" name="metode_bayar" value="{{ $m['value'] }}" required>
                    <span style="font-size:1.4rem">{{ $m['icon'] }}</span>
                    <div>
                        <div class="fw-bold">{{ $m['label'] }}</div>
                        <div style="font-size:.8rem;color:var(--teks-abu)">{{ $m['info'] }}</div>
                    </div>
                </label>
                @endforeach

                <div class="alert alert-info" style="margin-top:1rem">
                    ℹ️ <strong>Simulasi:</strong> Klik "Konfirmasi Pembayaran" untuk melanjutkan (tidak ada transaksi nyata).
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:1rem">
                    ✅ Konfirmasi Pembayaran
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Countdown 24 jam dari sekarang
const endTime = new Date().getTime() + (24 * 60 * 60 * 1000);
function updateCountdown() {
    const now = new Date().getTime();
    const dist = endTime - now;
    if (dist <= 0) { document.getElementById('countdown').textContent = '00:00:00'; return; }
    const h = String(Math.floor((dist % (1000*60*60*24)) / (1000*60*60))).padStart(2,'0');
    const m = String(Math.floor((dist % (1000*60*60)) / (1000*60))).padStart(2,'0');
    const s = String(Math.floor((dist % (1000*60)) / 1000)).padStart(2,'0');
    document.getElementById('countdown').textContent = h + ':' + m + ':' + s;
}
updateCountdown();
setInterval(updateCountdown, 1000);

// Highlight selected payment method
document.querySelectorAll('.payment-method input').forEach(r => {
    r.addEventListener('change', () => {
        document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
        r.closest('.payment-method').classList.add('selected');
    });
});
</script>
@endpush
@endsection
