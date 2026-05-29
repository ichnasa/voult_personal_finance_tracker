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

<!-- Financial Health + Cashflow Chart -->
<div class="row row-deck row-cards mb-3">
  <!-- Financial Health -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-heart-rate-monitor me-1"></i>Financial Health</h3>
      </div>
      <div class="card-body text-center">
        <div class="mx-auto mb-3" style="width: 140px; height: 140px; position: relative;">
          <canvas id="healthChart"></canvas>
          <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <div class="h1 mb-0" style="color: <?= $healthColor ?>"><?= $healthScore ?></div>
            <div class="text-muted fs-5"><?= $healthLabel ?></div>
          </div>
        </div>
        <div class="mt-3">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Spending Ratio</span>
            <span class="fw-medium"><?= $spendingRatio ?>%</span>
          </div>
          <div class="progress mb-3" style="height: 6px;">
            <div
              class="progress-bar <?= $spendingRatio > 80 ? 'bg-danger' : ($spendingRatio > 60 ? 'bg-warning' : 'bg-success') ?>"
              style="width: <?= $spendingRatio ?>%"></div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Saving Ratio</span>
            <span class="fw-medium"><?= $savingRatio ?>%</span>
          </div>
          <div class="progress mb-3" style="height: 6px;">
            <div
              class="progress-bar <?= $savingRatio >= 30 ? 'bg-success' : ($savingRatio >= 15 ? 'bg-warning' : 'bg-danger') ?>"
              style="width: <?= $savingRatio ?>%"></div>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary">Discipline</span>
            <span class="fw-medium"><?= $budgetDiscipline ?>%</span>
          </div>
          <div class="progress" style="height: 6px;">
            <div
              class="progress-bar <?= $budgetDiscipline >= 60 ? 'bg-success' : ($budgetDiscipline >= 30 ? 'bg-warning' : 'bg-danger') ?>"
              style="width: <?= $budgetDiscipline ?>%"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Cashflow Chart -->
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
</div>

<!-- Saving Goals + Wishlist Priority -->
<div class="row row-deck row-cards mb-3">
  <!-- Saving Goals -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-pig-money me-1"></i>Saving Goals</h3>
        <div class="card-actions">
          <a href="<?= base_url('tabungan') ?>" class="btn btn-ghost-primary btn-sm">Lihat Semua</a>
        </div>
      </div>
      <div class="card-body">
        <?php if (empty($savingGoals)): ?>
          <div class="text-center text-muted py-4">
            <i class="ti ti-pig-money fs-1 d-block mb-2"></i>
            Belum ada target tabungan aktif
          </div>
        <?php else: ?>
          <?php foreach ($savingGoals as $sg): ?>
            <?php
            $progress = $sg['target_nominal'] > 0
              ? round(($sg['nominal_terkumpul'] / $sg['target_nominal']) * 100)
              : 0;
            $barColor = $progress >= 100 ? 'bg-success' : ($progress >= 50 ? 'bg-primary' : 'bg-warning');
            ?>
            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="d-flex align-items-center">
                  <div>
                    <div class="fw-medium"><?= esc($sg['nama_tabungan']) ?></div>
                    <div class="text-muted fs-6">
                      Rp <?= number_format($sg['nominal_terkumpul'], 0, ',', '.') ?> /
                      Rp <?= number_format($sg['target_nominal'], 0, ',', '.') ?>
                    </div>
                  </div>
                </div>
                <span class="fw-bold"><?= $progress ?>%</span>
              </div>
              <div class="progress" style="height: 6px;">
                <div class="progress-bar <?= $barColor ?>" style="width: <?= min($progress, 100) ?>%"></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Wishlist Priority -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-star me-1"></i>Wishlist Priority</h3>
        <div class="card-actions">
          <a href="<?= base_url('wishlist') ?>" class="btn btn-ghost-primary btn-sm">Lihat Semua</a>
        </div>
      </div>
      <div class="card-body">
        <?php if (empty($wishlistPriority)): ?>
          <div class="text-center text-muted py-4">
            <i class="ti ti-star fs-1 d-block mb-2"></i>
            Belum ada wishlist aktif
          </div>
        <?php else: ?>
          <?php foreach ($wishlistPriority as $wl): ?>
            <?php
            $prioritasColor = match ($wl['prioritas']) {
              'tinggi' => 'bg-danger',
              'sedang' => 'bg-warning',
              default => 'bg-secondary',
            };
            $statusColor = match ($wl['status']) {
              'tercapai' => 'bg-success',
              'menabung' => 'bg-primary',
              default => 'bg-secondary',
            };
            $statusLabel = match ($wl['status']) {
              'tercapai' => 'Tercapai',
              'menabung' => 'Menabung',
              default => 'Belum Mulai',
            };
            ?>
            <div class="d-flex align-items-center mb-3">
              <div class="flex-fill">
                <div class="fw-medium"><?= esc($wl['nama_barang']) ?></div>
                <div class="text-muted fs-6">
                  Rp <?= number_format($wl['harga_target'], 0, ',', '.') ?>
                </div>
              </div>
              <div class="text-end">
                <span class="badge <?= $prioritasColor ?> text-white mb-1"><?= ucfirst($wl['prioritas']) ?></span><br>
                <span class="badge <?= $statusColor ?> text-white"><?= $statusLabel ?></span>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
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
  // ── Financial Health Donut Chart ──
  const healthCtx = document.getElementById('healthChart');
  if (healthCtx) {
    new Chart(healthCtx, {
      type: 'doughnut',
      data: {
        datasets: [{
          data: [<?= $healthScore ?>, <?= 100 - $healthScore ?>],
          backgroundColor: ['<?= $healthColor ?>', '#f0f0f0'],
          borderWidth: 0,
          cutout: '80%',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
          legend: { display: false },
          tooltip: { enabled: false }
        }
      }
    });
  }

  // ── Cashflow Bar Chart ──
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