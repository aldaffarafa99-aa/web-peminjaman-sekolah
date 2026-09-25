@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')
@php
    $totalUnitTampil = $barangs->sum('stok_total');
    $stokMenipisTampil = $barangs->filter(fn ($barang) => $barang->stok_total > 0 && ($barang->stok_tersedia / $barang->stok_total) < .2)->count();
@endphp
<div class="page-hero mb-4">
    <div>
        <div class="page-kicker"><i class="bi bi-box-seam me-1"></i> Inventaris sekolah</div>
        <h3 class="mb-1">Data Barang</h3>
        <p class="mb-0">Kelola koleksi barang dan pantau ketersediaannya.</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="hero-count"><strong class="count-up" data-count="{{ $barangs->total() }}">0</strong><span>jenis barang</span></div>
        <div class="hero-count secondary"><strong class="count-up" data-count="{{ $totalUnitTampil }}">0</strong><span>total unit</span></div>
        <div class="hero-low-stock"><i class="bi bi-exclamation-triangle"></i><strong>{{ $stokMenipisTampil }}</strong><span>stok menipis</span></div>
        <a href="{{ route('barang.create') }}" class="btn btn-primary page-action"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
    </div>
</div>

<div class="filter-panel mb-3">
    <form class="row g-2 align-items-center" method="GET">
        <div class="col-md-5">
            <div class="search-field"><i class="bi bi-search"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama atau kode barang..."></div>
        </div>
        <div class="col-auto"><button class="btn btn-dark filter-button"><i class="bi bi-funnel me-1"></i>Cari</button></div>
        @if(request('search'))
            <div class="col-auto"><a href="{{ route('barang.index') }}" class="clear-filter">Reset pencarian</a></div>
        @endif
    </form>
    <div class="quick-filters" aria-label="Filter cepat">
        <span class="quick-filter-label"><i class="bi bi-lightning-charge-fill"></i> Filter cepat</span>
        <button type="button" class="filter-chip active" data-filter="all">Semua <span>{{ $barangs->count() }}</span></button>
        <button type="button" class="filter-chip" data-filter="elektronik">Elektronik</button>
        <button type="button" class="filter-chip" data-filter="olahraga">Olahraga</button>
        <button type="button" class="filter-chip" data-filter="low">Stok menipis <span>{{ $stokMenipisTampil }}</span></button>
        <button type="button" class="filter-chip" data-filter="damaged">Kondisi rusak</button>
        <span class="active-filter-result d-none"><i class="bi bi-funnel-fill"></i> <strong id="filterResultCount">{{ $barangs->count() }}</strong> hasil</span>
    </div>
</div>

