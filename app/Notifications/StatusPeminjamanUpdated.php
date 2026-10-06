<?php

namespace App\Notifications;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusPeminjamanUpdated extends Notification
{
    use Queueable;

    public function __construct(private readonly Peminjaman $peminjaman, private readonly string $judul, private readonly string $pesan)
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
            'judul' => $this->judul,
            'pesan' => $this->pesan,
            'status' => $this->peminjaman->status,
        ];
    }
}
