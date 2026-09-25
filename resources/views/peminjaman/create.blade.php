@extends('layouts.app')
@section('title', 'Catat Peminjaman')

@section('content')
<div class="form-hero d-flex align-items-center justify-content-between mb-4">
    <div>
        <div class="text-uppercase small fw-bold text-primary">{{ auth()->user()->role === 'admin' ? 'Panel admin' : 'Portal siswa' }}</div>
        <h3 class="mb-1">{{ auth()->user()->role === 'admin' ? 'Catat Peminjaman Baru' : 'Ajukan Peminjaman' }}</h3><p class="mb-0">Lengkapi detail barang dan jadwal pengembalian.</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali ke dashboard</a>
</div>

<div class="form-card">
    <div class="form-card-heading"><span class="form-heading-icon"><i class="bi bi-clipboard2-plus"></i></span><div><strong>Detail peminjaman</strong><small>Pastikan informasi yang dimasukkan sudah sesuai.</small></div></div>
    <div class="card-body p-4 p-lg-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('peminjaman.store') }}">
            @csrf
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Barang</label>
                    <select name="barang_id" id="barang_id" class="form-select" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach ($barangs as $barang)
                            <option value="{{ $barang->id }}" data-stok="{{ $barang->stok_tersedia }}" @selected(old('barang_id') == $barang->id)>
                                {{ $barang->nama_barang }} (tersedia: {{ $barang->stok_tersedia }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jumlah</label>
                    <input type="number" min="1" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 1) }}" required>
                    <div id="stok-help" class="form-text">Pilih barang untuk melihat stok tersedia.</div>
                </div>
                <div class="col-md-6">
                            <label class="form-label">Nama Peminjam</label>
                            <input type="text" name="nama_peminjam" class="form-control" value="{{ old('nama_peminjam', auth()->user()->role === 'siswa' ? auth()->user()->name : '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelas / Jabatan</label>
                    <input type="text" name="kelas_jabatan" class="form-control" placeholder="Kelas XII IPA 1 / Guru Matematika" value="{{ old('kelas_jabatan') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Pinjam</label>
                    <input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rencana Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2">{{ old('catatan') }}</textarea>
                </div>
            </div>
            <div class="form-actions"><button class="btn btn-primary page-action"><i class="bi bi-check2-circle me-1"></i>Simpan Peminjaman</button><a href="{{ route('peminjaman.index') }}" class="btn btn-light"><i class="bi bi-x-lg me-1"></i>Batal</a></div>
        </form>
    </div>
</div>

<script>
    const barangSelect = document.getElementById('barang_id');
    const jumlahInput = document.getElementById('jumlah');
    const stokHelp = document.getElementById('stok-help');

    function updateStokInput() {
        const option = barangSelect.options[barangSelect.selectedIndex];
        const stok = option?.dataset.stok;
        jumlahInput.max = stok || '';
        stokHelp.textContent = stok ? `Maksimal ${stok} unit tersedia.` : 'Pilih barang untuk melihat stok tersedia.';
    }

    barangSelect.addEventListener('change', updateStokInput);
    updateStokInput();
</script>
<style>
    .form-hero h3 { color: #1d2942; font-size: 1.7rem; font-weight: 750; letter-spacing: -.035em; } .form-hero p { color: var(--muted); font-size: .85rem; } .form-hero .text-uppercase { letter-spacing: .14em; font-size: .7rem; } .form-card { background: #fff; border: 1px solid var(--line); border-radius: 18px; box-shadow: 0 10px 28px rgba(31,41,65,.05); overflow: hidden; } .form-card-heading { align-items: center; background: linear-gradient(110deg, #f7f7ff, #f4f8ff); border-bottom: 1px solid var(--line); display: flex; gap: .8rem; padding: 1.15rem 1.5rem; } .form-card-heading strong, .form-card-heading small { display: block; } .form-card-heading strong { color: #29354d; font-size: .9rem; } .form-card-heading small { color: #8995a9; font-size: .7rem; margin-top: .18rem; } .form-heading-icon { align-items: center; background: #5146e5; border-radius: 11px; color: #fff; display: flex; height: 38px; justify-content: center; width: 38px; } .form-card .form-label { color: #4d5b72; font-size: .78rem; font-weight: 700; margin-bottom: .45rem; } .form-card .form-control, .form-card .form-select { border-color: #e5e9f1; border-radius: 9px; font-size: .82rem; padding: .68rem .8rem; } .form-card .form-control:focus, .form-card .form-select:focus { border-color: #8179ed; box-shadow: 0 0 0 .2rem rgba(81,70,229,.1); } .form-actions { border-top: 1px solid #eef0f5; display: flex; gap: .6rem; padding-top: 1.25rem; } .form-actions .btn { border-radius: 9px; font-weight: 700; }
    @media(max-width: 767.98px) { .form-hero { align-items: flex-start !important; flex-direction: column; gap: 1rem; } .form-card .card-body { padding: 1.25rem !important; } .form-actions { flex-direction: column; } }
</style>
@endsection
