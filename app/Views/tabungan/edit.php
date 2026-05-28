<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row"><div class="col-md-8">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Edit Tabungan</h3></div>
    <form action="<?= base_url('tabungan/update/' . $item['id']) ?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger"><?php foreach (session()->getFlashdata('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?></div>
        <?php endif; ?>
        <div class="mb-3"><label>Nama Tabungan</label>
          <input type="text" name="nama_tabungan" class="form-control" value="<?= old('nama_tabungan') ?: esc($item['nama_tabungan']) ?>" required>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="mb-3"><label>Target Nominal (Rp)</label>
            <input type="number" name="target_nominal" class="form-control" value="<?= old('target_nominal') ?: $item['target_nominal'] ?>" min="1" required>
          </div></div>
          <div class="col-md-4"><div class="mb-3"><label>Nominal Terkumpul (Rp)</label>
            <input type="number" name="nominal_terkumpul" class="form-control" value="<?= old('nominal_terkumpul') ?: $item['nominal_terkumpul'] ?>" min="0" required>
          </div></div>
          <div class="col-md-4"><div class="mb-3"><label>Status</label>
            <select name="status" class="form-control" required>
              <option value="proses" <?= (old('status') ?: $item['status']) === 'proses' ? 'selected' : '' ?>>Proses</option>
              <option value="tercapai" <?= (old('status') ?: $item['status']) === 'tercapai' ? 'selected' : '' ?>>Tercapai</option>
            </select>
          </div></div>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="mb-3"><label>Deadline</label>
            <input type="date" name="deadline" class="form-control" value="<?= old('deadline') ?: $item['deadline'] ?>">
          </div></div>
          <div class="col-md-6"><div class="mb-3"><label>Hubungkan Wishlist</label>
            <select name="wishlist_id" class="form-control">
              <option value="">Tidak ada</option>
              <?php foreach ($wishlistItems as $w): ?>
                <option value="<?= $w['id'] ?>" <?= (old('wishlist_id') ?: $item['wishlist_id']) == $w['id'] ? 'selected' : '' ?>>
                  <?= esc($w['nama_barang']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div></div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Perbarui</button>
        <a href="<?= base_url('tabungan') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div></div>
<?= $this->endSection() ?>
