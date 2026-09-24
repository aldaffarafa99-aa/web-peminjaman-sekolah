@extends('layouts.app')
@section('title', 'Peminjaman')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Peminjaman</h3>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Catat Peminjaman</a>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari peminjam/barang/kode...">
    </div>
    <div class="col-auto">
        <select name="status" class="form-select">
            <option value="">Semua Status</option>
            <option value="dipinjam" @selected(request('status')==='dipinjam')>Dipinjam</option>
            <option value="terlambat" @selected(request('status')==='terlambat')>Terlambat</option>
            <option value="dikembalikan" @selected(request('status')==='dikembalikan')>Dikembalikan</option>
        </select>
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary">Filter</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Kelas/Jabatan</th>
                    <th>Barang</th>
                    <th>Jml</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjaman as $p)
                <tr>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->kelas_jabatan ?? '-' }}</td>
                    <td>{{ $p->barang->nama_barang }}</td>
                    <td>{{ $p->jumlah }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                    <td>
                        @if($p->status_tampil === 'dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>
                        @elseif($p->status_tampil === 'terlambat')
                            <span class="badge bg-danger">Terlambat</span>
                        @else
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if (auth()->user()->role === 'admin' && $p->status === 'dipinjam')
                            <form action="{{ route('peminjaman.kembalikan', $p) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm btn-success">Kembalikan</button>
                            </form>
                        @endif
                        @if (auth()->user()->role === 'admin')
                            <a href="{{ route('peminjaman.edit', $p) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('peminjaman.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center text-muted py-3">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $peminjaman->links() }}</div>
@endsection
