<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with('barang')
            ->when($request->user()->role !== 'admin', fn ($q) => $q->where('user_id', $request->user()->id))
            ->when($request->status === 'terlambat', fn ($q) => $q
                ->where('status', 'dipinjam')
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

    public function create()
    {
        $barangs = Barang::where('stok_tersedia', '>', 0)->orderBy('nama_barang')->get();
        return view('peminjaman.create', compact('barangs'));
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
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data, $request) {
            $barang = Barang::lockForUpdate()->findOrFail($data['barang_id']);

            if ($barang->stok_tersedia < $data['jumlah']) {
                abort(422, 'Stok barang tidak mencukupi.');
            }

            $barang->decrement('stok_tersedia', $data['jumlah']);

            Peminjaman::create([
                ...$data,
                'user_id' => $request->user()->id,
                'status' => 'dipinjam',
            ]);
        });

        $redirectRoute = $request->user()->role === 'admin'
            ? 'peminjaman.index'
            : 'dashboard';

        return redirect()->route($redirectRoute)->with('success', 'Peminjaman berhasil dicatat.');
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
    public function kembalikan(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        if ($peminjaman->status === 'dikembalikan') {
            return back()->with('error', 'Peminjaman ini sudah dikembalikan.');
        }

        DB::transaction(function () use ($peminjaman) {
            $peminjaman->update([
                'status' => 'dikembalikan',
                'tanggal_kembali_aktual' => now(),
            ]);

            $peminjaman->barang->increment('stok_tersedia', $peminjaman->jumlah);
        });

        return back()->with('success', 'Barang berhasil ditandai dikembalikan.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin', 403);

        DB::transaction(function () use ($peminjaman) {
            if ($peminjaman->status === 'dipinjam') {
                $peminjaman->barang->increment('stok_tersedia', $peminjaman->jumlah);
            }
            $peminjaman->delete();
        });

        return redirect()->route('peminjaman.index')->with('success', 'Data peminjaman dihapus.');
    }
}
