<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between">
    <div></div>
    <a href="<?= base_url('tabungan/create') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Target</a>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="card"><div class="card-body">
    <div class="empty-state">
      <i class="fas fa-piggy-bank"></i>
      <p>Belum ada target tabungan</p>
      <a href="<?= base_url('tabungan/create') ?>" class="btn btn-primary btn-sm">+ Buat Target</a>
    </div>
  </div></div>
<?php else: ?>
  <div class="row">
    <?php foreach ($items as $item): ?>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <?= esc($item['nama_tabungan']) ?>
            <?php if (!empty($item['wishlist_name'])): ?>
              <small class="text-muted ml-1"><i class="fas fa-star"></i> <?= esc($item['wishlist_name']) ?></small>
            <?php endif; ?>
          </h3>
          <div class="card-tools">
            <?php if ($item['status'] === 'tercapai'): ?>
              <span class="badge badge-success">Tercapai</span>
            <?php else: ?>
              <span class="badge badge-warning">Proses</span>
            <?php endif; ?>
            <a href="<?= base_url('tabungan/edit/' . $item['id']) ?>" class="btn btn-tool"><i class="fas fa-edit"></i></a>
            <a href="<?= base_url('tabungan/delete/' . $item['id']) ?>" class="btn btn-tool text-danger" onclick="return confirm('Hapus tabungan ini?')"><i class="fas fa-trash"></i></a>
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between mb-2">
            <h4 class="text-primary mb-0">Rp <?= number_format($item['nominal_terkumpul'], 0, ',', '.') ?></h4>
            <span class="text-muted">/ Rp <?= number_format($item['target_nominal'], 0, ',', '.') ?></span>
          </div>
          <div class="progress mb-2" style="height: 10px;">
            <div class="progress-bar bg-primary" style="width: <?= min($item['progress'], 100) ?>%"></div>
          </div>
          <div class="d-flex justify-content-between text-sm">
            <span>Progress</span>
            <strong><?= $item['progress'] ?>%</strong>
          </div>
          <?php if (!empty($item['deadline'])): ?>
            <?php
              $daysLeft = (int) ((strtotime($item['deadline']) - time()) / 86400);
              $deadlineClass = $daysLeft < 0 ? 'text-danger' : ($daysLeft < 30 ? 'text-warning' : 'text-muted');
            ?>
            <div class="mt-2 text-sm <?= $deadlineClass ?>">
              <i class="fas fa-calendar-alt mr-1"></i>
              Deadline: <?= date('d M Y', strtotime($item['deadline'])) ?>
              <?php if ($daysLeft >= 0): ?>(<?= $daysLeft ?> hari lagi)<?php else: ?>(terlambat <?= abs($daysLeft) ?> hari)<?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="d-flex justify-content-center"><?= $pager->links('default', 'default_full') ?></div>
<?php endif; ?>

<?= $this->endSection() ?>
