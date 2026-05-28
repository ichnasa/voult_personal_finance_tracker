# FinTrack — Dokumentasi Alur Aplikasi

> Dokumen ini menjelaskan alur aplikasi FinTrack dari nol, mulai dari arsitektur hingga cara kerja setiap modul.

---

## 1. Arsitektur Aplikasi

FinTrack menggunakan pola **MVC (Model-View-Controller)** di atas framework **CodeIgniter 4** dengan template **Tabler**.

```
Browser (User)
    │
    ▼
┌─────────────────────────────────────────────┐
│  Routes (app/Config/Routes.php)             │
│  Menentukan URL mana masuk ke Controller    │
│  mana                                       │
└──────────────────┬──────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────┐
│  Controller (app/Controllers/*.php)         │
│  Menerima request, proses logic, panggil    │
│  Model, kirim data ke View                  │
└───────┬─────────────────────────┬───────────┘
        │                         │
        ▼                         ▼
┌───────────────┐         ┌───────────────────┐
│  Model        │         │  View             │
│  (Models/)    │         │  (Views/)         │
│  Query ke DB  │         │  Render HTML      │
│  via Query    │         │  dengan Tabler    │
│  Builder      │         │  template         │
└───────┬───────┘         └───────────────────┘
        │
        ▼
┌───────────────┐
│  Database     │
│  fintrack_db  │
│  (MySQL)      │
└───────────────┘
```

---

## 2. Struktur Folder

```
fintrack/
├── app/
│   ├── Config/
│   │   ├── Routes.php       ← Semua routing URL
│   │   ├── Database.php     ← Konfigurasi database
│   │   └── Filters.php      ← Auth filter
│   │
│   ├── Controllers/          ← Logic bisnis
│   │   ├── Auth.php          ← Login, Register, Logout
│   │   ├── Home.php          ← Dashboard
│   │   ├── Pemasukan.php     ← CRUD Pemasukan
│   │   ├── Pengeluaran.php   ← CRUD Pengeluaran
│   │   ├── Budgeting.php     ← CRUD Budget
│   │   ├── Wishlist.php      ← CRUD Wishlist
│   │   ├── Tabungan.php      ← CRUD Tabungan
│   │   ├── Laporan.php       ← Laporan + Export
│   │   └── Profile.php       ← Profile + Ubah Password
│   │
│   ├── Models/               ← Akses database
│   │   ├── UserModel.php
│   │   ├── PemasukanModel.php
│   │   ├── PengeluaranModel.php
│   │   ├── KategoriModel.php
│   │   ├── BudgetingModel.php
│   │   ├── WishlistModel.php
│   │   └── TabunganModel.php
│   │
│   ├── Filters/
│   │   └── AuthFilter.php    ← Middleware autentikasi
│   │
│   ├── Views/
│   │   ├── layout/           ← Template Tabler
│   │   │   ├── main.php      ← Layout utama (page + sidebar + content)
│   │   │   ├── auth.php      ← Layout login/register (page-center)
│   │   │   └── sidebar.php   ← Tabler navbar-vertical
│   │   │
│   │   ├── auth/             ← Halaman login & register
│   │   ├── dashboard/        ← Halaman dashboard
│   │   ├── pemasukan/        ← index, create, edit
│   │   ├── pengeluaran/      ← index, create, edit
│   │   ├── budgeting/        ← index, create, edit
│   │   ├── wishlist/         ← index, create, edit
│   │   ├── tabungan/         ← index, create, edit
│   │   ├── profile/          ← index (edit profil + ubah password)
│   │   └── laporan/          ← index, print
│   │
│   └── Helpers/
│
├── public/
│   └── assets/
│       ├── css/custom.css         ← Override minimal FinTrack
│       ├── js/app.js              ← JavaScript custom FinTrack
│       ├── img/
│       └── uploads/               ← File nota yang diupload
│
└── .env
```

---

## 3. Alur Request (Dari Klik Sampai Tampil)

### Contoh: User membuka halaman Pemasukan

```
1. User klik menu "Pemasukan" di sidebar Tabler
   URL: http://localhost:8080/pemasukan

2. Routes.php mencocokkan URL:
   $routes->get('pemasukan', 'Pemasukan::index');

3. Controller Pemasukan::index() dijalankan:
   - AuthFilter cek session login (jika belum → redirect ke auth/login)
   - Ambil user_id dari session
   - Panggil PemasukanModel->getByUser($userId)
   - Kirim data ke view

4. View pemasukan/index.php di-render:
   - Extends layout/main.php (Tabler page wrapper)
   - Sidebar, header, footer sudah terintegrasi
   - Tampilkan tabel dengan data pemasukan

5. HTML dikirim ke browser → User melihat halaman
```

