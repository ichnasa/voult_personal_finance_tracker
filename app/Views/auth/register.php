<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
  <a href="<?= base_url('/') ?>" class="navbar-brand navbar-brand-autodark">
    <span class="fs-1 fw-bold">FinTrack</span>
  </a>
</div>

<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Buat akun baru</h2>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
          <div><?= esc($err) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/processRegister') ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-user"></i></span>
          <input type="text" name="name" class="form-control" placeholder="Nama lengkap" value="<?= old('name') ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-mail"></i></span>
          <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= old('email') ?>" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Min 6 karakter" required>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-lock"></i></span>
          <input type="password" name="password_confirm" class="form-control" placeholder="Ulangi password" required>
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Daftar</button>
      </div>
    </form>
  </div>
</div>

<div class="text-center text-secondary mt-3">
  Sudah punya akun? <a href="<?= base_url('auth/login') ?>" tabindex="-1">Masuk</a>
</div>

<?= $this->endSection() ?>
