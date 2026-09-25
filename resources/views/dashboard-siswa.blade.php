@extends('layouts.app')
@section('title', 'Dashboard Siswa')

@section('content')
<div class="student-hero">
    <div>
        <div class="student-eyebrow"><i class="bi bi-mortarboard-fill me-2"></i>Portal siswa</div>
        <h1>Halo, {{ auth()->user()->name }}.</h1>
        <p>Ajukan peminjaman barang dan pantau statusnya dengan mudah.</p>
    </div>
    <div class="student-hero-actions"><span class="student-online"><i></i> Akun aktif</span><a href="{{ route('peminjaman.create') }}" class="btn btn-student"><i class="bi bi-plus-circle me-2"></i>Ajukan peminjaman</a></div>
</div>

<div class="student-pulse mb-4"><i class="bi bi-calendar3"></i><span><strong>{{ now()->translatedFormat('l, d F Y') }}</strong><small>Kelola kebutuhan peminjamanmu hari ini</small></span><i class="bi bi-arrow-right-short"></i></div>

<div class="student-grid">
    <div class="student-card student-feature">
        <div class="student-feature-icon"><i class="bi bi-box-arrow-up-right"></i></div>
        <div><span>Mulai dari sini</span><h3>Butuh barang untuk kegiatan sekolah?</h3><p>Pilih barang yang tersedia, isi detail peminjaman, lalu kirim pengajuanmu.</p><a href="{{ route('peminjaman.create') }}">Buat pengajuan <i class="bi bi-arrow-right"></i></a></div>
    </div>
    <div class="student-card student-guide">
        <div class="student-guide-title"><i class="bi bi-info-circle me-2"></i>Alur peminjaman</div>
        <div class="student-step"><b>01</b><span>Pilih barang dan jumlahnya</span></div>
        <div class="student-step"><b>02</b><span>Tentukan rencana pengembalian</span></div>
        <div class="student-step"><b>03</b><span>Kembalikan sesuai jadwal</span></div>
    </div>
</div>

<div class="student-section-head"><div><h2>Peminjaman saya</h2><p>Riwayat pengajuan yang kamu buat.</p></div><a href="{{ route('peminjaman.index') }}">Lihat semua <i class="bi bi-arrow-up-right"></i></a></div>
<div class="student-card student-history">
    @forelse ($peminjamanSaya as $peminjaman)
        <div class="student-loan">
            <span class="student-loan-icon"><i class="bi bi-box-seam"></i></span>
            <div class="student-loan-main"><strong>{{ $peminjaman->barang->nama_barang }}</strong><small>{{ $peminjaman->jumlah }} unit · {{ $peminjaman->tanggal_pinjam->format('d M Y') }}</small></div>
            @if ($peminjaman->status_tampil === 'terlambat')
                <span class="student-status late">Terlambat</span>
            @elseif ($peminjaman->status_tampil === 'dikembalikan')
                <span class="student-status returned">Dikembalikan</span>
            @else
                <span class="student-status borrowed">Dipinjam</span>
            @endif
        </div>
    @empty
        <div class="student-empty"><i class="bi bi-inbox"></i><strong>Belum ada peminjaman</strong><span>Pengajuan pertamamu akan muncul di sini.</span></div>
    @endforelse
</div>

