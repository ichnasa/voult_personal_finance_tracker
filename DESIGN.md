# DESIGN.md — Sistem Informasi Keuangan Pribadi
> Panduan desain visual menggunakan template AdminLTE 3.2. Baca seluruh dokumen ini sebelum menulis satu baris kode pun.

---

## 1. Konsep Visual

**Aesthetic Direction: "Professional Admin Dashboard"**

Desain menggunakan template **AdminLTE 3.2** — admin dashboard template #1 yang dibangun di atas Bootstrap 4. Template ini memberikan tampilan profesional, bersih, dan konsisten tanpa perlu membangun design system dari nol.

**Kata kunci:** `clean` · `professional` · `organized` · `admin-panel` · `dashboard`

Pengguna adalah mahasiswa yang sedang membuat **project skripsi** — desain harus terlihat *profesional dan rapi*, menggunakan template yang sudah teruji dan digunakan secara luas.

---

## 2. Strategi CSS: AdminLTE 3.2 + Bootstrap 4

AdminLTE 3.2 dibangun di atas **Bootstrap 4** dan menyediakan komponen siap pakai. Pendekatan styling:

| Tanggung Jawab | Teknologi | Contoh |
|---|---|---|
| **Layout & Grid** | Bootstrap 4 | `col-md-4`, `row`, `d-flex`, `container-fluid` |
| **Komponen UI** | AdminLTE 3 + Bootstrap 4 | `.card`, `.info-box`, `.small-box`, `.btn`, `.badge` |
| **Navigasi** | AdminLTE 3 | `.main-sidebar`, `.main-header`, `.nav-sidebar` |
| **Tabel Data** | DataTables + AdminLTE | DataTables Bootstrap 4 integration |
| **Ikon** | Font Awesome 5 | `fas fa-wallet`, `fas fa-chart-bar` |
| **Custom styling** | `custom.css` | Override minimal untuk kebutuhan khusus FinTrack |

### Aturan wajib:

- **AdminLTE** → gunakan komponen bawaan sebanyak mungkin
- **Bootstrap 4** → gunakan class utilities dan komponen standar
- **Custom CSS** → hanya untuk kebutuhan spesifik FinTrack yang tidak tersedia di AdminLTE
- File custom CSS diletakkan di `assets/css/custom.css`
- **Jangan mengubah** file core AdminLTE (`adminlte.min.css`)

### Struktur file assets:

```
assets/
├── adminlte/
│   ├── css/
│   │   └── adminlte.min.css
│   ├── js/
│   │   └── adminlte.min.js
│   └── img/
├── plugins/
│   ├── bootstrap/
│   ├── jquery/
│   ├── fontawesome-free/
│   ├── chart.js/
│   ├── datatables-bs4/
│   ├── sweetalert2/
│   └── ...
├── css/
│   └── custom.css          ← Override minimal untuk FinTrack
├── js/
│   └── app.js              ← JavaScript custom FinTrack
├── img/
└── uploads/
```

Urutan load di `<head>`:
```html
<!-- 1. Font Awesome 5 (ikon) -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
<!-- 2. AdminLTE CSS -->
<link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
<!-- 3. Google Fonts (opsional) -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<!-- 4. Custom CSS FinTrack -->
<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
```

### Contoh penggunaan yang benar:

```html
<!-- ✅ BENAR: Komponen AdminLTE standar -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>Rp 4.200.000</h3>
        <p>Total Saldo</p>
      </div>
      <div class="icon"><i class="fas fa-wallet"></i></div>
    </div>
  </div>
</div>

<!-- ✅ BENAR: Card AdminLTE -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Daftar Pemasukan</h3>
  </div>
  <div class="card-body">
    <table id="tabel-pemasukan" class="table table-bordered table-striped">
      ...
    </table>
  </div>
</div>

<!-- ✅ BENAR: Button Bootstrap standar -->
<button class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</button>
<button class="btn btn-danger"><i class="fas fa-trash"></i> Hapus</button>
```

---

## 3. Tema & Warna

Menggunakan skin AdminLTE bawaan: **`sidebar-dark-primary`**

```html
<body class="hold-transition sidebar-mini layout-fixed">
```

