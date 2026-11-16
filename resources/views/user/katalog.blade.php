@extends('layouts.app')
@section('title', 'Katalog')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>📦 Katalog Alat Camping</h1>
        <p style="opacity:.85;margin-top:.25rem">Temukan perlengkapan yang Anda butuhkan</p>
    </div>
</div>

<div class="container section" style="padding-top:1.5rem">
    <!-- FILTER -->
    <form method="GET" action="{{ route('katalog') }}" class="card mb-3">
        <div class="card-body" style="display:flex;gap:1rem;flex-wrap:wrap;align-items:flex-end">
            <div class="form-group" style="flex:1;min-width:200px;margin:0">
                <label class="form-label">Cari Item</label>
                <input type="text" name="cari" class="form-control" placeholder="Nama alat camping..." value="{{ request('cari') }}">
            </div>
            <div class="form-group" style="flex:1;min-width:160px;margin:0">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-control">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="height:42px">🔍 Filter</button>
            <a href="{{ route('katalog') }}" class="btn btn-outline" style="height:42px">Reset</a>
        </div>
    </form>

    <!-- HASIL -->
    @if($items->count() > 0)
    <p class="text-abu mb-2" style="font-size:.88rem">{{ $items->count() }} item ditemukan</p>
    <div class="katalog-grid">
        @foreach($items as $item)
        <div class="item-card">
            <div class="item-card-img">
                @if($item->foto)
                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->nama_item }}">
                @else
                    @php
                    $icons = ['tenda'=>'⛺','sleeping_bag'=>'🛌','carrier'=>'🎒','kompor'=>'🔥','matras'=>'🟫','senter'=>'🔦'];
                    echo $icons[strtolower($item->kategori)] ?? '🏕';
                    @endphp
                @endif
            </div>
            <div class="item-card-body">
                <div class="mb-1">
                    <span class="badge badge-hijau">{{ ucfirst($item->kategori) }}</span>
                    @if($item->jumlah_stok > 0)
                        <span class="badge badge-biru">Stok: {{ $item->jumlah_stok }}</span>
                    @else
                        <span class="badge badge-merah">Habis</span>
                    @endif
                </div>
                <div class="item-card-title">{{ $item->nama_item }}</div>
                <div class="item-card-price">
                    Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}
                    <span>/hari</span>
                </div>
                @if($item->deskripsi)
                <p style="font-size:.82rem;color:var(--teks-abu);margin-top:6px;line-height:1.4">
                    {{ Str::limit($item->deskripsi, 80) }}
                </p>
                @endif
            </div>
            <div class="item-card-footer">
                @if($item->jumlah_stok > 0)
                    <a href="{{ route('katalog.detail', $item->item_id) }}" class="btn btn-primary btn-block">Sewa Sekarang</a>
                @else
                    <button class="btn btn-block" disabled style="background:#F3F4F6;color:#9CA3AF">Stok Habis</button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <div class="icon">🔍</div>
        <h3>Item tidak ditemukan</h3>
        <p class="text-abu">Coba kata kunci atau filter yang berbeda.</p>
        <a href="{{ route('katalog') }}" class="btn btn-outline mt-2">Lihat Semua</a>
    </div>
    @endif
</div>
@endsection
