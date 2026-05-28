# DESIGN.md — Sistem Informasi Keuangan Pribadi
> Panduan desain visual menggunakan template **Tabler** (https://tabler.io). Baca seluruh dokumen ini sebelum menulis satu baris kode pun.

---

## 1. Konsep Visual

**Aesthetic Direction: "Modern Admin Dashboard"**

Desain menggunakan template **Tabler** — admin dashboard template open-source modern yang dibangun di atas Bootstrap 5. Template ini memberikan tampilan premium, bersih, dan responsif tanpa perlu membangun design system dari nol.

**Kata kunci:** `modern` · `clean` · `professional` · `dashboard` · `premium`

Pengguna adalah mahasiswa yang sedang membuat **project skripsi** — desain harus terlihat *profesional dan modern*, menggunakan template yang sudah teruji dan digunakan secara luas.

---

## 2. Strategi CSS: Tabler + Bootstrap 5

Tabler dibangun di atas **Bootstrap 5** dan menyediakan komponen siap pakai. Pendekatan styling:

| Tanggung Jawab | Teknologi | Contoh |
|---|---|---|
| **Layout & Grid** | Bootstrap 5 | `col-md-4`, `row`, `d-flex`, `container-xl` |
| **Komponen UI** | Tabler + Bootstrap 5 | `.card`, `.card-stamp`, `.btn`, `.badge` |
| **Navigasi** | Tabler | `.navbar-vertical`, `.navbar`, `.nav-item` |
| **Tabel Data** | DataTables + Bootstrap 5 | DataTables Bootstrap 5 integration |
| **Ikon** | Tabler Icons | `ti ti-wallet`, `ti ti-chart-bar` |
| **Custom styling** | `custom.css` | Override minimal untuk kebutuhan khusus FinTrack |

### Aturan wajib:

- **Tabler** → gunakan komponen bawaan sebanyak mungkin
- **Bootstrap 5** → gunakan class utilities dan komponen standar
- **Custom CSS** → hanya untuk kebutuhan spesifik FinTrack yang tidak tersedia di Tabler
- File custom CSS diletakkan di `public/assets/css/custom.css`
- **Jangan mengubah** file core Tabler

### Struktur file assets:

```
public/assets/
├── css/
│   └── custom.css          ← Override minimal untuk FinTrack
├── js/
│   └── app.js              ← JavaScript custom FinTrack
├── img/
└── uploads/
```

### CDN Assets (dimuat di layout `<head>`):

```html
<!-- 1. Tabler CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/css/tabler.min.css">
<!-- 2. Tabler Icons (webfont) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<!-- 3. Custom CSS FinTrack -->
<link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
```

### JS Assets (sebelum `</body>`):

```html
<!-- 1. Tabler JS (includes Bootstrap 5) -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
<!-- 2. Custom JS FinTrack -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>
```

### Contoh penggunaan yang benar:

```html
<!-- ✅ BENAR: Stat card Tabler -->
<div class="col-sm-6 col-lg-3">
  <div class="card">
    <div class="card-body">
      <div class="d-flex align-items-center">
        <div class="subheader">Total Saldo</div>
      </div>
      <div class="h1 mb-0">Rp 4.200.000</div>
    </div>
  </div>
</div>

<!-- ✅ BENAR: Card Tabler -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Pemasukan</h3>
  </div>
  <div class="card-body">
    <table class="table table-vcenter">
      ...
    </table>
  </div>
</div>

<!-- ✅ BENAR: Button Bootstrap 5 -->
<button class="btn btn-primary"><i class="ti ti-plus"></i> Tambah</button>
<button class="btn btn-danger"><i class="ti ti-trash"></i> Hapus</button>
```

---

## 3. Tema & Warna

Menggunakan Tabler default theme:

```html
<body class="layout-fluid">
```

### Warna yang digunakan:

| Konteks | Class Bootstrap 5 / Tabler |
|---------|---------------------------|
| Pemasukan / positif | `bg-success`, `text-success`, `btn-success` |
| Pengeluaran / negatif | `bg-danger`, `text-danger`, `btn-danger` |
| Budget warning | `bg-warning`, `text-warning` |
| Info / netral | `bg-info`, `text-info` |
| Primary / CTA | `bg-primary`, `btn-primary` |
| Stat card saldo | `bg-azure` / `bg-info` |
| Stat card pemasukan | `bg-success` / `bg-green` |
| Stat card pengeluaran | `bg-danger` / `bg-red` |
| Stat card budget | `bg-warning` / `bg-yellow` |

### Aturan warna:
- Hijau (`success` / `green`) untuk pemasukan / hal positif
- Merah (`danger` / `red`) untuk pengeluaran / negatif
- Kuning (`warning` / `yellow`) untuk budget hampir habis
- Jangan membalik penggunaan warna

---

## 4. Tipografi

Tabler menggunakan **Inter** sebagai font default:

```css
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}
```

Aturan tipografi:
- **Semua teks** menggunakan font default Tabler (Inter)
- **Angka keuangan** di stat cards menggunakan class `.h1`, `.h3`, atau elemen heading sesuai komponen Tabler
- Jangan mengubah font default Tabler kecuali ada kebutuhan khusus

---

## 5. Layout Utama

### Struktur Tabler

```
┌─────────────────────────────────────────────────────────┐
│  NAVBAR (header)                                         │
│  Logo / Brand | Page Title | User Dropdown               │
├──────────┬──────────────────────────────────────────────┤
│          │                                               │
│ NAVBAR   │  PAGE WRAPPER                                 │
│ VERTICAL │  ┌──────────────────────────────────────────┐ │
│          │  │ Page Header (judul + breadcrumb)         │ │
│ Brand    │  ├──────────────────────────────────────────┤ │
│ Logo     │  │                                          │ │
│          │  │ Page Body                                │ │
│ Nav      │  │ (halaman utama di sini)                  │ │
│ Items    │  │                                          │ │
│          │  └──────────────────────────────────────────┘ │
│          │                                               │
│          │  FOOTER                                       │
├──────────┴──────────────────────────────────────────────┤
└─────────────────────────────────────────────────────────┘
```

### Struktur HTML Tabler:

```html
<div class="page">
  <!-- Navbar Vertical (Sidebar) -->
  <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <h1 class="navbar-brand navbar-brand-autodark">
        <a href="/"><span class="fw-bold">FinTrack</span></a>
      </h1>
      <div class="collapse navbar-collapse" id="sidebar-menu">
        <ul class="navbar-nav pt-lg-3">
          <li class="nav-item">
            <a class="nav-link active" href="/dashboard">
              <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
              <span class="nav-link-title">Dashboard</span>
            </a>
          </li>
          <!-- menu lainnya -->
        </ul>
      </div>
    </div>
  </aside>

  <!-- Navbar Header -->
  <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
      <div class="navbar-nav flex-row order-md-last">
        <div class="nav-item dropdown">
          <!-- User dropdown -->
        </div>
      </div>
    </div>
  </header>

  <!-- Page Wrapper -->
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="page-pretitle">Overview</div>
        <h2 class="page-title">Dashboard</h2>
      </div>
    </div>

    <!-- Page Body -->
    <div class="page-body">
      <div class="container-xl">
        <!-- Konten halaman di sini -->
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer footer-transparent d-print-none">
      <div class="container-xl">
        <span>&copy; 2026 FinTrack</span>
      </div>
    </footer>
  </div>
</div>
```

### Sidebar Menu Items:

| Menu | Ikon Tabler | URL |
|------|-------------|-----|
| Dashboard | `ti ti-dashboard` | `/` |
| Pemasukan | `ti ti-arrow-down-circle` | `/pemasukan` |
| Pengeluaran | `ti ti-arrow-up-circle` | `/pengeluaran` |
| Budgeting | `ti ti-wallet` | `/budgeting` |
| Wishlist | `ti ti-star` | `/wishlist` |
| Tabungan | `ti ti-pig-money` | `/tabungan` |
| Laporan | `ti ti-file-text` | `/laporan` |

---

## 6. Komponen UI

### Stat Card (Dashboard)

Digunakan untuk menampilkan ringkasan angka di dashboard:

```html
<div class="col-sm-6 col-lg-3">
  <div class="card">
    <div class="card-stamp">
      <div class="card-stamp-icon bg-success">
        <i class="ti ti-arrow-down-circle"></i>
      </div>
    </div>
    <div class="card-body">
      <div class="subheader">Total Pemasukan</div>
      <div class="h1 mb-3">Rp 5.000.000</div>
      <a href="/pemasukan" class="text-muted">Selengkapnya →</a>
    </div>
  </div>
</div>
```

### Card

```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Judul Card</h3>
    <div class="card-actions">
      <a href="#" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Tambah
      </a>
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

### Card dengan Status Border

```html
<div class="card">
  <div class="card-status-top bg-danger"></div>
  <div class="card-body">
    <h3 class="card-title">Card dengan status</h3>
    <p class="text-secondary">Konten card</p>
  </div>
</div>
```

### Tabel dengan DataTables

```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Daftar Pemasukan</h3>
  </div>
  <div class="card-body border-bottom py-3">
    <!-- Optional: search/filter controls -->
  </div>
  <div class="table-responsive">
    <table id="tabel-pemasukan" class="table table-vcenter card-table">
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
document.addEventListener('DOMContentLoaded', function() {
  new DataTable('#tabel-pemasukan', {
    responsive: true,
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' }
  });
});
</script>
```

### Form

```html
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Tambah Pemasukan</h3>
  </div>
  <form action="/pemasukan/store" method="post">
    <div class="card-body">
      <div class="mb-3">
        <label class="form-label" for="tanggal">Tanggal</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="nominal">Nominal</label>
        <input type="number" class="form-control" id="nominal" name="nominal" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="sumber">Sumber</label>
        <input type="text" class="form-control" id="sumber" name="sumber" required>
      </div>
      <div class="mb-3">
        <label class="form-label" for="catatan">Catatan</label>
        <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
      </div>
    </div>
    <div class="card-footer text-end">
      <a href="/pemasukan" class="btn btn-ghost-secondary me-2">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>
```

### Badge / Status

```html
<span class="badge bg-success">Tercapai</span>
<span class="badge bg-warning">Menabung</span>
<span class="badge bg-secondary">Belum Mulai</span>
<span class="badge bg-danger">Over Budget</span>
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
<div class="alert alert-success alert-dismissible" role="alert">
  <div class="d-flex">
    <div><i class="ti ti-check alert-icon"></i></div>
    <div><?= session()->getFlashdata('success') ?></div>
  </div>
  <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
<?php endif; ?>
```

### Modal (Bootstrap 5)

```html
<div class="modal modal-blur fade" id="modalHapus" tabindex="-1">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="modal-title">Konfirmasi Hapus</div>
        <div>Yakin ingin menghapus data ini?</div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-danger">Hapus</button>
      </div>
    </div>
  </div>
</div>
```

---

## 7. Grafik & Chart

Gunakan **Chart.js** (dimuat via CDN):

```html
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
```

Contoh konfigurasi chart Cashflow:

```javascript
const cashflowChart = new Chart(document.getElementById('cashflowChart'), {
  type: 'bar',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
    datasets: [
      {
        label: 'Pemasukan',
        backgroundColor: tabler.getColor('green'),
        data: [/* data pemasukan */]
      },
      {
        label: 'Pengeluaran',
        backgroundColor: tabler.getColor('red'),
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
│ SALDO    │ MASUK    │ KELUAR   │ BUDGET   │  ← Stat cards (4 kolom)
│ card     │ card     │ card     │ card     │
│ +stamp   │ +stamp   │ +stamp   │ +stamp   │
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
│ Page Header                                   │
│ Judul Halaman              [+ Tambah]         │
├──────────────────────────────────────────────┤
│ Card                                          │
│ ┌──────────────────────────────────────────┐  │
│ │ Card Header                              │  │
│ ├──────────────────────────────────────────┤  │
│ │ Table (table-vcenter card-table)         │  │
│ │ No | Tanggal | Keterangan | Nominal | Aksi│  │
│ │ ...                                      │  │
│ │ Pagination                               │  │
│ └──────────────────────────────────────────┘  │
└──────────────────────────────────────────────┘
```

---

## 9. Login & Register Page

Menggunakan layout Tabler khusus (page-center):

```html
<div class="page page-center">
  <div class="container container-tight py-4">
    <div class="text-center mb-4">
      <a href="/" class="navbar-brand navbar-brand-autodark">
        <span class="fw-bold fs-1">FinTrack</span>
      </a>
    </div>
    <div class="card card-md">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">Masuk ke akun Anda</h2>
        <form action="/auth/login" method="post" autocomplete="off">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" placeholder="email@contoh.com" autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password">
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Masuk</button>
          </div>
        </form>
      </div>
    </div>
    <div class="text-center text-secondary mt-3">
      Belum punya akun? <a href="/auth/register" tabindex="-1">Daftar</a>
    </div>
  </div>
</div>
```

---

## 10. Responsive

Tabler sudah responsive secara bawaan:
- **Desktop (≥992px):** Sidebar expanded, konten penuh
- **Tablet/Mobile (<992px):** Sidebar collapse menjadi hamburger menu
- Gunakan class `navbar-vertical navbar-expand-lg` untuk auto-collapse behavior

---

## 11. Aturan Wajib (DO & DON'T)

### ✅ DO
- Gunakan komponen Tabler standar (`.card`, `.card-stamp`, `.btn`, `.badge`, dll.)
- Gunakan Bootstrap 5 utilities (grid, spacing, flex, display)
- Gunakan Tabler Icons (`ti ti-*`) untuk semua ikon
- Gunakan DataTables (BS5) untuk tabel data
- Gunakan SweetAlert2 untuk konfirmasi hapus
- Gunakan Bootstrap Alert untuk flash message
- Letakkan custom CSS di `public/assets/css/custom.css`
- Ikuti struktur layout Tabler (`.page` > `.navbar-vertical` + `.page-wrapper`)
- Gunakan `data-bs-*` attributes (Bootstrap 5 standard)

### ❌ DON'T
- Jangan mengubah file core Tabler
- Jangan membuat komponen custom jika sudah tersedia di Tabler
- Jangan menggunakan framework CSS lain (Tailwind, Bulma, dll.)
- Jangan menggunakan Bootstrap 4 — Tabler menggunakan Bootstrap 5
- Jangan menggunakan jQuery kecuali untuk plugin yang memerlukannya (DataTables)
- Jangan menggunakan `data-toggle` / `data-dismiss` (itu Bootstrap 4, gunakan `data-bs-toggle` / `data-bs-dismiss`)

---

## 12. Dependencies

| Plugin | Fungsi | Load |
|--------|--------|------|
| Tabler Core | Template dashboard (termasuk Bootstrap 5) | Wajib |
| Tabler Icons | Ikon SVG webfont | Wajib |
| Chart.js | Grafik dashboard | Halaman dashboard & laporan |
| DataTables BS5 | Tabel data interaktif | Halaman list |
| SweetAlert2 | Dialog konfirmasi | Semua halaman CRUD |

---

## 13. Template Layout CodeIgniter 4

Buat file `app/Views/layout/main.php`:

```php
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title><?= $title ?? 'FinTrack' ?> — Keuangan Pribadi</title>

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
    <div class="container-xl">
      <div class="navbar-nav flex-row order-md-last">
        <span class="nav-link"><?= session('user_name') ?? 'User' ?></span>
        <a class="nav-link" href="/auth/logout">
          <i class="ti ti-logout"></i>
        </a>
      </div>
    </div>
  </header>

  <!-- Page Wrapper -->
  <div class="page-wrapper">
    <!-- Page Header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <h2 class="page-title"><?= $title ?? 'Dashboard' ?></h2>
      </div>
    </div>

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
        <span>&copy; 2026 <a href="#">FinTrack</a>.</span> Sistem Informasi Keuangan Pribadi.
      </div>
    </footer>
  </div>
</div>

<!-- Tabler Core JS (includes Bootstrap 5) -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@latest/dist/js/tabler.min.js"></script>
<!-- Custom JS -->
<script src="<?= base_url('assets/js/app.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
```

---

*DESIGN.md — versi 5.0 | Sistem Informasi Keuangan Pribadi | Tabler Dashboard Template*
