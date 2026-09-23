<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Peminjaman Barang') | Inventaris Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="app-shell">

<nav class="app-navbar navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid app-container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
          <span class="brand-mark"><img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 32px; width: 32px; object-fit: cover; border-radius: 8px;"></span>
            <span><strong>Peminjaman</strong><small>Barang Sekolah</small></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav me-auto gap-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</a>
                </li>
                @if (auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}" href="{{ route('barang.index') }}"><i class="bi bi-box-seam me-2"></i>Data Barang</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}"><i class="bi bi-arrow-left-right me-2"></i>Peminjaman</a>
                </li>
                @if (auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <span class="nav-link admin-nav-label"><i class="bi bi-shield-check me-2"></i>Mode Admin</span>
                    </li>
                @endif
            </ul>
            @auth
            <ul class="navbar-nav">
                <li class="nav-item d-flex align-items-center text-light me-3">
                    <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                    <span class="d-none d-sm-inline">{{ auth()->user()->name }}<small class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Siswa' }}</small></span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-logout btn-sm"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button>
                    </form>
                </li>
            </ul>
            @endauth
        </div>
    </div>
</nav>

<main class="app-main container-fluid app-container py-4 py-lg-5">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
    :root { --ink: #172033; --muted: #6c7890; --line: #e7ebf3; --brand: #5146e5; --brand-dark: #30289c; }
    .app-shell { min-height: 100vh; background: #f5f7fb; color: var(--ink); }
    .app-navbar { background: linear-gradient(110deg, #171a3d, #262064 58%, #5146e5); box-shadow: 0 12px 30px rgba(37, 35, 104, .16); }
    .app-container { max-width: 1440px; margin: 0 auto; }
    .app-navbar .navbar-brand { display: flex; align-items: center; gap: .7rem; color: #fff; letter-spacing: -.02em; }
    .app-navbar .navbar-brand small { display: block; color: #bfc4ff; font-size: .68rem; letter-spacing: .1em; text-transform: uppercase; }
    .brand-mark { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 12px; background: rgba(255,255,255,.14); color: #fff; }
    .app-navbar .nav-link { color: #cdd1f7; padding: .65rem .9rem; border-radius: 10px; transition: .2s ease; }
    .app-navbar .nav-link:hover, .app-navbar .nav-link.active { color: #fff; background: rgba(255,255,255,.13); }
    .app-navbar .admin-nav-label { color: #fce78b; font-size: .78rem; }
    .user-role { color: #c7caff; display: block; font-size: .65rem; margin-top: .1rem; }
    .user-avatar { display: inline-grid; place-items: center; width: 30px; height: 30px; margin-right: .5rem; border-radius: 50%; background: #a9a5ff; color: #211d69; font-weight: 700; }
    .btn-logout { color: #fff; border: 1px solid rgba(255,255,255,.28); border-radius: 9px; }
    .btn-logout:hover { background: #fff; color: #30289c; }
    .app-main .alert { border: 0; border-radius: 14px; box-shadow: 0 8px 20px rgba(31, 41, 65, .06); }
    .app-main .alert button { border: 0; }
    @media (max-width: 991.98px) { .app-navbar .navbar-collapse { padding: .75rem 0; } .app-navbar .nav-link { margin-top: .2rem; } }
</style>
</body>
</html>
