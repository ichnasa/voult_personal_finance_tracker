<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Keuangan — PLOOM</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DM Sans', sans-serif; font-size: 12px; color: #222; padding: 40px; }
    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #222; padding-bottom: 15px; }
    .header h1 { font-size: 20px; font-weight: 700; letter-spacing: 0.05em; }
    .header .subtitle { font-size: 12px; color: #666; margin-top: 4px; }
    .meta { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 11px; color: #555; }
    .summary { display: flex; gap: 20px; margin-bottom: 30px; }
    .summary-item { flex: 1; border: 1px solid #ddd; padding: 12px; text-align: center; }
    .summary-label { font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #888; margin-bottom: 4px; }
    .summary-value { font-family: 'Space Mono', monospace; font-size: 16px; font-weight: 700; }
    .positive { color: #00A65A; }
    .negative { color: #E53935; }
    h2 { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 11px; }
    th { background: #f5f5f5; text-align: left; padding: 6px 8px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #ddd; }
    td { padding: 5px 8px; border-bottom: 1px solid #eee; }
    .amount { font-family: 'Space Mono', monospace; font-weight: 700; text-align: right; }
    .total-row td { border-top: 2px solid #222; font-weight: 700; }
    .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #999; }
    @media print {
      body { padding: 20px; }
      @page { margin: 15mm; }
    }
  </style>
</head>
<body>
  <div class="header">
    <h1>PLOOM</h1>
    <div class="subtitle">Laporan Keuangan Pribadi</div>
  </div>

  <div class="meta">
    <div>Nama: <?= esc($userName) ?></div>
    <div>Periode: <?= date('d/m/Y', strtotime($dateFrom)) ?> — <?= date('d/m/Y', strtotime($dateTo)) ?></div>
    <div>Dicetak: <?= date('d/m/Y H:i') ?></div>
  </div>

  <div class="summary">
    <div class="summary-item">
      <div class="summary-label">Total Pemasukan</div>
      <div class="summary-value positive">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></div>
    </div>
    <div class="summary-item">
      <div class="summary-label">Total Pengeluaran</div>
      <div class="summary-value negative">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></div>
    </div>
    <div class="summary-item">
      <div class="summary-label">Selisih</div>
      <div class="summary-value <?= $selisih >= 0 ? 'positive' : 'negative' ?>">
        <?= $selisih >= 0 ? '+' : '-' ?>Rp <?= number_format(abs($selisih), 0, ',', '.') ?>
      </div>
    </div>
  </div>

  <h2>Pemasukan</h2>
  <table>
    <thead><tr><th>No</th><th>Tanggal</th><th>Sumber</th><th>Deskripsi</th><th style="text-align:right;">Nominal</th></tr></thead>
    <tbody>
      <?php $no = 1; foreach ($pemasukan as $p): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
        <td><?= esc($p['sumber']) ?></td>
        <td><?= esc($p['deskripsi'] ?? '-') ?></td>
        <td class="amount positive">+Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="4" style="text-align:right;">Total Pemasukan</td>
        <td class="amount positive">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <h2>Pengeluaran</h2>
  <table>
    <thead><tr><th>No</th><th>Tanggal</th><th>Kategori</th><th>Deskripsi</th><th style="text-align:right;">Nominal</th></tr></thead>
    <tbody>
      <?php $no = 1; foreach ($pengeluaran as $k): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td><?= date('d/m/Y', strtotime($k['tanggal'])) ?></td>
        <td><?= esc($k['kategori_name'] ?? '-') ?></td>
        <td><?= esc($k['deskripsi'] ?? '-') ?></td>
        <td class="amount negative">-Rp <?= number_format($k['nominal'], 0, ',', '.') ?></td>
      </tr>
      <?php endforeach; ?>
      <tr class="total-row">
        <td colspan="4" style="text-align:right;">Total Pengeluaran</td>
        <td class="amount negative">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>

  <div class="footer">
    PLOOM — Sistem Informasi Keuangan Pribadi | Dicetak otomatis oleh sistem
  </div>

  <script>window.onload = function() { window.print(); }</script>
</body>
</html>
