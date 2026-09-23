<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin.inventaris@sekolah.test'],
            ['name' => 'Admin Inventaris', 'role' => 'admin', 'password' => 'password'],
        );

        User::updateOrCreate(
            ['email' => 'admin.peminjaman@sekolah.test'],
            ['name' => 'Admin Peminjaman', 'role' => 'admin', 'password' => 'password'],
        );

        User::updateOrCreate(
            ['email' => 'siswa@sekolah.test'],
            ['name' => 'Siswa Demo', 'role' => 'siswa', 'password' => 'password'],
        );
    }
}
