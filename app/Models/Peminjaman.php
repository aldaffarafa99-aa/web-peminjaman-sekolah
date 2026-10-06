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
        'disetujui_oleh',
        'nama_peminjam',
        'kelas_jabatan',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'catatan',
        'tujuan_penggunaan',
        'tanggal_persetujuan',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
        'tanggal_persetujuan' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengembalians()
    {
        return $this->hasMany(Pengembalian::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    /**
     * Status yang ditampilkan (otomatis jadi "terlambat" kalau lewat
     * tanggal rencana kembali tapi belum dikembalikan).
     */
    public function getStatusTampilAttribute(): string
    {
        if (in_array($this->status, ['dipinjam', 'terlambat'], true)
            && $this->tanggal_kembali_rencana?->toDateString() < today()->toDateString()) {
            return 'terlambat';
        }
        return $this->status === 'dikembalikan' ? 'selesai' : $this->status;
    }
}
