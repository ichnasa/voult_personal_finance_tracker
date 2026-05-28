<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row"><div class="col-md-8">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Tambah Target Tabungan</h3></div>
    <form action="<?= base_url('tabungan/store') ?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger"><?php foreach (session()->getFlashdata('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?></div>
        <?php endif; ?>
        <div class="mb-3"><label>Nama Tabungan</label>
          <input type="text" name="nama_tabungan" class="form-control" placeholder="Contoh: Dana Darurat, Laptop Baru..." value="<?= old('nama_tabungan') ?>" required>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="mb-3"><label>Target Nominal (Rp)</label>
            <input type="number" name="target_nominal" class="form-control" placeholder="0" value="<?= old('target_nominal') ?>" min="1" required>
          </div></div>
          <div class="col-md-6"><div class="mb-3"><label>Nominal Awal (Rp)</label>
            <input type="number" name="nominal_terkumpul" class="form-control" placeholder="0" value="<?= old('nominal_terkumpul') ?: '0' ?>" min="0">
          </div></div>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="mb-3"><label>Deadline <small class="text-muted">(opsional)</small></label>
            <input type="date" name="deadline" class="form-control" value="<?= old('deadline') ?>">
          </div></div>
          <div class="col-md-6"><div class="mb-3"><label>Hubungkan Wishlist <small class="text-muted">(opsional)</small></label>
            <select name="wishlist_id" class="form-control">
              <option value="">Tidak ada</option>
              <?php foreach ($wishlistItems as $w): ?>
                <option value="<?= $w['id'] ?>" <?= old('wishlist_id') == $w['id'] ? 'selected' : '' ?>>
                  <?= esc($w['nama_barang']) ?> (Rp <?= number_format($w['harga_target'], 0, ',', '.') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div></div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
        <a href="<?= base_url('tabungan') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div></div>
<?= $this->endSection() ?>
