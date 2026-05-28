<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tambah Pengeluaran</h3>
      </div>
      <form action="<?= base_url('pengeluaran/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="card-body">
          <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
              <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div><?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control"
                  value="<?= old('tanggal') ?? date('Y-m-d') ?>" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="nominal" class="form-label">Nominal (Rp)</label>
                <input type="number" id="nominal" name="nominal" class="form-control" placeholder="0"
                  value="<?= old('nominal') ?>" min="1" step="1" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="mb-3">
                <label for="kategori_id" class="form-label">Kategori</label>
                <select id="kategori_id" name="kategori_id" class="form-control" required>
                  <option value="">Pilih Kategori</option>
                  <?php foreach ($kategoriList as $id => $name): ?>
                    <option value="<?= $id ?>" <?= old('kategori_id') == $id ? 'selected' : '' ?>><?= esc($name) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                <select id="metode_pembayaran" name="metode_pembayaran" class="form-control">
                  <option value="">Pilih Metode</option>
                  <?php foreach (['Cash', 'Transfer', 'E-Wallet', 'Debit', 'Kredit'] as $m): ?>
                    <option value="<?= $m ?>" <?= old('metode_pembayaran') === $m ? 'selected' : '' ?>><?= $m ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="catatan" class="form-label">Catatan <small class="text-muted">(opsional)</small></label>
            <textarea id="catatan" name="catatan" class="form-control" rows="3"
              placeholder="Catatan tambahan..."><?= old('catatan') ?></textarea>
          </div>
          <div class="mb-3">
            <label for="nota" class="form-label">Upload Nota <small class="text-muted">(opsional)</small></label>
            <input type="file" id="nota" name="nota" class="form-control" accept="image/*,.pdf">
            <small class="form-text text-muted fs-6">Format: JPG, PNG, PDF. Maks 2MB.</small>
          </div>
        </div>
        <div class="card-footer text-end">
          <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
          <a href="<?= base_url('pengeluaran') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?= $this->endSection() ?>