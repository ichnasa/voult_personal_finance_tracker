<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Stat Cards: Small Boxes -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>Rp <?= number_format($totalSaldo, 0, ',', '.') ?></h3>
        <p>Total Saldo</p>
      </div>
      <div class="icon"><i class="fas fa-wallet"></i></div>
      <a href="#" class="small-box-footer">Pemasukan - Pengeluaran <i class="fas fa-info-circle"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>Rp <?= number_format($totalPemasukanBulan, 0, ',', '.') ?></h3>
        <p>Pemasukan <?= date('F') ?></p>
      </div>
      <div class="icon"><i class="fas fa-arrow-circle-down"></i></div>
      <a href="<?= base_url('pemasukan') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>Rp <?= number_format($totalPengeluaranBulan, 0, ',', '.') ?></h3>
        <p>Pengeluaran <?= date('F') ?></p>
      </div>
      <div class="icon"><i class="fas fa-arrow-circle-up"></i></div>
      <a href="<?= base_url('pengeluaran') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>Rp <?= number_format($sisaBudget, 0, ',', '.') ?></h3>
        <p>Sisa Budget</p>
      </div>
      <div class="icon"><i class="fas fa-calculator"></i></div>
      <a href="<?= base_url('budgeting') ?>" class="small-box-footer">Dari Rp <?= number_format($totalBudget, 0, ',', '.') ?> <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
</div>

<!-- Charts & Summary Row -->
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Cashflow <?= date('Y') ?></h3>
      </div>
      <div class="card-body">
        <div style="height: 280px;">
          <canvas id="cashflowChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clipboard-list mr-1"></i> Ringkasan Bulan Ini</h3>
      </div>
      <div class="card-body">
        <!-- Budget Progress -->
        <?php
          $pct = $totalBudget > 0 ? min(($totalPengeluaranBulan / $totalBudget) * 100, 100) : 0;
          $barColor = $pct > 100 ? 'bg-danger' : ($pct > 80 ? 'bg-warning' : 'bg-primary');
        ?>
        <p class="text-sm mb-1">Budget Terpakai
          <span class="float-right"><?= $totalBudget > 0 ? round(($totalPengeluaranBulan / $totalBudget) * 100) : 0 ?>%</span>
        </p>
        <div class="progress mb-3" style="height: 8px;">
          <div class="progress-bar <?= $barColor ?>" style="width: <?= $pct ?>%"></div>
        </div>

        <div class="d-flex justify-content-between mb-2">
          <span class="text-sm text-muted">Pemasukan</span>
          <span class="text-sm text-income">+Rp <?= number_format($totalPemasukanBulan, 0, ',', '.') ?></span>
        </div>
        <div class="d-flex justify-content-between mb-3">
          <span class="text-sm text-muted">Pengeluaran</span>
          <span class="text-sm text-expense">-Rp <?= number_format($totalPengeluaranBulan, 0, ',', '.') ?></span>
        </div>

        <hr>
        <h6>Aksi Cepat</h6>
        <a href="<?= base_url('pemasukan/create') ?>" class="btn btn-success btn-block btn-sm mb-2">
          <i class="fas fa-plus mr-1"></i> Tambah Pemasukan
        </a>
        <a href="<?= base_url('pengeluaran/create') ?>" class="btn btn-danger btn-block btn-sm mb-2">
          <i class="fas fa-plus mr-1"></i> Tambah Pengeluaran
        </a>
        <a href="<?= base_url('budgeting') ?>" class="btn btn-default btn-block btn-sm">
          <i class="fas fa-wallet mr-1"></i> Kelola Budget
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Recent Transactions -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-1"></i> Transaksi Terakhir</h3>
        <div class="card-tools">
          <a href="<?= base_url('pemasukan') ?>" class="btn btn-tool"><i class="fas fa-arrow-circle-down"></i> Pemasukan</a>
          <a href="<?= base_url('pengeluaran') ?>" class="btn btn-tool"><i class="fas fa-arrow-circle-up"></i> Pengeluaran</a>
        </div>
      </div>
      <div class="card-body p-0">
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
          <div class="empty-state">
            <i class="fas fa-receipt"></i>
            <p>Belum ada transaksi</p>
          </div>
        <?php else: ?>
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Tipe</th>
                <th class="text-right">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($transactions as $t): ?>
              <tr>
                <td><?= date('d M Y', strtotime($t['tanggal'])) ?></td>
                <td><?= esc($t['keterangan']) ?></td>
                <td>
                  <?php if ($t['type'] === 'masuk'): ?>
                    <span class="badge badge-success">Masuk</span>
                  <?php else: ?>
                    <span class="badge badge-danger">Keluar</span>
                  <?php endif; ?>
                </td>
                <td class="text-right <?= $t['type'] === 'masuk' ? 'text-income' : 'text-expense' ?>">
                  <?= $t['type'] === 'masuk' ? '+' : '-' ?>Rp <?= number_format($t['nominal'], 0, ',', '.') ?>
                </td>
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
<script>
const ctx = document.getElementById('cashflowChart');
if (ctx) {
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
      datasets: [
        {
          label: 'Pemasukan',
          data: <?= json_encode($pemasukanBulanan) ?>,
          backgroundColor: '#28a745',
          borderRadius: 3,
          barPercentage: 0.6,
        },
        {
          label: 'Pengeluaran',
          data: <?= json_encode($pengeluaranBulanan) ?>,
          backgroundColor: '#dc3545',
          borderRadius: 3,
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
