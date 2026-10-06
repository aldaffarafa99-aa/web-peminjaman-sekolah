<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukti Peminjaman #{{ $peminjaman->id }}</title>
    <style>
        body{color:#172033;font:15px/1.5 Arial,sans-serif;margin:0;padding:32px;background:#f4f6fa}.receipt{background:#fff;border:1px solid #dce2ec;margin:auto;max-width:720px;padding:36px}.receipt-head{border-bottom:2px solid #172033;padding-bottom:18px}.receipt-head h1{font-size:24px;margin:0}.receipt-head p{color:#64748b;margin:4px 0 0}.receipt-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin:24px 0}.field small{color:#64748b;display:block;font-size:11px;text-transform:uppercase}.field strong{display:block;margin-top:4px}.qr{border-top:1px dashed #cbd5e1;display:flex;align-items:center;gap:20px;padding-top:20px}.qr img{height:144px;width:144px}.actions{margin:20px auto;max-width:720px;text-align:right}.actions a,.actions button{background:#1f2937;border:0;color:#fff;padding:10px 16px;text-decoration:none}@media print{body{background:#fff;padding:0}.receipt{border:0;max-width:none;padding:0}.actions{display:none}}@media(max-width:560px){body{padding:12px}.receipt{padding:20px}.receipt-grid{grid-template-columns:1fr 1fr;gap:12px}.qr{align-items:flex-start;flex-direction:column}}
    </style>
</head>
<body>
    <div class="actions"><button onclick="window.print()">Cetak bukti</button></div>
    <main class="receipt">
        <header class="receipt-head"><h1>Bukti Peminjaman Barang</h1><p>Inventaris Sekolah · No. transaksi #{{ str_pad((string) $peminjaman->id, 6, '0', STR_PAD_LEFT) }}</p></header>
        <section class="receipt-grid">
            <div class="field"><small>Nama peminjam</small><strong>{{ $peminjaman->nama_peminjam }}</strong></div>
            <div class="field"><small>Kelas / jabatan</small><strong>{{ $peminjaman->kelas_jabatan ?? '-' }}</strong></div>
            <div class="field"><small>Barang</small><strong>{{ $peminjaman->barang->nama_barang }} ({{ $peminjaman->barang->kode_barang }})</strong></div>
            <div class="field"><small>Jumlah</small><strong>{{ $peminjaman->jumlah }} unit</strong></div>
            <div class="field"><small>Tanggal pinjam</small><strong>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</strong></div>
            <div class="field"><small>Jatuh tempo</small><strong>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</strong></div>
            <div class="field"><small>Status</small><strong>{{ ucfirst($peminjaman->status_tampil) }}</strong></div>
            <div class="field"><small>Tujuan penggunaan</small><strong>{{ $peminjaman->tujuan_penggunaan ?? '-' }}</strong></div>
        </section>
        <section class="qr"><img src="{{ $qrCode }}" alt="QR verifikasi bukti peminjaman"><div><strong>Verifikasi transaksi</strong><p>Pindai QR untuk memeriksa status transaksi. Tautan QR berlaku 30 hari.</p></div></section>
    </main>
</body>
</html>
