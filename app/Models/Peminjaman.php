<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamen';

    protected $fillable = [
        'barang_id',
        'user_id',
        'nama_peminjam',
        'kelas_jabatan',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Status yang ditampilkan (otomatis jadi "terlambat" kalau lewat
     * tanggal rencana kembali tapi belum dikembalikan).
     */
    public function getStatusTampilAttribute(): string
    {
        if ($this->status === 'dipinjam'
            && $this->tanggal_kembali_rencana?->toDateString() < today()->toDateString()) {
            return 'terlambat';
        }
        return $this->status;
    }
}
