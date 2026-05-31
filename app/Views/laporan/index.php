<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between">
    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalCetak">
      <i class="ti ti-printer me-1"></i> Cetak / Export PDF
    </button>
  </div>
</div>

<!-- Filter -->
<div class="card mb-3">
  <div class="card-body">
    <form method="get" action="<?= base_url('laporan') ?>">
      <div class="row">
        <div class="col-md-3">
          <div class=""><label>Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($dateFrom) ?>"
              onchange="this.form.submit()">
          </div>
        </div>
        <div class="col-md-3">
          <div class=""><label>Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($dateTo) ?>"
              onchange="this.form.submit()">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <div class="mb-3 d-flex gap-2">
            <?php if (isset($_GET['date_from']) || isset($_GET['date_to'])): ?>
              <a href="<?= base_url('laporan') ?>" class="btn btn-icon btn-outline-danger" title="Reset Filter">
                <i class="ti ti-filter-off"></i>
              </a>
            <?php endif; ?>
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
        <div class="card-stamp-icon bg-green"><i class="ti ti-moneybag-plus stamp-bg-icon"></i></div>
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
        <div class="card-stamp-icon bg-red"><i class="ti ti-moneybag-minus stamp-bg-icon"></i></div>
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
        <div class="card-stamp-icon <?= $selisih >= 0 ? 'bg-azure' : 'bg-yellow' ?>"><i
            class="ti ti-scale stamp-bg-icon"></i></div>
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
    <div class="card" id="pemasukan-card" data-pjax-container>
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
                <th class="p-3">Tanggal</th>
                <th class="p-3">Sumber</th>
                <th class="p-3 text-end">Nominal</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pemasukan as $p): ?>
                <tr>
                  <td class="p-3">
                    <?= date('d/m/Y', strtotime($p['tanggal'])) ?>
                  </td>
                  <td class="p-3">
                    <?= esc($p['sumber']) ?>
                  </td>
                  <td class="p-3 text-end text-income">+Rp
                    <?= number_format($p['nominal'], 0, ',', '.') ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
      <?php if ($pager->getPageCount('pemasukan') > 1): ?>
        <div class="card-footer d-flex align-items-center">
          <?= $pager->links('pemasukan', 'tabler_pagination') ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card" id="pengeluaran-card" data-pjax-container>
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
                  <td class="p-3">
                    <?= date('d/m/Y', strtotime($k['tanggal'])) ?>
                  </td>
                  <td class="p-3">
                    <?= esc($k['kategori_name'] ?? '-') ?>
                  </td>
                  <td class="p-3 text-end text-expense">-Rp
                    <?= number_format($k['nominal'], 0, ',', '.') ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
      <?php if ($pager->getPageCount('pengeluaran') > 1): ?>
        <div class="card-footer d-flex align-items-center">
          <?= $pager->links('pengeluaran', 'tabler_pagination') ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Modal Cetak Laporan -->
<div class="modal modal-blur fade" id="modalCetak" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cetak Laporan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="<?= base_url('laporan/export') ?>" method="get" target="_blank">
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= date('Y-m-01') ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= date('Y-m-t') ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"
            onclick="setTimeout(() => bootstrap.Modal.getInstance(document.getElementById('modalCetak')).hide(), 500)">
            <i class="ti ti-printer me-1"></i> Cetak
          </button>
        </div>
      </form>
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