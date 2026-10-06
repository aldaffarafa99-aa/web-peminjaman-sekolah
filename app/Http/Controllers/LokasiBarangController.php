<?php

namespace App\Http\Controllers;

use App\Models\LokasiBarang;
use Illuminate\Http\Request;

class LokasiBarangController extends Controller
{
    public function index()
    {
        $items = LokasiBarang::withCount('barangs')->orderBy('nama_lokasi')->paginate(15);
        return view('master-data.lokasi.index', compact('items'));
    }

    public function create()
    {
        return view('master-data.form', ['item' => new LokasiBarang(), 'type' => 'lokasi']);
    }

    public function store(Request $request)
    {
        LokasiBarang::create($request->validate([
            'kode_lokasi' => 'required|string|max:100|unique:lokasi_barangs,kode_lokasi',
            'nama_lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
        ]));

        return redirect()->route('lokasi-barang.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(LokasiBarang $lokasi_barang)
    {
        return view('master-data.form', ['item' => $lokasi_barang, 'type' => 'lokasi']);
    }

    public function update(Request $request, LokasiBarang $lokasi_barang)
    {
        $lokasi_barang->update($request->validate([
            'kode_lokasi' => 'required|string|max:100|unique:lokasi_barangs,kode_lokasi,' . $lokasi_barang->id,
            'nama_lokasi' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:2000',
        ]));

        return redirect()->route('lokasi-barang.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(LokasiBarang $lokasi_barang)
    {
        if ($lokasi_barang->barangs()->exists()) {
            return back()->with('error', 'Lokasi masih digunakan oleh barang.');
        }

        $lokasi_barang->delete();
        return redirect()->route('lokasi-barang.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
