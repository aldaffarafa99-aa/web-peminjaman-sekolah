<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::resource('barang', BarangController::class)->except(['show']);
    });

    Route::resource('peminjaman', PeminjamanController::class)->except(['show']);
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route bawaan Laravel Breeze (profile, dsb) — otomatis ada setelah install Breeze
require __DIR__.'/auth.php';
