@extends('layouts.app')
@section('title', 'Data Barang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Data Barang</h3>
    <a href="{{ route('barang.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Barang</a>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-auto">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama/kode barang...">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-secondary">Cari</button>
    </div>
</form>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0 align-middle">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok Tersedia</th>
                    <th>Stok Total</th>
                    <th>Kondisi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                <tr>
                    <td>{{ $barang->kode_barang }}</td>
                    <td>{{ $barang->nama_barang }}</td>
                    <td>{{ $barang->kategori ?? '-' }}</td>
                    <td>{{ $barang->stok_tersedia }}</td>
                    <td>{{ $barang->stok_total }}</td>
                    <td>
                        @php
                            $badge = ['baik' => 'success', 'rusak_ringan' => 'warning', 'rusak_berat' => 'danger'][$barang->kondisi];
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ str_replace('_', ' ', $barang->kondisi) }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-3">Belum ada data barang.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $barangs->links() }}</div>
@endsection
