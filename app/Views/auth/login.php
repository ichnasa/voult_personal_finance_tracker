<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
  <a href="<?= base_url('/') ?>" class="navbar-brand navbar-brand-autodark">
    <span class="fs-1 fw-bold">PLOOM</span>
  </a>
</div>

<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Masuk ke akun Anda</h2>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
          <div><?= esc($err) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/processLogin') ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-mail"></i></span>
          <input type="email" name="email" class="form-control" placeholder="email@contoh.com"
            value="<?= old('email') ?>" required autocomplete="off">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-lock"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Password" required
            autocomplete="off">
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
      </div>
    </form>

    <!-- Divider -->
    <div class="hr-text my-4">atau</div>

    <!-- Google OAuth Button -->
    <a href="<?= base_url('auth/google') ?>" class="btn w-100" style="background: #fff; border: 1px solid #dadce0; color: #3c4043; font-weight: 500;">
      <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
      </svg>
      Masuk dengan Google
    </a>
  </div>
</div>

<div class="text-center text-secondary mt-3">
  Belum punya akun? <a href="<?= base_url('auth/register') ?>" tabindex="-1">Daftar</a>
</div>

<?= $this->endSection() ?>