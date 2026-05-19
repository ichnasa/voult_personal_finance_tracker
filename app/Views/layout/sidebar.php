<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="<?= base_url('/') ?>" class="brand-link">
    <i class="fas fa-chart-line brand-image ml-3" style="font-size: 20px; margin-top: 6px;"></i>
    <span class="brand-text font-weight-light"><b>Fin</b>Track</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <div class="img-circle elevation-2 bg-info text-center" style="width:34px;height:34px;line-height:34px;font-weight:700;color:#fff;font-size:14px;">
          <?= strtoupper(substr(session()->get('user_name') ?? 'U', 0, 1)) ?>
        </div>
      </div>
      <div class="info">
        <a href="#" class="d-block"><?= esc(session()->get('user_name') ?? 'User') ?></a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-header">MENU UTAMA</li>
        <li class="nav-item">
          <a href="<?= base_url('/') ?>" class="nav-link <?= ($active_menu ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('pemasukan') ?>" class="nav-link <?= ($active_menu ?? '') === 'pemasukan' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-arrow-circle-down"></i>
            <p>Pemasukan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('pengeluaran') ?>" class="nav-link <?= ($active_menu ?? '') === 'pengeluaran' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-arrow-circle-up"></i>
            <p>Pengeluaran</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('budgeting') ?>" class="nav-link <?= ($active_menu ?? '') === 'budgeting' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-wallet"></i>
            <p>Budgeting</p>
          </a>
        </li>

        <li class="nav-header">PERENCANAAN</li>
        <li class="nav-item">
          <a href="<?= base_url('wishlist') ?>" class="nav-link <?= ($active_menu ?? '') === 'wishlist' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-star"></i>
            <p>Wishlist</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('tabungan') ?>" class="nav-link <?= ($active_menu ?? '') === 'tabungan' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-piggy-bank"></i>
            <p>Tabungan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('laporan') ?>" class="nav-link <?= ($active_menu ?? '') === 'laporan' ? 'active' : '' ?>">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>Laporan</p>
          </a>
        </li>

        <li class="nav-header">AKUN</li>
        <li class="nav-item">
          <a href="<?= base_url('auth/logout') ?>" class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
