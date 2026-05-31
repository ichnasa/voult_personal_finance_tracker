<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
  <a href="<?= base_url('/') ?>" class="navbar-brand navbar-brand-autodark">
    <span class="fs-1 fw-bold">PLOOM</span>
  </a>
</div>

<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-4">Verifikasi OTP</h2>
    <p class="text-center text-secondary mb-4">
      Masukkan 6 digit kode OTP yang telah dikirimkan ke email Anda.
    </p>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger">
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success">
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
    <?php endif; ?>

    <form action="<?= base_url('auth/processOtp') ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Kode OTP</label>
        <div class="input-group input-group-flat">
          <input type="text" name="otp_code" class="form-control" placeholder="000000" 
            pattern="\d{6}" maxlength="6" required autocomplete="off" style="text-align: center; font-size: 24px; letter-spacing: 4px;">
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Verifikasi</button>
      </div>
    </form>
    
    <div class="text-center mt-3">
        <a href="<?= base_url('auth/resendOtp') ?>" class="text-secondary">Kirim Ulang OTP</a>
    </div>
  </div>
</div>

<div class="text-center text-secondary mt-3">
  Kembali ke <a href="<?= base_url('auth/login') ?>" tabindex="-1">Login</a>
</div>

<?= $this->endSection() ?>
