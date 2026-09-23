<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'petugas')->update(['role' => 'siswa']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('siswa')->change();
        });
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'siswa')->update(['role' => 'petugas']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('petugas')->change();
        });
    }
};