<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Stat Cards -->
<div class="row row-deck row-cards mb-3">
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon bg-azure"><i class="ti ti-wallet stamp-bg-icon"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Total Saldo</div>
        <div class="h1 mb-1">Rp <?= number_format($totalSaldo, 0, ',', '.') ?></div>
        <div class="text-muted fs-6">Total Saldo = Pemasukan - Pengeluaran</div>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon bg-green"><i class="ti ti-moneybag-plus stamp-bg-icon"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Pemasukan <?= date('F') ?></div>
        <div class="h1 mb-1">Rp <?= number_format($totalPemasukanBulan, 0, ',', '.') ?></div>
        <a href="<?= base_url('pemasukan') ?>" class="text-muted">Selengkapnya →</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon bg-red"><i class="ti ti-moneybag-minus stamp-bg-icon"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Pengeluaran <?= date('F') ?></div>
        <div class="h1 mb-1">Rp <?= number_format($totalPengeluaranBulan, 0, ',', '.') ?></div>
        <a href="<?= base_url('pengeluaran') ?>" class="text-muted">Selengkapnya →</a>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-stamp">
        <div class="card-stamp-icon bg-yellow"><i class="ti ti-calculator stamp-bg-icon"></i></div>
      </div>
      <div class="card-body">
        <div class="subheader">Sisa Budget</div>
        <div class="h1 mb-1">Rp <?= number_format($sisaBudget, 0, ',', '.') ?></div>
        <a href="<?= base_url('budgeting') ?>" class="text-muted">Dari Rp
          <?= number_format($totalBudget, 0, ',', '.') ?> →</a>
      </div>
    </div>
  </div>
</div>

<!-- Charts & Summary Row -->
<div class="row row-deck row-cards mb-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-chart-bar me-1"></i> Cashflow <?= date('Y') ?></h3>
      </div>
      <div class="card-body">
        <div style="height: 280px;"><canvas id="cashflowChart"></canvas></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-list-details me-1"></i> Ringkasan Bulan Ini</h3>
      </div>
      <div class="card-body">
        <?php
        $pct = $totalBudget > 0 ? min(($totalPengeluaranBulan / $totalBudget) * 100, 100) : 0;
        $barColor = $pct > 100 ? 'bg-danger' : ($pct > 80 ? 'bg-warning' : 'bg-primary');
        ?>
        <div class="d-flex justify-content-between mb-1">
          <span class="text-secondary">Budget Terpakai</span>
          <span><?= $totalBudget > 0 ? round(($totalPengeluaranBulan / $totalBudget) * 100) : 0 ?>%</span>
        </div>
        <div class="progress mb-3" style="height: 8px;">
          <div class="progress-bar <?= $barColor ?>" style="width: <?= $pct ?>%"></div>
        </div>

        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Pemasukan</span>
          <span class="text-income">+Rp <?= number_format($totalPemasukanBulan, 0, ',', '.') ?></span>
        </div>
        <div class="d-flex justify-content-between mb-3">
          <span class="text-secondary">Pengeluaran</span>
          <span class="text-expense">-Rp <?= number_format($totalPengeluaranBulan, 0, ',', '.') ?></span>
        </div>

        <hr class="my-3">
        <h4 class="mb-2">Aksi Cepat</h4>
        <a href="<?= base_url('pemasukan/create') ?>" class="btn btn-success w-100 mb-2">
          <i class="ti ti-plus me-1"></i> Tambah Pemasukan
        </a>
        <a href="<?= base_url('pengeluaran/create') ?>" class="btn btn-danger w-100 mb-2">
          <i class="ti ti-plus me-1"></i> Tambah Pengeluaran
        </a>
        <a href="<?= base_url('budgeting') ?>" class="btn btn-outline-secondary w-100">
          <i class="ti ti-wallet me-1"></i> Kelola Budget
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Recent Transactions -->
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-history me-1"></i> Transaksi Terakhir</h3>
        <div class="card-actions">
          <a href="<?= base_url('pemasukan') ?>" class="btn btn-ghost-primary btn-sm"><i
              class="ti ti-moneybag-plus"></i> Pemasukan</a>
          <a href="<?= base_url('pengeluaran') ?>" class="btn btn-ghost-danger btn-sm"><i
              class="ti ti-moneybag-minus"></i> Pengeluaran</a>
        </div>
      </div>
      <?php
      $transactions = [];
      foreach ($recentPemasukan as $p) {
        $transactions[] = ['type' => 'masuk', 'tanggal' => $p['tanggal'], 'nominal' => $p['nominal'], 'keterangan' => $p['sumber']];
      }
      foreach ($recentPengeluaran as $k) {
        $transactions[] = ['type' => 'keluar', 'tanggal' => $k['tanggal'], 'nominal' => $k['nominal'], 'keterangan' => $k['kategori_name'] ?? 'Lainnya'];
      }
      usort($transactions, fn($a, $b) => strtotime($b['tanggal']) - strtotime($a['tanggal']));
      $transactions = array_slice($transactions, 0, 5);
      ?>

      <?php if (empty($transactions)): ?>
        <div class="card-body">
          <div class="empty-state">
            <i class="ti ti-receipt"></i>
            <p>Belum ada transaksi</p>
          </div>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-vcenter card-table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th class="text-end">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($transactions as $t): ?>
                <tr>
                  <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                  <td><?= esc($t['keterangan']) ?></td>
                  <td>
                    <?php if ($t['type'] === 'masuk'): ?>
                      <span class="badge bg-success text-white        ">Masuk</span>
                    <?php else: ?>
                      <span class="badge bg-danger text-white">Keluar</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-end <?= $t['type'] === 'masuk' ? 'text-income' : 'text-expense' ?>">
                    <?= $t['type'] === 'masuk' ? '+' : '-' ?>Rp <?= number_format($t['nominal'], 0, ',', '.') ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const ctx = document.getElementById('cashflowChart');
  if (ctx) {
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [
          {
            label: 'Pemasukan',
            data: <?= json_encode($pemasukanBulanan) ?>,
            backgroundColor: '#2fb344',
            borderRadius: 4,
            barPercentage: 0.6,
          },
          {
            label: 'Pengeluaran',
            data: <?= json_encode($pengeluaranBulanan) ?>,
            backgroundColor: '#d63939',
            borderRadius: 4,
            barPercentage: 0.6,
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { labels: { boxWidth: 12, padding: 16 } }
        },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true }
        }
      }
    });
  }
</script>
<?= $this->endSection() ?>