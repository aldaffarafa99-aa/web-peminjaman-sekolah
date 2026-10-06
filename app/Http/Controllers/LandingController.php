<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $totalBarang = Barang::count();
        $barangTersedia = Barang::where('status_barang', 'tersedia')->sum('stok_tersedia');
        $sedangDipinjam = Peminjaman::whereIn('status', ['dipinjam', 'terlambat'])->count();
        $kategoriBarang = Barang::whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct('kategori')
            ->count('kategori');

        $barangPopuler = Barang::withCount([
            'peminjaman as total_peminjaman' => function ($query) {
                $query->whereIn('status', ['dipinjam', 'selesai', 'dikembalikan']);
            },
        ])
            ->orderByDesc('total_peminjaman')
            ->orderByDesc('updated_at')
            ->take(6)
            ->get();

        $searchTerm = trim((string) $request->query('search', ''));
        $searchResults = [];

        if ($searchTerm !== '') {
            $searchResults = Barang::where(function ($query) use ($searchTerm) {
                $query->where('nama_barang', 'like', "%{$searchTerm}%")
                    ->orWhere('kode_barang', 'like', "%{$searchTerm}%")
                    ->orWhere('kategori', 'like', "%{$searchTerm}%");
            })
                ->orderBy('nama_barang')
                ->take(5)
                ->get();
        }

        $featuredItems = $barangPopuler->map(function (Barang $barang) {
            return [
                'id' => $barang->id,
                'nama' => $barang->nama_barang,
                'kategori' => $barang->kategori ?? 'Umum',
                'stok' => $barang->stok_tersedia,
                'status' => $barang->status_barang === 'tersedia' && $barang->stok_tersedia > 0 ? 'Tersedia' : 'Tidak tersedia',
                'route' => auth()->check()
                    ? route('peminjaman.create', ['barang_id' => $barang->id])
                    : route('login'),
            ];
        })->values();

        return view('landing', compact(
            'totalBarang',
            'barangTersedia',
            'sedangDipinjam',
            'kategoriBarang',
            'barangPopuler',
            'searchTerm',
            'searchResults',
            'featuredItems'
        ));
    }
}