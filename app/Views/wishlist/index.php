<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between">
    <div></div>
    <a href="<?= base_url('wishlist/create') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Wishlist</a>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="card"><div class="card-body">
    <div class="empty-state">
      <i class="fas fa-star"></i>
      <p>Belum ada wishlist</p>
      <a href="<?= base_url('wishlist/create') ?>" class="btn btn-primary btn-sm">+ Tambah Wishlist</a>
    </div>
  </div></div>
<?php else: ?>
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped">
        <thead><tr>
          <th style="width:50px">No</th><th>Nama Barang</th><th class="text-right">Harga Target</th>
          <th class="text-center">Prioritas</th><th class="text-center">Status</th><th>Catatan</th><th class="text-center" style="width:100px">Aksi</th>
        </tr></thead>
        <tbody>
          <?php $no = 1 + (($pager->getCurrentPage() - 1) * 10); ?>
          <?php foreach ($items as $item): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><strong><?= esc($item['nama_barang']) ?></strong></td>
            <td class="text-right">Rp <?= number_format($item['harga_target'], 0, ',', '.') ?></td>
            <td class="text-center">
              <?php
                $pClass = match($item['prioritas']) { 'tinggi' => 'badge-danger', 'sedang' => 'badge-warning', default => 'badge-secondary' };
              ?>
              <span class="badge <?= $pClass ?>"><?= ucfirst($item['prioritas']) ?></span>
            </td>
            <td class="text-center">
              <?php
                $sClass = match($item['status']) { 'tercapai' => 'badge-success', 'menabung' => 'badge-warning', default => 'badge-secondary' };
                $sLabel = str_replace('_', ' ', ucfirst($item['status']));
              ?>
              <span class="badge <?= $sClass ?>"><?= $sLabel ?></span>
            </td>
            <td class="text-muted"><?= esc($item['catatan'] ?? '-') ?></td>
            <td class="text-center">
              <a href="<?= base_url('wishlist/edit/' . $item['id']) ?>" class="btn btn-sm btn-default"><i class="fas fa-edit"></i></a>
              <a href="<?= base_url('wishlist/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus wishlist ini?')"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer clearfix"><?= $pager->links('default', 'default_full') ?></div>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
