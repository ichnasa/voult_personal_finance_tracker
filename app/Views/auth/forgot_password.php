<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
  <a href="<?= base_url('/') ?>" class="navbar-brand navbar-brand-autodark">
    <span class="fs-1 fw-bold">PLOOM</span>
  </a>
</div>

<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Lupa Password</h2>
    <p class="text-center text-secondary mb-4">
      Masukkan alamat email Anda dan kami akan mengirimkan instruksi kode OTP untuk mengatur ulang password Anda.
    </p>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/processForgotPassword') ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group input-group-flat">
          <span class="input-group-text"><i class="ti ti-mail"></i></span>
          <input type="email" name="email" class="form-control" placeholder="Masukkan email Anda" required autocomplete="off">
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Kirim Kode OTP</button>
      </div>
    </form>
  </div>
</div>

<div class="text-center text-secondary mt-3">
  Ingat password Anda? <a href="<?= base_url('auth/login') ?>" tabindex="-1">Kembali ke layar login</a>
</div>

<?= $this->endSection() ?>
