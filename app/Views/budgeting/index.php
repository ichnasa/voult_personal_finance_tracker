<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between flex-wrap">
    <form method="get" class="d-flex gap-2" id="filter-form">
      <select name="bulan" class="form-select" style="width: 130px;" onchange="this.form.submit()">
        <?php for ($m = 1; $m <= 12; $m++): ?>
          <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
          </option>
        <?php endfor; ?>
      </select>
      <input type="number" name="tahun" class="form-control" style="width: 90px;" value="<?= $tahun ?>"
        onchange="this.form.submit()">
      <?php if (isset($_GET['bulan']) || isset($_GET['tahun'])): ?>
        <button type="button" onclick="window.location.href='<?= base_url('budgeting') ?>'"
          class="btn btn-outline-danger" title="Reset Filter">
          <i class="ti ti-filter-off me-1"></i> Reset Filter
        </button>
      <?php endif; ?>
    </form>
    <a href="<?= base_url('budgeting/create') ?>" class="btn btn-primary"><i class="ti ti-plus me-1"></i> Tambah
      Budget</a>
  </div>
</div>

<?php if (empty($budgets)): ?>
  <div class="card">
    <div class="card-body">
      <div class="empty-state">
        <i class="ti ti-wallet"></i>
        <p>Belum ada budget untuk bulan ini</p>
        <a href="<?= base_url('budgeting/create') ?>" class="btn btn-primary btn-sm">+ Tambah Budget</a>
      </div>
    </div>
  </div>
<?php else: ?>
  <div class="row g-2">
    <?php foreach ($budgets as $b): ?>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?= esc($b['kategori_name']) ?></h3>
            <div class="card-actions d-flex align-items-center" style="gap: 12px;">
              <a href="<?= base_url('budgeting/edit/' . $b['id']) ?>" class="btn btn-ghost-secondary btn-sm"><i
                  class="ti ti-edit"></i></a>
              <a href="<?= base_url('budgeting/delete/' . $b['id']) ?>" class="btn btn-ghost-secondary btn-sm text-danger"
                onclick="return confirm('Hapus budget ini?')"><i class="ti ti-trash"></i></a>
            </div>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Terpakai</span>
              <strong>Rp <?= number_format($b['spent'], 0, ',', '.') ?> / Rp
                <?= number_format($b['nominal_budget'], 0, ',', '.') ?></strong>
            </div>
            <?php
            $barColor = $b['percent'] > 100 ? 'bg-danger' : ($b['percent'] > 80 ? 'bg-warning' : 'bg-success');
            $badgeClass = $b['percent'] > 100 ? 'bg-danger text-white' : ($b['percent'] > 80 ? 'bg-warning text-white' : 'bg-success text-white');
            $badgeLabel = $b['percent'] > 100 ? 'Over Budget' : ($b['percent'] > 80 ? 'Warning' : 'Normal');
            ?>
            <div class="progress mb-2" style="height: 10px;">
              <div class="progress-bar <?= $barColor ?>" style="width: <?= min($b['percent'], 100) ?>%"></div>
            </div>
            <div class="d-flex justify-content-between">
              <span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span>
              <span class="text-muted"><?= $b['percent'] ?>%</span>
            </div>
            <div class="mt-2 text-sm">
              Sisa: <strong class="<?= $b['remaining'] < 0 ? 'text-danger' : 'text-success' ?>">
                Rp <?= number_format(abs($b['remaining']), 0, ',', '.') ?><?= $b['remaining'] < 0 ? ' (lebih)' : '' ?>
              </strong>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>