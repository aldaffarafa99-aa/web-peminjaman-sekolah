@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
@php
    $activeCount = $peminjaman->where('status', 'dipinjam')->count();
    $returnedCount = $peminjaman->where('status', 'dikembalikan')->count();
    $overdueCount = $peminjaman->filter(fn ($item) => $item->status_tampil === 'terlambat')->count();
@endphp
<div class="page-hero mb-4">
    <div><div class="page-kicker"><i class="bi bi-arrow-left-right me-1"></i> Aktivitas inventaris</div><h3 class="mb-1">Data Peminjaman <span class="header-mini-stat"><i class="bi bi-clock"></i> {{ $activeCount }} aktif <b>·</b> {{ $returnedCount }} selesai</span></h3><p class="mb-0">Pantau barang yang sedang digunakan dan riwayat pengembaliannya.</p></div>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary page-action"><i class="bi bi-plus-lg"></i> Catat Peminjaman</a>
</div>

<div class="filter-panel mb-3">
    <form class="row g-2 align-items-center" method="GET">
        <div class="col-md-5"><div class="search-field"><i class="bi bi-search"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari peminjam, barang, atau kode..."></div></div>
        <div class="col-md-3"><div class="status-select-wrap"><select name="status" class="form-select filter-select"><option value="">Semua status</option><option value="dipinjam" @selected(request('status')==='dipinjam')>Dipinjam</option><option value="terlambat" @selected(request('status')==='terlambat')>Terlambat</option><option value="dikembalikan" @selected(request('status')==='dikembalikan')>Dikembalikan</option></select><span class="active-count-badge">{{ $activeCount }}</span></div></div>
        <div class="col-auto"><button class="btn btn-dark filter-button"><i class="bi bi-funnel me-1"></i>Filter</button></div>
        @if(request('search') || request('status'))<div class="col-auto"><a href="{{ route('peminjaman.index') }}" class="clear-filter">Reset filter</a></div>@endif
    </form>
    <div class="quick-filters"><span class="quick-filter-label"><i class="bi bi-lightning-charge-fill"></i> Filter cepat</span><button type="button" class="loan-filter-chip active" data-filter="all">Semua <span>{{ $peminjaman->count() }}</span></button><button type="button" class="loan-filter-chip" data-filter="dipinjam">Dipinjam <span>{{ $activeCount }}</span></button><button type="button" class="loan-filter-chip" data-filter="dikembalikan">Dikembalikan <span>{{ $returnedCount }}</span></button><button type="button" class="loan-filter-chip" data-filter="terlambat">Terlambat <span>{{ $overdueCount }}</span></button><span class="active-filter-result d-none"><i class="bi bi-funnel-fill"></i> <strong id="loanFilterResult">0</strong> hasil</span></div>
</div>

@if($overdueCount > 0)
    <div class="urgent-banner mb-3"><span class="urgent-icon"><i class="bi bi-exclamation-triangle-fill"></i></span><span><strong>{{ $overdueCount }} peminjaman terlambat</strong><small>Segera tindak lanjuti pengembalian barang yang melewati jatuh tempo.</small></span><button type="button" class="urgent-action loan-filter-chip" data-filter="terlambat">Lihat sekarang <i class="bi bi-arrow-right"></i></button></div>
@endif

