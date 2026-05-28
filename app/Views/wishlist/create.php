<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row"><div class="col-md-8">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Tambah Wishlist</h3></div>
    <form action="<?= base_url('wishlist/store') ?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger"><?php foreach (session()->getFlashdata('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?></div>
        <?php endif; ?>
        <div class="mb-3"><label>Nama Barang</label>
          <input type="text" name="nama_barang" class="form-control" placeholder="Nama barang impian..." value="<?= old('nama_barang') ?>" required>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="mb-3"><label>Harga Target (Rp)</label>
            <input type="number" name="harga_target" class="form-control" placeholder="0" value="<?= old('harga_target') ?>" min="1" required>
          </div></div>
          <div class="col-md-6"><div class="mb-3"><label>Prioritas</label>
            <select name="prioritas" class="form-control" required>
              <option value="rendah" <?= old('prioritas') === 'rendah' ? 'selected' : '' ?>>Rendah</option>
              <option value="sedang" <?= old('prioritas', 'sedang') === 'sedang' ? 'selected' : '' ?>>Sedang</option>
              <option value="tinggi" <?= old('prioritas') === 'tinggi' ? 'selected' : '' ?>>Tinggi</option>
            </select>
          </div></div>
        </div>
        <div class="mb-3"><label>Catatan <small class="text-muted">(opsional)</small></label>
          <textarea name="catatan" class="form-control" rows="3"><?= old('catatan') ?></textarea>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
        <a href="<?= base_url('wishlist') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div></div>
<?= $this->endSection() ?>
