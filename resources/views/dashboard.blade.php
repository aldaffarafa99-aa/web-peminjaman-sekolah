@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $dashboardTrends = [
        ['change' => '+12%', 'tone' => 'up', 'label' => 'dari minggu lalu', 'bars' => [35, 48, 42, 62, 54, 78, 70, 91]],
        ['change' => '+8%', 'tone' => 'up', 'label' => 'dari minggu lalu', 'bars' => [42, 35, 58, 48, 66, 59, 76, 84]],
        ['change' => '+16%', 'tone' => 'up', 'label' => 'dari minggu lalu', 'bars' => [28, 48, 38, 70, 58, 74, 68, 88]],
        ['change' => '-4%', 'tone' => 'down', 'label' => 'dari minggu lalu', 'bars' => [76, 68, 72, 58, 64, 48, 42, 36]],
    ];
    $weeklyLoans = [4, 7, 5, 9, 8, 12, 10];
    $weeklyLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
@endphp
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

<div class="dashboard-pulse mb-4">
    <span class="pulse-mark"><i class="bi bi-broadcast-pin"></i></span>
    <span><strong>Ringkasan hari ini</strong><small>{{ now()->translatedFormat('l, d F Y') }} · Sistem inventaris aktif</small></span>
    <span class="pulse-status"><i></i> Semua layanan normal</span>
</div>

