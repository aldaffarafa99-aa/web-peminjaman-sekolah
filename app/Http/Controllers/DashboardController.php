<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            $peminjamanSaya = Peminjaman::with('barang')
                ->where('user_id', auth()->id())
                ->latest()
                ->take(6)
                ->get();

            return view('dashboard-siswa', compact('peminjamanSaya'));
        }

        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok_total');
        $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', '<', today())
            ->count();
        $stokMenipis = Barang::where('stok_tersedia', '<=', 2)
            ->orderBy('stok_tersedia')
            ->orderBy('nama_barang')
            ->take(5)
            ->get();

        $peminjamanTerbaru = Peminjaman::with('barang')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalBarang', 'totalStok', 'sedangDipinjam', 'terlambat', 'peminjamanTerbaru', 'stokMenipis'
        ));
    }
}
