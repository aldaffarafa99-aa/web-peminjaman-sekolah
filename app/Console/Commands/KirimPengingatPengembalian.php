<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use App\Notifications\PengembalianReminder;
use Illuminate\Console\Command;

class KirimPengingatPengembalian extends Command
{
    protected $signature = 'peminjaman:ingatkan-pengembalian';
    protected $description = 'Kirim notifikasi pengingat pengembalian barang H-1';

    public function handle(): int
    {
        Peminjaman::with(['user', 'barang'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tanggal_kembali_rencana', today()->addDay())
            ->whereNotNull('user_id')
            ->chunkById(100, function ($peminjamanList): void {
                foreach ($peminjamanList as $peminjaman) {
                    $alreadyNotified = $peminjaman->user->notifications()
                        ->where('type', PengembalianReminder::class)
                        ->where('data->peminjaman_id', $peminjaman->id)
                        ->exists();

                    if (! $alreadyNotified) {
                        $peminjaman->user->notify(new PengembalianReminder($peminjaman));
                    }
                }
            });

        $this->info('Pengingat pengembalian H-1 telah diproses.');
        return self::SUCCESS;
    }
}
