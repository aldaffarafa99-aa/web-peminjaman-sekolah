@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="dashboard-head d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3 mb-4 mb-lg-5">
    <div>
        <div class="eyebrow"><i class="bi bi-stars me-1"></i> {{ auth()->user()->role === 'admin' ? 'Pusat kontrol administrator' : 'Ringkasan hari ini' }}</div>
        <h1 class="dashboard-title mb-2">Halo, {{ auth()->user()->name }}.</h1>
        <p class="dashboard-subtitle mb-0">{{ auth()->user()->role === 'admin' ? 'Kelola inventaris dan pantau seluruh aktivitas peminjaman sekolah.' : 'Pantau semua aktivitas inventaris sekolah dari satu tempat.' }}</p>
    </div>
    <div class="d-flex gap-2">
        @if (auth()->user()->role === 'admin')
            <a href="{{ route('barang.create') }}" class="btn btn-light action-button"><i class="bi bi-plus-lg me-2"></i>Tambah barang</a>
        @endif
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary action-button"><i class="bi bi-arrow-up-right-circle me-2"></i>Catat peminjaman</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-indigo">
            <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
            <div><div class="stat-label">Jenis barang</div><div class="stat-value">{{ $totalBarang }}</div></div>
            <div class="stat-note"><i class="bi bi-arrow-up-right"></i> Terkelola</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-blue">
            <div class="stat-icon"><i class="bi bi-stack"></i></div>
            <div><div class="stat-label">Total unit</div><div class="stat-value">{{ $totalStok }}</div></div>
            <div class="stat-note">Unit tercatat</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-orange">
            <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
            <div><div class="stat-label">Sedang dipinjam</div><div class="stat-value">{{ $sedangDipinjam }}</div></div>
            <div class="stat-note">Perlu dipantau</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-red">
            <div class="stat-icon"><i class="bi bi-alarm"></i></div>
            <div><div class="stat-label">Terlambat</div><div class="stat-value">{{ $terlambat }}</div></div>
            <div class="stat-note {{ $terlambat ? 'text-danger' : '' }}">{{ $terlambat ? 'Perlu tindakan' : 'Semua aman' }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="dashboard-panel h-100">
            <div class="panel-heading">
                <div><h5 class="mb-1">Peminjaman terbaru</h5><p class="mb-0">Aktivitas terakhir yang tercatat di sistem.</p></div>
                <a href="{{ route('peminjaman.index') }}" class="panel-link">Lihat semua <i class="bi bi-arrow-up-right"></i></a>
            </div>
            <div class="table-responsive">
        <table class="table activity-table mb-0">
            <thead>
                <tr>
                    <th>Peminjam</th><th>Barang</th><th>Jumlah</th><th>Tanggal</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamanTerbaru as $p)
                <tr>
                    <td><strong>{{ $p->nama_peminjam }}</strong><small>{{ $p->kelas_jabatan ?? 'Peminjam' }}</small></td>
                    <td>{{ $p->barang->nama_barang }}</td><td>{{ $p->jumlah }} unit</td><td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
                    <td>
                        @if($p->status_tampil === 'dikembalikan')
                            <span class="status-pill status-success">Dikembalikan</span>
                        @elseif($p->status_tampil === 'terlambat')
                            <span class="status-pill status-danger">Terlambat</span>
                        @else
                            <span class="status-pill status-warning">Dipinjam</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-state"><i class="bi bi-inbox"></i><span>Belum ada data peminjaman.</span></td></tr>
                @endforelse
            </tbody>
        </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        @if (auth()->user()->role === 'admin')
            <div class="admin-card mb-4">
                <div class="admin-card-top"><span class="admin-badge"><i class="bi bi-shield-lock-fill"></i></span><span>AKSES ADMIN</span></div>
                <h5>Kontrol inventaris aktif</h5>
                <p>Anda memiliki akses untuk mengatur data barang dan memantau semua peminjaman.</p>
                <div class="admin-links"><a href="{{ route('barang.index') }}"><i class="bi bi-box-seam"></i> Kelola barang</a><a href="{{ route('peminjaman.index') }}"><i class="bi bi-clipboard-data"></i> Pantau peminjaman</a></div>
            </div>
        @endif
        <div class="dashboard-panel quick-panel mb-4">
            <div class="panel-heading"><div><h5 class="mb-1">Aksi cepat</h5><p class="mb-0">Kelola data lebih praktis.</p></div></div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('barang.index') }}" class="quick-link"><span class="quick-icon purple"><i class="bi bi-boxes"></i></span><span><strong>Kelola barang</strong><small>Lihat dan perbarui stok</small></span><i class="bi bi-chevron-right"></i></a>
            @endif
            <a href="{{ route('peminjaman.index') }}" class="quick-link"><span class="quick-icon blue"><i class="bi bi-clock-history"></i></span><span><strong>Riwayat peminjaman</strong><small>Lacak semua aktivitas</small></span><i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="insight-card">
            <div class="insight-icon"><i class="bi bi-lightbulb"></i></div>
            <div><h6>Jaga inventaris tetap rapi</h6><p>Periksa stok dan barang terlambat secara berkala agar aktivitas sekolah tetap lancar.</p></div>
        </div>
    </div>
