@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label">Kode Barang</label>
        <input type="text" name="kode_barang" class="form-control" value="{{ old('kode_barang', $barang->kode_barang ?? '') }}" required>
    </div>
    <div class="col-md-8">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" value="{{ old('nama_barang', $barang->nama_barang ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Kategori</label>
        <input type="text" name="kategori" class="form-control" placeholder="Elektronik, ATK, Olahraga, dll" value="{{ old('kategori', $barang->kategori ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Stok Total</label>
        <input type="number" min="0" name="stok_total" class="form-control" value="{{ old('stok_total', $barang->stok_total ?? 0) }}" required>
        @isset($barang)
            <div class="form-text">Stok tersedia saat ini: {{ $barang->stok_tersedia }}</div>
        @endisset
    </div>
    <div class="col-md-4">
        <label class="form-label">Kondisi</label>
        <select name="kondisi" class="form-select" required>
            @foreach (['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat'] as $val => $label)
                <option value="{{ $val }}" @selected(old('kondisi', $barang->kondisi ?? 'baik') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $barang->deskripsi ?? '') }}</textarea>
    </div>
</div>
