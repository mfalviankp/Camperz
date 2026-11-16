@extends('layouts.app')
@section('title', $item->nama_item)

@section('content')
<div class="container section" style="padding-top:2rem">
    <a href="{{ route('katalog') }}" class="btn btn-outline btn-sm mb-3">← Kembali ke Katalog</a>

    <div class="grid-2" style="gap:2rem;align-items:start">
        <!-- FOTO ITEM -->
        <div class="card" style="overflow:hidden">
            @if($item->foto)
                <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_item }}" style="width:100%;height:360px;object-fit:cover">
            @else
                <div style="height:360px;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:flex;align-items:center;justify-content:center;font-size:8rem">
                    @php
                    $icons = ['tenda'=>'⛺','sleeping_bag'=>'🛌','carrier'=>'🎒','kompor'=>'🔥','matras'=>'🟫','senter'=>'🔦'];
                    echo $icons[strtolower($item->kategori)] ?? '🏕';
                    @endphp
                </div>
            @endif
        </div>

        <!-- DETAIL & FORM SEWA -->
        <div>
            <div class="card">
                <div class="card-body">
                    <span class="badge badge-hijau mb-2">{{ ucfirst($item->kategori) }}</span>
                    <h1 style="font-size:1.6rem;margin-bottom:.5rem">{{ $item->nama_item }}</h1>
                    <div style="font-size:1.8rem;font-weight:800;color:var(--hijau)">
                        Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                        <span style="font-size:1rem;color:var(--teks-abu);font-weight:500">/hari</span>
                    </div>

                    @if($item->deskripsi)
                    <p style="margin-top:1rem;color:var(--teks-abu);line-height:1.7">{{ $item->deskripsi }}</p>
                    @endif

                    <div style="display:flex;align-items:center;gap:8px;margin:1rem 0;padding:12px;background:var(--krem);border-radius:8px">
                        @if($item->jumlah_stok > 0)
                            <span style="color:var(--hijau);font-weight:700">✅ Tersedia</span>
                            <span class="text-abu">— {{ $item->jumlah_stok }} unit</span>
                        @else
                            <span style="color:#EF4444;font-weight:700">❌ Stok Habis</span>
                        @endif
                    </div>

                    @if($item->jumlah_stok > 0)
                    <!-- FORM TAMBAH KE KERANJANG -->
                    <form method="POST" action="{{ route('keranjang.tambah') }}">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai Sewa</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                   min="{{ date('Y-m-d') }}" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required
                                   id="tanggalMulai" onchange="hitungHari()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Selesai Sewa</label>
                            <input type="date" name="tanggal_selesai" class="form-control"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('tanggal_selesai') }}" required
                                   id="tanggalSelesai" onchange="hitungHari()">
                        </div>

                        <!-- Preview harga -->
                        <div id="previewHarga" style="display:none;background:var(--krem);border-radius:10px;padding:1rem;margin-bottom:1rem">
                            <div class="order-row">
                                <span class="text-abu">Durasi Sewa</span>
                                <span id="previewHari" class="fw-bold"></span>
                            </div>
                            <div class="order-row">
                                <span class="text-abu">Harga per Hari</span>
                                <span>Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</span>
                            </div>
                            <div class="order-row">
                                <span class="text-abu">Biaya Layanan (5%)</span>
                                <span id="previewLayanan"></span>
                            </div>
                            <div class="order-total">
                                <span>Estimasi Total</span>
                                <span id="previewTotal" class="text-hijau"></span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block btn-lg">
                            🛒 Tambah ke Keranjang
                        </button>
                    </form>
                    @else
                        <div class="alert alert-warning">Maaf, stok item ini sedang habis. Coba lagi nanti.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const hargaSewa = {{ $item->harga_sewa }};

function hitungHari() {
    const mulai = document.getElementById('tanggalMulai').value;
    const selesai = document.getElementById('tanggalSelesai').value;
    if (!mulai || !selesai) return;

    const d1 = new Date(mulai), d2 = new Date(selesai);
    let hari = Math.round((d2 - d1) / (1000 * 60 * 60 * 24));
    if (hari < 1) hari = 1;

    const subtotal = hargaSewa * hari;
    const layanan = subtotal * 0.05;
    const total = subtotal + layanan;

    document.getElementById('previewHari').textContent = hari + ' hari';
    document.getElementById('previewLayanan').textContent = 'Rp ' + layanan.toLocaleString('id-ID');
    document.getElementById('previewTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('previewHarga').style.display = 'block';
}
</script>
@endpush
@endsection
