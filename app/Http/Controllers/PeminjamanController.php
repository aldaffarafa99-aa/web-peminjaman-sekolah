<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use App\Notifications\StatusPeminjamanUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with('barang')
            ->when($request->user()->role !== 'admin', fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($request->status === 'terlambat', fn ($q) => $q
                ->whereIn('status', ['dipinjam', 'terlambat'])
                ->whereDate('tanggal_kembali_rencana', '<', today()))
            ->when($request->status && $request->status !== 'terlambat', fn ($q) => $q->where('status', $request->status))
            ->when($request->search, function ($q) use ($request) {
                $search = "%{$request->search}%";
                $q->where(function ($query) use ($search) {
                    $query->where('nama_peminjam', 'like', $search)
                        ->orWhere('kelas_jabatan', 'like', $search)
                        ->orWhereHas('barang', function ($barangQuery) use ($search) {
                            $barangQuery->where('nama_barang', 'like', $search)
                                ->orWhere('kode_barang', 'like', $search);
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create(Request $request)
    {
        $selectedBarangId = $request->query('barang_id');

        $barangs = Barang::where('status_barang', 'tersedia')
            ->where('stok_tersedia', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        return view('peminjaman.create', compact('barangs', 'selectedBarangId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'nama_peminjam' => 'required|string|max:255',
            'kelas_jabatan' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'tujuan_penggunaan' => 'required|string|max:2000',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman = DB::transaction(function () use ($data, $request) {
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);
            abort_unless($barang->status_barang === 'tersedia', 422, 'Barang sedang tidak tersedia untuk dipinjam.');
            abort_if($barang->stok_tersedia < $data['jumlah'], 422, 'Stok barang tidak mencukupi.');

            return Peminjaman::create([
                ...$data,
                'user_id' => $request->user()->id,
                'nama_peminjam' => $request->user()->role === 'admin' ? $data['nama_peminjam'] : $request->user()->name,
                'status' => 'pending',
            ]);
        });
        User::where('role', 'admin')->each(fn (User $admin) => $admin->notify(
            new StatusPeminjamanUpdated($peminjaman, 'Pengajuan peminjaman baru', $peminjaman->nama_peminjam . ' mengajukan ' . $peminjaman->barang->nama_barang . '.')
        ));

        $redirectRoute = $request->user()->role === 'admin'
            ? 'peminjaman.index'
            : 'dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Pengajuan peminjaman berhasil dikirim.');
    }

    public function setujui(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            abort_unless($peminjaman->status === 'pending', 422, 'Hanya pengajuan pending yang dapat disetujui.');
            $peminjaman->update([
                'status' => 'disetujui',
                'disetujui_oleh' => auth()->id(),
                'tanggal_persetujuan' => now(),
            ]);
        });
        $peminjaman->refresh();
        $this->notifyBorrower($peminjaman, 'Pengajuan disetujui', 'Pengajuan peminjaman Anda telah disetujui. Silakan menunggu serah-terima barang.');

        return back()->with('success', 'Pengajuan disetujui. Stok akan berkurang saat barang diserahkan.');
    }

    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($request->user()->role === 'admin', 403);
        $data = $request->validate(['alasan_penolakan' => 'required|string|max:1000']);
        DB::transaction(function () use ($peminjaman, $request, $data) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            abort_unless($peminjaman->status === 'pending', 422, 'Hanya pengajuan pending yang dapat ditolak.');
            $peminjaman->update([
                'status' => 'ditolak',
                'disetujui_oleh' => $request->user()->id,
                'tanggal_persetujuan' => now(),
                'alasan_penolakan' => $data['alasan_penolakan'],
            ]);
        });
        $peminjaman->refresh();
        $this->notifyBorrower($peminjaman, 'Pengajuan ditolak', $data['alasan_penolakan']);

        return back()->with('success', 'Pengajuan peminjaman ditolak.');
    }

    public function serahkan(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        DB::transaction(function () use ($peminjaman) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            abort_unless($peminjaman->status === 'disetujui', 422, 'Barang hanya dapat diserahkan setelah pengajuan disetujui.');
            $barang = Barang::lockForUpdate()->findOrFail($peminjaman->barang_id);
            abort_if($barang->stok_tersedia < $peminjaman->jumlah, 422, 'Stok barang tidak mencukupi.');

            $barang->decrement('stok_tersedia', $peminjaman->jumlah);
            $peminjaman->update(['status' => 'dipinjam']);
        });
        $peminjaman->refresh();
        $this->notifyBorrower($peminjaman, 'Barang diserahkan', 'Peminjaman Anda telah aktif. Perhatikan tanggal pengembalian yang tercatat.');

        return back()->with('success', 'Barang diserahkan dan stok telah diperbarui.');
    }

    public function edit(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($request->user()->role === 'admin', 403);

        $data = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'kelas_jabatan' => 'nullable|string|max:255',
            'tanggal_kembali_rencana' => 'required|date',
            'catatan' => 'nullable|string',
        ]);

        $peminjaman->update($data);

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman diperbarui.');
    }

    /**
     * Tandai barang sudah dikembalikan.
     */
    public function kembalikan(Request $request, Peminjaman $peminjaman)
    {
        abort_unless($request->user()->role === 'admin', 403);

        abort_unless(in_array($peminjaman->status, ['dipinjam', 'terlambat'], true), 422, 'Peminjaman ini belum berstatus dipinjam.');
        $data = $request->validate([
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat',
            'catatan_pengembalian' => 'nullable|string|max:1000',
        ]);

        DB::transaction(function () use ($peminjaman, $request, $data) {
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            abort_unless(in_array($peminjaman->status, ['dipinjam', 'terlambat'], true), 422, 'Peminjaman ini sudah diproses.');
            $peminjaman->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => now(),
            ]);
            $peminjaman->barang->increment('stok_tersedia', $peminjaman->jumlah);
            $peminjaman->pengembalian()->create([
                'user_id' => $request->user()->id,
                'tanggal_pengembalian' => now(),
                'kondisi_barang' => $data['kondisi_barang'],
                'catatan' => $data['catatan_pengembalian'] ?? null,
            ]);
        });
        $peminjaman->refresh();
        $this->notifyBorrower($peminjaman, 'Pengembalian selesai', 'Barang telah diterima dan pengembalian Anda selesai diproses.');

        return back()->with('success', 'Pengembalian berhasil dicatat dan stok telah diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        DB::transaction(function () use ($peminjaman) {
            if (in_array($peminjaman->status, ['dipinjam', 'terlambat'], true)) {
                $peminjaman->barang->increment('stok_tersedia', $peminjaman->jumlah);
            }
            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman dihapus.');
    }

    private function notifyBorrower(Peminjaman $peminjaman, string $title, string $message): void
    {
        $peminjaman->user?->notify(new StatusPeminjamanUpdated($peminjaman, $title, $message));
    }
}
