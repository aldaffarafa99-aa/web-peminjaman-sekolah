@extends('layouts.app')
@section('title', 'Catat Peminjaman')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <div class="text-uppercase small fw-bold text-primary">{{ auth()->user()->role === 'admin' ? 'Panel admin' : 'Portal siswa' }}</div>
        <h3 class="mb-0">{{ auth()->user()->role === 'admin' ? 'Catat Peminjaman Baru' : 'Ajukan Peminjaman' }}</h3>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-light"><i class="bi bi-arrow-left me-1"></i> Kembali ke dashboard</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
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
            <div class="row g-3 mb-3">
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
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
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
@endsection
