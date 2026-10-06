@extends('layouts.app')
@section('title', 'Lokasi Barang')
@section('content')
<div class="page-hero mb-4"><div><div class="page-kicker">Data master</div><h3 class="mb-1">Lokasi Barang</h3><p class="mb-0">Catat ruang atau tempat penyimpanan inventaris.</p></div><a href="{{ route('lokasi-barang.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah lokasi</a></div>
<div class="data-card table-responsive"><table class="table mb-0 align-middle"><thead><tr><th>Kode</th><th>Nama lokasi</th><th>Deskripsi</th><th>Barang</th><th class="text-end">Aksi</th></tr></thead><tbody>
@forelse ($items as $item)<tr><td>{{ $item->kode_lokasi }}</td><td>{{ $item->nama_lokasi }}</td><td>{{ $item->deskripsi ?? '-' }}</td><td>{{ $item->barangs_count }}</td><td class="text-end text-nowrap"><a class="btn btn-sm btn-light" href="{{ route('lokasi-barang.edit', $item) }}">Edit</a><form class="d-inline" method="POST" action="{{ route('lokasi-barang.destroy', $item) }}" onsubmit="return confirm('Hapus lokasi ini?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>
@empty<tr><td colspan="5" class="text-center text-muted py-4">Belum ada lokasi.</td></tr>@endforelse
</tbody></table></div><div class="mt-3">{{ $items->links() }}</div>
@endsection
