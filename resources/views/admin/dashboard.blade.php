@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')
<!-- STAT CARDS -->
<div class="grid-4 mb-3">
    <div class="stat-card">
        <div class="stat-icon biru">📤</div>
        <div>
            <div class="stat-num">{{ $perluDiambil }}</div>
            <div class="stat-label">Perlu Diambil</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon hijau">✅</div>
        <div>
            <div class="stat-num">{{ $aktif }}</div>
            <div class="stat-label">Sedang Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon merah">⏰</div>
        <div>
            <div class="stat-num">{{ $terlambat }}</div>
            <div class="stat-label">Terlambat</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon oranye">💰</div>
        <div>
            <div class="stat-num" style="font-size:1.2rem">Rp {{ number_format($pemasukanBulanIni/1000, 0, ',', '.') }}rb</div>
            <div class="stat-label">Pemasukan Bulan Ini</div>
        </div>
    </div>
</div>

<!-- BARIS KEDUA -->
<div class="grid-3 mb-3">
    <div class="stat-card">
        <div class="stat-icon ungu" style="background:rgba(139,92,246,.1);color:#7C3AED">🔍</div>
        <div>
            <div class="stat-num">{{ $menungguInspeksi }}</div>
            <div class="stat-label">Menunggu Inspeksi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon merah">⚠️</div>
        <div>
            <div class="stat-num">{{ $menungguDenda }}</div>
            <div class="stat-label">Menunggu Bayar Denda</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon hijau">📦</div>
        <div>
            <div class="stat-num">{{ $totalItem }}</div>
            <div class="stat-label">Total Stok Tersedia</div>
        </div>
    </div>
</div>

<!-- AKSI CEPAT -->
<div class="grid-2 mb-3">
    <a href="{{ route('admin.scan.pickup') }}" class="stat-card" style="cursor:pointer;text-decoration:none;background:linear-gradient(135deg,var(--hijau),var(--hijau-muda));color:white">
        <div style="font-size:2.5rem">📤</div>
        <div>
            <div style="font-size:1.1rem;font-weight:700">Scan Pickup</div>
            <div style="opacity:.85;font-size:.85rem">Konfirmasi pengambilan barang</div>
        </div>
    </a>
    <a href="{{ route('admin.scan.return') }}" class="stat-card" style="cursor:pointer;text-decoration:none;background:linear-gradient(135deg,var(--oranye-tua),var(--oranye));color:white">
        <div style="font-size:2.5rem">📥</div>
        <div>
            <div style="font-size:1.1rem;font-weight:700">Scan Return</div>
            <div style="opacity:.85;font-size:.85rem">Terima pengembalian barang</div>
        </div>
    </a>
</div>

<!-- TRANSAKSI TERBARU -->
<div class="card">
    <div class="card-header" style="display:flex;justify-content:space-between">
        <span>📋 Transaksi Terbaru</span>
        <a href="{{ route('admin.laporan') }}" class="btn btn-outline btn-sm">Laporan Lengkap</a>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Penyewa</th><th>Item</th><th>Tanggal</th><th>Total</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksiTerbaru as $t)
                <tr>
                    <td style="font-family:monospace;font-size:.82rem">#{{ $t->reservation_id }}</td>
                    <td>{{ $t->user->nama ?? '-' }}</td>
                    <td>{{ $t->item->nama_item ?? '-' }}</td>
                    <td style="font-size:.82rem">{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>Rp {{ number_format($t->total_harga + $t->biaya_layanan, 0, ',', '.') }}</td>
                    <td><span class="badge badge-abu status-{{ $t->status }}">{{ str_replace('_',' ',ucfirst($t->status)) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-abu">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
