@extends('layouts.admin')
@section('title', 'Stok Barang')
@section('page-title', 'Manajemen Stok Barang')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem">
    <p class="text-abu">{{ $items->count() }} item terdaftar</p>
    <a href="{{ route('admin.stok.create') }}" class="btn btn-primary">+ Tambah Item</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Foto</th><th>Nama Item</th><th>Kategori</th><th>Harga/Hari</th><th>Stok</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td>
                        @if($item->foto)
                            <img src="{{ asset('storage/'.$item->foto) }}" style="width:50px;height:50px;border-radius:8px;object-fit:cover">
                        @else
                            <div style="width:50px;height:50px;border-radius:8px;background:var(--krem);display:flex;align-items:center;justify-content:center;font-size:1.5rem">🏕</div>
                        @endif
                    </td>
                    <td><div class="fw-bold">{{ $item->nama_item }}</div><div style="font-size:.8rem;color:var(--teks-abu)">{{ Str::limit($item->deskripsi, 50) }}</div></td>
                    <td><span class="badge badge-biru">{{ ucfirst($item->kategori) }}</span></td>
                    <td class="fw-bold">Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}</td>
                    <td><span class="fw-800" style="font-size:1.2rem;color:{{ $item->jumlah_stok > 0 ? 'var(--hijau)' : '#EF4444' }}">{{ $item->jumlah_stok }}</span></td>
                    <td>
                        <span class="badge {{ $item->status === 'tersedia' ? 'badge-hijau' : 'badge-merah' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:.5rem">
                            <a href="{{ route('admin.stok.edit', $item->item_id) }}" class="btn btn-outline btn-sm">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.stok.destroy', $item->item_id) }}" onsubmit="return confirm('Pindahkan ke maintenance?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#FEF3C7;color:#92400E;border:1px solid #F59E0B" {{ $item->status === 'maintenance' ? 'disabled' : '' }}>
                                    🔧 Maintenance
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-abu" style="padding:2rem">Belum ada item</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