<div class="row g-3 mb-4">
    @foreach ([['class' => 'stat-indigo', 'icon' => 'bi-box-seam', 'label' => 'Jenis barang', 'value' => $totalBarang], ['class' => 'stat-blue', 'icon' => 'bi-stack', 'label' => 'Total unit', 'value' => $totalStok], ['class' => 'stat-orange', 'icon' => 'bi-arrow-left-right', 'label' => 'Sedang dipinjam', 'value' => $sedangDipinjam], ['class' => 'stat-red', 'icon' => 'bi-alarm', 'label' => 'Terlambat', 'value' => $terlambat]] as $index => $stat)
        <div class="col-md-3">
            <div class="stat-card {{ $stat['class'] }}">
                <div class="stat-icon"><i class="bi {{ $stat['icon'] }}"></i></div>
                <div class="stat-content"><div class="stat-label">{{ $stat['label'] }}</div><div class="stat-value">{{ $stat['value'] }}</div><span class="stat-change {{ $dashboardTrends[$index]['tone'] === 'down' ? 'negative' : '' }}"><i class="bi bi-{{ $dashboardTrends[$index]['tone'] === 'down' ? 'arrow-down-right' : 'arrow-up-right' }}"></i>{{ $dashboardTrends[$index]['change'] }} <small>{{ $dashboardTrends[$index]['label'] }}</small></span></div>
                <div class="mini-sparkline">@foreach($dashboardTrends[$index]['bars'] as $bar)<i style="height: {{ $bar }}%"></i>@endforeach</div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="dashboard-panel trend-panel h-100">
            <div class="panel-heading"><div><h5 class="mb-1">Tren peminjaman</h5><p class="mb-0">Aktivitas 7 hari terakhir <span class="trend-live"><i></i> Live view</span></p></div><span class="trend-total"><strong>{{ array_sum($weeklyLoans) }}</strong><small>total aktivitas</small></span></div>
            <div class="trend-chart"><div class="chart-y-axis"><span>12</span><span>8</span><span>4</span><span>0</span></div><div class="chart-area">@foreach($weeklyLoans as $index => $loan)<div class="chart-column"><div class="chart-tooltip">{{ $loan }} peminjaman</div><span class="chart-bar" style="height: {{ ($loan / 12) * 100 }}%"></span><small>{{ $weeklyLabels[$index] }}</small></div>@endforeach</div></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="dashboard-panel today-summary h-100">
            <div class="panel-heading"><div><h5 class="mb-1">Ringkasan hari ini</h5><p class="mb-0">Pantauan cepat sistem</p></div><span class="today-icon"><i class="bi bi-sun"></i></span></div>
            <div class="today-body"><div class="today-date">{{ now()->translatedFormat('l, d F Y') }}</div><div class="today-metric"><span class="today-metric-icon purple"><i class="bi bi-activity"></i></span><span><strong>{{ $peminjamanTerbaru->count() }}</strong><small>aktivitas terbaru terpantau</small></span></div><div class="today-metric"><span class="today-metric-icon {{ $terlambat ? 'red' : 'green' }}"><i class="bi bi-{{ $terlambat ? 'exclamation-triangle' : 'check2-circle' }}"></i></span><span><strong>{{ $terlambat ? $terlambat : 'Aman' }}</strong><small>{{ $terlambat ? 'peminjaman perlu ditindaklanjuti' : 'tidak ada keterlambatan aktif' }}</small></span></div></div>
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
                <tr><th>Peminjam</th><th>Barang</th><th>Jumlah</th><th>Tanggal</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($peminjamanTerbaru as $p)
                <tr>
                    <td><div class="dashboard-borrower"><span>{{ strtoupper(substr($p->nama_peminjam, 0, 1)) }}</span><strong>{{ $p->nama_peminjam }}</strong><small>{{ $p->kelas_jabatan ?? 'Peminjam' }}</small></div></td>
                    <td><div class="dashboard-item"><span class="dashboard-item-icon"><i class="bi bi-{{ str_contains(strtolower($p->barang->nama_barang), 'proyektor') ? 'display' : (str_contains(strtolower($p->barang->nama_barang), 'laptop') ? 'laptop' : 'box-seam') }}"></i></span><span>{{ $p->barang->nama_barang }}</span></div></td><td>{{ $p->jumlah }} unit</td><td>{{ $p->tanggal_pinjam->format('d M Y') }}</td>
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
                <div class="admin-card-top"><span class="admin-badge"><i class="bi bi-stars"></i></span><span>AKSES ADMIN</span></div>
                <h5>Kontrol cepat</h5><p>Semua pintasan kerja dalam satu tempat.</p>
                <div class="admin-shortcuts"><a href="{{ route('barang.create') }}" class="admin-shortcut"><i class="bi bi-plus-lg"></i><span>Tambah barang</span></a><a href="{{ route('barang.index') }}" class="admin-shortcut"><i class="bi bi-boxes"></i><span>Kelola barang</span></a><a href="{{ route('peminjaman.index') }}" class="admin-shortcut"><i class="bi bi-clipboard2-data"></i><span>Pantau pinjam</span></a><button type="button" class="admin-shortcut" data-bs-toggle="modal" data-bs-target="#exportSoonModal"><i class="bi bi-download"></i><span>Export laporan</span></button></div>
            </div>
        @endif
        <div class="dashboard-panel quick-panel mb-4">
            <div class="panel-heading"><div><h5 class="mb-1">Aksi cepat</h5><p class="mb-0">Kelola data lebih praktis.</p></div></div>
            @if (auth()->user()->role === 'admin')
                <a href="{{ route('barang.index') }}" class="quick-link"><span class="quick-icon purple"><i class="bi bi-boxes"></i></span><span><strong>Kelola barang</strong><small>Lihat dan perbarui stok</small></span><i class="bi bi-chevron-right"></i></a>
            @endif
            <a href="{{ route('peminjaman.index') }}" class="quick-link"><span class="quick-icon blue"><i class="bi bi-clock-history"></i></span><span><strong>Riwayat peminjaman</strong><small>Lacak semua aktivitas</small></span><i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="dashboard-panel mb-4">
            <div class="panel-heading">
                <div><h5 class="mb-1">Stok menipis</h5><p class="mb-0">Barang dengan sisa maksimal 2 unit.</p></div>
                <a href="{{ route('barang.index') }}" class="panel-link">Kelola <i class="bi bi-arrow-up-right"></i></a>
            </div>
            @forelse ($stokMenipis as $barang)
                <div class="quick-link">
                    <span class="quick-icon {{ $barang->stok_tersedia === 0 ? 'red' : 'orange' }}"><i class="bi bi-box-seam"></i></span>
                    <span><strong>{{ $barang->nama_barang }}</strong><small>{{ $barang->kode_barang }}</small></span>
                    <strong class="stock-count {{ $barang->stok_tersedia === 0 ? 'text-danger' : 'text-warning' }}">{{ $barang->stok_tersedia }}</strong>
                </div>
            @empty
                <p class="text-muted small px-4 pb-3 mb-0">Semua stok masih mencukupi.</p>
            @endforelse
        </div>
        <div class="insight-card">
            <div class="insight-icon"><i class="bi bi-lightbulb"></i></div>
            <div><h6>Jaga inventaris tetap rapi</h6><p>Periksa stok dan barang terlambat secara berkala agar aktivitas sekolah tetap lancar.</p></div>
        </div>
    </div>
