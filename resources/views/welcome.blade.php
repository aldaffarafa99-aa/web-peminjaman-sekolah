<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem inventaris sekolah modern untuk peminjaman barang secara cepat, transparan, dan efisien.">
    <title>Peminjaman Barang Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg-deep: #040816;
            --bg-soft: rgba(15, 23, 42, 0.78);
            --panel: rgba(15, 23, 42, 0.52);
            --panel-light: rgba(255, 255, 255, 0.08);
            --text: #e2e8f0;
            --muted: #a5b4cf;
            --indigo: #6366f1;
            --indigo-2: #8b5cf6;
            --emerald: #34d399;
            --cyan: #67e8f9;
            --rose: #fb7185;
            --amber: #fbbf24;
            --border: rgba(148, 163, 184, 0.2);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(99, 102, 241, 0.18), transparent 26%),
                radial-gradient(circle at 80% 0%, rgba(59, 130, 246, 0.18), transparent 22%),
                linear-gradient(135deg, #f7f8ff 0%, #eef3ff 34%, #edf5ff 100%);
            color: #0f172a;
        }

        .mesh-bg {
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                radial-gradient(circle at 15% 20%, rgba(99, 102, 241, 0.22), transparent 20%),
                radial-gradient(circle at 80% 10%, rgba(168, 85, 247, 0.17), transparent 22%),
                radial-gradient(circle at 50% 70%, rgba(16, 185, 129, 0.1), transparent 25%);
            filter: blur(16px);
        }

        .glass {
            background: rgba(15, 23, 42, 0.38);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .glass-light {
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 18px 40px rgba(79, 70, 229, 0.08);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .floating-nav {
            background: rgba(15, 23, 42, 0.38);
            border: 1px solid rgba(148, 163, 184, 0.18);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.14);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .nav-link {
            position: relative;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: white;
        }

        .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -8px;
            width: 100%;
            height: 2px;
            border-radius: 9999px;
            background: linear-gradient(90deg, rgba(99,102,241,0.8), rgba(167,139,250,0.8));
            transform: scaleX(0);
            transform-origin: center;
            transition: transform 0.2s ease;
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #a5b4fc 0%, #c4b5fd 30%, #67e8f9 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .primary-btn {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 40%, #4f46e5 100%);
            box-shadow: 0 20px 30px rgba(99, 102, 241, 0.38);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .primary-btn:hover {
            transform: translateY(-2px) scale(1.01);
            box-shadow: 0 26px 36px rgba(99, 102, 241, 0.46);
        }

        .ghost-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            transition: all 0.2s ease;
        }

        .ghost-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-2px);
        }

        .search-shell {
            background: rgba(15, 23, 42, 0.34);
            border: 1px solid rgba(148, 163, 184, 0.14);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.14);
        }

        .search-shell input::placeholder {
            color: rgba(148, 163, 184, 0.9);
        }

        .result-item {
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .result-item:hover {
            background: rgba(99, 102, 241, 0.08);
            transform: translateX(2px);
        }

        .bento-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.75rem;
            border: 1px solid rgba(148, 163, 184, 0.16);
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.46));
            box-shadow: 0 18px 38px rgba(15, 23, 42, 0.14);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }

        .bento-card:hover {
            transform: translateY(-4px) scale(1.005);
            border-color: rgba(99, 102, 241, 0.45);
            box-shadow: 0 25px 55px rgba(79, 70, 229, 0.22);
        }

        .status-track {
            position: relative;
            padding-top: 0.5rem;
        }

        .status-track::before {
            content: "";
            position: absolute;
            left: 1.3rem;
            top: 0.5rem;
            bottom: 0.5rem;
            width: 2px;
            background: linear-gradient(180deg, rgba(52,211,153,0.8), rgba(99,102,241,0.8), rgba(148,163,184,0.25));
        }

        .status-node {
            position: relative;
            z-index: 1;
        }

        .status-node::before {
            content: "";
            position: absolute;
            left: -1.6rem;
            top: 0.4rem;
            width: 0.8rem;
            height: 0.8rem;
            border-radius: 9999px;
            background: linear-gradient(135deg, #34d399, #22c55e);
            box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.18);
        }

        .qr-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(148, 163, 184, 0.12);
            box-shadow: inset 0 0 20px rgba(99, 102, 241, 0.08);
        }

        .qr-grid {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 0.2rem;
        }

        .qr-grid span {
            display: block;
            width: 100%;
            aspect-ratio: 1;
            border-radius: 0.3rem;
            background: rgba(148, 163, 184, 0.15);
        }

        .qr-grid span:nth-child(odd) {
            background: rgba(99, 102, 241, 0.88);
        }

        .qr-grid span:nth-child(4n) {
            background: rgba(16, 185, 129, 0.9);
        }

        .catalog-item {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(148, 163, 184, 0.1);
            transition: all 0.2s ease;
        }

        .catalog-item:hover {
            border-color: rgba(99, 102, 241, 0.34);
            background: rgba(99, 102, 241, 0.06);
        }

        .step-number {
            background: linear-gradient(135deg, rgba(99,102,241,0.35), rgba(167,139,250,0.18));
            border: 1px solid rgba(167,139,250,0.36);
            box-shadow: 0 0 25px rgba(99, 102, 241, 0.22);
        }

        .faq-item {
            background: rgba(15, 23, 42, 0.26);
            border: 1px solid rgba(148, 163, 184, 0.12);
            transition: all 0.2s ease;
        }

        .faq-item:hover {
            border-color: rgba(99, 102, 241, 0.4);
        }

        @media (max-width: 767px) {
            .floating-nav {
                border-radius: 1.25rem;
            }
        }
    </style>
