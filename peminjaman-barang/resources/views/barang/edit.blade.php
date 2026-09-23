@extends('layouts.app')
@section('title', 'Edit Barang')

@section('content')
<h3 class="mb-3">Edit Barang</h3>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('barang.update', $barang) }}">
            @csrf
            @method('PUT')
            @include('barang._form')
            <button class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
