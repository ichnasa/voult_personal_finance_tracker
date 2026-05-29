<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= esc($title ?? 'FinTrack') ?> — Keuangan Pribadi</title>

  <!-- Tabler CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

  <?= $this->renderSection('styles') ?>
</head>

<body class="layout-fluid">
  <div class="page">

    <!-- Sidebar -->
    <?= $this->include('layout/sidebar') ?>

    <!-- Navbar Header -->
    <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
      <div class="container-xl d-flex justify-content-between align-items-center">
        <div class="d-flex flex-row align-items-center gap-3">
          <h2 class="page-title mb-0">
            <?= esc($title ?? 'Dashboard') ?>
          </h2>
          <span class="text-muted me-3">
            <?= date('d M Y') ?>
          </span>
        </div>
        <div class="navbar-nav flex-row">
          <div class="d-flex align-items-center">
            <span class="nav-link d-flex align-items-center lh-1 px-0">
              <?php if (!empty(session()->get('user_avatar')) && file_exists(FCPATH . 'assets/uploads/avatars/' . session()->get('user_avatar'))): ?>
                <span class="avatar avatar-sm rounded"
                  style="background-image: url(<?= base_url('assets/uploads/avatars/' . esc(session()->get('user_avatar'))) ?>)">
                </span>
              <?php else: ?>
                <span
                  class="avatar avatar-sm bg-primary-lt"><?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?></span>
              <?php endif; ?>
              <div class="d-none d-xl-block ps-2">
                <div><?= esc(session()->get('user_name') ?? 'User') ?></div>
              </div>
            </span>
            <a class="nav-link px-2" href="<?= base_url('auth/logout') ?>" title="Logout">
              <i class="ti ti-logout"></i>
            </a>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Wrapper -->
    <div class="page-wrapper">

      <!-- Page Body -->
      <div class="page-body">
        <div class="container-xl">
          <!-- Flash Messages -->
          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
              <div class="d-flex">
                <div><i class="ti ti-check alert-icon"></i></div>
                <div><?= session()->getFlashdata('success') ?></div>
              </div>
              <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
          <?php endif; ?>

          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
              <div class="d-flex">
                <div><i class="ti ti-alert-circle alert-icon"></i></div>
                <div><?= session()->getFlashdata('error') ?></div>
              </div>
              <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
            </div>
          <?php endif; ?>

          <!-- Page Content -->
          <?= $this->renderSection('content') ?>
        </div>
      </div>

      <!-- Footer -->
      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-auto ms-auto">
              <b>Version</b> 1.0
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              <span>&copy; <?= date('Y') ?> <a href="<?= base_url() ?>">FinTrack</a>.</span> Sistem Informasi Keuangan
              Pribadi.
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <!-- Tabler Core JS (includes Bootstrap 5) -->
  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Custom JS -->
  <script src="<?= base_url('assets/js/app.js') ?>"></script>
  <!-- Page Scripts -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>