@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div style="min-height:80vh;display:flex;align-items:center;justify-content:center;padding:2rem">
    <div style="width:100%;max-width:420px">
        <!-- Logo -->
        <div class="text-center mb-3">
            <div style="font-size:3rem">🏕</div>
            <h1 style="font-size:2rem;color:var(--hijau-tua)">Camperz</h1>
            <p class="text-abu">Masuk ke akun Anda</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('login.proses') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="margin-top:1rem">
                        Masuk
                    </button>
                </form>

                <p class="text-center mt-2" style="font-size:.9rem">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-hijau fw-bold">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <!-- Demo accounts -->
        <div class="alert alert-info mt-2" style="font-size:.82rem">
            <div><strong>Demo Admin:</strong> admin@camperz.id / admin123</div>
            <div><strong>Demo User:</strong> user@camperz.id / user123</div>
        </div>
    </div>
</div>
@endsection
