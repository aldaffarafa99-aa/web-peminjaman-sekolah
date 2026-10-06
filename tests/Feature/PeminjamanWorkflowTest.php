<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PeminjamanWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_pending_loan_is_created_and_approved_changes_status(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $admin = User::factory()->create(['role' => 'admin']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Laptop',
            'kategori' => 'Elektronik',
            'stok_total' => 5,
            'stok_tersedia' => 5,
            'kondisi' => 'baik',
        ]);

        $this->actingAs($user)
            ->post('/peminjaman', [
                'barang_id' => $barang->id,
                'nama_peminjam' => $user->name,
                'kelas_jabatan' => 'XII IPA 1',
                'jumlah' => 2,
                'tanggal_pinjam' => now()->format('Y-m-d'),
                'tanggal_kembali_rencana' => now()->addDays(3)->format('Y-m-d'),
                'tujuan_penggunaan' => 'Kegiatan pembelajaran',
                'catatan' => 'Uji coba',
            ])
            ->assertRedirect('/dashboard');

        $peminjaman = Peminjaman::first();
        $this->assertSame('pending', $peminjaman->status);
        $this->assertSame(5, $barang->fresh()->stok_tersedia);

        $this->actingAs($admin)
            ->patch('/peminjaman/' . $peminjaman->id . '/setujui')
            ->assertRedirect();

        $this->assertSame('disetujui', $peminjaman->fresh()->status);
        $this->assertSame(5, $barang->fresh()->stok_tersedia);

        $this->actingAs($admin)
            ->patch('/peminjaman/' . $peminjaman->id . '/serahkan')
            ->assertRedirect();
        $this->assertSame('dipinjam', $peminjaman->fresh()->status);
        $this->assertSame(3, $barang->fresh()->stok_tersedia);
    }

    public function test_returning_item_restores_stock_and_marks_complete(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'siswa']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-002',
            'nama_barang' => 'Proyektor',
            'kategori' => 'Elektronik',
            'stok_total' => 4,
            'stok_tersedia' => 4,
            'kondisi' => 'baik',
        ]);

        $peminjaman = Peminjaman::create([
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'nama_peminjam' => $user->name,
            'kelas_jabatan' => 'XI IPS 2',
            'jumlah' => 1,
            'tanggal_pinjam' => now()->subDays(2)->format('Y-m-d'),
            'tanggal_kembali_rencana' => now()->addDays(2)->format('Y-m-d'),
            'status' => 'dipinjam',
        ]);

        $barang->decrement('stok_tersedia', 1);

        $this->actingAs($admin)
            ->patch('/peminjaman/' . $peminjaman->id . '/kembalikan', [
                'kondisi_barang' => 'baik',
                'catatan_pengembalian' => 'Selesai',
            ])
            ->assertRedirect();

        $this->assertSame('selesai', $peminjaman->fresh()->status);
        $this->assertSame(4, $barang->fresh()->stok_tersedia);
    }

    public function test_admin_can_manage_categories_and_locations_used_by_inventory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/kategori', ['nama' => 'Elektronik', 'deskripsi' => 'Perangkat sekolah'])
            ->assertRedirect('/kategori');
        $this->actingAs($admin)
            ->post('/lokasi-barang', ['kode_lokasi' => 'R-01', 'nama_lokasi' => 'Ruang Sarpras'])
            ->assertRedirect('/lokasi-barang');

        $kategori = \App\Models\Kategori::firstOrFail();
        $lokasi = \App\Models\LokasiBarang::firstOrFail();
        $barang = Barang::create([
            'kode_barang' => 'BRG-003',
            'nama_barang' => 'Kamera',
            'kategori_id' => $kategori->id,
            'lokasi_barang_id' => $lokasi->id,
            'stok_total' => 1,
            'stok_tersedia' => 1,
            'kondisi' => 'baik',
        ]);

        $this->get('/barang')->assertOk()->assertSee('Ruang Sarpras');
        $this->assertSame('Elektronik', $barang->kategoriRelasi->nama);

        $teacher = User::factory()->create(['role' => 'guru']);
        $this->actingAs($teacher)->get('/barang')->assertOk()->assertSee('Kamera');
        $this->get('/barang/create')->assertForbidden();
    }

    public function test_admin_report_export_and_h_minus_one_reminder_are_available(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'siswa']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-004',
            'nama_barang' => 'Speaker',
            'stok_total' => 2,
            'stok_tersedia' => 1,
            'kondisi' => 'baik',
        ]);
        Peminjaman::create([
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'nama_peminjam' => $user->name,
            'jumlah' => 1,
            'tanggal_pinjam' => today(),
            'tanggal_kembali_rencana' => today()->addDay(),
            'tujuan_penggunaan' => 'Kegiatan kelas',
            'status' => 'dipinjam',
        ]);

        $this->actingAs($admin)->get('/laporan')->assertOk();
        $this->actingAs($admin)->get('/laporan/export')->assertDownload();

        $this->artisan('peminjaman:ingatkan-pengembalian')->assertExitCode(0);
        $this->assertDatabaseCount('notifications', 1);
        $this->artisan('peminjaman:ingatkan-pengembalian')->assertExitCode(0);
        $this->assertDatabaseCount('notifications', 1);

        $this->actingAs($user)->get('/notifikasi')->assertOk()->assertSee('Pengingat pengembalian H-1');
    }

    public function test_borrower_can_print_receipt_and_signed_qr_verifies_transaction(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-005',
            'nama_barang' => 'Mikrofon',
            'stok_total' => 1,
            'stok_tersedia' => 1,
            'kondisi' => 'baik',
        ]);
        $peminjaman = Peminjaman::create([
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'nama_peminjam' => $user->name,
            'jumlah' => 1,
            'tanggal_pinjam' => today(),
            'tanggal_kembali_rencana' => today()->addDays(2),
            'tujuan_penggunaan' => 'Acara sekolah',
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get('/peminjaman/' . $peminjaman->id . '/bukti')
            ->assertOk()
            ->assertSee('data:image/svg+xml;base64,', false);

        $verificationUrl = URL::temporarySignedRoute('peminjaman.verifikasi', now()->addMinutes(5), [
            'peminjaman' => $peminjaman->id,
        ]);
        $this->get($verificationUrl)->assertOk()->assertSee('Transaksi terverifikasi');
        $this->get('/peminjaman/' . $peminjaman->id . '/verifikasi')->assertForbidden();
    }

    public function test_overdue_command_persists_late_status(): void
    {
        $user = User::factory()->create(['role' => 'siswa']);
        $barang = Barang::create([
            'kode_barang' => 'BRG-006',
            'nama_barang' => 'Tripod',
            'stok_total' => 1,
            'stok_tersedia' => 0,
            'kondisi' => 'baik',
        ]);
        $peminjaman = Peminjaman::create([
            'barang_id' => $barang->id,
            'user_id' => $user->id,
            'nama_peminjam' => $user->name,
            'jumlah' => 1,
            'tanggal_pinjam' => today()->subDays(4),
            'tanggal_kembali_rencana' => today()->subDay(),
            'tujuan_penggunaan' => 'Kegiatan sekolah',
            'status' => 'dipinjam',
        ]);

        $this->artisan('peminjaman:tandai-terlambat')->assertExitCode(0);
        $this->assertSame('terlambat', $peminjaman->fresh()->status);
    }
}
