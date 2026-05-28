<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row"><div class="col-md-8">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Edit Wishlist</h3></div>
    <form action="<?= base_url('wishlist/update/' . $item['id']) ?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger"><?php foreach (session()->getFlashdata('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?></div>
        <?php endif; ?>
        <div class="mb-3"><label>Nama Barang</label>
          <input type="text" name="nama_barang" class="form-control" value="<?= old('nama_barang') ?: esc($item['nama_barang']) ?>" required>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="mb-3"><label>Harga Target (Rp)</label>
            <input type="number" name="harga_target" class="form-control" value="<?= old('harga_target') ?: $item['harga_target'] ?>" min="1" required>
          </div></div>
          <div class="col-md-4"><div class="mb-3"><label>Prioritas</label>
            <select name="prioritas" class="form-control" required>
              <?php foreach (['rendah', 'sedang', 'tinggi'] as $p): ?>
                <option value="<?= $p ?>" <?= (old('prioritas') ?: $item['prioritas']) === $p ? 'selected' : '' ?>><?= ucfirst($p) ?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="mb-3"><label>Status</label>
            <select name="status" class="form-control" required>
              <?php foreach (['belum_mulai' => 'Belum Mulai', 'menabung' => 'Menabung', 'tercapai' => 'Tercapai'] as $k => $v): ?>
                <option value="<?= $k ?>" <?= (old('status') ?: $item['status']) === $k ? 'selected' : '' ?>><?= $v ?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
        </div>
        <div class="mb-3"><label>Catatan</label>
          <textarea name="catatan" class="form-control" rows="3"><?= old('catatan') ?: esc($item['catatan'] ?? '') ?></textarea>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Perbarui</button>
        <a href="<?= base_url('wishlist') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div></div>
<?= $this->endSection() ?>
