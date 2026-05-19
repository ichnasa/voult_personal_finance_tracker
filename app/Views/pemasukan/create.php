<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Tambah Pemasukan</h3>
      </div>
      <form action="<?= base_url('pemasukan/store') ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
          <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
              <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= old('tanggal') ?: date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label for="nominal">Nominal (Rp)</label>
            <input type="number" id="nominal" name="nominal" class="form-control" placeholder="0" value="<?= old('nominal') ?>" min="1" step="1" required>
          </div>
          <div class="form-group">
            <label for="sumber">Sumber Pemasukan</label>
            <input type="text" id="sumber" name="sumber" class="form-control" placeholder="Contoh: Gaji, Freelance, Bonus..." value="<?= old('sumber') ?>" required>
          </div>
          <div class="form-group">
            <label for="catatan">Catatan <small class="text-muted">(opsional)</small></label>
            <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Catatan tambahan..."><?= old('catatan') ?></textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
          <a href="<?= base_url('pemasukan') ?>" class="btn btn-default ml-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
