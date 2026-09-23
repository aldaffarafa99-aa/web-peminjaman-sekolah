@extends('layouts.app')
@section('title', 'Tambah Barang')

@section('content')
<h3 class="mb-3">Tambah Barang</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('barang.store') }}">
            @csrf
            @include('barang._form')
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
