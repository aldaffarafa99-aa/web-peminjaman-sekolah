<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $items = Kategori::withCount('barangs')->orderBy('nama')->paginate(15);
        return view('master-data.kategori.index', compact('items'));
    }

    public function create()
    {
        return view('master-data.form', ['item' => new Kategori(), 'type' => 'kategori']);
    }

    public function store(Request $request)
    {
        Kategori::create($request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama',
            'deskripsi' => 'nullable|string|max:2000',
        ]));

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('master-data.form', ['item' => $kategori, 'type' => 'kategori']);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $kategori->update($request->validate([
            'nama' => 'required|string|max:255|unique:kategoris,nama,' . $kategori->id,
            'deskripsi' => 'nullable|string|max:2000',
        ]));

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->barangs()->exists()) {
            return back()->with('error', 'Kategori masih digunakan oleh barang.');
        }

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
