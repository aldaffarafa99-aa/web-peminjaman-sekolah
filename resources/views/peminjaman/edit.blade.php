@extends('layouts.app')
@section('title', 'Edit Peminjaman')

@section('content')
<h3 class="mb-3">Edit Peminjaman</h3>

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

        <p class="text-muted">Barang: <strong>{{ $peminjaman->barang->nama_barang }}</strong> ({{ $peminjaman->jumlah }} unit)</p>

        <form method="POST" action="{{ route('peminjaman.update', $peminjaman) }}">
            @csrf
            @method('PUT')
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Peminjam</label>
                    <input type="text" name="nama_peminjam" class="form-control" value="{{ old('nama_peminjam', $peminjaman->nama_peminjam) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelas / Jabatan</label>
                    <input type="text" name="kelas_jabatan" class="form-control" value="{{ old('kelas_jabatan', $peminjaman->kelas_jabatan) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rencana Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana', $peminjaman->tanggal_kembali_rencana->format('Y-m-d')) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="2">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                </div>
            </div>
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
