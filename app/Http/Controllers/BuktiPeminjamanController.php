<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\URL;

class BuktiPeminjamanController extends Controller
{
    public function show(Peminjaman $peminjaman)
    {
        abort_unless(auth()->user()->role === 'admin' || $peminjaman->user_id === auth()->id(), 403);
        $peminjaman->load(['barang', 'user']);
        $verificationUrl = URL::temporarySignedRoute(
            'peminjaman.verifikasi',
            now()->addDays(30),
            ['peminjaman' => $peminjaman->id],
        );
        $qrCode = Builder::create()
            ->writer(new SvgWriter())
            ->data($verificationUrl)
            ->size(240)
            ->margin(8)
            ->build()
            ->getDataUri();

        return view('peminjaman.bukti', compact('peminjaman', 'qrCode'));
    }

    public function verify(Peminjaman $peminjaman)
    {
        $peminjaman->load('barang');
        return view('peminjaman.verifikasi', compact('peminjaman'));
    }
}
