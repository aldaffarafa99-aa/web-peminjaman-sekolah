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

<div class="app-background-shape shape-one"></div>
<div class="app-background-shape shape-two"></div>

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
                <li class="nav-item me-3">
                    <a href="{{ route('profile.edit') }}" class="profile-nav-link" title="Pengaturan profil">
                        @if (auth()->user()->avatar_path)
                            <img src="{{ asset('storage/' . auth()->user()->avatar_path) }}" alt="Foto profil" class="user-avatar user-avatar-image">
                        @else
                            <span class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                        <span class="d-none d-sm-inline">{{ auth()->user()->name }}<small class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Siswa' }}</small></span>
                        <i class="bi bi-chevron-down profile-nav-chevron"></i>
                    </a>
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
    .app-shell { min-height: 100vh; background: radial-gradient(circle at 90% 8%, rgba(222,220,255,.42), transparent 22rem), #f5f7fb; color: var(--ink); overflow-x: hidden; position: relative; }
    .app-background-shape { border: 1px solid rgba(81,70,229,.08); border-radius: 50%; pointer-events: none; position: fixed; z-index: 0; } .shape-one { height: 420px; right: -235px; top: 180px; width: 420px; } .shape-two { bottom: -285px; height: 520px; left: -300px; width: 520px; }
    .app-navbar { background: linear-gradient(110deg, #171a3d, #262064 58%, #5146e5); box-shadow: 0 12px 30px rgba(37, 35, 104, .16); position: relative; z-index: 10; }
    .app-container { max-width: 1440px; margin: 0 auto; }
    .app-navbar .navbar-brand { display: flex; align-items: center; gap: .7rem; color: #fff; letter-spacing: -.02em; }
    .app-navbar .navbar-brand small { display: block; color: #bfc4ff; font-size: .68rem; letter-spacing: .1em; text-transform: uppercase; }
    .brand-mark { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 12px; background: rgba(255,255,255,.14); color: #fff; }
    .app-navbar .nav-link { color: #cdd1f7; padding: .65rem .9rem; border-radius: 10px; transition: .2s ease; }
    .app-navbar .nav-link:hover, .app-navbar .nav-link.active { color: #fff; background: rgba(255,255,255,.13); }
    .app-navbar .admin-nav-label { color: #fce78b; font-size: .78rem; }
    .user-role { color: #c7caff; display: block; font-size: .65rem; margin-top: .1rem; }
    .profile-nav-link { align-items: center; color: #fff; display: flex; gap: .5rem; text-decoration: none; } .profile-nav-link:hover { color: #fff; opacity: .88; } .profile-nav-chevron { color: #c7caff; font-size: .65rem; margin-left: .15rem; }
    .user-avatar { display: inline-grid; place-items: center; width: 30px; height: 30px; margin-right: .1rem; border-radius: 50%; background: #a9a5ff; color: #211d69; font-weight: 700; } .user-avatar-image { object-fit: cover; }
    .btn-logout { color: #fff; border: 1px solid rgba(255,255,255,.28); border-radius: 9px; }
    .btn-logout:hover { background: #fff; color: #30289c; }
    .app-main { animation: page-arrive .45s ease both; position: relative; z-index: 1; }
    .app-main .alert { border: 0; border-radius: 14px; box-shadow: 0 8px 20px rgba(31, 41, 65, .06); }
    .app-main .alert button { border: 0; }
    .app-main .btn, .app-main .card, .app-main .dashboard-panel, .app-main .data-card, .app-main .form-card, .app-main .student-card, .app-main .stat-card { transition: box-shadow .25s ease, transform .25s ease, border-color .25s ease; }
    .app-main .btn:hover { transform: translateY(-2px); }
    .app-main .stat-card:hover, .app-main .data-card:hover, .app-main .form-card:hover { border-color: #d8d5ff; box-shadow: 0 16px 32px rgba(48,40,156,.1); transform: translateY(-3px); }
    @keyframes page-arrive { from { opacity: 0; transform: translateY(7px); } to { opacity: 1; transform: translateY(0); } }
    @media (prefers-reduced-motion: reduce) { .app-main, .app-main .btn, .app-main .card, .app-main .dashboard-panel, .app-main .data-card, .app-main .form-card, .app-main .student-card, .app-main .stat-card { animation: none; transition: none; } }
    @media (max-width: 991.98px) { .app-navbar .navbar-collapse { padding: .75rem 0; } .app-navbar .nav-link { margin-top: .2rem; } }
</style>
</body>
</html>
