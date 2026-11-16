@extends('layouts.admin')
@section('title', 'Laporan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<!-- FILTER BULAN -->
<div class="card mb-3">
    <div class="card-body" style="display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap">
        <form method="GET" action="{{ route('admin.laporan') }}" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end;width:100%">
            <div class="form-group" style="margin:0">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create(null,$m)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="form-group" style="margin:0">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @for($y=date('Y');$y>=2024;$y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-primary">🔍 Filter</button>
            <a href="{{ route('admin.laporan.pdf', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn btn-oranye" target="_blank">📄 Cetak PDF</a>
        </form>
    </div>
</div>

<!-- RINGKASAN -->
<div class="grid-4 mb-3">
    <div class="stat-card">
        <div class="stat-icon hijau">💰</div>
        <div>
            <div class="stat-num" style="font-size:1.2rem">Rp {{ number_format($totalPemasukan/1000, 0, ',', '.') }}rb</div>
            <div class="stat-label">Total Pemasukan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon biru">💳</div>
        <div>
            <div class="stat-num" style="font-size:1.2rem">Rp {{ number_format($pemasukanSewa/1000, 0, ',', '.') }}rb</div>
            <div class="stat-label">Pemasukan Sewa</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon merah">⚠️</div>
        <div>
            <div class="stat-num" style="font-size:1.2rem">Rp {{ number_format($pemasukanDenda/1000, 0, ',', '.') }}rb</div>
            <div class="stat-label">Pemasukan Denda</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon oranye">✅</div>
        <div>
            <div class="stat-num">{{ $transaksiSelesai }}</div>
            <div class="stat-label">Transaksi Selesai</div>
        </div>
    </div>
</div>

<!-- TABEL MINGGUAN -->
<div class="card mb-3">
    <div class="card-header">📊 Statistik Per Minggu</div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>Minggu</th><th>Periode</th><th>Jumlah Transaksi</th><th>Pendapatan</th></tr>
            </thead>
            <tbody>
                @foreach($dataMingguan as $d)
                <tr>
                    <td>Minggu {{ $d['minggu'] }}</td>
                    <td>{{ $d['periode'] }}</td>
                    <td>{{ $d['jumlah_transaksi'] }}</td>
                    <td class="fw-bold text-hijau">Rp {{ number_format($d['pendapatan'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- DAFTAR TRANSAKSI -->
<div class="card">
    <div class="card-header">📋 Semua Transaksi Bulan Ini ({{ $transaksis->count() }} transaksi)</div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr><th>ID</th><th>Penyewa</th><th>Item</th><th>Total</th><th>Status</th><th>Tanggal</th></tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                <tr>
                    <td style="font-family:monospace">#{{ $t->reservation_id }}</td>
                    <td>{{ $t->user->nama ?? '-' }}</td>
                    <td>{{ $t->item->nama_item ?? '-' }}</td>
                    <td>Rp {{ number_format($t->total_harga + $t->biaya_layanan, 0, ',', '.') }}</td>
                    <td><span class="badge badge-abu status-{{ $t->status }}">{{ str_replace('_',' ',ucfirst($t->status)) }}</span></td>
                    <td style="font-size:.82rem">{{ $t->created_at->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-abu" style="padding:2rem">Tidak ada transaksi bulan ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
