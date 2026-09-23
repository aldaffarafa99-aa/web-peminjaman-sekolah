<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok_total',
        'stok_tersedia',
        'kondisi',
        'deskripsi',
    ];

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
