@extends('layouts.admin')
@section('title', $item ? 'Edit Item' : 'Tambah Item')
@section('page-title', $item ? 'Edit Item' : 'Tambah Item Baru')

@section('content')
<div style="max-width:700px">
    <a href="{{ route('admin.stok.index') }}" class="btn btn-outline btn-sm mb-3">← Kembali</a>

    <div class="card">
        <div class="card-body">
            <form method="POST"
                  action="{{ $item ? route('admin.stok.update', $item->item_id) : route('admin.stok.store') }}"
                  enctype="multipart/form-data">
                @csrf
                @if($item) @method('PUT') @endif

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Item *</label>
                        <input type="text" name="nama_item" class="form-control" value="{{ old('nama_item', $item->nama_item ?? '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori *</label>
                        <select name="kategori" class="form-control" required>
                            @foreach(['tenda','sleeping_bag','carrier','kompor','matras','senter','lainnya'] as $kat)
                            <option value="{{ $kat }}" {{ old('kategori', $item->kategori ?? '') === $kat ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$kat)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Harga Sewa / Hari (Rp) *</label>
                        <input type="number" name="harga_sewa" class="form-control" value="{{ old('harga_sewa', $item->harga_sewa ?? '') }}" min="0" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jumlah Stok *</label>
                        <input type="number" name="jumlah_stok" class="form-control" value="{{ old('jumlah_stok', $item->jumlah_stok ?? 0) }}" min="0" required>
                    </div>
                </div>

                @if($item)
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="tersedia" {{ $item->status === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="maintenance" {{ $item->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $item->deskripsi ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Item</label>
                    @if($item && $item->foto)
                    <div style="margin-bottom:8px">
                        <img src="{{ asset('storage/'.$item->foto) }}" style="height:100px;border-radius:8px;object-fit:cover">
                        <div style="font-size:.8rem;color:var(--teks-abu);margin-top:4px">Foto saat ini</div>
                    </div>
                    @endif
                    <input type="file" name="foto" class="form-control" accept="image/*">
                </div>

                <div style="display:flex;gap:1rem">
                    <button type="submit" class="btn btn-primary btn-lg">
                        {{ $item ? '💾 Simpan Perubahan' : '+ Tambah Item' }}
                    </button>
                    <a href="{{ route('admin.stok.index') }}" class="btn btn-outline btn-lg">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
