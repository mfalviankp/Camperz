@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem">
    <div style="width:100%;max-width:440px">
        <div class="text-center mb-3">
            <div style="font-size:3rem">🏕</div>
            <h1 style="font-size:2rem;color:var(--hijau-tua)">Buat Akun</h1>
            <p class="text-abu">Bergabung dengan Camperz sekarang</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('register.proses') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Anda" value="{{ old('nama') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:1rem">
                        Daftar Sekarang
                    </button>
                </form>

                <p class="text-center mt-2" style="font-size:.9rem">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-hijau fw-bold">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
