<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="row"><div class="col-md-8">
  <div class="card">
    <div class="card-header"><h3 class="card-title">Tambah Budget</h3></div>
    <form action="<?= base_url('budgeting/store') ?>" method="post">
      <?= csrf_field() ?>
      <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger"><?php foreach (session()->getFlashdata('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?></div>
        <?php endif; ?>
        <div class="row">
          <div class="col-md-6"><div class="mb-3"><label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
              <option value="">Pilih Kategori</option>
              <?php foreach ($kategoriList as $id => $name): ?>
                <option value="<?= $id ?>" <?= old('kategori_id') == $id ? 'selected' : '' ?>><?= esc($name) ?></option>
              <?php endforeach; ?>
            </select>
          </div></div>
          <div class="col-md-6"><div class="mb-3"><label>Nominal Budget (Rp)</label>
            <input type="number" name="nominal_budget" class="form-control" placeholder="0" value="<?= old('nominal_budget') ?>" min="1" required>
          </div></div>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="mb-3"><label>Bulan</label>
            <select name="bulan" class="form-control" required>
              <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= (old('bulan') ?: date('m')) == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                  <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                </option>
              <?php endfor; ?>
            </select>
          </div></div>
          <div class="col-md-6"><div class="mb-3"><label>Tahun</label>
            <input type="number" name="tahun" class="form-control" value="<?= old('tahun') ?: date('Y') ?>" required>
          </div></div>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i> Simpan</button>
        <a href="<?= base_url('budgeting') ?>" class="btn btn-ghost-secondary ms-2">Batal</a>
      </div>
    </form>
  </div>
</div></div>
<?= $this->endSection() ?>