### Warna yang digunakan:

| Konteks | Class AdminLTE / Bootstrap |
|---------|---------------------------|
| Pemasukan / positif | `bg-success`, `text-success`, `btn-success` |
| Pengeluaran / negatif | `bg-danger`, `text-danger`, `btn-danger` |
| Budget warning | `bg-warning`, `text-warning` |
| Info / netral | `bg-info`, `text-info` |
| Primary / CTA | `bg-primary`, `btn-primary` |
| Stat card saldo | `bg-info` |
| Stat card pemasukan | `bg-success` |
| Stat card pengeluaran | `bg-danger` |
| Stat card budget | `bg-warning` |

### Aturan warna:
- Hijau (`success`) untuk pemasukan / hal positif
- Merah (`danger`) untuk pengeluaran / negatif
- Kuning (`warning`) untuk budget hampir habis
- Jangan membalik penggunaan warna

---

## 4. Tipografi

AdminLTE menggunakan **Source Sans Pro** sebagai font default:

```css
body {
  font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
```

Aturan tipografi:
- **Semua teks** menggunakan font default AdminLTE
- **Angka keuangan** di stat cards boleh ditampilkan dengan `<h3>` atau `<h4>` sesuai komponen AdminLTE
- Jangan mengubah font default AdminLTE kecuali ada kebutuhan khusus

---

## 5. Layout Utama

### Struktur AdminLTE

```
┌─────────────────────────────────────────────────────────┐
│  MAIN HEADER (navbar)                                    │
│  Logo + Sidebar Toggle | Breadcrumb | User Dropdown      │
├──────────┬──────────────────────────────────────────────┤
│          │                                               │
│  MAIN    │  CONTENT WRAPPER                              │
│  SIDEBAR │  ┌──────────────────────────────────────────┐ │
│          │  │ Content Header (judul + breadcrumb)      │ │
│  Brand   │  ├──────────────────────────────────────────┤ │
│  Logo    │  │                                          │ │
│          │  │ Section Content                           │ │
│  Nav     │  │ (halaman utama di sini)                  │ │
│  Menu    │  │                                          │ │
│          │  └──────────────────────────────────────────┘ │
│          │                                               │
│          │  MAIN FOOTER                                  │
├──────────┴──────────────────────────────────────────────┤
└─────────────────────────────────────────────────────────┘
```

### Struktur HTML AdminLTE:

```html
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left: sidebar toggle -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <!-- Right: user menu -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown">...</li>
    </ul>
  </nav>

  <!-- Main Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link">
      <span class="brand-text font-weight-light">FinTrack</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
          <li class="nav-item">
            <a href="/dashboard" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <!-- menu lainnya -->
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1>Dashboard</h1></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <!-- Konten halaman di sini -->
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>&copy; 2026 FinTrack</strong>
  </footer>
</div>
</body>
```

### Sidebar Menu Items:

| Menu | Ikon | URL |
|------|------|-----|
| Dashboard | `fas fa-tachometer-alt` | `/` |
| Pemasukan | `fas fa-arrow-circle-down` | `/pemasukan` |
| Pengeluaran | `fas fa-arrow-circle-up` | `/pengeluaran` |
| Budgeting | `fas fa-wallet` | `/budgeting` |
| Wishlist | `fas fa-star` | `/wishlist` |
| Tabungan | `fas fa-piggy-bank` | `/tabungan` |
| Laporan | `fas fa-file-alt` | `/laporan` |

---

## 6. Komponen UI

### Small Box (Stat Card Dashboard)

Digunakan untuk menampilkan ringkasan angka di dashboard:

```html
<div class="col-lg-3 col-6">
  <div class="small-box bg-success">
    <div class="inner">
      <h3>Rp 5.000.000</h3>
      <p>Total Pemasukan</p>
    </div>
    <div class="icon"><i class="fas fa-arrow-circle-down"></i></div>
    <a href="/pemasukan" class="small-box-footer">
      Selengkapnya <i class="fas fa-arrow-circle-right"></i>
    </a>
  </div>
</div>
```

### Card

```html
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Judul Card</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <!-- konten -->
  </div>
  <div class="card-footer">
    <!-- footer opsional -->
  </div>
</div>
```

