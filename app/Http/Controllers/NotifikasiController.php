<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasi = $request->user()->notifications()->latest()->paginate(20);
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function tandaiDibaca(Request $request, string $notification)
    {
        $item = $request->user()->notifications()->whereKey($notification)->firstOrFail();
        $item->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }
}