---

## 4. Alur Autentikasi

### Register (Daftar Akun Baru)

```
┌──────────────────────────────────────────────────────┐
│  1. User buka /auth/register                         │
│  2. Halaman register Tabler (page-center layout)     │
│  3. Isi form: Nama, Email, Password, Konfirmasi      │
│  4. Submit form → POST /auth/processRegister         │
│                                                      │
│  Controller Auth::processRegister():                 │
│  ├── Validasi input (nama min 3, email unik, dll)    │
│  ├── Jika GAGAL → redirect back + tampil error       │
│  ├── Jika BERHASIL:                                  │
│  │   ├── Hash password dengan password_hash()        │
│  │   ├── Insert ke tabel 'users'                     │
│  │   └── Redirect ke /auth/login + flash "Berhasil"  │
└──────────────────────────────────────────────────────┘
```

### Login (Masuk)

```
┌──────────────────────────────────────────────────────┐
│  1. User buka /auth/login                            │
│  2. Halaman login Tabler (page-center layout)        │
│  3. Isi Email + Password                             │
│  4. Submit → POST /auth/processLogin                 │
│                                                      │
│  Controller Auth::processLogin():                    │
│  ├── Cari user by email di database                  │
│  ├── Verifikasi password: password_verify()          │
│  ├── Jika SALAH → redirect back + "Email/password    │
│  │                 salah"                             │
│  ├── Jika BENAR:                                     │
│  │   ├── Set session:                                │
│  │   │   - user_id                                   │
│  │   │   - user_name                                 │
│  │   │   - user_email                                │
│  │   │   - logged_in = true                          │
│  │   └── Redirect ke / (Dashboard)                   │
└──────────────────────────────────────────────────────┘
```

### Logout

```
Auth::logout()  →  session()->destroy()  →  redirect ke /auth/login
```

---

## 5. Sistem Layout (Template Tabler)

FinTrack menggunakan 2 layout:

### Layout `main.php` — Untuk halaman utama (setelah login)

Menggunakan struktur Tabler:

```
┌──────────────────────────────────────────────────┐
│ NAVBAR HEADER                                     │
│                          User Name  [Logout]      │
├──────────┬───────────────────────────────────────┤
│          │ PAGE HEADER                            │
│ NAVBAR   │ Judul Halaman                          │
│ VERTICAL ├───────────────────────────────────────┤
│          │                                       │
│ FinTrack │ PAGE BODY                              │
│          │ (konten halaman di sini)               │
│ • Dash   │                                       │
│ • Masuk  │                                       │
│ • Keluar │                                       │
│ • Budget │                                       │
│ • Wish   │                                       │
│ • Tabung │                                       │
│ • Lapor  │                                       │
│          ├───────────────────────────────────────┤
│          │ FOOTER                                 │
│          │ © 2026 FinTrack                        │
└──────────┴───────────────────────────────────────┘
```

Cara kerja (CI4 layout sections):
```php
// Di main.php:
// Layout Tabler page dengan sidebar, header, footer
// Konten dimuat via $this->renderSection('content')

// Di view halaman:
<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
  <!-- konten halaman -->
<?= $this->endSection() ?>
```

### Layout `auth.php` — Untuk halaman login/register (tanpa sidebar)

Menggunakan layout Tabler page-center:

```
┌──────────────────────────────────┐
│                                  │
│         ┌──────────────┐         │
│         │   FinTrack   │         │
│         ├──────────────┤         │
│         │              │         │
│         │  FORM LOGIN  │         │
│         │  (card)      │         │
│         │              │         │
│         │  Email [___] │         │
│         │  Pass  [___] │         │
│         │              │         │
│         │  [  Masuk  ] │         │
│         │              │         │
│         │  Daftar →    │         │
│         └──────────────┘         │
│                                  │
└──────────────────────────────────┘
```

---

## 6. Database & Relasi

### Diagram Relasi Tabel

```
┌─────────┐     ┌──────────┐
│  users  │────<│ pemasukan│    user memiliki banyak pemasukan
│         │     └──────────┘
│  id     │
│  name   │     ┌────────────┐     ┌──────────┐
│  email  │────<│pengeluaran │>────│ kategori │
│  pass   │     │            │     │          │
└────┬────┘     └────────────┘     │  id      │
     │                              │  name    │
     │          ┌──────────┐        └────┬─────┘
     ├─────────<│ budgeting│>────────────┘
     │          └──────────┘    budget juga punya kategori
     │
     │          ┌──────────┐
     ├─────────<│ wishlist │
     │          └─────┬────┘
     │                │ (opsional)
     │          ┌─────┴────┐
     └─────────<│ tabungan │    tabungan bisa terhubung ke wishlist
                └──────────┘
```

