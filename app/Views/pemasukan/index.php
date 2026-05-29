<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
  $sortDir = $filters['sort_dir'] ?? 'DESC';
  $nextDir = $sortDir === 'ASC' ? 'DESC' : 'ASC';
  $sortIcon = $sortDir === 'ASC' ? 'ti-sort-ascending' : 'ti-sort-descending';
  $qs = http_build_query(array_filter(array_merge($filters, ['sort_dir' => $nextDir])));

  // Count active filters
  $activeCount = 0;
  foreach (['date_from','date_to','nominal_min','nominal_max','search'] as $k) {
    if (!empty($filters[$k])) $activeCount++;
  }
?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <div class="d-flex gap-2">
      <button class="btn btn-white" data-bs-toggle="modal" data-bs-target="#filterModal">
        <i class="ti ti-filter me-1"></i> Filter
        <?php if ($activeCount > 0): ?>
          <span class="badge bg-primary text-white ms-1"><?= $activeCount ?></span>
        <?php endif; ?>
      </button>
      <?php if ($activeCount > 0): ?>
        <a href="<?= base_url('pemasukan') ?>" class="btn btn-white text-danger"><i class="ti ti-x me-1"></i> Reset</a>
      <?php endif; ?>
    </div>
    <a href="<?= base_url('pemasukan/create') ?>" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Tambah Pemasukan</a>
  </div>
</div>

<!-- Filter Modal -->
<div class="modal modal-blur fade" id="filterModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="get" action="<?= base_url('pemasukan') ?>">
        <div class="modal-header">
          <h5 class="modal-title"><i class="ti ti-filter me-2"></i>Filter</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          <!-- Date Range -->
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-bold mb-0">Rentang Tanggal</label>
            </div>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label text-muted small">Dari</label>
                <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
              </div>
              <div class="col-6">
                <label class="form-label text-muted small">Sampai</label>
                <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
              </div>
            </div>
          </div>

          <hr class="my-3">

          <!-- Nominal Range -->
          <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <label class="form-label fw-bold mb-0">Nominal</label>
            </div>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label text-muted small">Min (Rp)</label>
                <input type="number" name="nominal_min" class="form-control" placeholder="0" value="<?= esc($filters['nominal_min'] ?? '') ?>" min="0">
              </div>
              <div class="col-6">
                <label class="form-label text-muted small">Max (Rp)</label>
                <input type="number" name="nominal_max" class="form-control" placeholder="0" value="<?= esc($filters['nominal_max'] ?? '') ?>" min="0">
              </div>
            </div>
          </div>

          <hr class="my-3">

          <!-- Search -->
          <div class="mb-4">
            <label class="form-label fw-bold">Cari Keyword</label>
            <input type="text" name="search" class="form-control" placeholder="Cari sumber atau catatan..." value="<?= esc($filters['search'] ?? '') ?>">
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
          <a href="<?= base_url('pemasukan') ?>" class="btn btn-ghost-secondary">Reset All</a>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> Apply Filters
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Table -->
<?php if (empty($items)): ?>
  <div class="card">
    <div class="card-body">
      <div class="empty-state">
        <i class="ti ti-moneybag-plus"></i>
        <p>Belum ada data pemasukan</p>
        <a href="<?= base_url('pemasukan/create') ?>" class="btn btn-primary btn-sm">+ Tambah Pemasukan</a>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="card" id="pemasukan-table-card" data-pjax-container>
    <div class="table-responsive">
      <table class="table table-vcenter card-table">
        <thead>
          <tr>
            <th style="width:50px">No</th>
            <th>
              <a href="<?= base_url('pemasukan?' . $qs) ?>" class="text-decoration-none text-reset d-inline-flex align-items-center">
                Tanggal <i class="ti <?= $sortIcon ?> ms-1"></i>
              </a>
            </th>
            <th>Sumber</th>
            <th>Catatan</th>
            <th class="text-end">Nominal</th>
            <th class="text-center" style="width:100px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1 + (($pager->getCurrentPage() - 1) * 10); ?>
          <?php foreach ($items as $item): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= date('d M Y', strtotime($item['tanggal'])) ?></td>
              <td><?= esc($item['sumber']) ?></td>
              <td class="text-muted"><?= esc($item['catatan'] ?? '-') ?></td>
              <td class="text-end text-income">+Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
              <td>
                <div class="d-flex justify-content-center" style="gap: 12px;">
                  <a href="<?= base_url('pemasukan/edit/' . $item['id']) ?>" class="btn btn-sm btn-ghost-secondary" title="Edit"><i class="ti ti-edit"></i></a>
                  <a href="<?= base_url('pemasukan/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')" title="Hapus"><i class="ti ti-trash"></i></a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex align-items-center"><?= $pager->links('default', 'tabler_pagination') ?></div>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>