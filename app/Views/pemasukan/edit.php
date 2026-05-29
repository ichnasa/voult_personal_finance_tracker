<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Edit Pemasukan</h3>
      </div>
      <form action="<?= base_url('pemasukan/update/' . $item['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">
          <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
              <?php foreach (session()->getFlashdata('errors') as $err): ?>
                <div><?= esc($err) ?></div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label for="tanggal">Tanggal</label>
            <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= old('tanggal') ?: $item['tanggal'] ?>" required>
          </div>
          <div class="mb-3">
            <label for="nominal">Nominal (Rp)</label>
            <input type="number" id="nominal" name="nominal" class="form-control" value="<?= old('nominal') ?: $item['nominal'] ?>" min="1" step="1" required>
          </div>
          <div class="mb-3">
            <label for="sumber">Sumber Pemasukan</label>
            <input type="text" id="sumber" name="sumber" class="form-control" value="<?= old('sumber') ?: esc($item['sumber']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="deskripsi">Deskripsi <small class="text-muted">(opsional)</small></label>
            <textarea id="deskripsi" name="deskripsi" class="form-control" rows="3"><?= old('deskripsi') ?: esc($item['deskripsi'] ?? '') ?></textarea>
          </div>
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Perbarui</button>
          <a href="<?= base_url('pemasukan') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