### Deskripsi Tabel

| Tabel | Fungsi | Kolom Penting |
|-------|--------|---------------|
| `users` | Data pengguna | id, name, email, password, phone, address, avatar |
| `kategori` | Kategori pengeluaran | id, name |
| `pemasukan` | Catatan pemasukan | user_id, tanggal, nominal, sumber |
| `pengeluaran` | Catatan pengeluaran | user_id, kategori_id, tanggal, nominal, metode_pembayaran, nota |
| `budgeting` | Budget per kategori/bulan | user_id, kategori_id, bulan, tahun, nominal_budget |
| `wishlist` | Barang yang diinginkan | user_id, nama_barang, harga_target, prioritas, status |
| `tabungan` | Target menabung | user_id, wishlist_id, nama_tabungan, target_nominal, nominal_terkumpul, deadline |

---

## 7. Alur Setiap Modul

### 7.1 Dashboard (`/`)

```
Controller: Home::index()
│
├── Query total saldo (pemasukan - pengeluaran all time)
├── Query pemasukan bulan ini
├── Query pengeluaran bulan ini
├── Query sisa budget bulan ini
├── Query data chart bulanan (12 bulan)
├── Query 5 transaksi terakhir (gabungan)
├── Hitung Financial Health Score:
│   ├── Spending Ratio = pengeluaran / pemasukan × 100
│   ├── Saving Ratio = (pemasukan - pengeluaran) / pemasukan × 100
│   ├── Budget Discipline = (1 - pengeluaran/budget) × 100
│   └── Overall Score = weighted average (0-100)
├── Query tabungan aktif (max 3) untuk Saving Goals
├── Query wishlist prioritas tinggi (max 3) untuk Wishlist Priority
│
└── Render: dashboard/index.php
    ├── 4 Stat cards Tabler (Saldo, Pemasukan, Pengeluaran, Sisa Budget)
    ├── Card Financial Health (donut chart + progress bars)
    ├── Card + Chart.js Bar Chart (Cashflow tahunan)
    ├── Card Saving Goals (progress bar tabungan aktif)
    ├── Card Wishlist Priority (badge prioritas + status)
    └── Card + Tabel transaksi terakhir
```

### 7.2 Pemasukan (`/pemasukan`)

```
ALUR CRUD:

LIST (/pemasukan)
  Controller: Pemasukan::index()
  ├── PemasukanModel->getByUser() dengan filter
  └── Render tabel dalam card Tabler

CREATE (/pemasukan/create → POST /pemasukan/store)
  1. Tampilkan form dalam card Tabler
  2. User isi: tanggal, nominal, sumber, catatan
  3. Submit → validasi server-side
  4. Jika valid: INSERT ke DB → redirect + flash alert success
  5. Jika invalid: redirect back + tampil error

EDIT (/pemasukan/edit/5 → POST /pemasukan/update/5)
  1. Cek kepemilikan (user_id harus cocok dengan session)
  2. Tampilkan form dalam card dengan data existing
  3. Submit → validasi → UPDATE → redirect

DELETE (/pemasukan/delete/5)
  1. Cek kepemilikan
  2. Konfirmasi SweetAlert2
  3. DELETE dari DB → redirect + flash alert
```

### 7.3 Pengeluaran (`/pengeluaran`)

```
Sama seperti Pemasukan, PLUS:
├── Dropdown kategori (dari tabel kategori)
├── Pilihan metode pembayaran (Cash, Transfer, E-Wallet, Debit, Kredit)
├── Upload nota (gambar/PDF)
│   ├── File disimpan ke public/assets/uploads/
│   ├── Nama file di-random untuk keamanan
│   └── Saat edit: bisa upload nota baru (lama dihapus)
└── Filter tambahan: by kategori
```

### 7.4 Budgeting (`/budgeting`)

```
ALUR KHUSUS:

1. User set budget per kategori per bulan
   Contoh: "Makanan = Rp 2.000.000 untuk Mei 2026"

2. Saat index ditampilkan:
   ├── Ambil semua budget bulan X tahun Y
   ├── Untuk SETIAP budget:
   │   ├── Hitung pengeluaran aktual di kategori itu
   │   ├── Hitung persentase: (spent / budget) × 100
   │   └── Tentukan status:
   │       ├── ≤ 80%  → Badge "Normal" (bg-success)
   │       ├── 80-100% → Badge "Warning" (bg-warning)
   │       └── > 100% → Badge "Over Budget" (bg-danger)
   └── Tampilkan Bootstrap progress bar per kategori

3. Filter bulan/tahun: user bisa lihat budget bulan lain
```