</head>
<body class="min-h-screen antialiased">
    <div class="mesh-bg"></div>

    <header class="fixed inset-x-0 top-0 z-50 pt-5">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="floating-nav mx-auto flex max-w-6xl items-center justify-between rounded-full px-4 py-3 sm:px-6">
                <a href="#top" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-400 shadow-[0_12px_24px_rgba(99,102,241,0.4)]">
                        <span class="text-lg font-black text-white">S</span>
                    </div>
                    <div class="leading-none text-left">
                        <div class="text-sm font-black tracking-tight text-white">Peminjaman Barang</div>
                        <div class="mt-1 text-[10px] font-medium uppercase tracking-[0.24em] text-slate-300">Sekolah</div>
                    </div>
                </a>

                <div class="hidden items-center gap-8 text-sm font-medium md:flex">
                    <a href="#fitur" class="nav-link">Fitur</a>
                    <a href="#katalog" class="nav-link">Katalog Cepat</a>
                    <a href="#cara-pinjam" class="nav-link">Cara Pinjam</a>
                    <a href="#faq" class="nav-link">FAQ</a>
                </div>

                <a href="{{ route('login') }}" class="primary-btn inline-flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-semibold text-white shadow-[0_12px_28px_rgba(99,102,241,0.38)] sm:px-5">
                    Masuk / Pinjam Sekarang
                    <span aria-hidden="true">→</span>
                </a>
            </nav>
        </div>
    </header>

    <main id="top" class="relative overflow-hidden">
        <section class="relative pt-32 pb-20 sm:pt-36 lg:pt-40 lg:pb-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-[1.08fr_0.92fr]">
                    <div class="relative z-10">
                        <div class="mb-6 inline-flex items-center gap-3 rounded-full border border-indigo-300/30 bg-white/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.24em] text-indigo-100 shadow-[0_12px_24px_rgba(99,102,241,0.14)] backdrop-blur-xl">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400 shadow-[0_0_18px_rgba(52,211,153,0.8)]"></span>
                            Smart school inventory
                        </div>

                        <h1 class="max-w-3xl text-4xl font-black leading-[0.94] tracking-[-0.06em] text-slate-900 sm:text-5xl lg:text-7xl">
                            Pinjam Fasilitas Sekolah:
                            <span class="gradient-text">Cepat, Transparan, &amp; Tanpa Ribet.</span>
                        </h1>

                        <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">
                            Sistem manajemen inventaris pintar untuk siswa, guru, dan sarpras sekolah.
                        </p>

                        <div class="mt-8 rounded-[1.75rem] border border-white/25 bg-white/10 p-3 shadow-[0_18px_40px_rgba(15,23,42,0.12)] backdrop-blur-xl">
                            <div class="flex items-center gap-3 rounded-[1.2rem] border border-indigo-200/30 bg-slate-950/80 p-3 shadow-inner shadow-indigo-500/10">
                                <span class="text-lg text-indigo-300">⌕</span>
                                <input id="searchInput" type="text" placeholder="Cari barang favoritmu... misal: Proyektor, Kamera, Bola" class="w-full border-0 bg-transparent text-sm text-white placeholder:text-slate-400 focus:outline-none focus:ring-0" autocomplete="off">
                                <button class="primary-btn rounded-full px-5 py-2 text-sm font-semibold text-white">Cari</button>
                            </div>
                            <div id="searchResults" class="mt-3 hidden max-h-64 overflow-hidden rounded-2xl border border-white/10 bg-slate-950/70 p-2 shadow-inner shadow-indigo-500/10"></div>
                        </div>

                        <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-slate-600">
                            <span class="rounded-full border border-emerald-200 bg-emerald-100/80 px-3 py-1 font-medium text-emerald-700">⚡ 98% ACC Instan</span>
                            <span class="rounded-full border border-indigo-200 bg-indigo-100/80 px-3 py-1 font-medium text-indigo-700">📦 450+ Barang Siap Dipinjam</span>
                        </div>
                    </div>

                    <div class="relative z-10">
                        <div class="relative mx-auto max-w-lg">
                            <div class="glass rounded-[2rem] p-5 text-white shadow-[0_30px_70px_rgba(79,70,229,0.22)]">
                                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-[0.28em] text-slate-300">Inventaris hari ini</p>
                                        <h2 class="mt-2 text-3xl font-black tracking-[-0.05em] text-white">128</h2>
                                    </div>
                                    <div class="rounded-2xl border border-emerald-400/30 bg-emerald-400/10 px-3 py-2 text-sm font-semibold text-emerald-300">▲ 12.4%</div>
                                </div>

                                <div class="mt-6 flex items-end justify-between gap-3">
                                    <div class="flex h-28 w-full items-end gap-2">
                                        <div class="h-1/3 flex-1 rounded-t-xl bg-gradient-to-t from-indigo-500 to-indigo-300"></div>
                                        <div class="h-2/5 flex-1 rounded-t-xl bg-gradient-to-t from-violet-500 to-violet-300"></div>
                                        <div class="h-1/2 flex-1 rounded-t-xl bg-gradient-to-t from-cyan-400 to-cyan-200"></div>
                                        <div class="h-2/3 flex-1 rounded-t-xl bg-gradient-to-t from-indigo-500 to-violet-300"></div>
                                        <div class="h-3/5 flex-1 rounded-t-xl bg-gradient-to-t from-emerald-400 to-emerald-300"></div>
                                        <div class="h-5/6 flex-1 rounded-t-xl bg-gradient-to-t from-indigo-400 to-cyan-300"></div>
                                        <div class="h-4/5 flex-1 rounded-t-xl bg-gradient-to-t from-violet-500 to-indigo-300"></div>
                                    </div>
                                </div>

                                <div class="mt-5 grid grid-cols-3 gap-3 text-xs text-slate-300">
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <div class="text-[10px] uppercase tracking-[0.2em] text-slate-400">Tersedia</div>
                                        <div class="mt-2 text-xl font-bold text-emerald-300">89</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <div class="text-[10px] uppercase tracking-[0.2em] text-slate-400">Dipinjam</div>
                                        <div class="mt-2 text-xl font-bold text-amber-300">27</div>
                                    </div>
                                    <div class="rounded-2xl border border-white/10 bg-white/5 p-3">
                                        <div class="text-[10px] uppercase tracking-[0.2em] text-slate-400">Perbaikan</div>
                                        <div class="mt-2 text-xl font-bold text-rose-300">12</div>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute -left-6 top-16 flex items-center gap-3 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3 text-left shadow-[0_20px_40px_rgba(16,185,129,0.18)] backdrop-blur-xl">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-400/20 text-xl">✅</div>
                                <div>
                                    <div class="text-[10px] uppercase tracking-[0.18em] text-emerald-200">Disetujui</div>
                                    <div class="text-sm font-semibold text-white">Proyektor Epson</div>
                                </div>
                            </div>

                            <div class="absolute -right-5 bottom-8 flex items-center gap-3 rounded-2xl border border-indigo-300/20 bg-indigo-500/10 px-4 py-3 text-left shadow-[0_20px_40px_rgba(99,102,241,0.14)] backdrop-blur-xl">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-400/20 text-xl">📦</div>
                                <div>
                                    <div class="text-[10px] uppercase tracking-[0.18em] text-indigo-200">Stok</div>
                                    <div class="text-sm font-semibold text-white">3 unit siap dipinjam</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="fitur" class="relative pb-20 lg:pb-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Semua yang dibutuhkan</p>
                    <h2 class="mt-4 text-3xl font-black tracking-[-0.05em] text-slate-900 sm:text-5xl">Inventaris sekolah jadi lebih rapi, cepat, dan aman.</h2>
                </div>

                <div class="mt-12 grid gap-5 md:grid-cols-12">
                    <div class="md:col-span-7">
                        <div class="bento-card h-full p-6 sm:p-7">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.25em] text-slate-300">Live status tracking</p>
                                    <h3 class="mt-2 text-2xl font-bold text-white">Peminjaman aktif</h3>
                                </div>
                                <div class="rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3 py-1 text-xs font-semibold text-emerald-300">Online</div>
                            </div>

                            <div class="status-track mt-8 space-y-6">
                                <div class="status-node pl-6">
                                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 p-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-emerald-200">1. Mengajukan</p>
                                            <p class="mt-1 text-sm font-semibold text-white">Ayu — Laptop Dell</p>
                                        </div>
                                        <span class="rounded-full bg-emerald-500/20 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-200">Terkirim</span>
                                    </div>
                                </div>

                                <div class="status-node pl-6">
                                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-indigo-300/20 bg-indigo-500/10 p-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-200">2. Disetujui admin</p>
                                            <p class="mt-1 text-sm font-semibold text-white">Proyektor Epson — 2 unit</p>
                                        </div>
                                        <span class="rounded-full bg-indigo-500/20 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-200">Approved</span>
                                    </div>
                                </div>

                                <div class="status-node pl-6">
                                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-amber-300/20 bg-amber-500/10 p-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-amber-200">3. Diambil</p>
                                            <p class="mt-1 text-sm font-semibold text-white">Kamera Canon — Senin, 09.00</p>
                                        </div>
                                        <span class="rounded-full bg-amber-500/20 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-amber-200">Pickup</span>
                                    </div>
                                </div>

                                <div class="status-node pl-6">
                                    <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-400/20 bg-white/5 p-3">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-slate-300">4. Dikembalikan</p>
                                            <p class="mt-1 text-sm font-semibold text-white">Speaker Portable — 1 hari lalu</p>
                                        </div>
                                        <span class="rounded-full bg-slate-500/20 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-200">Done</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-5">
                        <div class="bento-card h-full p-6 sm:p-7">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.25em] text-slate-300">Quick scan</p>
                                    <h3 class="mt-2 text-2xl font-bold text-white">Pengembalian otomatis</h3>
                                </div>
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl border border-indigo-300/20 bg-indigo-500/10 text-xl text-indigo-200">◼</div>
                            </div>

                            <div class="mt-8 flex flex-col items-center justify-center rounded-[1.8rem] border border-white/10 bg-slate-950/70 p-6 text-center">
                                <div class="qr-box rounded-2xl p-4">
                                    <div class="qr-grid">
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                        <span></span><span></span><span></span><span></span><span></span><span></span><span></span>
                                    </div>
                                </div>

                                <p class="mt-5 text-sm text-slate-300">Scan QR code untuk konfirmasi pengembalian</p>
                                <button class="mt-4 rounded-full border border-indigo-300/25 bg-indigo-500/15 px-4 py-2 text-sm font-semibold text-indigo-100">Scan Sekarang</button>
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-12">
                        <div id="katalog" class="bento-card p-6 sm:p-7">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.25em] text-slate-300">Katalog barang populer</p>
                                    <h3 class="mt-2 text-2xl font-bold text-white">Barang paling sering dipinjam</h3>
                                </div>
                                <a href="#" class="text-sm font-medium text-indigo-200 hover:text-white">Lihat semua barang →</a>
                            </div>

                            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                                <div class="catalog-item rounded-[1.5rem] p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500/20 to-cyan-400/20 text-2xl">📷</div>
                                        <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-300">Tersedia</span>
                                    </div>
                                    <h4 class="mt-4 text-lg font-bold text-white">Kamera Canon</h4>
                                    <p class="mt-1 text-sm text-slate-300">2 unit tersedia · kelas multimedia</p>
                                </div>

                                <div class="catalog-item rounded-[1.5rem] p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-violet-500/20 to-indigo-400/20 text-2xl">📽️</div>
                                        <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-emerald-300">Tersedia</span>
                                    </div>
                                    <h4 class="mt-4 text-lg font-bold text-white">Proyektor BenQ</h4>
                                    <p class="mt-1 text-sm text-slate-300">3 unit tersedia · ruang guru</p>
                                </div>

                                <div class="catalog-item rounded-[1.5rem] p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500/20 to-rose-300/20 text-2xl">🔊</div>
                                        <span class="rounded-full bg-rose-500/10 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-rose-300">Dipinjam</span>
                                    </div>
                                    <h4 class="mt-4 text-lg font-bold text-white">Speaker Portable</h4>
                                    <p class="mt-1 text-sm text-slate-300">1 unit sedang dipinjam</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="cara-pinjam" class="relative pb-20 lg:pb-28">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Cara pinjam</p>
                    <h2 class="mt-4 text-3xl font-black tracking-[-0.05em] text-slate-900 sm:text-5xl">Alur 3 langkah yang sederhana</h2>
                </div>

                <div class="grid gap-5 lg:grid-cols-3">
                    <div class="glass-light rounded-[2rem] p-6 sm:p-7">
                        <div class="step-number inline-flex h-14 w-14 items-center justify-center rounded-2xl text-xl font-black text-white">01</div>
                        <h3 class="mt-6 text-2xl font-bold text-slate-900">Cari &amp; Pesan</h3>
                        <p class="mt-3 text-base leading-7 text-slate-600">Pilih barang yang dibutuhkan, tentukan tanggal pinjam, dan kirim pengajuan secara online.</p>
                    </div>

                    <div class="glass-light rounded-[2rem] p-6 sm:p-7">
                        <div class="step-number inline-flex h-14 w-14 items-center justify-center rounded-2xl text-xl font-black text-white">02</div>
                        <h3 class="mt-6 text-2xl font-bold text-slate-900">Ambil &amp; Scan QR</h3>
                        <p class="mt-3 text-base leading-7 text-slate-600">Tunjukkan QR code ke petugas sarpras untuk verifikasi dan penyerahan barang dengan cepat.</p>
                    </div>

                    <div class="glass-light rounded-[2rem] p-6 sm:p-7">
                        <div class="step-number inline-flex h-14 w-14 items-center justify-center rounded-2xl text-xl font-black text-white">03</div>
                        <h3 class="mt-6 text-2xl font-bold text-slate-900">Kembalikan Tepat Waktu</h3>
                        <p class="mt-3 text-base leading-7 text-slate-600">Setelah selesai, sistem akan otomatis mengingatkan jadwal pengembalian dan mencatat statusnya.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="relative pb-24">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="mb-10 text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">FAQ</p>
                    <h2 class="mt-4 text-3xl font-black tracking-[-0.05em] text-slate-900 sm:text-5xl">Pertanyaan yang sering ditanya</h2>
                </div>

                <div class="space-y-4">
                    <details class="faq-item rounded-[1.5rem] p-5 open:border-indigo-400/40" open>
                        <summary class="cursor-pointer list-none text-lg font-semibold text-white">Apakah peminjaman bisa dilakukan dari ponsel?</summary>
                        <p class="mt-3 text-slate-300">Ya. Sistem dibuat responsif agar siswa bisa mengajukan dan memantau peminjaman dari perangkat apa pun, termasuk ponsel.</p>
                    </details>

                    <details class="faq-item rounded-[1.5rem] p-5">
                        <summary class="cursor-pointer list-none text-lg font-semibold text-white">Bagaimana jika barang sedang dipinjam?</summary>
                        <p class="mt-3 text-slate-300">Sistem menampilkan status stok secara real-time dan akan memberitahu bahwa barang tersebut sedang dipinjam atau tidak tersedia.</p>
                    </details>

                    <details class="faq-item rounded-[1.5rem] p-5">
                        <summary class="cursor-pointer list-none text-lg font-semibold text-white">Apakah ada notifikasi pengembalian?</summary>
                        <p class="mt-3 text-slate-300">Tersedia notifikasi pengingat otomatis untuk membantu siswa mengembalikan barang tepat waktu dan menjaga kelancaran operasional sekolah.</p>
                    </details>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200/80 bg-white/55 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-8 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 via-violet-500 to-cyan-400 text-xl font-black text-white">S</div>
                <div>
                    <div class="text-sm font-black text-slate-900">Peminjaman Barang</div>
                    <div class="text-[10px] uppercase tracking-[0.24em] text-slate-500">Sekolah</div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-5 text-sm text-slate-600">
                <a href="#fitur" class="hover:text-indigo-600">Fitur</a>
                <a href="#katalog" class="hover:text-indigo-600">Katalog</a>
                <a href="#cara-pinjam" class="hover:text-indigo-600">Cara Pinjam</a>
                <a href="#faq" class="hover:text-indigo-600">FAQ</a>
            </div>

            <div class="text-sm text-slate-600">
                <p>© {{ date('Y') }} Peminjaman Barang Sekolah</p>
                <p class="mt-1">Kontak Sarpras: sarpras@sekolah.sch.id</p>
            </div>
        </div>
    </footer>

    <script>
        const inventory = [
            { name: 'Proyektor Epson', status: 'Tersedia', stock: '3 Unit', icon: '📽️' },
            { name: 'Kamera Canon', status: 'Tersedia', stock: '2 Unit', icon: '📷' },
            { name: 'Bola Basket', status: 'Tersedia', stock: '5 Unit', icon: '🏀' },
            { name: 'Speaker Portable', status: 'Dipinjam', stock: '1 Unit', icon: '🔊' },
            { name: 'Laptop Dell', status: 'Tersedia', stock: '4 Unit', icon: '💻' },
            { name: 'Tripod Kamera', status: 'Dipinjam', stock: '1 Unit', icon: '🎥' },
        ];

        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        function renderResults(query) {
            const term = query.trim().toLowerCase();
            const filtered = inventory.filter(item => item.name.toLowerCase().includes(term));

            if (!term || filtered.length === 0) {
                searchResults.innerHTML = `
                    <div class="rounded-2xl border border-dashed border-slate-700 bg-slate-900/40 p-4 text-sm text-slate-300">
                        ${term ? 'Barang yang Anda cari belum tersedia.' : 'Coba cari: Proyektor, Kamera, atau Bola'}
                    </div>
                `;
                searchResults.classList.remove('hidden');
                return;
            }

            searchResults.innerHTML = filtered.slice(0, 4).map(item => `
                <button type="button" class="result-item flex w-full items-center justify-between gap-3 rounded-2xl px-3 py-2 text-left text-white">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/5 text-lg">${item.icon}</span>
                        <div>
                            <div class="text-sm font-semibold">${item.name}</div>
                            <div class="text-xs text-slate-400">${item.stock}</div>
                        </div>
                    </div>
                    <span class="rounded-full px-2 py-1 text-[10px] font-bold uppercase tracking-[0.15em] ${item.status === 'Tersedia' ? 'bg-emerald-500/15 text-emerald-300' : 'bg-rose-500/15 text-rose-300'}">${item.status}</span>
                </button>
            `).join('');

            searchResults.classList.remove('hidden');
        }

        searchInput.addEventListener('input', (event) => {
            renderResults(event.target.value);
        });

        searchInput.addEventListener('focus', () => {
            renderResults(searchInput.value);
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('#searchInput') && !event.target.closest('#searchResults')) {
                searchResults.classList.add('hidden');
            }
        });

        renderResults('');
    </script>
</body>
</html>
