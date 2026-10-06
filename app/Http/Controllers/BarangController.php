<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\LokasiBarang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::with(['kategoriRelasi', 'lokasi'])->when($request->search, function ($q) use ($request) {
            $q->where(function ($search) use ($request) {
                $search->where('nama_barang', 'like', "%{$request->search}%")
                ->orWhere('kode_barang', 'like', "%{$request->search}%")
                ->orWhere('kategori', 'like', "%{$request->search}%")
                ->orWhereHas('lokasi', fn ($location) => $location->where('nama_lokasi', 'like', "%{$request->search}%"));
            });
            })
            ->orderBy('nama_barang')
            ->paginate(10)
            ->withQueryString();

        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create', $this->masterData());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'lokasi_barang_id' => 'nullable|exists:lokasi_barangs,id',
            'stok_total' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status_barang' => 'required|in:tersedia,perbaikan,tidak_tersedia',
            'deskripsi' => 'nullable|string',
        ]);

        if ($data['kategori_id'] ?? null) {
            $data['kategori'] = Kategori::findOrFail($data['kategori_id'])->nama;
        }

        // stok tersedia awal = stok total
        $data['stok_tersedia'] = $data['stok_total'];

        Barang::create($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', ['barang' => $barang] + $this->masterData());
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'kode_barang' => 'required|string|unique:barangs,kode_barang,' . $barang->id,
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:100',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'lokasi_barang_id' => 'nullable|exists:lokasi_barangs,id',
            'stok_total' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'status_barang' => 'required|in:tersedia,perbaikan,tidak_tersedia',
            'deskripsi' => 'nullable|string',
        ]);

        if ($data['kategori_id'] ?? null) {
            $data['kategori'] = Kategori::findOrFail($data['kategori_id'])->nama;
        }

        $sedangDipinjam = $barang->peminjaman()->whereIn('status', ['dipinjam', 'terlambat'])->sum('jumlah');
        if ($data['stok_total'] < $sedangDipinjam) {
            return back()->withInput()->withErrors([
                'stok_total' => "Stok total tidak boleh kurang dari {$sedangDipinjam} unit yang sedang dipinjam.",
            ]);
        }

        // sesuaikan stok tersedia mengikuti selisih perubahan stok total
        $selisih = $data['stok_total'] - $barang->stok_total;
        $data['stok_tersedia'] = max(0, $barang->stok_tersedia + $selisih);

        $barang->update($data);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->peminjaman()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    private function masterData(): array
    {
        return [
            'kategoris' => Kategori::orderBy('nama')->get(),
            'lokasis' => LokasiBarang::orderBy('nama_lokasi')->get(),
        ];
    }
}
