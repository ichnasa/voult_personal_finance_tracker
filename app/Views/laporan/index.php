<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between">
    <div></div>
    <a href="<?= base_url('laporan/export?date_from=' . $dateFrom . '&date_to=' . $dateTo) ?>" target="_blank"
      class="btn btn-ghost-secondary">
      <i class="ti ti-printer me-1"></i> Cetak / Export PDF
    </a>
  </div>
</div>

<!-- Filter -->
<div class="card mb-3">
  <div class="card-body">
    <form method="get" action="<?= base_url('laporan') ?>">
      <div class="row">
        <div class="col-md-3">
          <div class="mb-3"><label>Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom) ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="mb-3"><label>Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo) ?>">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <div class="mb-3">
            <button type="submit" class="btn btn-ghost-secondary"><i class="ti ti-search me-1"></i> Filter</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Summary -->
<div class="row row-deck row-cards mb-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon bg-green"><i class="ti ti-moneybag-plus"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Total Pemasukan</div>
        <div class="h1 mb-0">Rp <?= number_format($totalPemasukan, 0, ',', '.') ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon bg-red"><i class="ti ti-moneybag-minus"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Total Pengeluaran</div>
        <div class="h1 mb-0">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon <?= $selisih >= 0 ? 'bg-azure' : 'bg-yellow' ?>"><i class="ti ti-scale"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Selisih</div>
        <div class="h1 mb-0"><?= $selisih >= 0 ? '+' : '-' ?>Rp <?= number_format(abs($selisih), 0, ',', '.') ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Expense by Category -->
<?php if (!empty($perKategori)): ?>
  <div class="row mb-3">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="ti ti-chart-pie me-1"></i> Pengeluaran per Kategori</h3>
        </div>
        <div class="card-body">
          <div style="height:250px;"><canvas id="kategoriChart"></canvas></div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><i class="ti ti-list me-1"></i> Detail Kategori</h3>
        </div>
        <div class="table-responsive">
          <table class="table">
            <?php foreach ($perKategori as $k): ?>
              <tr>
                <td><?= esc($k['kategori_name']) ?></td>
                <td class="text-end text-expense">Rp <?= number_format($k['total'], 0, ',', '.') ?></td>
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
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-moneybag-plus me-1"></i> Daftar Pemasukan</h3>
      </div>
      <div class="table-responsive">
        <?php if (empty($pemasukan)): ?>
          <div class="p-3 text-center text-muted">Tidak ada data</div>
        <?php else: ?>
          <table class="table table-sm table-vcenter">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Sumber</th>
                <th class="text-end">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pemasukan as $p): ?>
                <tr>
                  <td><?= date('d/m/Y', strtotime($p['tanggal'])) ?></td>
                  <td><?= esc($p['sumber']) ?></td>
                  <td class="text-end text-income">+Rp <?= number_format($p['nominal'], 0, ',', '.') ?></td>
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
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-moneybag-minus me-1"></i> Daftar Pengeluaran</h3>
      </div>
      <div class="table-responsive">
        <?php if (empty($pengeluaran)): ?>
          <div class="p-3 text-center text-muted">Tidak ada data</div>
        <?php else: ?>
          <table class="table table-sm table-vcenter">
            <thead>
              <tr>
                <th class="p-3">Tanggal</th>
                <th class="p-3">Kategori</th>
                <th class="p-3 text-end">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pengeluaran as $k): ?>
                <tr>
                  <td class="p-3"><?= date('d/m/Y', strtotime($k['tanggal'])) ?></td>
                  <td class="p-3"><?= esc($k['kategori_name'] ?? '-') ?></td>
                  <td class="p-3 text-end text-expense">-Rp <?= number_format($k['nominal'], 0, ',', '.') ?></td>
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
      const colors = ['#206bc4', '#2fb344', '#d63939', '#f76707', '#4299e1', '#ae3ec9', '#f59f00'];
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