### Tabel dengan DataTables

```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Pemasukan</h3>
  </div>
  <div class="card-body">
    <table id="tabel-pemasukan" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>No</th>
          <th>Tanggal</th>
          <th>Sumber</th>
          <th>Nominal</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <!-- data -->
      </tbody>
    </table>
  </div>
</div>

<script>
$(function () {
  $("#tabel-pemasukan").DataTable({
    "responsive": true,
    "autoWidth": false,
    "language": { "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json" }
  });
});
</script>
```

### Form

```html
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">Tambah Pemasukan</h3>
  </div>
  <form action="/pemasukan/store" method="post">
    <div class="card-body">
      <div class="form-group">
        <label for="tanggal">Tanggal</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
      </div>
      <div class="form-group">
        <label for="nominal">Nominal</label>
        <input type="number" class="form-control" id="nominal" name="nominal" required>
      </div>
      <div class="form-group">
        <label for="sumber">Sumber</label>
        <input type="text" class="form-control" id="sumber" name="sumber" required>
      </div>
      <div class="form-group">
        <label for="catatan">Catatan</label>
        <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
      </div>
    </div>
    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="/pemasukan" class="btn btn-default">Batal</a>
    </div>
  </form>
</div>
```

### Badge / Status

```html
<span class="badge badge-success">Tercapai</span>
<span class="badge badge-warning">Menabung</span>
<span class="badge badge-secondary">Belum Mulai</span>
<span class="badge badge-danger">Over Budget</span>
```

### Progress Bar

```html
<div class="progress">
  <div class="progress-bar bg-success" role="progressbar" style="width: 75%">75%</div>
</div>
```

### Flash Message (Alert)

```html
<!-- Menggunakan SweetAlert2 atau Bootstrap Alert -->
<?php if (session()->getFlashdata('success')): ?>
<div class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <i class="icon fas fa-check"></i> <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>
```

### Modal (Bootstrap 4)

```html
<div class="modal fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">Yakin ingin menghapus data ini?</div>
      <div class="modal-footer">
        <button class="btn btn-default" data-dismiss="modal">Batal</button>
        <button class="btn btn-danger">Hapus</button>
      </div>
    </div>
  </div>
</div>
```

---

## 7. Grafik & Chart

Gunakan **Chart.js** (tersedia di plugins AdminLTE):

```html
<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>
```

Contoh konfigurasi chart Cashflow:

```javascript
var cashflowChart = new Chart($('#cashflowChart'), {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [
      {
        label: 'Pemasukan',
        backgroundColor: '#28a745',
        data: [/* data pemasukan */]
      },
      {
        label: 'Pengeluaran',
        backgroundColor: '#dc3545',
        data: [/* data pengeluaran */]
      }
    ]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false
  }
});
```

---

## 8. Halaman Spesifik

### Dashboard

```
┌──────────┬──────────┬──────────┬──────────┐
│ SALDO    │ MASUK    │ KELUAR   │ BUDGET   │  ← Small boxes (4 kolom)
│ bg-info  │bg-success│bg-danger │bg-warning│
└──────────┴──────────┴──────────┴──────────┘

┌─────────────────────────┬────────────────────┐
│ GRAFIK CASHFLOW         │ PROGRESS TABUNGAN  │
│ (card + bar chart)      │ (card + progress)  │
└─────────────────────────┴────────────────────┘

┌─────────────────────────┬────────────────────┐
│ TRANSAKSI TERAKHIR      │ WISHLIST TERATAS   │
│ (card + table)          │ (card + list)      │
└─────────────────────────┴────────────────────┘
```

### Halaman List (Pemasukan, Pengeluaran, dll.)

```
┌──────────────────────────────────────────────┐
│ Content Header                                │
│ Judul Halaman              Breadcrumb         │
├──────────────────────────────────────────────┤
│ Card                                          │
│ ┌──────────────────────────────────────────┐  │
│ │ Card Header    [+ Tambah]                │  │
│ ├──────────────────────────────────────────┤  │
│ │ DataTable                                │  │
│ │ Search | Entries per page                │  │
│ │ No | Tanggal | Keterangan | Nominal | Aksi│  │
│ │ ...                                      │  │
│ │ Pagination                               │  │
│ └──────────────────────────────────────────┘  │
└──────────────────────────────────────────────┘
```

