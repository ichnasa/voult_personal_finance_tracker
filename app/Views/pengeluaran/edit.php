<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row"><div class="col-md-8">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Edit Pengeluaran</h3></div>
    <form action="<?= base_url('pengeluaran/update/' . $item['id']) ?>" method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $err): ?><div><?= esc($err) ?></div><?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3"><label for="tanggal">Tanggal</label>
              <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= old('tanggal') ?: $item['tanggal'] ?>" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3"><label for="nominal">Nominal (Rp)</label>
              <input type="number" id="nominal" name="nominal" class="form-control" value="<?= old('nominal') ?: $item['nominal'] ?>" min="1" step="1" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3"><label for="kategori_id">Kategori</label>
              <select id="kategori_id" name="kategori_id" class="form-control" required>
                <option value="">Pilih Kategori</option>
                <?php foreach ($kategoriList as $id => $name): ?>
                  <option value="<?= $id ?>" <?= (old('kategori_id') ?: $item['kategori_id']) == $id ? 'selected' : '' ?>><?= esc($name) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3"><label for="metode_pembayaran">Metode Pembayaran</label>
              <select id="metode_pembayaran" name="metode_pembayaran" class="form-control">
                <option value="">Pilih Metode</option>
                <?php foreach (['Cash', 'Transfer', 'E-Wallet', 'Debit', 'Kredit'] as $m): ?>
                  <option value="<?= $m ?>" <?= (old('metode_pembayaran') ?: $item['metode_pembayaran']) === $m ? 'selected' : '' ?>><?= $m ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="mb-3"><label for="catatan">Catatan</label>
          <textarea id="catatan" name="catatan" class="form-control" rows="3"><?= old('catatan') ?: esc($item['catatan'] ?? '') ?></textarea>
        </div>
        <div class="mb-3"><label for="nota">Upload Nota Baru</label>
          <?php if (!empty($item['nota'])): ?>
            <div class="mb-2"><small>Nota saat ini: <a href="<?= base_url('assets/uploads/' . $item['nota']) ?>" target="_blank"><?= esc($item['nota']) ?></a></small></div>
          <?php endif; ?>
          <div>
            <input type="file" id="nota" name="nota" class="form-control" accept="image/*,.pdf">
            <label class="
          </div>
          <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah nota.</small>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Perbarui</button>
        <a href="<?= base_url('pengeluaran') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div></div>
<?= $this->endSection() ?>
