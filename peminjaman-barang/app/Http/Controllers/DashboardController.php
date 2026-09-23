<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalStok = Barang::sum('stok_total');
        $sedangDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $terlambat = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', '<', now())
            ->count();

        $peminjamanTerbaru = Peminjaman::with('barang')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalBarang', 'totalStok', 'sedangDipinjam', 'terlambat', 'peminjamanTerbaru'
        ));
    }
}
