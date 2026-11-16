<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Camperz') — Sewa Alat Camping</title>
    <meta name="description" content="Camperz — platform sewa alat camping terpercaya untuk petualangan Anda.">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">🏕 Camperz</a>

    <div class="navbar-menu">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('katalog') }}" class="{{ request()->routeIs('katalog*') ? 'active' : '' }}">Katalog</a>
        @if(session('user_id'))
        <a href="{{ route('transaksi.index') }}" class="{{ request()->routeIs('transaksi*') ? 'active' : '' }}">Transaksi</a>
        @endif
    </div>

    <div class="navbar-right">
        @if(session('user_id'))
            {{-- Ikon notifikasi dengan badge --}}
            @php $jmlNotif = \App\Http\Controllers\NotifikasiController::hitungBelumDibaca(); @endphp
            <a href="{{ route('notifikasi') }}" class="notif-btn" title="Notifikasi">
                🔔
                @if($jmlNotif > 0)
                    <span class="notif-badge">{{ $jmlNotif }}</span>
                @endif
            </a>

            {{-- Ikon keranjang --}}
            <a href="{{ route('keranjang') }}" class="notif-btn" title="Keranjang">
                🛒
                @if(count(session('keranjang', [])) > 0)
                    <span class="notif-badge">{{ count(session('keranjang', [])) }}</span>
                @endif
            </a>

            {{-- Dropdown user --}}
            <div class="user-dropdown">
                <button class="user-btn" onclick="toggleDropdown()">
                    <div class="user-avatar">{{ strtoupper(substr(session('nama', 'U'), 0, 1)) }}</div>
                    {{ session('nama') }}
                    ▾
                </button>
                <div class="dropdown-menu" id="userDropdown">
                    <a href="{{ route('profil') }}">👤 Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">🚪 Logout</button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
        @endif
    </div>
</nav>

<!-- FLASH MESSAGE -->
<div class="container" style="margin-top:1rem">
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">ℹ️ {{ session('info') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            ❌
            <ul style="margin:0;padding-left:1rem">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif
</div>

<!-- KONTEN HALAMAN -->
<main style="min-height:calc(100vh - 64px - 64px)">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="footer">
    <p>© {{ date('Y') }} <span>Camperz</span> — Sistem Penyewaan Alat Camping. Dibuat dengan ❤️ oleh Tim Pengembang.</p>
</footer>

<script>
function toggleDropdown() {
    document.getElementById('userDropdown').classList.toggle('show');
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.user-dropdown')) {
        document.getElementById('userDropdown')?.classList.remove('show');
    }
});

// Disable tombol submit saat diklik
document.addEventListener('submit', function(e) {
    const btn = e.target.querySelector('button[type="submit"]');
    if (btn && !btn.classList.contains('no-loading')) {
        btn.disabled = true;
        btn.dataset.originalText = btn.innerHTML;
        btn.innerHTML = '⏳ Memproses...';
    }
});
</script>
@stack('scripts')
</body>
</html>
