<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
$sortDir = $filters['sort_dir'] ?? 'DESC';
$nextDir = $sortDir === 'ASC' ? 'DESC' : 'ASC';

// Count active filters
$activeCount = 0;
foreach (['date_from', 'date_to', 'terkumpul_min', 'terkumpul_max', 'target_min', 'target_max', 'progress_min', 'progress_max', 'status'] as $k) {
  if (!empty($filters[$k]))
    $activeCount++;
}
?>

<div class="d-flex flex-column" style="min-height: calc(100vh - 200px);">

  <div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center">
      <div class="d-flex gap-2 align-items-center">
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-white btn-icon active" id="btn-view-grid" title="Grid View (2 Kolom)">
            <i class="ti ti-grid-dots"></i>
          </button>
          <button type="button" class="btn btn-white btn-icon" id="btn-view-list" title="List View (1 Kolom)">
            <i class="ti ti-list"></i>
          </button>
        </div>
        <button class="btn btn-white" data-bs-toggle="modal" data-bs-target="#filterModal">
          <i class="ti ti-filter me-1"></i> Filter
          <?php if ($activeCount > 0): ?>
            <span class="badge bg-primary text-white ms-1"><?= $activeCount ?></span>
          <?php endif; ?>
        </button>
        <?php if ($activeCount > 0): ?>
          <a href="<?= base_url('tabungan') ?>" class="btn btn-white text-danger"><i class="ti ti-x me-1"></i> Reset</a>
        <?php endif; ?>
      </div>
      <a href="<?= base_url('tabungan/create') ?>" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Tambah
        Target</a>
    </div>
  </div>

  <!-- Filter Modal -->
  <div class="modal modal-blur fade" id="filterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form method="get" action="<?= base_url('tabungan') ?>">
          <div class="modal-header">
            <h5 class="modal-title"><i class="ti ti-filter me-2"></i>Filter Tabungan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">

            <!-- Tanggal Dibuat -->
            <div class="mb-4">
              <label class="form-label fw-bold mb-2">Tanggal Dibuat</label>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label text-muted small">Dari</label>
                  <input type="date" name="date_from" class="form-control"
                    value="<?= esc($filters['date_from'] ?? '') ?>">
                </div>
                <div class="col-6">
                  <label class="form-label text-muted small">Sampai</label>
                  <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
                </div>
              </div>
              <small class="form-text text-muted">Jika hanya mengisi "Dari", akan mencari tanggal persis
                tersebut.</small>
            </div>

            <hr class="my-3">

            <!-- Target Nominal -->
            <div class="mb-4">
              <label class="form-label fw-bold mb-2">Target Nominal (Rp)</label>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label text-muted small">Min</label>
                  <input type="number" name="target_min" class="form-control" placeholder="0"
                    value="<?= esc($filters['target_min'] ?? '') ?>" min="0">
                </div>
                <div class="col-6">
                  <label class="form-label text-muted small">Max</label>
                  <input type="number" name="target_max" class="form-control" placeholder="0"
                    value="<?= esc($filters['target_max'] ?? '') ?>" min="0">
                </div>
              </div>
              <small class="form-text text-muted">Jika Max dikosongkan, akan mencari nilai persis Min.</small>
            </div>

            <hr class="my-3">

            <!-- Nominal Terkumpul -->
            <div class="mb-4">
              <label class="form-label fw-bold mb-2">Nominal Terkumpul (Rp)</label>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label text-muted small">Min</label>
                  <input type="number" name="terkumpul_min" class="form-control" placeholder="0"
                    value="<?= esc($filters['terkumpul_min'] ?? '') ?>" min="0">
                </div>
                <div class="col-6">
                  <label class="form-label text-muted small">Max</label>
                  <input type="number" name="terkumpul_max" class="form-control" placeholder="0"
                    value="<?= esc($filters['terkumpul_max'] ?? '') ?>" min="0">
                </div>
              </div>
              <small class="form-text text-muted">Jika Max dikosongkan, akan mencari nilai persis Min.</small>
            </div>

            <hr class="my-3">

            <!-- Progress -->
            <div class="mb-4">
              <label class="form-label fw-bold mb-2">Progress (%)</label>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label text-muted small">Min (%)</label>
                  <input type="number" name="progress_min" class="form-control" placeholder="0"
                    value="<?= esc($filters['progress_min'] ?? '') ?>" min="0" max="100">
                </div>
                <div class="col-6">
                  <label class="form-label text-muted small">Max (%)</label>
                  <input type="number" name="progress_max" class="form-control" placeholder="100"
                    value="<?= esc($filters['progress_max'] ?? '') ?>" min="0" max="100">
                </div>
              </div>
              <small class="form-text text-muted">Jika Max dikosongkan, akan mencari persentase persis Min.</small>
            </div>

            <hr class="my-3">

            <!-- Status -->
            <div class="mb-4">
              <label class="form-label fw-bold mb-2">Status</label>
              <select name="status" class="form-select">
                <option value="">Semua Status</option>
                <option value="proses" <?= ($filters['status'] ?? '') === 'proses' ? 'selected' : '' ?>>Proses</option>
                <option value="tercapai" <?= ($filters['status'] ?? '') === 'tercapai' ? 'selected' : '' ?>>Tercapai
                </option>
              </select>
            </div>

            <hr class="my-3">

            <!-- Sort -->
            <div class="mb-2">
              <label class="form-label fw-bold">Urutkan Tanggal</label>
              <select name="sort_dir" class="form-select">
                <option value="DESC" <?= $sortDir === 'DESC' ? 'selected' : '' ?>>Terbaru Dulu</option>
                <option value="ASC" <?= $sortDir === 'ASC' ? 'selected' : '' ?>>Terlama Dulu</option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <a href="<?= base_url('tabungan') ?>" class="btn btn-ghost-secondary">Reset All</a>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-check me-1"></i> Apply Filters
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Content Area -->
  <div class="flex-grow-1 d-flex flex-column" id="tabungan-content" data-pjax-container>
    <?php if (empty($items)): ?>
      <div class="card">
        <div class="card-body">
          <div class="empty-state">
            <i class="ti ti-pig-money"></i>
            <p>Belum ada target tabungan</p>
            <a href="<?= base_url('tabungan/create') ?>" class="btn btn-primary btn-sm">+ Buat Target</a>
          </div>
        </div>
      </div>
    <?php else: ?>
      <div class="row g-2" id="tabungan-list">
        <?php foreach ($items as $item): ?>
          <div class="col-md-6 tabungan-item">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <?= esc($item['nama_tabungan']) ?>
                  <?php if (!empty($item['wishlist_name'])): ?>
                    <small class="text-muted ms-1"><i class="ti ti-star"></i> <?= esc($item['wishlist_name']) ?></small>
                  <?php endif; ?>
                </h3>
                <div class="card-actions d-flex align-items-center" style="gap: 12px;">
                  <?php if ($item['status'] === 'tercapai'): ?>
                    <span class="badge bg-success text-white">Tercapai</span>
                  <?php else: ?>
                    <span class="badge bg-warning text-white">Proses</span>
                  <?php endif; ?>
                  <a href="<?= base_url('tabungan/edit/' . $item['id']) ?>" class="btn btn-ghost-secondary btn-sm"><i
                      class="ti ti-edit"></i></a>
                  <a href="<?= base_url('tabungan/delete/' . $item['id']) ?>"
                    class="btn btn-ghost-secondary btn-sm text-danger" onclick="return confirm('Hapus tabungan ini?')"><i
                      class="ti ti-trash"></i></a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex mb-2">
                  <h4 class="text-primary mb-0 me-1">Rp <?= number_format($item['nominal_terkumpul'], 0, ',', '.') ?></h4>
                  <span class="text-muted">/ Rp <?= number_format($item['target_nominal'], 0, ',', '.') ?></span>
                </div>
                <div class="progress mb-2" style="height: 10px;">
                  <div class="progress-bar bg-primary" style="width: <?= min($item['progress'], 100) ?>%"></div>
                </div>
                <div class="d-flex text-sm">
                  <span class="me-1">Progress</span>
                  <strong><?= $item['progress'] ?>%</strong>
                </div>
                <?php if (!empty($item['deadline'])): ?>
                  <?php
                  $daysLeft = (int) ((strtotime($item['deadline']) - time()) / 86400);
                  $deadlineClass = $daysLeft < 0 ? 'text-danger' : ($daysLeft < 30 ? 'text-warning' : 'text-muted');
                  ?>
                  <div class="mt-2 text-sm <?= $deadlineClass ?>">
                    <i class="ti ti-calendar me-1"></i>
                    Deadline: <?= date('d M Y', strtotime($item['deadline'])) ?>
                    <?php if ($daysLeft >= 0): ?>(<?= $daysLeft ?> hari lagi)<?php else: ?>(terlambat <?= abs($daysLeft) ?>
                      hari)<?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <!-- Pagination (always at bottom) -->
    <div class="mt-auto pt-3">
      <div class="d-flex justify-content-center w-100"><?= $pager->links('default', 'tabler_pagination') ?></div>
    </div>
  </div>

</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const btnGrid = document.getElementById('btn-view-grid');
    const btnList = document.getElementById('btn-view-list');
    const items = document.querySelectorAll('.tabungan-item');
    const currentView = localStorage.getItem('tabungan_view') || 'grid';

    function setView(view) {
      if (view === 'list') {
        items.forEach(item => {
          item.classList.remove('col-md-6');
          item.classList.add('col-md-12');
        });
        if (btnList) btnList.classList.add('active');
        if (btnGrid) btnGrid.classList.remove('active');
        localStorage.setItem('tabungan_view', 'list');
      } else {
        items.forEach(item => {
          item.classList.remove('col-md-12');
          item.classList.add('col-md-6');
        });
        if (btnGrid) btnGrid.classList.add('active');
        if (btnList) btnList.classList.remove('active');
        localStorage.setItem('tabungan_view', 'grid');
      }
    }

    if (btnGrid && btnList) {
      setView(currentView);
      btnGrid.addEventListener('click', () => setView('grid'));
      btnList.addEventListener('click', () => setView('list'));
    }
  });
</script>

<?= $this->endSection() ?>