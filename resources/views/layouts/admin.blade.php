<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard') | Camperz</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

<div class="admin-layout">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand">🏕 Camperz <span style="font-size:.7rem;opacity:.6;display:block;font-weight:400">Panel Admin</span></div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.stok.index') }}" class="{{ request()->routeIs('admin.stok*') ? 'active' : '' }}">
                <span class="icon">📦</span> Stok Barang
            </a>
            <a href="{{ route('admin.scan.pickup') }}" class="{{ request()->routeIs('admin.scan.pickup*') ? 'active' : '' }}">
                <span class="icon">📤</span> Scan Pickup
            </a>
            <a href="{{ route('admin.scan.return') }}" class="{{ request()->routeIs('admin.scan.return*') || request()->routeIs('admin.inspeksi*') ? 'active' : '' }}">
                <span class="icon">📥</span> Scan Return
            </a>
            <a href="{{ route('admin.laporan') }}" class="{{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
                <span class="icon">📋</span> Laporan
            </a>
            <div style="margin-top:auto;padding:1rem 1.5rem;border-top:1px solid rgba(255,255,255,.1)">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="no-loading" style="background:none;border:none;color:rgba(255,255,255,.7);font-family:'Nunito',sans-serif;font-weight:600;font-size:.9rem;cursor:pointer;display:flex;align-items:center;gap:8px;padding:0">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- KONTEN ADMIN -->
    <div class="admin-content">
        <div class="admin-topbar">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <span style="font-size:.85rem;color:#6B7280;font-weight:600">👤 {{ session('nama') }}</span>
        </div>

        <div class="admin-body">
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

            @yield('content')
        </div>
    </div>
</div>

<script>
document.addEventListener('submit', function(e) {
    const btn = e.target.querySelector('button[type="submit"]');
    if (btn && !btn.classList.contains('no-loading')) {
        btn.disabled = true;
        btn.innerHTML = '⏳ Memproses...';
    }
});
</script>
@stack('scripts')
</body>
</html>
