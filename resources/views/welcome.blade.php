<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Peminjaman Barang Sekolah membantu sekolah mengelola inventaris dan peminjaman dengan lebih mudah.">
    <title>Peminjaman Barang Sekolah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="landing-body font-sans text-slate-900 antialiased">
    <header class="landing-header">
        <div class="landing-container flex items-center justify-between py-5">
            <a href="{{ url('/') }}" class="landing-brand">
                <span class="landing-logo"><img src="{{ asset('images/logo.png') }}" alt=""></span>
                <span><strong>Peminjaman Barang</strong><small>Sekolah</small></span>
            </a>
            <nav class="hidden items-center gap-7 text-sm font-medium text-slate-600 md:flex">
                <a href="#fitur" class="transition hover:text-indigo-600">Fitur</a>
                <a href="#cara-kerja" class="transition hover:text-indigo-600">Cara kerja</a>
                <a href="{{ route('login') }}" class="landing-nav-login">Masuk</a>
            </nav>
            <a href="{{ route('login') }}" class="landing-mobile-login md:hidden">Masuk</a>
        </div>
    </header>

    <main>
        <section class="landing-hero">
            <div class="landing-orb landing-orb-one"></div>
            <div class="landing-orb landing-orb-two"></div>
            <div class="landing-container relative z-10 grid items-center gap-12 py-16 lg:grid-cols-[1.05fr_.95fr] lg:py-24">
                <div class="max-w-2xl">
                    <div class="landing-kicker"><span class="landing-kicker-dot"></span>Sistem inventaris sekolah yang lebih rapi</div>
                    <h1>Kelola barang sekolah dengan <span>lebih mudah.</span></h1>
                    <p class="landing-lead">Satu ruang kerja untuk mengajukan peminjaman, memantau status, dan menjaga inventaris sekolah tetap tertata.</p>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('login') }}" class="landing-primary-button">Masuk ke aplikasi <i class="bi bi-arrow-up-right"></i></a>
                        <a href="{{ route('register') }}" class="landing-secondary-button">Buat akun siswa</a>
                    </div>
                    <div class="landing-trust"><span class="landing-check"><i class="bi bi-check2"></i></span>Mudah digunakan oleh siswa dan admin sekolah</div>
                </div>

                <div class="landing-hero-art" aria-hidden="true">
                    <div class="landing-art-glow"></div>
                    <div class="landing-art-card landing-art-main">
                        <div class="flex items-center justify-between"><div class="art-label">Ringkasan inventaris</div><i class="bi bi-three-dots text-slate-400"></i></div>
                        <div class="mt-6 flex items-end justify-between"><div><div class="art-number">128</div><div class="art-muted">Total barang tercatat</div></div><span class="art-trend"><i class="bi bi-arrow-up"></i> 12%</span></div>
                        <div class="art-chart mt-7"><span style="height:38%"></span><span style="height:54%"></span><span style="height:46%"></span><span style="height:72%"></span><span style="height:62%"></span><span style="height:88%"></span><span style="height:76%"></span><span style="height:96%"></span></div>
                        <div class="mt-5 flex justify-between text-[10px] text-slate-400"><span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span><span>Mei</span><span>Jun</span><span>Jul</span><span>Ags</span></div>
                    </div>
                    <div class="landing-art-card landing-art-float landing-art-loan"><span class="art-float-icon bg-emerald-100 text-emerald-600"><i class="bi bi-check-lg"></i></span><span><strong>Peminjaman disetujui</strong><small>Proyektor Epson · 2 unit</small></span></div>
                    <div class="landing-art-card landing-art-float landing-art-stock"><span class="art-float-icon bg-indigo-100 text-indigo-600"><i class="bi bi-box-seam"></i></span><span><strong>Stok terpantau</strong><small>Semua data tersinkron</small></span></div>
                </div>
            </div>
        </section>

        <section id="fitur" class="landing-section bg-white">
            <div class="landing-container py-20 lg:py-24">
                <div class="mx-auto max-w-2xl text-center"><div class="landing-section-kicker">Semua yang dibutuhkan</div><h2>Urusan peminjaman jadi lebih ringan.</h2><p class="landing-section-copy">Dirancang untuk membuat alur inventaris sekolah lebih jelas, cepat, dan mudah dipantau.</p></div>
                <div class="mt-12 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="landing-feature"><span class="feature-icon feature-purple"><i class="bi bi-send"></i></span><h3>Ajukan online</h3><p>Siswa dapat mengajukan peminjaman dari mana saja tanpa formulir kertas.</p></div>
                    <div class="landing-feature"><span class="feature-icon feature-blue"><i class="bi bi-activity"></i></span><h3>Status real-time</h3><p>Pantau status peminjaman dengan informasi yang selalu terbarui.</p></div>
                    <div class="landing-feature"><span class="feature-icon feature-orange"><i class="bi bi-clock-history"></i></span><h3>Riwayat lengkap</h3><p>Semua aktivitas tersimpan rapi dan mudah ditemukan kembali.</p></div>
                    <div class="landing-feature"><span class="feature-icon feature-green"><i class="bi bi-boxes"></i></span><h3>Stok otomatis</h3><p>Stok tersedia menyesuaikan setiap peminjaman dan pengembalian.</p></div>
                </div>
            </div>
        </section>

        <section id="cara-kerja" class="landing-section landing-process-section">
            <div class="landing-container grid gap-12 py-20 lg:grid-cols-[.8fr_1.2fr] lg:items-center lg:py-24">
                <div><div class="landing-section-kicker">Cara kerja</div><h2>Empat langkah untuk meminjam barang.</h2><p class="landing-section-copy">Alur sederhana yang membuat siswa dan admin selalu tahu apa yang harus dilakukan berikutnya.</p><a href="{{ route('register') }}" class="landing-text-link">Mulai sebagai siswa <i class="bi bi-arrow-right"></i></a></div>
                <div class="landing-steps">
                    <div class="landing-step"><span>01</span><div><h3>Ajukan</h3><p>Pilih barang, jumlah, dan tanggal pengembalian yang kamu butuhkan.</p></div></div>
                    <div class="landing-step"><span>02</span><div><h3>Disetujui admin</h3><p>Admin memeriksa ketersediaan dan mencatat pengajuanmu.</p></div></div>
                    <div class="landing-step"><span>03</span><div><h3>Pinjam</h3><p>Ambil barang sesuai jadwal dan gunakan dengan bertanggung jawab.</p></div></div>
                    <div class="landing-step"><span>04</span><div><h3>Kembalikan</h3><p>Kembalikan barang, lalu stok akan diperbarui otomatis.</p></div></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <div class="landing-container flex flex-col gap-4 py-8 sm:flex-row sm:items-center sm:justify-between"><a href="{{ url('/') }}" class="landing-brand"><span class="landing-logo"><img src="{{ asset('images/logo.png') }}" alt=""></span><span><strong>Peminjaman Barang</strong><small>Sekolah</small></span></a><p>© {{ date('Y') }} Peminjaman Barang Sekolah. Dibuat untuk sekolah yang lebih tertata.</p></div>
    </footer>

    <style>
        :root { --landing-ink:#1b2340; --landing-line:#e9ebf5; }
        .landing-body{background:#f7f8fc}.landing-container{margin:0 auto;max-width:1180px;padding-left:1.25rem;padding-right:1.25rem}.landing-header{background:rgba(255,255,255,.86);border-bottom:1px solid rgba(231,235,243,.8);position:relative;z-index:20;backdrop-filter:blur(16px)}.landing-brand{align-items:center;color:var(--landing-ink);display:inline-flex;gap:.7rem;text-decoration:none}.landing-brand strong,.landing-brand small{display:block}.landing-brand strong{font-size:.95rem;letter-spacing:-.02em}.landing-brand small{color:#7d87a0;font-size:.62rem;letter-spacing:.14em;text-transform:uppercase}.landing-logo{align-items:center;background:linear-gradient(135deg,#292075,#604fe9);border-radius:13px;box-shadow:0 8px 18px rgba(81,70,229,.22);display:flex;height:39px;justify-content:center;overflow:hidden;width:39px}.landing-logo img{height:34px;object-fit:contain;width:34px}.landing-nav-login{border:1px solid #dfe2f0;border-radius:9px;color:#433abf;padding:.55rem .85rem;transition:.2s ease}.landing-nav-login:hover{border-color:#b9b6f7;background:#f3f2ff}.landing-mobile-login{border-radius:8px;background:#5146e5;color:#fff;font-size:.8rem;font-weight:700;padding:.55rem .8rem}.landing-hero{background:linear-gradient(135deg,#f8f8ff 0%,#f1f0ff 48%,#f8f8ff 100%);min-height:630px;overflow:hidden;position:relative}.landing-orb{border:1px solid rgba(81,70,229,.1);border-radius:50%;position:absolute}.landing-orb-one{height:430px;right:-130px;top:-200px;width:430px}.landing-orb-two{bottom:-250px;height:500px;left:-250px;width:500px}.landing-kicker,.landing-section-kicker{color:#5146e5;font-size:.72rem;font-weight:800;letter-spacing:.16em;text-transform:uppercase}.landing-kicker{align-items:center;display:inline-flex;gap:.5rem;margin-bottom:1.2rem}.landing-kicker-dot{background:#7a70ed;border-radius:50%;box-shadow:0 0 0 5px #dedcff;height:7px;width:7px}.landing-hero h1{color:#1c2442;font-size:clamp(2.7rem,5vw,4.8rem);font-weight:750;letter-spacing:-.065em;line-height:1.02;max-width:720px}.landing-hero h1 span{color:#5146e5}.landing-lead{color:#65718b;font-size:1.06rem;line-height:1.8;margin:1.5rem 0 2rem;max-width:550px}.landing-primary-button,.landing-secondary-button{align-items:center;border-radius:11px;display:inline-flex;font-size:.85rem;font-weight:700;justify-content:center;padding:.9rem 1.15rem;text-decoration:none;transition:.2s ease}.landing-primary-button{background:linear-gradient(110deg,#5146e5,#7548d7);box-shadow:0 12px 24px rgba(81,70,229,.22);color:#fff;gap:.55rem}.landing-primary-button:hover{color:#fff;transform:translateY(-2px)}.landing-secondary-button{background:#fff;border:1px solid #dfe2f0;color:#3f47a0}.landing-secondary-button:hover{background:#f4f3ff;border-color:#bdb9f7;color:#373091}.landing-trust{align-items:center;color:#77829a;display:flex;font-size:.75rem;gap:.5rem;margin-top:1.35rem}.landing-check{align-items:center;background:#dff6eb;border-radius:50%;color:#259266;display:inline-flex;height:18px;justify-content:center;width:18px}.landing-hero-art{min-height:430px;position:relative}.landing-art-glow{background:#d9d6ff;border-radius:50%;filter:blur(45px);height:280px;left:18%;opacity:.6;position:absolute;top:12%;width:280px}.landing-art-card{background:rgba(255,255,255,.9);border:1px solid rgba(219,222,241,.95);border-radius:18px;box-shadow:0 20px 45px rgba(52,46,133,.12);position:absolute}.landing-art-main{left:8%;padding:1.5rem;top:13%;width:78%}.art-label,.art-muted{color:#8a94aa;font-size:.72rem}.art-number{color:#212b4c;font-size:2.6rem;font-weight:750;letter-spacing:-.06em}.art-trend{background:#e5f8ef;border-radius:99px;color:#218653;font-size:.68rem;font-weight:700;padding:.35rem .55rem}.art-chart{align-items:end;border-bottom:1px solid #e7eaf4;display:flex;gap:.65rem;height:105px}.art-chart span{background:linear-gradient(180deg,#8178ef,#5146e5);border-radius:6px 6px 2px 2px;flex:1;opacity:.85}.landing-art-float{align-items:center;display:flex;gap:.7rem;padding:.75rem .9rem;width:230px}.landing-art-loan{left:0;top:67%}.landing-art-stock{right:0;top:4%}.art-float-icon{align-items:center;border-radius:10px;display:flex;height:34px;justify-content:center;width:34px}.landing-art-float strong,.landing-art-float small{display:block}.landing-art-float strong{color:#34405d;font-size:.72rem}.landing-art-float small{color:#919aaf;font-size:.62rem;margin-top:.14rem}.landing-section{scroll-margin-top:80px}.landing-section h2{color:#1d2643;font-size:clamp(1.8rem,3vw,2.7rem);font-weight:750;letter-spacing:-.05em;line-height:1.1;margin-top:.6rem}.landing-section-copy{color:#78839a;line-height:1.7;margin:1rem auto 0;max-width:540px}.landing-feature{border:1px solid var(--landing-line);border-radius:16px;padding:1.3rem;transition:.2s ease}.landing-feature:hover{border-color:#c7c4fa;box-shadow:0 12px 28px rgba(45,42,125,.07);transform:translateY(-3px)}.feature-icon{align-items:center;border-radius:11px;display:flex;height:40px;justify-content:center;margin-bottom:1.1rem;width:40px}.feature-purple{background:#eeedff;color:#5146e5}.feature-blue{background:#e7f2ff;color:#2775d3}.feature-orange{background:#fff3df;color:#df881d}.feature-green{background:#e5f7ee;color:#269161}.landing-feature h3{color:#29334e;font-size:.95rem;font-weight:750}.landing-feature p{color:#8490a6;font-size:.78rem;line-height:1.65;margin-top:.5rem}.landing-process-section{background:#f4f5fb}.landing-text-link{color:#5146e5;display:inline-flex;font-size:.82rem;font-weight:750;gap:.5rem;margin-top:1.5rem;text-decoration:none}.landing-steps{background:#fff;border:1px solid var(--landing-line);border-radius:18px;padding:.5rem 1.5rem}.landing-step{align-items:flex-start;border-bottom:1px solid #eef0f5;display:flex;gap:1rem;padding:1.25rem 0}.landing-step:last-child{border-bottom:0}.landing-step>span{color:#8a82ee;font-size:.78rem;font-weight:800;padding-top:.15rem}.landing-step h3{color:#29334e;font-size:.95rem;font-weight:750}.landing-step p{color:#8490a6;font-size:.78rem;line-height:1.55;margin-top:.3rem}.landing-footer{background:#1d1b50;color:#bdbce3}.landing-footer .landing-brand{color:#fff}.landing-footer .landing-brand small{color:#a7a5d0}.landing-footer p{font-size:.72rem}@media(max-width:767px){.landing-container{padding-left:1rem;padding-right:1rem}.landing-hero{min-height:auto}.landing-hero-art{min-height:380px}.landing-art-main{left:4%;top:10%;width:90%}.landing-art-stock{right:-2%;top:0;transform:scale(.85);transform-origin:right top}.landing-art-loan{left:-2%;top:70%;transform:scale(.85);transform-origin:left top}.landing-art-float{width:210px}.landing-footer p{line-height:1.5}}
    </style>
</body>
</html>
