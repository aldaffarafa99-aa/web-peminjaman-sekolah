@extends('layouts.app')
@php
    $isLocation = $type === 'lokasi';
    $baseRoute = $isLocation ? 'lokasi-barang' : 'kategori';
    $title = $isLocation ? 'Lokasi Barang' : 'Kategori';
    $nameField = $isLocation ? 'nama_lokasi' : 'nama';
    $isEditing = $item->exists;
@endphp
@section('title', ($isEditing ? 'Edit ' : 'Tambah ') . $title)

@section('content')
<div class="page-hero mb-4">
    <div><div class="page-kicker">Data master</div><h3 class="mb-1">{{ $isEditing ? 'Edit' : 'Tambah' }} {{ $title }}</h3></div>
    <a href="{{ route($baseRoute . '.index') }}" class="btn btn-light">Kembali</a>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ $isEditing ? route($baseRoute . '.update', $item) : route($baseRoute . '.store') }}">
            @csrf
            @if ($isEditing) @method('PUT') @endif
            @if ($isLocation)
                <div class="mb-3"><label class="form-label">Kode lokasi</label><input class="form-control" name="kode_lokasi" value="{{ old('kode_lokasi', $item->kode_lokasi) }}" required></div>
            @endif
            <div class="mb-3"><label class="form-label">{{ $isLocation ? 'Nama lokasi' : 'Nama kategori' }}</label><input class="form-control" name="{{ $nameField }}" value="{{ old($nameField, $item->{$nameField}) }}" required></div>
            <div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi', $item->deskripsi) }}</textarea></div>
            <button class="btn btn-primary">{{ $isEditing ? 'Simpan perubahan' : 'Tambah' }}</button>
            <a href="{{ route($baseRoute . '.index') }}" class="btn btn-light">Batal</a>
        </form>
    </div>
</div>
@endsection