<div class="data-card">
    <div class="data-card-heading"><div><strong>Daftar inventaris</strong><span>Informasi stok terkini</span></div><span class="live-indicator"><i></i> Data aktif</span></div>
    <div class="table-responsive">
        <table class="table inventory-table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Stok Tersedia</th><th>Kondisi</th><th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                @php
                    $stockPercent = $barang->stok_total > 0 ? ($barang->stok_tersedia / $barang->stok_total) * 100 : 0;
                    $stockTone = $stockPercent < 20 ? 'critical' : ($stockPercent < 55 ? 'warning' : 'safe');
                    $category = strtolower(($barang->kategori ?? '') . ' ' . $barang->nama_barang);
                    $categoryIcon = str_contains($category, 'proyektor') ? 'display' : (str_contains($category, 'laptop') ? 'laptop' : (str_contains($category, 'bola') || str_contains($category, 'olahraga') ? 'dribbble' : (str_contains($category, 'elektronik') ? 'cpu' : (str_contains($category, 'atk') ? 'pencil' : 'box-seam'))));
                    $categoryKey = str_contains($category, 'elektronik') ? 'elektronik' : (str_contains($category, 'olahraga') || str_contains($category, 'bola') ? 'olahraga' : 'other');
                    $conditionKey = $barang->kondisi === 'baik' ? 'good' : 'damaged';
                @endphp
                <tr class="inventory-row" data-category="{{ $categoryKey }}" data-low="{{ $stockPercent < 20 ? 'true' : 'false' }}" data-condition="{{ $conditionKey }}">
                    <td><span class="code-chip">{{ $barang->kode_barang }}</span></td>
                    <td><div class="item-name"><span class="item-icon category-{{ $categoryKey }}"><i class="bi bi-{{ $categoryIcon }}"></i></span><span><strong>{{ $barang->nama_barang }} @if($stockPercent < 20)<em class="low-stock-badge"><i class="bi bi-exclamation-circle"></i> Menipis</em>@endif</strong>@if($barang->deskripsi)<small>{{ Str::limit($barang->deskripsi, 35) }}</small>@else<small class="no-description">Deskripsi belum ditambahkan</small>@endif</span></div></td>
                    <td><span class="category-text">{{ $barang->kategori ?? 'Umum' }}</span></td>
                    <td><div class="stock-info"><strong>{{ $barang->stok_tersedia }} <small>/ {{ $barang->stok_total }} unit</small></strong><div class="stock-track {{ $stockTone }}"><span style="width: {{ $stockPercent }}%"></span></div></div></td>
                    <td>
                        @php $badge = ['baik' => 'good', 'rusak_ringan' => 'warning', 'rusak_berat' => 'danger'][$barang->kondisi]; @endphp
                        <span class="condition-badge {{ $badge }}"><i class="bi bi-circle-fill"></i>{{ $barang->kondisi === 'baik' ? 'Baik' : ($barang->kondisi === 'rusak_ringan' ? 'Perlu perhatian' : 'Rusak') }}</span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('barang.edit', $barang) }}" class="icon-action edit" title="Edit barang"><i class="bi bi-pencil-square"></i></a>
                        <button type="button" class="icon-action delete" title="Hapus barang" data-bs-toggle="modal" data-bs-target="#deleteBarangModal" data-delete-url="{{ route('barang.destroy', $barang) }}" data-barang-name="{{ $barang->nama_barang }}"><i class="bi bi-trash3"></i></button>
                        <form id="delete-form-{{ $barang->id }}" action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="empty-table"><i class="bi bi-inbox"></i><strong>Belum ada data barang</strong><span>Tambahkan barang pertama untuk memulai inventaris.</span></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="table-footer"><span>Menampilkan <strong id="visibleInventoryCount">{{ $barangs->count() }}</strong> dari <strong>{{ $barangs->total() }}</strong> barang</span><div class="pagination-wrap">{{ $barangs->links() }}</div></div>

<div class="modal fade" id="deleteBarangModal" tabindex="-1" aria-labelledby="deleteBarangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBarangModalLabel"><i class="bi bi-trash3 text-danger me-2"></i>Hapus barang?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah kamu yakin ingin menghapus <strong id="deleteBarangName"></strong>?</p>
                <small class="text-muted">Data yang sudah dihapus tidak dapat dikembalikan.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmDeleteBarang" class="btn btn-danger"><i class="bi bi-trash3 me-1"></i>Ya, hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    const deleteBarangModal = document.getElementById('deleteBarangModal');
    const deleteBarangName = document.getElementById('deleteBarangName');
    const confirmDeleteBarang = document.getElementById('confirmDeleteBarang');
    let deleteBarangUrl = null;

    deleteBarangModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        deleteBarangUrl = button.getAttribute('data-delete-url');
        deleteBarangName.textContent = button.getAttribute('data-barang-name');
    });

    confirmDeleteBarang.addEventListener('click', () => {
        if (deleteBarangUrl) {
            document.querySelector(`form[action="${deleteBarangUrl}"]`).submit();
        }
    });

    const inventoryRows = [...document.querySelectorAll('.inventory-row')];
    const filterChips = [...document.querySelectorAll('.filter-chip')];
    const filterResult = document.querySelector('.active-filter-result');
    const filterResultCount = document.getElementById('filterResultCount');
    const visibleInventoryCount = document.getElementById('visibleInventoryCount');

    filterChips.forEach(chip => chip.addEventListener('click', () => {
        filterChips.forEach(item => item.classList.remove('active'));
        chip.classList.add('active');
        const filter = chip.dataset.filter;
        let visible = 0;
        inventoryRows.forEach(row => {
            const match = filter === 'all' || (filter === 'low' && row.dataset.low === 'true') || (filter === 'damaged' && row.dataset.condition === 'damaged') || row.dataset.category === filter;
            row.classList.toggle('filtered-out', !match);
            if (match) visible++;
        });
        filterResult.classList.toggle('d-none', filter === 'all');
        filterResultCount.textContent = visible;
        visibleInventoryCount.textContent = visible;
    }));

    document.querySelectorAll('.count-up').forEach(counter => {
        const target = Number(counter.dataset.count);
        let current = 0;
        const step = Math.max(1, Math.ceil(target / 16));
        const timer = setInterval(() => { current = Math.min(target, current + step); counter.textContent = current; if (current >= target) clearInterval(timer); }, 35);
    });