</div>

<div class="modal fade" id="exportSoonModal" tabindex="-1" aria-labelledby="exportSoonModalLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow"><div class="modal-body export-modal-body"><span class="export-modal-icon"><i class="bi bi-download"></i></span><h5 id="exportSoonModalLabel">Export laporan</h5><p>Fitur export sedang disiapkan. Untuk sementara, laporan dapat dipantau dari halaman Peminjaman.</p><button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mengerti</button></div></div></div></div>

<style>
    .dashboard-title { color: #18213a; font-size: clamp(1.7rem, 3vw, 2.45rem); font-weight: 750; letter-spacing: -.045em; }
    .stat-content { position: relative; z-index: 1; } .stat-change { align-items: center; color: #3c9b70; display: flex; font-size: .67rem; font-weight: 800; gap: .18rem; margin-top: .45rem; } .stat-change small { color: #9aa5b7; font-size: .6rem; font-weight: 500; } .stat-change.negative { color: #d45561; } .mini-sparkline { align-items: end; bottom: 12px; display: flex; gap: 3px; height: 27px; opacity: .8; position: absolute; right: 12px; width: 58px; } .mini-sparkline i { background: currentColor; border-radius: 3px 3px 1px 1px; display: block; flex: 1; min-height: 4px; opacity: .35; } .stat-indigo .mini-sparkline { color: #5146e5; } .stat-blue .mini-sparkline { color: #2775d3; } .stat-orange .mini-sparkline { color: #df881d; } .stat-red .mini-sparkline { color: #de4b59; }
    .trend-panel { overflow: hidden; } .trend-live { align-items: center; color: #3d956d; display: inline-flex; font-size: .62rem; font-weight: 700; gap: .25rem; margin-left: .4rem; } .trend-live i { background: #4bb582; border-radius: 50%; height: 5px; width: 5px; } .trend-total { text-align: right; } .trend-total strong, .trend-total small { display: block; } .trend-total strong { color: var(--brand); font-size: 1.35rem; } .trend-total small { color: #9aa5b7; font-size: .62rem; } .trend-chart { display: flex; height: 185px; padding: .25rem 1.4rem 1.2rem; } .chart-y-axis { color: #b1b8c6; display: flex; flex-direction: column; font-size: .6rem; justify-content: space-between; padding: .2rem .7rem .95rem 0; } .chart-area { background: repeating-linear-gradient(to bottom, transparent 0, transparent 45px, #f0f2f7 46px); display: flex; flex: 1; gap: clamp(.7rem, 3vw, 2rem); height: 100%; justify-content: space-around; padding: 0 .5rem; } .chart-column { align-items: center; display: flex; flex: 1; flex-direction: column; justify-content: end; position: relative; } .chart-bar { background: linear-gradient(180deg, #8179ed, #5146e5); border-radius: 7px 7px 2px 2px; box-shadow: 0 5px 12px rgba(81,70,229,.18); display: block; max-width: 25px; min-height: 8px; transition: filter .2s ease, transform .2s ease; width: 100%; } .chart-column:hover .chart-bar { filter: brightness(1.08); transform: scaleY(1.04); } .chart-column small { color: #929caf; font-size: .62rem; margin-top: .55rem; } .chart-tooltip { background: #27205f; border-radius: 5px; color: #fff; font-size: .58rem; opacity: 0; padding: .25rem .35rem; pointer-events: none; position: absolute; top: -10px; transform: translateY(4px); transition: .2s ease; white-space: nowrap; } .chart-column:hover .chart-tooltip { opacity: 1; transform: translateY(0); }
    .today-summary { background: linear-gradient(145deg, #fff, #fafaff); } .today-icon { align-items: center; background: #fff3dc; border-radius: 9px; color: #d88a1d !important; display: flex !important; height: 30px; justify-content: center; width: 30px; } .today-body { padding: 0 1.4rem 1.25rem; } .today-date { color: #8e99ac; font-size: .7rem; margin-bottom: 1rem; } .today-metric { align-items: center; border-top: 1px solid #eef0f5; display: flex; gap: .7rem; padding: .8rem 0; } .today-metric-icon { align-items: center; border-radius: 9px; display: flex; height: 32px; justify-content: center; width: 32px; } .today-metric-icon.purple { background: #eeedff; color: var(--brand); } .today-metric-icon.red { background: #ffebed; color: #d24754; } .today-metric-icon.green { background: #e8f7ef; color: #258756; } .today-metric strong, .today-metric small { display: block; } .today-metric strong { color: #303b55; font-size: .9rem; } .today-metric small { color: #9aa5b7; font-size: .65rem; margin-top: .12rem; }
    .dashboard-pulse { align-items: center; background: linear-gradient(100deg, #fff, #f5f4ff); border: 1px solid #e2e0fa; border-radius: 14px; box-shadow: 0 7px 20px rgba(54,45,145,.05); display: flex; gap: .75rem; padding: .72rem 1rem; } .pulse-mark { align-items: center; background: #eeedff; border-radius: 10px; color: var(--brand); display: flex; height: 35px; justify-content: center; width: 35px; } .dashboard-pulse strong, .dashboard-pulse small { display: block; } .dashboard-pulse strong { color: #343d59; font-size: .76rem; } .dashboard-pulse small { color: #929caf; font-size: .68rem; margin-top: .1rem; } .pulse-status { align-items: center; color: #31855e; display: flex; font-size: .68rem; font-weight: 700; gap: .35rem; margin-left: auto; } .pulse-status i { animation: pulse-dot 1.8s infinite; background: #4bb582; border-radius: 50%; height: 7px; width: 7px; } @keyframes pulse-dot { 0%,100% { box-shadow: 0 0 0 0 rgba(75,181,130,.35); } 50% { box-shadow: 0 0 0 5px rgba(75,181,130,0); } }
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
    .activity-table td strong, .activity-table td small { display: block; } .activity-table td strong { color: #25304a; } .activity-table td small { color: #9aa4b6; font-size: .7rem; margin-top: .18rem; } .dashboard-borrower { min-width: 115px; padding-left: 2rem; position: relative; } .dashboard-borrower > span { align-items: center; background: #eeedff; border-radius: 50%; color: var(--brand); display: flex; font-size: .7rem; font-weight: 800; height: 26px; justify-content: center; left: 0; position: absolute; top: .05rem; width: 26px; } .dashboard-item { align-items: center; display: flex; gap: .5rem; } .dashboard-item-icon { align-items: center; background: #eaf1ff; border-radius: 8px; color: #3976d2; display: flex; height: 28px; justify-content: center; width: 28px; } .dashboard-item span:last-child { color: #435069; } .activity-table tbody tr { transition: background .2s ease; } .activity-table tbody tr:hover { background: #fbfcff; }
    .status-pill { border-radius: 99px; display: inline-block; font-size: .68rem; font-weight: 700; padding: .38rem .65rem; } .status-success { background: #e8f7ef; color: #218653; } .status-danger { background: #ffebed; color: #d34352; } .status-warning { background: #fff3dc; color: #b97515; }
    .empty-state { color: #9aa4b6 !important; padding: 2rem !important; text-align: center; } .empty-state i { display: block; font-size: 1.7rem; margin-bottom: .4rem; }
    .quick-panel { padding-bottom: .65rem; } .quick-link { align-items: center; border-top: 1px solid #f0f2f7; color: inherit; display: flex; gap: .75rem; padding: 1rem 1.4rem; text-decoration: none; transition: .2s ease; } .quick-link:hover { background: #fafbff; color: inherit; padding-left: 1.6rem; }
    .quick-link > span:nth-child(2) { flex: 1; } .quick-link strong, .quick-link small { display: block; } .quick-link strong { font-size: .82rem; } .quick-link small { color: #98a2b5; font-size: .7rem; margin-top: .15rem; } .quick-link > i { color: #a8b1c1; font-size: .75rem; }
    .quick-icon { align-items: center; border-radius: 11px; display: flex; height: 37px; justify-content: center; width: 37px; } .quick-icon.purple { background: #eeedff; color: #5146e5; } .quick-icon.blue { background: #e7f2ff; color: #2775d3; } .quick-icon.orange { background: #fff3df; color: #df881d; } .quick-icon.red { background: #ffebee; color: #de4b59; } .stock-count { font-size: .9rem; }
    .insight-card { align-items: flex-start; background: linear-gradient(135deg, #27205f, #5146e5); border-radius: 18px; color: #fff; display: flex; gap: .8rem; padding: 1.35rem; } .insight-icon { align-items: center; background: rgba(255,255,255,.15); border-radius: 10px; display: flex; flex: 0 0 35px; height: 35px; justify-content: center; } .insight-card h6 { font-weight: 700; margin: .1rem 0 .35rem; } .insight-card p { color: #d6d5ff; font-size: .75rem; line-height: 1.55; margin: 0; }
    .admin-card { background: linear-gradient(135deg, #171a3d, #30289c); border-radius: 18px; box-shadow: 0 12px 24px rgba(48,40,156,.18); color: #fff; padding: 1.35rem; }
    .admin-card-top { align-items: center; color: #fce78b; display: flex; font-size: .65rem; font-weight: 800; gap: .55rem; letter-spacing: .13em; }
    .admin-badge { align-items: center; background: rgba(252,231,139,.16); border-radius: 9px; color: #fce78b; display: flex; height: 32px; justify-content: center; width: 32px; }
    .admin-card h5 { font-weight: 700; margin: 1rem 0 .35rem; } .admin-card p { color: #cfd1f7; font-size: .76rem; line-height: 1.55; margin-bottom: 1rem; }
    .admin-shortcuts { display: grid; gap: .5rem; grid-template-columns: repeat(4, 1fr); margin-top: 1rem; } .admin-shortcut { align-items: center; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.1); border-radius: 10px; color: #fff; display: flex; flex-direction: column; gap: .4rem; justify-content: center; min-height: 66px; padding: .5rem .25rem; text-align: center; text-decoration: none; transition: .2s ease; } .admin-shortcut:hover { background: rgba(255,255,255,.2); color: #fff; transform: translateY(-3px); } .admin-shortcut:disabled { cursor: not-allowed; opacity: .58; } .admin-shortcut i { color: #fce78b; font-size: 1.1rem; } .admin-shortcut span { font-size: .58rem; line-height: 1.2; } .export-modal-body { padding: 2rem; text-align: center; } .export-modal-icon { align-items: center; background: #eeedff; border-radius: 14px; color: var(--brand); display: inline-flex; font-size: 1.4rem; height: 48px; justify-content: center; margin-bottom: .8rem; width: 48px; } .export-modal-body h5 { color: #29354d; } .export-modal-body p { color: #7e8a9e; font-size: .8rem; line-height: 1.6; margin: .4rem auto 1.2rem; max-width: 280px; }
</style>
@endsection