---

## 9. Login & Register Page

Menggunakan layout AdminLTE khusus login:

```html
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>Fin</b>Track
  </div>
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Masuk ke akun Anda</p>
      <form action="/auth/login" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text"><span class="fas fa-lock"></span></div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
      </form>
      <p class="mb-0 mt-3">
        <a href="/auth/register">Belum punya akun? Daftar</a>
      </p>
    </div>
  </div>
</div>
</body>
```

---

## 10. Responsive

AdminLTE sudah responsive secara bawaan:
- **Desktop (≥992px):** Sidebar expanded, konten penuh
- **Tablet/Mobile (<992px):** Sidebar auto-collapse, bisa toggle via hamburger menu
- Gunakan class `sidebar-mini` di `<body>` untuk auto-collapse behavior

---

## 11. Aturan Wajib (DO & DON'T)

### ✅ DO
- Gunakan komponen AdminLTE standar (`.card`, `.small-box`, `.info-box`, `.btn`, dll.)
- Gunakan Bootstrap 4 utilities (grid, spacing, flex, display)
- Gunakan Font Awesome 5 untuk semua ikon
- Gunakan DataTables untuk tabel data
- Gunakan SweetAlert2 untuk konfirmasi hapus
- Gunakan Bootstrap Alert untuk flash message
- Letakkan custom CSS di `assets/css/custom.css`
- Ikuti struktur layout AdminLTE (`.wrapper` > `.main-sidebar` + `.content-wrapper`)

### ❌ DON'T
- Jangan mengubah file core AdminLTE (`adminlte.min.css`, `adminlte.min.js`)
- Jangan membuat komponen custom jika sudah tersedia di AdminLTE
- Jangan menggunakan framework CSS lain (Tailwind, Bulma, dll.)
- Jangan menggunakan Bootstrap 5 — AdminLTE 3 menggunakan Bootstrap 4
- Jangan menghapus jQuery — AdminLTE 3 dan DataTables membutuhkannya

---

## 12. Dependencies (Plugin AdminLTE)

| Plugin | Fungsi | Load |
|--------|--------|------|
| jQuery 3.x | Library dasar | Wajib |
| Bootstrap 4 | Framework CSS/JS | Wajib |
| Font Awesome 5 | Ikon | Wajib |
| AdminLTE 3.2 | Template dashboard | Wajib |
| Chart.js | Grafik dashboard | Halaman dashboard |
| DataTables BS4 | Tabel data interaktif | Halaman list |
| SweetAlert2 | Dialog konfirmasi | Semua halaman CRUD |
| bs-custom-file-input | Upload file styling | Halaman pengeluaran |

---

## 13. Template Layout CodeIgniter

Buat file `application/views/layout/main.php`:

```php
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'FinTrack' ?> — Keuangan Pribadi</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/css/adminlte.min.css') ?>">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <span class="nav-link"><?= session('user_name') ?? 'User' ?></span>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/auth/logout"><i class="fas fa-sign-out-alt"></i></a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <?php $this->load->view('layout/sidebar'); ?>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?? 'Dashboard' ?></h1>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="icon fas fa-check"></i> <?= $this->session->flashdata('success') ?>
        </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="icon fas fa-ban"></i> <?= $this->session->flashdata('error') ?>
        </div>
        <?php endif; ?>

        <!-- Page Content -->
        <?= $content ?>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
    <strong>&copy; 2026 <a href="#">FinTrack</a>.</strong> Sistem Informasi Keuangan Pribadi.
  </footer>
</div>

<!-- jQuery -->
<script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('assets/adminlte/js/adminlte.min.js') ?>"></script>
<!-- Page JS (opsional) -->
<?php if (isset($page_js)): ?>
  <script src="<?= base_url('assets/js/' . $page_js) ?>"></script>
<?php endif; ?>
</body>
</html>
```

---

*DESIGN.md — versi 4.0 | Sistem Informasi Keuangan Pribadi | AdminLTE 3.2 Dashboard Template*