### 7.5 Wishlist (`/wishlist`)

```
ALUR:
1. User tambah barang impian:
   ├── Nama barang
   ├── Harga target
   ├── Prioritas: Rendah | Sedang | Tinggi
   └── Catatan (opsional)

2. Status tracking:
   ├── Belum Mulai → badge bg-secondary
   ├── Menabung → badge bg-warning
   └── Tercapai → badge bg-success

3. Sorting: Tinggi → Sedang → Rendah (otomatis)

4. Integrasi: Wishlist bisa dihubungkan ke Tabungan
```

### 7.6 Tabungan (`/tabungan`)

```
ALUR:
1. User buat target tabungan:
   ├── Nama tabungan (misal: "Dana Darurat")
   ├── Target nominal (misal: Rp 10.000.000)
   ├── Nominal awal (misal: Rp 500.000)
   ├── Deadline (opsional)
   └── Link ke wishlist (opsional)

2. Progress tracking:
   ├── Progress = (terkumpul / target) × 100%
   ├── Ditampilkan sebagai Bootstrap 5 progress bar
   └── User update nominal_terkumpul via edit

3. Deadline warning:
   ├── > 30 hari → warna normal
   ├── < 30 hari → warna kuning (warning)
   └── Lewat deadline → warna merah (danger) + "terlambat X hari"

4. Status: Proses → Tercapai (manual update)
```

### 7.7 Laporan (`/laporan`)

```
ALUR:
1. User pilih rentang tanggal (default: bulan ini)
2. Sistem menghitung:
   ├── Total pemasukan dalam rentang
   ├── Total pengeluaran dalam rentang
   ├── Selisih (surplus/defisit)
   └── Breakdown pengeluaran per kategori

3. Tampilan (dalam card Tabler):
   ├── 3 Summary stat cards
   ├── Doughnut chart (pengeluaran per kategori)
   ├── Tabel detail pemasukan
   └── Tabel detail pengeluaran

4. Export/Print:
   ├── Klik "Cetak / Export PDF"
   ├── Buka halaman /laporan/export (layout khusus cetak)
   ├── Otomatis trigger window.print()
   └── User bisa save as PDF dari dialog print browser
```

### 7.8 Profile (`/profile`)

```
ALUR:
1. User buka halaman Profile dari sidebar
   → GET /profile
   → Controller Profile::index()
   → Ambil data user dari session user_id
   → Hitung total transaksi (pemasukan + pengeluaran)
   → Render: profile/index.php
      ├── Profile header card (avatar + nama + email)
      ├── Card foto profil + upload avatar
      ├── Card ringkasan akun (member sejak, total transaksi)
      ├── Card edit profil (form)
      └── Card ubah password (form)

2. Edit Profil (via Card / Modal):
   → User ubah nama/email/telepon/alamat
   → Submit → POST /profile/update
   → Controller Profile::update()
   ├── Validasi input (nama min 3, email unik kecuali milik sendiri)
   ├── Jika GAGAL → redirect back + tampil error
   ├── Jika BERHASIL:
   │   ├── Update tabel 'users'
   │   ├── Update session (user_name, user_email)
   │   └── Redirect ke /profile + flash "Berhasil"

3. Ubah Password (via Card / Modal):
   → User isi password lama, baru, konfirmasi
   → Submit → POST /profile/updatePassword
   → Controller Profile::updatePassword()
   ├── Validasi input (password baru min 8, konfirmasi cocok)
   ├── Verifikasi password lama dengan password_verify()
   ├── Jika SALAH → redirect back + "Password lama tidak sesuai"
   ├── Jika BENAR:
   │   ├── Hash password baru
   │   ├── Update tabel 'users'
   │   └── Redirect ke /profile + flash "Berhasil"

4. Upload Avatar:
   → User pilih file foto (JPG/PNG, max 2MB)
   → Submit → POST /profile/updateAvatar
   → Controller Profile::updateAvatar()
   ├── Validasi file (ukuran, tipe)
   ├── Hapus avatar lama jika ada
   ├── Upload ke public/assets/uploads/avatars/
   └── Update kolom avatar di tabel 'users'
```

---

## 8. Sistem Styling

### Tabler Components

Semua komponen visual menggunakan class Tabler dan Bootstrap 5 standar.