</div>

<style>
    .dashboard-title { color: #18213a; font-size: clamp(1.7rem, 3vw, 2.45rem); font-weight: 750; letter-spacing: -.045em; }
    .dashboard-subtitle, .panel-heading p { color: var(--muted); }
    .eyebrow { color: var(--brand); font-size: .75rem; font-weight: 700; letter-spacing: .14em; margin-bottom: .7rem; text-transform: uppercase; }
    .action-button { border-radius: 11px; padding: .7rem 1rem; font-weight: 600; }
    .btn-primary { background: linear-gradient(110deg, #5146e5, #7246d8); border: 0; box-shadow: 0 10px 20px rgba(81,70,229,.2); }
    .btn-primary:hover { background: linear-gradient(110deg, #4036c9, #6034c4); transform: translateY(-1px); }
    .stat-card { align-items: center; background: #fff; border: 1px solid var(--line); border-radius: 18px; display: flex; gap: .85rem; min-height: 125px; padding: 1.1rem; position: relative; overflow: hidden; box-shadow: 0 8px 24px rgba(31,41,65,.04); }
    .stat-card::after { border-radius: 50%; content: ''; height: 90px; position: absolute; right: -35px; top: -38px; width: 90px; }
    .stat-icon { align-items: center; border-radius: 13px; display: flex; flex: 0 0 43px; height: 43px; justify-content: center; font-size: 1.15rem; }
    .stat-indigo .stat-icon { background: #eeedff; color: #5146e5; } .stat-indigo::after { background: #f2f1ff; }
    .stat-blue .stat-icon { background: #e7f2ff; color: #2775d3; } .stat-blue::after { background: #eff7ff; }
    .stat-orange .stat-icon { background: #fff3df; color: #df881d; } .stat-orange::after { background: #fff8ec; }
    .stat-red .stat-icon { background: #ffebee; color: #de4b59; } .stat-red::after { background: #fff2f3; }
    .stat-label { color: var(--muted); font-size: .78rem; } .stat-value { color: #1c2640; font-size: 1.7rem; font-weight: 750; line-height: 1.15; margin-top: .2rem; }
    .stat-note { bottom: 13px; color: #94a0b5; font-size: .68rem; position: absolute; right: 14px; z-index: 1; } .stat-note i { color: #4bae83; }
    .dashboard-panel { background: #fff; border: 1px solid var(--line); border-radius: 18px; box-shadow: 0 8px 24px rgba(31,41,65,.04); overflow: hidden; }
    .panel-heading { align-items: center; display: flex; justify-content: space-between; padding: 1.35rem 1.4rem; } .panel-heading h5 { font-weight: 700; } .panel-heading p { font-size: .78rem; }
    .panel-link { color: var(--brand); font-size: .78rem; font-weight: 700; text-decoration: none; } .panel-link:hover { color: var(--brand-dark); }
    .activity-table thead th { background: #fafbfe; border-bottom: 1px solid var(--line); color: #8994a8; font-size: .68rem; font-weight: 700; letter-spacing: .06em; padding: .8rem 1.4rem; text-transform: uppercase; white-space: nowrap; }
    .activity-table tbody td { border-color: #f0f2f7; color: #435069; font-size: .82rem; padding: 1rem 1.4rem; white-space: nowrap; } .activity-table tbody tr:last-child td { border-bottom: 0; }
    .activity-table td strong, .activity-table td small { display: block; } .activity-table td strong { color: #25304a; } .activity-table td small { color: #9aa4b6; font-size: .7rem; margin-top: .18rem; }
    .status-pill { border-radius: 99px; display: inline-block; font-size: .68rem; font-weight: 700; padding: .38rem .65rem; } .status-success { background: #e8f7ef; color: #218653; } .status-danger { background: #ffebed; color: #d34352; } .status-warning { background: #fff3dc; color: #b97515; }
    .empty-state { color: #9aa4b6 !important; padding: 2rem !important; text-align: center; } .empty-state i { display: block; font-size: 1.7rem; margin-bottom: .4rem; }
    .quick-panel { padding-bottom: .65rem; } .quick-link { align-items: center; border-top: 1px solid #f0f2f7; color: inherit; display: flex; gap: .75rem; padding: 1rem 1.4rem; text-decoration: none; transition: .2s ease; } .quick-link:hover { background: #fafbff; color: inherit; padding-left: 1.6rem; }
    .quick-link > span:nth-child(2) { flex: 1; } .quick-link strong, .quick-link small { display: block; } .quick-link strong { font-size: .82rem; } .quick-link small { color: #98a2b5; font-size: .7rem; margin-top: .15rem; } .quick-link > i { color: #a8b1c1; font-size: .75rem; }
    .quick-icon { align-items: center; border-radius: 11px; display: flex; height: 37px; justify-content: center; width: 37px; } .quick-icon.purple { background: #eeedff; color: #5146e5; } .quick-icon.blue { background: #e7f2ff; color: #2775d3; }
    .insight-card { align-items: flex-start; background: linear-gradient(135deg, #27205f, #5146e5); border-radius: 18px; color: #fff; display: flex; gap: .8rem; padding: 1.35rem; } .insight-icon { align-items: center; background: rgba(255,255,255,.15); border-radius: 10px; display: flex; flex: 0 0 35px; height: 35px; justify-content: center; } .insight-card h6 { font-weight: 700; margin: .1rem 0 .35rem; } .insight-card p { color: #d6d5ff; font-size: .75rem; line-height: 1.55; margin: 0; }
    .admin-card { background: linear-gradient(135deg, #171a3d, #30289c); border-radius: 18px; box-shadow: 0 12px 24px rgba(48,40,156,.18); color: #fff; padding: 1.35rem; }
    .admin-card-top { align-items: center; color: #fce78b; display: flex; font-size: .65rem; font-weight: 800; gap: .55rem; letter-spacing: .13em; }
    .admin-badge { align-items: center; background: rgba(252,231,139,.16); border-radius: 9px; color: #fce78b; display: flex; height: 32px; justify-content: center; width: 32px; }
    .admin-card h5 { font-weight: 700; margin: 1rem 0 .35rem; } .admin-card p { color: #cfd1f7; font-size: .76rem; line-height: 1.55; margin-bottom: 1rem; }
    .admin-links { border-top: 1px solid rgba(255,255,255,.14); display: flex; gap: 1rem; padding-top: .85rem; } .admin-links a { color: #fff; font-size: .72rem; font-weight: 600; text-decoration: none; } .admin-links a:hover { color: #fce78b; }
</style>
@endsection
