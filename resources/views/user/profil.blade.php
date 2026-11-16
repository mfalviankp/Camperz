@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="page-header">
    <div class="container"><h1>👤 Profil Saya</h1></div>
</div>

<div class="container section" style="padding-top:1.5rem;max-width:600px">
    @if(!$user->no_hp || !$user->alamat)
    <div class="alert alert-warning">⚠️ Lengkapi profil Anda agar bisa melakukan pemesanan.</div>
    @endif

    <div class="card">
        <div class="card-body" style="text-align:center;padding-top:2rem">
            @if($user->foto_profil)
                <img src="{{ asset('storage/'.$user->foto_profil) }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--hijau)">
            @else
                <div style="width:90px;height:90px;border-radius:50%;background:var(--hijau);color:white;font-size:2.5rem;display:flex;align-items:center;justify-content:center;margin:0 auto;font-weight:700">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
            @endif
            <h2 style="margin-top:.75rem">{{ $user->nama }}</h2>
            <p class="text-abu">{{ $user->email }}</p>
            <span class="badge {{ $user->role === 'admin' ? 'badge-merah' : 'badge-hijau' }}">{{ ucfirst($user->role) }}</span>
        </div>

        <div style="border-top:1px solid var(--border);padding:1.5rem">
            <form method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_hp', $user->no_hp) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3" placeholder="Jl. ..." required>{{ old('alamat', $user->alamat) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Profil <span class="text-abu">(opsional)</span></label>
                    <input type="file" name="foto_profil" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    💾 Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