<div class="data-card">
    <div class="data-card-heading"><div><strong>Aktivitas peminjaman</strong><span>Riwayat transaksi terbaru</span></div><span class="live-indicator"><i></i> Terpantau</span></div>
    <div class="table-responsive">
        <table class="table loan-table mb-0 align-middle">
            <thead><tr><th>Peminjam</th><th>Barang</th><th>Jumlah</th><th>Tanggal</th><th>Jatuh tempo</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse ($peminjaman as $p)
                @php
                    $daysUntil = today()->startOfDay()->diffInDays($p->tanggal_kembali_rencana->startOfDay(), false);
                    $avatarTone = abs(crc32($p->nama_peminjam)) % 4;
                    $rowStatus = $p->status_tampil;
                @endphp
                <tr class="loan-row" data-status="{{ $rowStatus }}">
                    <td><div class="borrower"><span class="borrower-avatar tone-{{ $avatarTone }}">{{ strtoupper(substr($p->nama_peminjam, 0, 1)) }}</span><span><strong>{{ $p->nama_peminjam }}</strong><small>{{ $p->kelas_jabatan ?? 'Peminjam' }}</small></span></div></td>
                    <td><strong class="loan-item">{{ $p->barang->nama_barang }}</strong><small class="loan-code">{{ $p->barang->kode_barang }}</small></td>
                    <td><span class="quantity-chip">{{ $p->jumlah }} <small>unit</small></span></td>
                    <td><span class="date-text">{{ $p->tanggal_pinjam->format('d M Y') }}</span></td>
                    <td><span class="due-date {{ $rowStatus === 'dikembalikan' ? 'neutral' : ($daysUntil < 0 ? 'overdue' : ($daysUntil <= 2 ? 'soon' : '')) }}"><i class="bi bi-{{ $daysUntil < 0 && $rowStatus !== 'dikembalikan' ? 'alarm' : 'calendar3' }}"></i>{{ $p->tanggal_kembali_rencana->format('d M Y') }}@if($rowStatus !== 'dikembalikan')<small>@if($daysUntil < 0) Lewat {{ abs($daysUntil) }} hari @elseif($daysUntil === 0) Jatuh tempo hari ini @elseif($daysUntil <= 2) H-{{ $daysUntil }} @endif</small>@endif</span></td>
                    <td>@if($rowStatus === 'dikembalikan')<span class="loan-status returned"><i class="bi bi-check-circle-fill"></i>Dikembalikan</span>@elseif($rowStatus === 'terlambat')<span class="loan-status late"><i class="bi bi-exclamation-circle-fill"></i>Terlambat</span>@else<span class="loan-status active"><i class="bi bi-clock-fill"></i>Dipinjam</span>@endif</td>
                    <td class="text-end text-nowrap">
                        @if (auth()->user()->role === 'admin' && $p->status === 'dipinjam')<form action="{{ route('peminjaman.kembalikan', $p) }}" method="POST" class="d-inline">@csrf @method('PATCH')<button class="table-action return" title="Tandai dikembalikan"><i class="bi bi-box-arrow-in-down"></i></button></form>@endif
                        @if (auth()->user()->role === 'admin')<a href="{{ route('peminjaman.edit', $p) }}" class="table-action" title="Edit"><i class="bi bi-pencil-square"></i></a><button type="button" class="table-action delete" title="Hapus" data-bs-toggle="modal" data-bs-target="#deletePeminjamanModal" data-delete-form="delete-peminjaman-{{ $p->id }}" data-peminjam-name="{{ $p->nama_peminjam }}"><i class="bi bi-trash3"></i></button><form id="delete-peminjaman-{{ $p->id }}" action="{{ route('peminjaman.destroy', $p) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-table"><i class="bi bi-inbox"></i><strong>Belum ada data peminjaman</strong><span>Transaksi baru akan muncul di sini.</span></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="table-footer"><span>Menampilkan <strong id="visibleLoanCount">{{ $peminjaman->count() }}</strong> dari <strong>{{ $peminjaman->total() }}</strong> peminjaman</span><div class="pagination-wrap">{{ $peminjaman->links() }}</div></div>
<div class="modal fade" id="deletePeminjamanModal" tabindex="-1" aria-labelledby="deletePeminjamanModalLabel" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content border-0 shadow"><div class="modal-header"><h5 class="modal-title" id="deletePeminjamanModalLabel"><i class="bi bi-trash3 text-danger me-2"></i>Hapus peminjaman?</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body"><p class="mb-0">Hapus data peminjaman milik <strong id="deletePeminjamName"></strong>?</p><small class="text-muted">Stok akan dikembalikan jika barang masih berstatus dipinjam.</small></div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button><button type="button" id="confirmDeletePeminjaman" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i>Ya, hapus</button></div></div></div></div>
<script>
    const deletePeminjamanModal = document.getElementById('deletePeminjamanModal');
    let deletePeminjamanForm = null;
    deletePeminjamanModal.addEventListener('show.bs.modal', event => { const button = event.relatedTarget; deletePeminjamanForm = button.getAttribute('data-delete-form'); document.getElementById('deletePeminjamName').textContent = button.getAttribute('data-peminjam-name'); });
    document.getElementById('confirmDeletePeminjaman').addEventListener('click', () => { if (deletePeminjamanForm) document.getElementById(deletePeminjamanForm).submit(); });

    const loanRows = [...document.querySelectorAll('.loan-row')];
    const loanFilterChips = [...document.querySelectorAll('.loan-filter-chip')];
    const loanFilterResult = document.querySelector('.active-filter-result');
    const loanFilterResultCount = document.getElementById('loanFilterResult');
    const visibleLoanCount = document.getElementById('visibleLoanCount');
    loanFilterChips.forEach(chip => chip.addEventListener('click', () => {
        loanFilterChips.forEach(item => item.classList.remove('active'));
        const filter = chip.dataset.filter;
        if (chip.classList.contains('urgent-action')) document.querySelector('[data-filter="terlambat"].loan-filter-chip').classList.add('active'); else chip.classList.add('active');
        let visible = 0;
        loanRows.forEach(row => { const match = filter === 'all' || row.dataset.status === filter; row.classList.toggle('filtered-out', !match); if (match) visible++; });
        loanFilterResult.classList.toggle('d-none', filter === 'all'); loanFilterResultCount.textContent = visible; visibleLoanCount.textContent = visible;
    }));
