<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['kode_barang' => 'ELK-001', 'nama_barang' => 'Proyektor Epson', 'kategori' => 'Elektronik', 'stok_total' => 5],
            ['kode_barang' => 'ELK-002', 'nama_barang' => 'Laptop Lab Komputer', 'kategori' => 'Elektronik', 'stok_total' => 10],
            ['kode_barang' => 'OLG-001', 'nama_barang' => 'Bola Basket', 'kategori' => 'Olahraga', 'stok_total' => 8],
            ['kode_barang' => 'OLG-002', 'nama_barang' => 'Net Voli', 'kategori' => 'Olahraga', 'stok_total' => 3],
            ['kode_barang' => 'ATK-001', 'nama_barang' => 'Spidol Whiteboard', 'kategori' => 'ATK', 'stok_total' => 30],
        ];

        foreach ($items as $item) {
            Barang::create([
                ...$item,
                'stok_tersedia' => $item['stok_total'],
                'kondisi' => 'baik',
            ]);
        }
    }
}
