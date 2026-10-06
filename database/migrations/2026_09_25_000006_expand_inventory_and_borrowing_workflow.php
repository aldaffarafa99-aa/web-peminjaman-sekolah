<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::create('lokasi_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi')->unique();
            $table->string('nama_lokasi');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        Schema::table('barangs', function (Blueprint $table) {
            $table->foreignId('kategori_id')->nullable()->after('kategori')->constrained('kategoris')->nullOnDelete();
            $table->foreignId('lokasi_barang_id')->nullable()->after('kategori_id')->constrained('lokasi_barangs')->nullOnDelete();
        });

        Schema::table('peminjamen', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
            $table->foreignId('disetujui_oleh')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_persetujuan')->nullable();
            $table->text('alasan_penolakan')->nullable();
        });

        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjamen')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggal_pengembalian');
            $table->string('kondisi_barang');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalians');
        Schema::table('peminjamen', function (Blueprint $table) {
            $table->dropConstrainedForeignId('disetujui_oleh');
            $table->dropColumn(['tanggal_persetujuan', 'alasan_penolakan']);
            $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat'])->default('dipinjam')->change();
        });
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('kategori_id');
            $table->dropConstrainedForeignId('lokasi_barang_id');
        });
        Schema::dropIfExists('lokasi_barangs');
        Schema::dropIfExists('kategoris');
    }
};
