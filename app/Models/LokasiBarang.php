<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiBarang extends Model
{
    protected $fillable = ['kode_lokasi', 'nama_lokasi', 'deskripsi'];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
