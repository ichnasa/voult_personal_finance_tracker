<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row mb-3">
  <div class="col-12 d-flex justify-content-between">
    <div></div>
    <a href="<?= base_url('pengeluaran/create') ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Pengeluaran</a>
  </div>
</div>

<div class="card">
  <div class="card-body">
    <form method="get" action="<?= base_url('pengeluaran') ?>">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group"><label>Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control" value="<?= esc($filters['date_from'] ?? '') ?>">
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group"><label>Sampai Tanggal</label>
            <input type="date" name="date_to" class="form-control" value="<?= esc($filters['date_to'] ?? '') ?>">
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group"><label>Kategori</label>
            <select name="kategori_id" class="form-control">
              <option value="">Semua Kategori</option>
              <?php foreach ($kategoriList as $id => $name): ?>
                <option value="<?= $id ?>" <?= ($filters['kategori_id'] ?? '') == $id ? 'selected' : '' ?>><?= esc($name) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group"><label>Cari</label>
            <input type="text" name="search" class="form-control" placeholder="Cari..." value="<?= esc($filters['search'] ?? '') ?>">
          </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <div class="form-group">
            <button type="submit" class="btn btn-default mr-1"><i class="fas fa-search"></i> Filter</button>
            <a href="<?= base_url('pengeluaran') ?>" class="btn btn-default">Reset</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<?php if (empty($items)): ?>
  <div class="card"><div class="card-body">
    <div class="empty-state">
      <i class="fas fa-arrow-circle-up"></i>
      <p>Belum ada data pengeluaran</p>
      <a href="<?= base_url('pengeluaran/create') ?>" class="btn btn-primary btn-sm">+ Tambah Pengeluaran</a>
    </div>
  </div></div>
<?php else: ?>
  <div class="card">
    <div class="card-body p-0">
      <table class="table table-striped">
        <thead><tr>
          <th style="width:50px">No</th><th>Tanggal</th><th>Kategori</th><th>Metode</th><th>Catatan</th><th class="text-right">Nominal</th><th class="text-center" style="width:120px">Aksi</th>
        </tr></thead>
        <tbody>
          <?php $no = 1 + (($pager->getCurrentPage() - 1) * 10); ?>
          <?php foreach ($items as $item): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= date('d M Y', strtotime($item['tanggal'])) ?></td>
            <td><span class="badge badge-secondary"><?= esc($item['kategori_name'] ?? '-') ?></span></td>
            <td class="text-muted"><?= esc($item['metode_pembayaran'] ?? '-') ?></td>
            <td class="text-muted"><?= esc($item['catatan'] ?? '-') ?></td>
            <td class="text-right text-expense">-Rp <?= number_format($item['nominal'], 0, ',', '.') ?></td>
            <td class="text-center">
              <?php if (!empty($item['nota'])): ?>
                <a href="<?= base_url('assets/uploads/' . $item['nota']) ?>" target="_blank" class="btn btn-sm btn-default" title="Lihat Nota"><i class="fas fa-image"></i></a>
              <?php endif; ?>
              <a href="<?= base_url('pengeluaran/edit/' . $item['id']) ?>" class="btn btn-sm btn-default" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="<?= base_url('pengeluaran/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus data ini?')" title="Hapus"><i class="fas fa-trash"></i></a>
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
