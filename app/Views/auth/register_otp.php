<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<?php $registerData = session()->get('temp_register_data'); ?>

<div class="text-center mb-4">
  <a href="<?= base_url('/') ?>" class="navbar-brand navbar-brand-autodark">
    <span class="fs-1 fw-bold">PLOOM</span>
  </a>
</div>

<div class="card card-md">
  <div class="card-body">
    <h2 class="h2 text-center mb-2">Verifikasi Email</h2>
    <p class="text-center text-secondary mb-4">
      Kode verifikasi 6 digit telah dikirimkan ke<br>
      <strong><?= esc($registerData['email'] ?? '') ?></strong>
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

    <form action="<?= base_url('auth/processRegisterOtp') ?>" method="post" autocomplete="off">
      <?= csrf_field() ?>

      <div class="mb-3">
        <label class="form-label">Kode Verifikasi</label>
        <div class="input-group input-group-flat">
          <input
            type="text"
            name="otp_code"
            id="otp_code"
            class="form-control"
            placeholder="000000"
            pattern="\d{6}"
            maxlength="6"
            required
            autocomplete="off"
            autofocus
            style="text-align: center; font-size: 24px; letter-spacing: 4px;"
          >
        </div>
      </div>

      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Verifikasi &amp; Buat Akun</button>
      </div>
    </form>

    <div class="text-center mt-3">
      <a href="<?= base_url('auth/resendRegisterOtp') ?>" class="text-secondary">
        Kirim Ulang Kode Verifikasi
      </a>
    </div>
  </div>
</div>

<div class="text-center text-secondary mt-3">
  Batal? <a href="<?= base_url('auth/register') ?>" tabindex="-1">Kembali ke Daftar</a>
</div>

<?= $this->endSection() ?>
