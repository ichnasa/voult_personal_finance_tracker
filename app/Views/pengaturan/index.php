<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Card: Kategori Pengeluaran -->
<div class="card mb-3">
  <div class="card-header">
    <h3 class="card-title"><i class="ti ti-category me-2"></i>Kategori Pengeluaran</h3>
    <div class="card-actions">
      <span class="badge bg-primary text-white"><?= count($kategoriList) ?> kategori</span>
    </div>
  </div>
  <div class="card-body">
    <p class="text-secondary mb-3">
      Kelola kategori yang digunakan untuk mengelompokkan pengeluaran dan budget Anda.
      Kategori yang masih digunakan di pengeluaran atau budgeting tidak bisa dihapus.
    </p>

    <!-- Form Tambah Kategori (inline) -->
    <form action="<?= base_url('pengaturan/kategori/store') ?>" method="post">
      <?= csrf_field() ?>
      <div class="row g-2 align-items-end mb-3">
        <div class="col">
          <label class="form-label" for="kategori_name">Tambah Kategori Baru</label>
          <input type="text" class="form-control" id="kategori_name" name="name"
            placeholder="Contoh: Makanan, Transportasi..." value="<?= old('name') ?>" required maxlength="100">
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i>Tambah
          </button>
        </div>
      </div>
    </form>

    <hr class="my-3">

    <!-- Daftar Kategori -->
    <?php if (empty($kategoriList)): ?>
      <div class="empty py-4">
        <div class="empty-icon"><i class="ti ti-category" style="font-size: 3rem;"></i></div>
        <p class="empty-title">Belum ada kategori</p>
        <p class="empty-subtitle text-secondary">
          Tambahkan kategori pertama Anda menggunakan form di atas.
        </p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-vcenter">
          <thead>
            <tr>
              <th style="width: 50px">No</th>
              <th>Nama Kategori</th>
              <th style="width: 200px" class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($kategoriList as $i => $kat): ?>
              <tr>
                <td class="text-secondary"><?= $i + 1 ?></td>
                <td>
                  <i class="ti ti-tag me-1 text-muted"></i>
                  <?= esc($kat['name']) ?>
                </td>
                <td class="text-end">
                  <a href="#editKategori<?= $kat['id'] ?>" class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal">
                    <i class="ti ti-edit me-1"></i>Edit
                  </a>
                  <a href="#hapusKategori<?= $kat['id'] ?>" class="btn btn-sm btn-outline-danger ms-1"
                    data-bs-toggle="modal">
                    <i class="ti ti-trash me-1"></i>Hapus
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- ==================== MODALS ==================== -->

<?php foreach ($kategoriList as $kat): ?>

  <!-- Modal: Edit Kategori -->
  <div class="modal modal-blur fade" id="editKategori<?= $kat['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Edit Kategori</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('pengaturan/kategori/update/' . $kat['id']) ?>" method="post">
          <?= csrf_field() ?>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label" for="edit_name_<?= $kat['id'] ?>">Nama Kategori</label>
              <input type="text" class="form-control" id="edit_name_<?= $kat['id'] ?>" name="name"
                value="<?= esc($kat['name']) ?>" required maxlength="100">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy me-1"></i>Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal: Hapus Kategori -->
  <div class="modal modal-blur fade" id="hapusKategori<?= $kat['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-status bg-danger"></div>
        <div class="modal-body text-center py-4">
          <i class="ti ti-alert-triangle mb-2 text-danger" style="font-size: 3rem;"></i>
          <h3 class="mb-1">Hapus Kategori?</h3>
          <div class="text-secondary">
            Apakah Anda yakin ingin menghapus kategori <strong>"<?= esc($kat['name']) ?>"</strong>?
            Tindakan ini tidak bisa dibatalkan.
          </div>
        </div>
        <div class="modal-footer">
          <div class="w-100">
            <div class="row">
              <div class="col">
                <button type="button" class="btn w-100" data-bs-dismiss="modal">Batal</button>
              </div>
              <div class="col">
                <a href="<?= base_url('pengaturan/kategori/delete/' . $kat['id']) ?>" class="btn btn-danger w-100">
                  <i class="ti ti-trash me-1"></i>Hapus
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php endforeach; ?>

<?= $this->endSection() ?>