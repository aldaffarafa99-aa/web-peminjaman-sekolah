@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h3 class="mb-4">Dashboard</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary shadow-sm">
            <div class="card-body">
                <div class="fs-4 fw-bold">{{ $totalBarang }}</div>
                <div>Jenis Barang</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-secondary shadow-sm">
            <div class="card-body">
                <div class="fs-4 fw-bold">{{ $totalStok }}</div>
                <div>Total Unit Barang</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning shadow-sm">
            <div class="card-body">
                <div class="fs-4 fw-bold">{{ $sedangDipinjam }}</div>
                <div>Sedang Dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-danger shadow-sm">
            <div class="card-body">
                <div class="fs-4 fw-bold">{{ $terlambat }}</div>
                <div>Terlambat Dikembalikan</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header fw-bold">Peminjaman Terbaru</div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tgl Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peminjamanTerbaru as $p)
                <tr>
                    <td>{{ $p->nama_peminjam }}</td>
                    <td>{{ $p->barang->nama_barang }}</td>
                    <td>{{ $p->jumlah }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>
                        @if($p->status_tampil === 'dikembalikan')
                            <span class="badge bg-success">Dikembalikan</span>
                        @elseif($p->status_tampil === 'terlambat')
                            <span class="badge bg-danger">Terlambat</span>
                        @else
                            <span class="badge bg-warning text-dark">Dipinjam</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
