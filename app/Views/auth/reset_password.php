<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
  <a href="<?= base_url('/') ?>" class="navbar-brand navbar-brand-autodark">
    <span class="fs-1 fw-bold">PLOOM</span>
  </a>
</div>

<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Buat Password Baru</h2>
    <p class="text-center text-secondary mb-4">
      OTP telah terverifikasi. Silakan masukkan password baru untuk akun Anda.
    </p>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
          <div><?= esc($err) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/processResetPassword') ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required autocomplete="new-password">
        </div>
      </div>
      
      <div class="mb-3">
        <label class="form-label">Konfirmasi Password Baru</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-lock-check"></i></span>
          <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password baru" required autocomplete="new-password">
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Simpan Password Baru</button>
      </div>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
