<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between">
    <div></div>
    <a href="<?= base_url('laporan/export?date_from=' . $dateFrom . '&date_to=' . $dateTo) ?>" target="_blank" class="btn btn-default">
      <i class="fas fa-print mr-1"></i> Cetak / Export PDF
    </a>
  </div>
</div>

<!-- Filter -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= base_url('laporan') ?>">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group"><label>Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom) ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group"><label>Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo) ?>">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <div class="form-group">
            <button type="submit" class="btn btn-default"><i class="fas fa-search mr-1"></i> Filter</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Summary -->
<div class="row">
  <div class="col-md-4">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></h3>
        <p>Total Pemasukan</p>
      </div>
      <div class="icon"><i class="fas fa-arrow-circle-down"></i></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></h3>
        <p>Total Pengeluaran</p>
      </div>
      <div class="icon"><i class="fas fa-arrow-circle-up"></i></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="small-box <?= $selisih >= 0 ? 'bg-info' : 'bg-warning' ?>">
      <div class="inner">
        <h3><?= $selisih >= 0 ? '+' : '-' ?>Rp <?= number_format(abs($selisih), 0, ',', '.') ?></h3>
        <p>Selisih</p>
      </div>
      <div class="icon"><i class="fas fa-balance-scale"></i></div>
    </div>
  </div>
</div>

<!-- Expense by Category -->
<?php if (!empty($perKategori)): ?>
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Pengeluaran per Kategori</h3></div>
      <div class="card-body">
        <div style="height:250px;"><canvas id="kategoriChart"></canvas></div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-list mr-1"></i> Detail Kategori</h3></div>
      <div class="card-body p-0">
        <table class="table">
          <?php foreach ($perKategori as $k): ?>
          <tr>
            <td><?= esc($k['kategori_name']) ?></td>
            <td class="text-right text-expense">Rp <?= number_format($k['total'], 0, ',', '.') ?></td>
          </tr>
          <?php endforeach; ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Detail Tables -->
<div class="row">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-arrow-circle-down mr-1"></i> Daftar Pemasukan</h3></div>
      <div class="card-body p-0">
        <?php if (empty($pemasukan)): ?>
          <div class="p-3 text-center text-muted">Tidak ada data</div>
        <?php else: ?>
          <table class="table table-sm table-striped">
            <thead><tr><th>Tanggal</th><th>Sumber</th><th class="text-right">Nominal</th></tr></thead>
            <tbody>
              <?php foreach ($pemasukan as $p): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
                <td><?= esc($p['sumber']) ?></td>
                <td class="text-right text-income">+Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header"><h3 class="card-title"><i class="fas fa-arrow-circle-up mr-1"></i> Daftar Pengeluaran</h3></div>
      <div class="card-body p-0">
        <?php if (empty($pengeluaran)): ?>
          <div class="p-3 text-center text-muted">Tidak ada data</div>
        <?php else: ?>
          <table class="table table-sm table-striped">
            <thead><tr><th>Tanggal</th><th>Kategori</th><th class="text-right">Nominal</th></tr></thead>
            <tbody>
              <?php foreach ($pengeluaran as $k): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($k['tanggal'])) ?></td>
                <td><?= esc($k['kategori_name'] ?? '-') ?></td>
                <td class="text-right text-expense">-Rp <?= number_format($k['nominal'], 0, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (!empty($perKategori)): ?>
<script>
const katCtx = document.getElementById('kategoriChart');
if (katCtx) {
  const colors = ['#007bff','#28a745','#dc3545','#ffc107','#17a2b8','#6f42c1','#fd7e14'];
  new Chart(katCtx, {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_column($perKategori, 'kategori_name')) ?>,
      datasets: [{
        data: <?= json_encode(array_map('floatval', array_column($perKategori, 'total'))) ?>,
        backgroundColor: colors.slice(0, <?= count($perKategori) ?>),
        borderWidth: 0,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 12 } }
      }
    }
  });
}
</script>
<?php endif; ?>
<?= $this->endSection() ?>
