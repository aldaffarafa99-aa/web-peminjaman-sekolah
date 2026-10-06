<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);
        $peminjaman = (clone $query)->with(['barang', 'user'])->latest()->paginate(20)->withQueryString();
        $totalPinjaman = (clone $query)->count();
        $totalUnit = (clone $query)->sum('jumlah');

        return view('laporan.index', compact('peminjaman', 'totalPinjaman', 'totalUnit'));
    }

    public function export(Request $request): StreamedResponse
    {
        $peminjaman = $this->filteredQuery($request)->with(['barang', 'user'])->latest()->get();

        return response()->streamDownload(function () use ($peminjaman) {
            $stream = fopen('php://output', 'w');
            fwrite($stream, "\xEF\xBB\xBF");
            fputcsv($stream, ['ID', 'Peminjam', 'Kelas/Jabatan', 'Barang', 'Jumlah', 'Tanggal Pinjam', 'Jatuh Tempo', 'Tanggal Kembali', 'Status']);

            foreach ($peminjaman as $item) {
                fputcsv($stream, [
                    $item->id,
                    $item->nama_peminjam,
                    $item->kelas_jabatan,
                    $item->barang?->nama_barang,
                    $item->jumlah,
                    $item->tanggal_pinjam?->format('Y-m-d'),
                    $item->tanggal_kembali_rencana?->format('Y-m-d'),
                    $item->tanggal_kembali_aktual?->format('Y-m-d'),
                    $item->status_tampil,
                ]);
            }

            fclose($stream);
        }, 'laporan-peminjaman-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredQuery(Request $request): Builder
    {
        return Peminjaman::query()
            ->when($request->filled('tanggal_mulai'), fn (Builder $query) => $query->whereDate('tanggal_pinjam', '>=', $request->date('tanggal_mulai')))
            ->when($request->filled('tanggal_selesai'), fn (Builder $query) => $query->whereDate('tanggal_pinjam', '<=', $request->date('tanggal_selesai')))
            ->when($request->filled('status'), function (Builder $query) use ($request) {
                if ($request->string('status')->toString() === 'terlambat') {
                    $query->whereIn('status', ['dipinjam', 'terlambat'])
                        ->whereDate('tanggal_kembali_rencana', '<', today());
                    return;
                }

                $query->where('status', $request->string('status')->toString());
            })
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $search = '%' . $request->string('search')->toString() . '%';
                $query->where(function (Builder $query) use ($search) {
                    $query->where('nama_peminjam', 'like', $search)
                        ->orWhereHas('barang', fn (Builder $barang) => $barang->where('nama_barang', 'like', $search));
                });
            });
    }
}