| Komponen | Class | Fungsi |
|----------|-------|--------|
| Stat Card | `.card` + `.card-stamp` | Stat card dashboard |
| Card | `.card` | Container utama |
| Table | `.table .table-vcenter .card-table` | Tabel data |
| DataTables | `#id` + JS init | Tabel interaktif |
| Button | `.btn .btn-primary/danger/success` | Tombol aksi |
| Badge | `.badge .bg-success/danger/warning` | Label status |
| Progress | `.progress .progress-bar` | Progress bar |
| Alert | `.alert .alert-success/danger` | Flash message |
| Modal | `.modal .modal-blur` | Dialog konfirmasi |
| Form | `.mb-3 .form-label .form-control` | Input form |
| Sidebar | `.navbar-vertical` | Navigasi samping |
| Navbar | `.navbar .navbar-expand-md` | Header atas |

### Warna Utama

```
Primary    : Tabler primary (biru)
Success    : Tabler success / green — pemasukan
Danger     : Tabler danger / red — pengeluaran
Warning    : Tabler warning / yellow — budget warning
Info       : Tabler info / azure — informasi
```

### Ikon

```
Tabler Icons (webfont)
Contoh: ti ti-wallet, ti ti-chart-bar, ti ti-pig-money
Docs: https://tabler.io/icons
```

---

## 9. Alur Data: Dari Input Sampai Tampil di Dashboard

```
Contoh: User menambah pemasukan Rp 5.000.000

1. User klik "Tambah Pemasukan" di Dashboard
   → Buka /pemasukan/create

2. User isi form (dalam card Tabler):
   Tanggal: 2026-05-18
   Nominal: 5000000
   Sumber: Gaji
   Catatan: Gaji bulan Mei

3. Submit → POST /pemasukan/store
   → Controller validasi
   → PemasukanModel->insert([...])
   → Data masuk ke tabel 'pemasukan'

4. Redirect ke /pemasukan → tampil di tabel

5. User kembali ke Dashboard (/)
   → Home::index() query ulang:
   ├── getTotalAll() → Saldo terupdate (stat card)
   ├── getTotalBulanIni() → Pemasukan bulan ini terupdate
   ├── getMonthlyTotals() → Chart terupdate
   └── getRecent() → Transaksi terakhir terupdate

6. Dashboard menampilkan data terbaru
```

---

## 10. Keamanan

| Mekanisme | Implementasi |
|-----------|-------------|
| Password hashing | `password_hash()` + `password_verify()` |
| Session-based auth | CI4 Session library |
| Route protection | CI4 Filters (AuthFilter) |
| Data ownership | Setiap query di-filter by `user_id` dari session |
| CSRF protection | CI4 `csrf_field()` di form |
| Input escaping | `esc()` helper di output view |
| SQL injection | CI4 Query Builder (parameterized) |

---

## 11. Cara Menjalankan

```bash
# 1. Masuk ke folder project
cd /home/ichawfa/Documents/CodeOnFedora/fintrack

# 2. Pastikan database sudah ada
#    (sudah dibuat: fintrack_db)

# 3. Import tabel dari DATABASE.md

# 4. Tabler dimuat via CDN — tidak perlu setup manual

# 5. Jalankan server development
php spark serve --port 8080

# 6. Buka browser
#    http://localhost:8080
#    → Redirect ke halaman Login
#    → Daftar akun baru → Login → Dashboard
```

---

## 12. Ringkasan Alur User

```
┌─────────────┐     ┌───────────┐     ┌─────────────┐
│  Register   │────>│   Login   │────>│  Dashboard  │
└─────────────┘     └───────────┘     └──────┬──────┘
                                             │
                    ┌────────────────────────┬┼────────────────────────┐
                    │                        ││                        │
              ┌─────┴──────┐          ┌──────┴┴─────┐          ┌──────┴──────┐
              │ Pemasukan  │          │ Pengeluaran │          │  Budgeting  │
              │ (CRUD)     │          │ (CRUD+Nota) │          │ (Monitor)   │
              └────────────┘          └─────────────┘          └─────────────┘
                    │                        │                        │
              ┌─────┴──────┐          ┌──────┴──────┐          ┌──────┴──────┐
              │  Wishlist  │─────────>│  Tabungan   │          │   Laporan   │
              │ (Impian)   │  link    │  (Progress) │          │ (Export PDF)│
              └────────────┘          └─────────────┘          └─────────────┘
                                                                      │
                                                               ┌──────┴──────┐
                                                               │   Profile   │
                                                               │ (Edit+Pass) │
                                                               └─────────────┘
```

---

*Dokumen ini dibuat untuk project FinTrack — Sistem Informasi Keuangan Pribadi menggunakan Tabler.*