</script>
<style>
    .page-hero { align-items: center; display: flex; justify-content: space-between; } .page-kicker { color: var(--brand); font-size: .7rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; } .page-hero h3 { color: #1d2942; font-size: 1.7rem; font-weight: 750; letter-spacing: -.035em; } .page-hero p { color: var(--muted); font-size: .85rem; } .page-action { border: 0; border-radius: 10px; font-weight: 700; padding: .7rem 1rem; } .filter-panel, .data-card { background: #fff; border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 8px 24px rgba(31,41,65,.04); } .filter-panel { padding: .7rem; } .search-field { align-items: center; display: flex; } .search-field i { color: #9aa5b8; margin: 0 -.1rem 0 .85rem; z-index: 1; } .search-field .form-control { border: 0; box-shadow: none; margin-left: -.55rem; padding-left: 1.8rem; } .filter-select { border-color: #edf0f5; font-size: .8rem; } .filter-button { border-radius: 9px; } .clear-filter { color: var(--brand); font-size: .78rem; font-weight: 600; text-decoration: none; } .data-card { overflow: hidden; } .data-card-heading { align-items: center; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; padding: 1.05rem 1.25rem; } .data-card-heading strong, .data-card-heading span { display: block; } .data-card-heading strong { color: #26324a; font-size: .92rem; } .data-card-heading span { color: #9aa5b8; font-size: .7rem; margin-top: .15rem; } .live-indicator { align-items: center; color: #3b936b !important; display: flex !important; font-weight: 700; gap: .35rem; margin: 0 !important; } .live-indicator i { background: #48b883; border-radius: 50%; display: block; height: 6px; width: 6px; } .loan-table thead th { background: #fafbfe; border-bottom: 1px solid var(--line); color: #8b96a9; font-size: .65rem; letter-spacing: .08em; padding: .85rem 1.25rem; text-transform: uppercase; white-space: nowrap; } .loan-table tbody td { border-color: #f0f2f7; color: #5d6a80; font-size: .78rem; padding: 1rem 1.25rem; } .loan-table tbody tr { transition: background .2s ease; } .loan-table tbody tr:hover { background: #fbfcff; } .borrower { align-items: center; display: flex; gap: .65rem; } .borrower-avatar { align-items: center; background: #eeedff; border-radius: 50%; color: var(--brand); display: flex; flex: 0 0 32px; font-size: .75rem; font-weight: 800; height: 32px; justify-content: center; width: 32px; } .borrower strong, .borrower small, .loan-code { display: block; } .borrower strong, .loan-item { color: #28344d; font-size: .8rem; } .borrower small, .loan-code { color: #9da7b7; font-size: .67rem; margin-top: .18rem; } .quantity-chip { background: #eef5ff; border-radius: 7px; color: #3678d4; font-weight: 800; padding: .35rem .5rem; } .quantity-chip small { font-size: .62rem; font-weight: 500; } .date-text { color: #66748b; white-space: nowrap; } .loan-status { align-items: center; border-radius: 99px; display: inline-flex; font-size: .65rem; font-weight: 700; gap: .32rem; padding: .4rem .6rem; white-space: nowrap; } .loan-status.active { background: #fff3dc; color: #b87512; } .loan-status.late { background: #ffebed; color: #d24754; } .loan-status.returned { background: #e8f7ef; color: #258756; } .table-action { align-items: center; background: transparent; border: 0; border-radius: 7px; color: #66748a; display: inline-flex; height: 31px; justify-content: center; margin-left: .1rem; text-decoration: none; width: 31px; } .table-action:hover { background: #f0f2f7; color: var(--brand); } .table-action.return { color: #24885a; } .table-action.delete { color: #d35460; } .empty-table { color: #9aa5b7 !important; padding: 3rem !important; text-align: center; } .empty-table i, .empty-table strong, .empty-table span { display: block; } .empty-table i { font-size: 1.8rem; margin-bottom: .35rem; } .empty-table strong { color: #59677d; font-size: .85rem; } .empty-table span { font-size: .72rem; margin-top: .2rem; } .pagination-wrap { margin-top: 1rem; } @media(max-width: 767.98px) { .page-hero { align-items: flex-start; flex-direction: column; gap: 1rem; } .data-card-heading { align-items: flex-start; flex-direction: column; gap: .5rem; } }
</style>
<style>
    .header-mini-stat { background: #eeedff; border-radius: 99px; color: var(--brand); display: inline-flex; font-size: .62rem; font-weight: 700; gap: .35rem; letter-spacing: 0; margin-left: .5rem; padding: .35rem .55rem; transform: translateY(-3px); vertical-align: middle; } .header-mini-stat b { color: #a7a2df; } .status-select-wrap { position: relative; } .status-select-wrap .filter-select { padding-right: 4rem; } .active-count-badge { background: #eeedff; border-radius: 99px; color: var(--brand); font-size: .6rem; font-weight: 800; padding: .22rem .4rem; pointer-events: none; position: absolute; right: 2.2rem; top: 50%; transform: translateY(-50%); }
    .quick-filters { align-items: center; display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .55rem; padding: .25rem .4rem 0; } .quick-filter-label { color: #9aa5b7; font-size: .68rem; font-weight: 700; margin-right: .2rem; } .quick-filter-label i { color: #df9b2d; } .loan-filter-chip { background: #f7f8fb; border: 1px solid #edf0f5; border-radius: 99px; color: #778398; font-size: .68rem; padding: .34rem .6rem; transition: .2s ease; } .loan-filter-chip span { background: #e9ebf3; border-radius: 99px; font-size: .58rem; margin-left: .2rem; padding: .1rem .3rem; } .loan-filter-chip:hover, .loan-filter-chip.active { background: #eeedff; border-color: #dcd9ff; color: var(--brand); } .loan-filter-chip.active span { background: #dcd9ff; } .active-filter-result { color: var(--brand); font-size: .68rem; margin-left: auto; }
    .urgent-banner { align-items: center; background: #fff8e8; border: 1px solid #f6dfaa; border-radius: 13px; color: #9b6a18; display: flex; gap: .7rem; padding: .75rem 1rem; } .urgent-icon { align-items: center; background: #ffe9ae; border-radius: 9px; display: flex; height: 32px; justify-content: center; width: 32px; } .urgent-banner strong, .urgent-banner small { display: block; } .urgent-banner strong { font-size: .76rem; } .urgent-banner small { color: #af8b4c; font-size: .67rem; margin-top: .12rem; } .urgent-action { border-color: #f0d899; color: #9b6a18; margin-left: auto; white-space: nowrap; } .urgent-action:hover { background: #ffe9ae; color: #805512; }
    .borrower-avatar { transition: transform .2s ease; } .borrower:hover .borrower-avatar { transform: rotate(-7deg) scale(1.08); } .borrower-avatar.tone-0 { background: #eeedff; color: #5146e5; } .borrower-avatar.tone-1 { background: #e5f5ff; color: #2775d3; } .borrower-avatar.tone-2 { background: #e8f7ef; color: #258756; } .borrower-avatar.tone-3 { background: #fff3dc; color: #b87512; }
    .loan-row { cursor: pointer; transition: background .2s ease, opacity .2s ease; } .loan-row:nth-child(even) { background: #fcfcfe; } .loan-row:hover { background: #f3f2ff !important; } .loan-row.filtered-out { display: none; } .due-date { align-items: flex-start; color: #66748b; display: inline-flex; flex-direction: column; font-size: .75rem; gap: .14rem; white-space: nowrap; } .due-date i { margin-right: .28rem; } .due-date small { font-size: .62rem; font-weight: 700; } .due-date.soon { color: #b87814; } .due-date.soon small { color: #d69a30; } .due-date.overdue { color: #d24754; } .due-date.overdue small { color: #d24754; } .due-date.neutral { color: #9aa5b7; } .loan-status.late { animation: late-pulse 1.9s ease-in-out infinite; } @keyframes late-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(210,71,84,0); } 50% { box-shadow: 0 0 0 4px rgba(210,71,84,.13); } } .table-footer { align-items: center; color: #929caf; display: flex; font-size: .7rem; justify-content: space-between; margin-top: 1rem; } .table-footer strong { color: #56647b; } .pagination-wrap { margin-top: 0; } .table-action.return:hover { background: #e8f7ef; color: #258756; } .table-action.delete:hover { background: #ffebed; color: #d24754; }
    @media(max-width: 767.98px) { .header-mini-stat { display: flex; margin: .5rem 0 0; width: max-content; } .urgent-banner { align-items: flex-start; flex-wrap: wrap; } .urgent-action { margin-left: 2.65rem; } .table-footer { align-items: flex-start; flex-direction: column; gap: .6rem; } }
</style>
@endsection
