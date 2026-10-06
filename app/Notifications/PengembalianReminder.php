<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PengembalianReminder extends Notification
{
    use Queueable;

    public function __construct(private readonly Peminjaman $peminjaman)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'peminjaman_id' => $this->peminjaman->id,
            'judul' => 'Pengingat pengembalian H-1',
            'pesan' => $this->peminjaman->barang->nama_barang . ' harus dikembalikan besok.',
            'tanggal_kembali_rencana' => $this->peminjaman->tanggal_kembali_rencana->toDateString(),
        ];
    }
}
