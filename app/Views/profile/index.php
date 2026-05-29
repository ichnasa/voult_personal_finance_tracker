<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Profile Header: Avatar + Info + Actions -->
<div class="row mb-3">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-auto">
            <?php if (!empty($user['avatar'])): ?>
              <span class="avatar avatar-xl rounded"
                style="background-image: url(<?= base_url('assets/uploads/avatars/' . esc($user['avatar'])) ?>)">
              </span>
            <?php else: ?>
              <span class="avatar avatar-xl rounded bg-primary-lt fs-1">
                <?= strtoupper(substr(esc($user['name']), 0, 1)) ?>
              </span>
            <?php endif; ?>
          </div>
          <div class="col">
            <h2 class="mb-1"><?= esc($user['name']) ?></h2>
            <div class="text-secondary">
              <i class="ti ti-mail me-1"></i><?= esc($user['email']) ?>
            </div>
            <div class="text-muted mt-1">
              <i class="ti ti-calendar me-1"></i>Member sejak <?= date('d M Y', strtotime($user['created_at'])) ?>
            </div>
          </div>
          <div class="col-auto">
            <a href="#editProfil" class="btn btn-primary" data-bs-toggle="modal">
              <i class="ti ti-edit me-1"></i>Edit Profile
            </a>
            <?php if (empty($user['google_id'])): ?>
              <a href="#ubahPassword" class="btn btn-outline-secondary ms-1" data-bs-toggle="modal">
                <i class="ti ti-key me-1"></i>Ubah Password
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">

  <!-- Kolom Kiri: Avatar Upload + Ringkasan -->
  <div class="col-lg-4">

    <!-- Card Upload Avatar -->
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-camera me-2"></i>Foto Profil</h3>
      </div>
      <div class="card-body text-center">
        <div class="mb-3">
          <?php if (!empty($user['avatar'])): ?>
            <span class="avatar avatar-xl rounded"
              style="background-image: url(<?= base_url('assets/uploads/avatars/' . esc($user['avatar'])) ?>)">
            </span>
          <?php else: ?>
            <span class="avatar avatar-xl rounded bg-primary-lt fs-1">
              <?= strtoupper(substr(esc($user['name']), 0, 1)) ?>
            </span>
          <?php endif; ?>
        </div>
        <form action="<?= base_url('profile/updateAvatar') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="mb-2">
            <input type="file" class="form-control form-control-sm" id="avatar" name="avatar" accept="image/*">
          </div>
          <button type="submit" class="btn btn-outline-primary btn-sm w-100">
            <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan Foto
          </button>
          <small class="text-muted d-block mt-2">Format: JPG, JPEG, PNG. Maks: 2MB.</small>
        </form>
      </div>
    </div>

    <!-- Card Ringkasan Akun -->
    <div class="card mb-3">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-info-circle me-2"></i>Ringkasan Akun</h3>
      </div>
      <div class="card-body">
        <div class="datagrid">
          <div class="datagrid-item">
            <div class="datagrid-title">Member Sejak</div>
            <div class="datagrid-content">
              <?= date('d M Y', strtotime($user['created_at'])) ?>
            </div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Total Transaksi</div>
            <div class="datagrid-content">
              <span class="badge bg-primary text-white"><?= number_format($total_transaksi) ?> transaksi</span>
            </div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Pemasukan Tercatat</div>
            <div class="datagrid-content">
              <span class="badge bg-success  text-white"><?= number_format($total_pemasukan) ?> data</span>
            </div>
          </div>
          <div class="datagrid-item">
            <div class="datagrid-title">Pengeluaran Tercatat</div>
            <div class="datagrid-content">
              <span class="badge bg-danger  text-white""><?= number_format($total_pengeluaran) ?> data</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Kolom Kanan: Form Edit + Ubah Password -->
  <div class=" col-lg-8">

                <!-- Validation Errors -->
                <?php if (session()->getFlashdata('errors')): ?>
                  <div class="alert alert-danger alert-dismissible" role="alert">
                    <div class="d-flex">
                      <div><i class="ti ti-alert-circle alert-icon"></i></div>
                      <div>
                        <ul class="mb-0">
                          <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                  </div>
                <?php endif; ?>

                <!-- Card Edit Profil -->
                <div class="card mb-3">
                  <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-user-edit me-2"></i>Edit Profil</h3>
                  </div>
                  <form action="<?= base_url('profile/update') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label" for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name"
                              value="<?= esc(old('name', $user['name'])) ?>" required>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                              value="<?= esc(old('email', $user['email'])) ?>" readonly disabled>
                            <small class="form-hint mt-1 text-muted"><i class="ti ti-lock me-1"></i>Email tidak dapat
                              diubah setelah terdaftar.</small>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer text-end">
                      <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                      </button>
                    </div>
                  </form>
                </div>

                <!-- Card Ubah Password -->
                <div class="card mb-3">
                  <div class="card-header">
                    <h3 class="card-title"><i class="ti ti-lock me-2"></i>Ubah Password</h3>
                  </div>
                  <?php if (!empty($user['google_id'])): ?>
                    <div class="card-body">
                      <div class="alert alert-info mb-0">
                        <div class="d-flex align-items-center">
                          <i class="ti ti-brand-google fs-2 me-2"></i>
                          <div class="mb-0">
                            Akun ini terhubung dengan Google. Anda tidak dapat mengubah password.
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php else: ?>
                    <form action="<?= base_url('profile/updatePassword') ?>" method="post">
                      <?= csrf_field() ?>
                      <div class="card-body">
                        <div class="mb-3">
                          <label class="form-label" for="current_password">Password Lama</label>
                          <input type="password" class="form-control" id="current_password" name="current_password"
                            placeholder="Masukkan password lama" required>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="form-label" for="new_password">Password Baru</label>
                              <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Minimal 8 karakter" required>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="mb-3">
                              <label class="form-label" for="confirm_password">Konfirmasi Password Baru</label>
                              <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="Ulangi password baru" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-footer text-end">
                        <button type="submit" class="btn btn-warning">
                          <i class="ti ti-key me-1"></i>Ubah Password
                        </button>
                      </div>
                    </form>
                  <?php endif; ?>
                </div>

            </div>

          </div>

          <!-- ==================== MODALS ==================== -->

          <!-- Modal: Edit Profil (quick access from header) -->
          <div class="modal modal-blur fade" id="editProfil" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"><i class="ti ti-user-edit me-2"></i>Edit Profil</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('profile/update') ?>" method="post">
                  <?= csrf_field() ?>
                  <div class="modal-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label" for="modal_name">Nama Lengkap</label>
                          <input type="text" class="form-control" id="modal_name" name="name"
                            value="<?= esc($user['name']) ?>" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="mb-3">
                          <label class="form-label" for="modal_email">Email</label>
                          <input type="email" class="form-control" id="modal_email" name="email"
                            value="<?= esc($user['email']) ?>" readonly disabled>
                          <small class="form-hint mt-1 text-muted"><i class="ti ti-lock me-1"></i>Email tidak dapat
                            diubah.</small>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                      <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <!-- Modal: Ubah Password (quick access from header) -->
          <?php if (empty($user['google_id'])): ?>
            <div class="modal modal-blur fade" id="ubahPassword" tabindex="-1">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"><i class="ti ti-lock me-2"></i>Ubah Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <form action="<?= base_url('profile/updatePassword') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label" for="modal_current_password">Password Lama</label>
                        <input type="password" class="form-control" id="modal_current_password" name="current_password"
                          placeholder="Masukkan password lama" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="modal_new_password">Password Baru</label>
                        <input type="password" class="form-control" id="modal_new_password" name="new_password"
                          placeholder="Minimal 8 karakter" required>
                      </div>
                      <div class="mb-3">
                        <label class="form-label" for="modal_confirm_password">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="modal_confirm_password" name="confirm_password"
                          placeholder="Ulangi password baru" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-warning">
                        <i class="ti ti-key me-1"></i>Ubah Password
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?= $this->endSection() ?>