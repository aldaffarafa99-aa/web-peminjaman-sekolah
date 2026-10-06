<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use Illuminate\Console\Command;

class TandaiPeminjamanTerlambat extends Command
{
    protected $signature = 'peminjaman:tandai-terlambat';
    protected $description = 'Perbarui status peminjaman yang melewati tanggal pengembalian';

    public function handle(): int
    {
        $updated = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', '<', today())
            ->update(['status' => 'terlambat', 'updated_at' => now()]);

        $this->info("{$updated} peminjaman ditandai terlambat.");
        return self::SUCCESS;
    }
}
