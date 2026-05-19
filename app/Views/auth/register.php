<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="register-box">
  <div class="register-logo">
    <b>Fin</b>Track
  </div>
  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Buat akun baru</p>

      <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
          <?php foreach (session()->getFlashdata('errors') as $err): ?>
            <div><?= esc($err) ?></div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="<?= base_url('auth/processRegister') ?>" method="post">
        <?= csrf_field() ?>

        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="<?= old('name') ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-user"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password (min 6 karakter)" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input type="password" name="password_confirm" class="form-control" placeholder="Konfirmasi Password" required>
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Daftar</button>
      </form>

      <p class="mb-0 mt-3">
        <a href="<?= base_url('auth/login') ?>">Sudah punya akun? Masuk</a>
      </p>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
