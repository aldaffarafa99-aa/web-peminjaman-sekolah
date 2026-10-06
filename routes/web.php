<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\BuktiPeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LokasiBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/peminjaman/{peminjaman}/verifikasi', [BuktiPeminjamanController::class, 'verify'])
    ->middleware('signed')
    ->name('peminjaman.verifikasi');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

    Route::middleware('admin')->group(function () {
        Route::resource('barang', BarangController::class)->except(['index', 'show']);
        Route::resource('kategori', KategoriController::class)->except(['show']);
        Route::resource('lokasi-barang', LokasiBarangController::class)->except(['show']);
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
    });

    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);
    Route::get('/peminjaman/{peminjaman}/bukti', [BuktiPeminjamanController::class, 'show'])->name('peminjaman.bukti');
    Route::middleware('admin')->group(function () {
        Route::patch('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
        Route::patch('/peminjaman/{peminjaman}/tolak', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
        Route::patch('/peminjaman/{peminjaman}/serahkan', [PeminjamanController::class, 'serahkan'])->name('peminjaman.serahkan');
        Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::patch('/notifikasi/{notification}/dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.dibaca');
});

// Route bawaan Laravel Breeze (profile, dsb) — otomatis ada setelah install Breeze
require __DIR__.'/auth.php';