<style>
    .student-hero { align-items: center; background: linear-gradient(120deg, #27205f, #5146e5); border-radius: 22px; color: #fff; display: flex; justify-content: space-between; margin-bottom: 1.5rem; overflow: hidden; padding: 2.2rem 2.4rem; position: relative; }
    .student-hero::before { background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent); content: ''; height: 100%; left: -35%; position: absolute; top: 0; transform: skewX(-18deg); width: 28%; animation: student-shine 7s ease-in-out infinite; } @keyframes student-shine { 0%, 55% { left: -35%; } 85%, 100% { left: 120%; } }
    .student-hero::after { border: 1px solid rgba(255,255,255,.15); border-radius: 50%; content: ''; height: 270px; position: absolute; right: -75px; top: -115px; width: 270px; }
    .student-eyebrow { color: #c9c8ff; font-size: .75rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; } .student-hero h1 { font-size: clamp(1.8rem, 3vw, 2.5rem); letter-spacing: -.04em; margin: .65rem 0 .35rem; } .student-hero p { color: #dedcff; margin: 0; }
    .student-hero-actions { align-items: flex-end; display: flex; flex-direction: column; gap: .8rem; position: relative; z-index: 1; } .student-online { align-items: center; color: #d7f8e7; display: flex; font-size: .7rem; font-weight: 700; gap: .35rem; } .student-online i { background: #69d39c; border-radius: 50%; height: 7px; width: 7px; } .btn-student { background: #fff; border: 0; border-radius: 11px; color: #3930ad; font-weight: 700; padding: .75rem 1rem; position: relative; z-index: 1; } .btn-student:hover { background: #f0efff; color: #282184; }
    .student-pulse { align-items: center; background: #fff; border: 1px solid #e7ebf3; border-radius: 13px; box-shadow: 0 7px 20px rgba(31,41,65,.04); color: #5146e5; display: flex; gap: .7rem; padding: .75rem 1rem; } .student-pulse span { flex: 1; } .student-pulse strong, .student-pulse small { display: block; } .student-pulse strong { color: #39445e; font-size: .75rem; } .student-pulse small { color: #9aa5b7; font-size: .68rem; margin-top: .1rem; } .student-pulse > i:last-child { color: #a8b1c1; font-size: 1.2rem; }
    .student-grid { display: grid; gap: 1rem; grid-template-columns: 1.25fr .75fr; margin-bottom: 2rem; } .student-card { background: #fff; border: 1px solid #e7ebf3; border-radius: 18px; box-shadow: 0 8px 24px rgba(31,41,65,.04); transition: box-shadow .25s ease, transform .25s ease; } .student-card:hover { box-shadow: 0 16px 32px rgba(50,43,148,.09); transform: translateY(-3px); }
    .student-feature { align-items: flex-start; background: #f0f3ff; display: flex; gap: 1rem; padding: 1.5rem; } .student-feature-icon { align-items: center; background: #5146e5; border-radius: 13px; color: #fff; display: flex; flex: 0 0 43px; height: 43px; justify-content: center; } .student-feature span { color: #6c72a5; font-size: .73rem; font-weight: 700; text-transform: uppercase; } .student-feature h3 { color: #242553; font-size: 1.15rem; margin: .35rem 0; } .student-feature p { color: #6d7391; font-size: .82rem; line-height: 1.55; margin: 0 0 .8rem; } .student-feature a, .student-section-head a { color: #5146e5; font-size: .8rem; font-weight: 700; text-decoration: none; }
    .student-guide { padding: 1.35rem 1.5rem; } .student-guide-title { color: #29334d; font-size: .85rem; font-weight: 700; margin-bottom: 1rem; } .student-step { align-items: center; border-top: 1px solid #eef0f5; color: #59657b; display: flex; font-size: .78rem; gap: .8rem; padding: .75rem 0; } .student-step b { color: #5146e5; font-size: .7rem; }
    .student-section-head { align-items: end; display: flex; justify-content: space-between; margin-bottom: .8rem; } .student-section-head h2 { color: #1f2942; font-size: 1.2rem; margin: 0 0 .2rem; } .student-section-head p { color: #8490a5; font-size: .78rem; margin: 0; }
    .student-history { overflow: hidden; } .student-loan { align-items: center; border-bottom: 1px solid #eef0f5; display: flex; gap: .85rem; padding: 1rem 1.35rem; transition: background .2s ease, padding-left .2s ease; } .student-loan:hover { background: #fbfcff; padding-left: 1.55rem; } .student-loan:last-child { border: 0; } .student-loan-icon { align-items: center; background: #eeedff; border-radius: 11px; color: #5146e5; display: flex; height: 37px; justify-content: center; width: 37px; } .student-loan-main { flex: 1; } .student-loan-main strong, .student-loan-main small { display: block; } .student-loan-main strong { color: #29334d; font-size: .84rem; } .student-loan-main small { color: #929caf; font-size: .72rem; margin-top: .18rem; } .student-status { border-radius: 99px; font-size: .68rem; font-weight: 700; padding: .38rem .65rem; } .student-status.borrowed { background: #fff3dc; color: #b97515; } .student-status.returned { background: #e8f7ef; color: #218653; } .student-status.late { background: #ffebed; color: #d34352; }
    .student-empty { align-items: center; color: #9aa4b6; display: flex; flex-direction: column; gap: .25rem; padding: 2.5rem; text-align: center; } .student-empty i { color: #b4bbca; font-size: 1.7rem; margin-bottom: .25rem; } .student-empty strong { color: #5d6980; font-size: .88rem; } .student-empty span { font-size: .75rem; }
    @media (max-width: 767.98px) { .student-hero { align-items: flex-start; flex-direction: column; gap: 1.2rem; padding: 1.5rem; } .student-hero-actions { align-items: flex-start; } .student-grid { grid-template-columns: 1fr; } .student-section-head { align-items: flex-start; flex-direction: column; gap: .5rem; } }
</style>
@endsection