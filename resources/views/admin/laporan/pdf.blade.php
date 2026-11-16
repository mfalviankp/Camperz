<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Camperz — {{ $namaBulan }}</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1A1A1A; margin: 0; padding: 20px; }
    .header { text-align: center; border-bottom: 3px solid #2D6A4F; padding-bottom: 15px; margin-bottom: 20px; }
    .header h1 { font-size: 22px; color: #1B4332; margin: 0 0 4px; }
    .header p { color: #6B7280; margin: 0; font-size: 11px; }
    .stats { display: flex; gap: 10px; margin-bottom: 20px; }
    .stat-box { flex: 1; background: #F0F9F4; border: 1px solid #A7F3D0; border-radius: 8px; padding: 12px; text-align: center; }
    .stat-num { font-size: 18px; font-weight: bold; color: #2D6A4F; }
    .stat-label { font-size: 9px; color: #6B7280; text-transform: uppercase; margin-top: 2px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #1B4332; color: white; padding: 8px; font-size: 10px; text-align: left; }
    td { padding: 7px 8px; border-bottom: 1px solid #E5E7EB; font-size: 10px; }
    tr:nth-child(even) td { background: #F9FAFB; }
    .section-title { font-size: 13px; font-weight: bold; color: #1B4332; margin: 16px 0 8px; border-left: 4px solid #F4A261; padding-left: 8px; }
    .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #9CA3AF; border-top: 1px solid #E5E7EB; padding-top: 10px; }
    .badge-selesai { background: #D1FAE5; color: #065F46; padding: 2px 6px; border-radius: 10px; }
    .badge-lain { background: #F3F4F6; color: #6B7280; padding: 2px 6px; border-radius: 10px; }
</style>
</head>
<body>

<div class="header">
    <h1>🏕 CAMPERZ — Laporan Keuangan</h1>
    <p>Periode: {{ $namaBulan }} | Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

<!-- RINGKASAN -->
<div class="section-title">Ringkasan</div>
<table style="margin-bottom:16px">
    <tr>
        <td style="width:40%"><strong>Total Pemasukan</strong></td>
        <td><strong>Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</strong></td>
    </tr>
    <tr>
        <td>Pemasukan dari Sewa</td>
        <td>Rp {{ number_format($pemasukanSewa, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Pemasukan dari Denda</td>
        <td>Rp {{ number_format($pemasukanDenda, 0, ',', '.') }}</td>
    </tr>
    <tr>
        <td>Jumlah Transaksi Selesai</td>
        <td>{{ $transaksiSelesai }} transaksi</td>
    </tr>
</table>

<!-- DAFTAR TRANSAKSI -->
<div class="section-title">Detail Transaksi</div>
<table>
    <thead>
        <tr><th>#</th><th>Penyewa</th><th>Item</th><th>Mulai</th><th>Selesai</th><th>Total</th><th>Status</th></tr>
    </thead>
    <tbody>
        @forelse($transaksis as $i => $t)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $t->user->nama ?? '-' }}</td>
            <td>{{ $t->item->nama_item ?? '-' }}</td>
            <td>{{ $t->tanggal_mulai->format('d/m/Y') }}</td>
            <td>{{ $t->tanggal_selesai->format('d/m/Y') }}</td>
            <td>Rp {{ number_format($t->total_harga + $t->biaya_layanan, 0, ',', '.') }}</td>
            <td>
                <span class="{{ $t->status === 'selesai' ? 'badge-selesai' : 'badge-lain' }}">
                    {{ str_replace('_',' ',ucfirst($t->status)) }}
                </span>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#9CA3AF">Tidak ada transaksi</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Dokumen ini digenerate otomatis oleh sistem Camperz | {{ now()->format('d M Y H:i:s') }}
</div>
</body>
</html>
