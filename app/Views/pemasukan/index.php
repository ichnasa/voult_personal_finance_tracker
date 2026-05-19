<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between align-items-center">
    <div></div>
    <a href="<?= base_url('pemasukan/create') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Pemasukan</a>
  </div>
</div>

<!-- Filter -->
<div class="card">
  <div class="card-body">
    <form method="get" action="<?= base_url('pemasukan') ?>">
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label>Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label>Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label>Cari</label>
            <input type="text" name="search" class="form-control" placeholder="Cari sumber atau catatan..." value="<?= esc($filters['search'] ?? '') ?>">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <div class="form-group w-100">
            <button type="submit" class="btn btn-default mr-1"><i class="fas fa-search"></i> Filter</button>
            <a href="<?= base_url('pemasukan') ?>" class="btn btn-default">Reset</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Table -->
<?php if (empty($items)): ?>
  <div class="card"><div class="card-body">
    <div class="empty-state">
      <i class="fas fa-arrow-circle-down"></i>
      <p>Belum ada data pemasukan</p>
      <a href="<?= base_url('pemasukan/create') ?>" class="btn btn-primary btn-sm">+ Tambah Pemasukan</a>
    </div>
  </div></div>
<?php else: ?>
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped">
        <thead>
          <tr>
            <th style="width:50px">No</th>
            <th>Tanggal</th>
            <th>Sumber</th>
            <th>Catatan</th>
            <th class="text-right">Nominal</th>
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
            <td class="text-right text-income">+Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
            <td class="text-center">
              <a href="<?= base_url('pemasukan/edit/' . $item['id']) ?>" class="btn btn-sm btn-default" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="<?= base_url('pemasukan/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer clearfix">
      <?= $pager->links('default', 'default_full') ?>
    </div>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>
