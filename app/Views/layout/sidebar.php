<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
      aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Brand -->
    <h1 class="navbar-brand navbar-brand-autodark">
      <a href="<?= base_url('/') ?>">
        <i class="ti ti-chart-line me-1"></i>
        <span class="fw-bold">Fin</span>Track
      </a>
    </h1>

    <!-- User (mobile) -->
    <div class="navbar-nav flex-row d-lg-none">
      <a class="nav-link px-2" href="<?= base_url('auth/logout') ?>" title="Logout">
        <i class="ti ti-logout"></i>
      </a>
    </div>

    <!-- Sidebar Menu -->
    <div class="collapse navbar-collapse" id="sidebar-menu">
      <ul class="navbar-nav pt-lg-3">

        <!-- Menu Utama -->
        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/') ?>">
            <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
            <span class="nav-link-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'pemasukan' ? 'active' : '' ?>"
            href="<?= base_url('pemasukan') ?>">
            <span class="nav-link-icon"><i class="ti ti-moneybag-plus"></i></span>
            <span class="nav-link-title">Pemasukan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'pengeluaran' ? 'active' : '' ?>"
            href="<?= base_url('pengeluaran') ?>">
            <span class="nav-link-icon"><i class="ti ti-moneybag-minus"></i></span>
            <span class="nav-link-title">Pengeluaran</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'budgeting' ? 'active' : '' ?>"
            href="<?= base_url('budgeting') ?>">
            <span class="nav-link-icon"><i class="ti ti-wallet"></i></span>
            <span class="nav-link-title">Budgeting</span>
          </a>
        </li>

        <li class="nav-item mt-2 mb-1"><small class="nav-link-title text-uppercase text-muted ps-3">Perencanaan</small>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'wishlist' ? 'active' : '' ?>"
            href="<?= base_url('wishlist') ?>">
            <span class="nav-link-icon"><i class="ti ti-star"></i></span>
            <span class="nav-link-title">Wishlist</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'tabungan' ? 'active' : '' ?>"
            href="<?= base_url('tabungan') ?>">
            <span class="nav-link-icon"><i class="ti ti-pig-money"></i></span>
            <span class="nav-link-title">Tabungan</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'laporan' ? 'active' : '' ?>"
            href="<?= base_url('laporan') ?>">
            <span class="nav-link-icon"><i class="ti ti-file-text"></i></span>
            <span class="nav-link-title">Laporan</span>
          </a>
        </li>

        <li class="nav-item mt-2 mb-1"><small class="nav-link-title text-uppercase text-muted ps-3">Akun</small></li>

        <li class="nav-item">
          <a class="nav-link <?= ($active_menu ?? '') === 'profile' ? 'active' : '' ?>"
            href="<?= base_url('profile') ?>">
            <span class="nav-link-icon"><i class="ti ti-user"></i></span>
            <span class="nav-link-title">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('auth/logout') ?>">
            <span class="nav-link-icon"><i class="ti ti-logout"></i></span>
            <span class="nav-link-title">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</aside>