</script>
<style>
    .page-hero { align-items: center; display: flex; justify-content: space-between; } .page-kicker { color: var(--brand); font-size: .7rem; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; } .page-hero h3 { color: #1d2942; font-size: 1.7rem; font-weight: 750; letter-spacing: -.035em; } .page-hero p { color: var(--muted); font-size: .85rem; } .hero-count { border-right: 1px solid var(--line); padding-right: 1.25rem; text-align: right; } .hero-count strong, .hero-count span { display: block; } .hero-count strong { color: var(--brand); font-size: 1.4rem; } .hero-count span { color: var(--muted); font-size: .68rem; } .page-action { border: 0; border-radius: 10px; font-weight: 700; padding: .7rem 1rem; }
    .filter-panel, .data-card { background: #fff; border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 8px 24px rgba(31,41,65,.04); } .filter-panel { padding: .7rem; } .search-field { align-items: center; display: flex; } .search-field i { color: #9aa5b8; margin: 0 -.1rem 0 .85rem; z-index: 1; } .search-field .form-control { border: 0; box-shadow: none; margin-left: -.55rem; padding-left: 1.8rem; } .filter-button { border-radius: 9px; } .clear-filter { color: var(--brand); font-size: .78rem; font-weight: 600; text-decoration: none; }
    .data-card { overflow: hidden; } .data-card-heading { align-items: center; border-bottom: 1px solid var(--line); display: flex; justify-content: space-between; padding: 1.05rem 1.25rem; } .data-card-heading strong, .data-card-heading span { display: block; } .data-card-heading strong { color: #26324a; font-size: .92rem; } .data-card-heading span { color: #9aa5b8; font-size: .7rem; margin-top: .15rem; } .live-indicator { align-items: center; color: #3b936b !important; display: flex !important; font-weight: 700; gap: .35rem; margin: 0 !important; } .live-indicator i { background: #48b883; border-radius: 50%; display: block; height: 6px; width: 6px; }
    .inventory-table thead th { background: #fafbfe; border-bottom: 1px solid var(--line); color: #8b96a9; font-size: .65rem; letter-spacing: .08em; padding: .85rem 1.25rem; text-transform: uppercase; white-space: nowrap; } .inventory-table tbody td { border-color: #f0f2f7; color: #5d6a80; font-size: .78rem; padding: 1rem 1.25rem; } .inventory-table tbody tr { transition: background .2s ease; } .inventory-table tbody tr:hover { background: #fbfcff; } .code-chip { background: #f0efff; border-radius: 6px; color: var(--brand); font-size: .68rem; font-weight: 800; padding: .35rem .5rem; } .item-name { align-items: center; display: flex; gap: .7rem; } .item-name strong, .item-name small { display: block; } .item-name strong { color: #28344d; font-size: .82rem; } .item-name small { color: #a0a9b8; font-size: .67rem; margin-top: .18rem; } .item-icon { align-items: center; background: #eaf1ff; border-radius: 10px; color: #3976d2; display: flex; height: 35px; justify-content: center; width: 35px; } .category-text { color: #68758b; } .stock-info { min-width: 105px; } .stock-info strong { color: #334159; font-size: .78rem; } .stock-info small { color: #9ba5b6; font-size: .65rem; font-weight: 500; } .stock-track { background: #edf0f5; border-radius: 99px; height: 5px; margin-top: .42rem; overflow: hidden; width: 100px; } .stock-track span { background: linear-gradient(90deg, #5146e5, #6d91eb); border-radius: inherit; display: block; height: 100%; } .condition-badge { align-items: center; border-radius: 99px; display: inline-flex; font-size: .65rem; font-weight: 700; gap: .35rem; padding: .36rem .55rem; text-transform: capitalize; } .condition-badge i { font-size: .35rem; } .condition-badge.good { background: #e8f7ef; color: #27885a; } .condition-badge.warning { background: #fff4dc; color: #b97a18; } .condition-badge.danger { background: #ffebed; color: #d24754; } .icon-action { align-items: center; background: transparent; border: 1px solid transparent; border-radius: 7px; display: inline-flex; height: 31px; justify-content: center; margin-left: .15rem; text-decoration: none; width: 31px; } .icon-action.edit { color: #61718b; } .icon-action.delete { color: #d35460; } .icon-action:hover { background: #f1f3f8; } .empty-table { color: #9aa5b7 !important; padding: 3rem !important; text-align: center; } .empty-table i, .empty-table strong, .empty-table span { display: block; } .empty-table i { font-size: 1.8rem; margin-bottom: .35rem; } .empty-table strong { color: #59677d; font-size: .85rem; } .empty-table span { font-size: .72rem; margin-top: .2rem; } .pagination-wrap { margin-top: 1rem; } @media(max-width: 767.98px) { .page-hero { align-items: flex-start; flex-direction: column; gap: 1rem; } .hero-count { display: none; } .data-card-heading { align-items: flex-start; flex-direction: column; gap: .5rem; } }
</style>
<style>
    .hero-count.secondary { border-right: 0; } .hero-low-stock { align-items: center; background: #fff4dc; border-radius: 9px; color: #b97816; display: flex; font-size: .68rem; gap: .3rem; padding: .5rem .6rem; } .hero-low-stock strong { font-size: .85rem; } .hero-low-stock span { color: #9d7b3b; }
    .quick-filters { align-items: center; display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .55rem; padding: .25rem .4rem 0; } .quick-filter-label { color: #9aa5b7; font-size: .68rem; font-weight: 700; margin-right: .2rem; } .quick-filter-label i { color: #df9b2d; } .filter-chip { background: #f7f8fb; border: 1px solid #edf0f5; border-radius: 99px; color: #778398; font-size: .68rem; padding: .34rem .6rem; transition: .2s ease; } .filter-chip span { background: #e9ebf3; border-radius: 99px; font-size: .58rem; margin-left: .2rem; padding: .1rem .3rem; } .filter-chip:hover, .filter-chip.active { background: #eeedff; border-color: #dcd9ff; color: var(--brand); } .filter-chip.active span { background: #dcd9ff; } .active-filter-result { color: var(--brand); font-size: .68rem; margin-left: auto; }
    .inventory-table tbody tr { cursor: pointer; } .inventory-table tbody tr:nth-child(even) { background: #fcfcfe; } .inventory-table tbody tr:hover { background: #f3f2ff; } .inventory-row.filtered-out { display: none; } .item-name .no-description { color: #b4bbc7; font-style: italic; } .item-icon.category-elektronik { background: #eeedff; color: var(--brand); } .item-icon.category-olahraga { background: #e8f7ef; color: #258756; } .low-stock-badge { background: #fff4dc; border-radius: 5px; color: #b97816; font-size: .58rem; font-style: normal; font-weight: 700; margin-left: .35rem; padding: .2rem .3rem; white-space: nowrap; } .stock-track span { background: #43ad7a; } .stock-track.warning span { background: #dfa02c; } .stock-track.critical span { background: #dc5860; } .table-footer { align-items: center; color: #929caf; display: flex; font-size: .7rem; justify-content: space-between; margin-top: 1rem; } .table-footer strong { color: #56647b; } .pagination-wrap { margin-top: 0; } .icon-action.edit:hover { background: #eaf1ff; color: #3976d2; } .icon-action.delete:hover { background: #ffebed; color: #d24754; }
    @media(max-width: 767.98px) { .hero-count, .hero-low-stock { display: none; } .table-footer { align-items: flex-start; flex-direction: column; gap: .6rem; } }
</style>
@endsection
