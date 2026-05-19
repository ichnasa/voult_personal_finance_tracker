# FinTrack — Dokumentasi Alur Aplikasi

> Dokumen ini menjelaskan alur aplikasi FinTrack dari nol, mulai dari arsitektur hingga cara kerja setiap modul.

---

## 1. Arsitektur Aplikasi

FinTrack menggunakan pola **MVC (Model-View-Controller)** di atas framework **CodeIgniter 3** dengan template **AdminLTE 3.2**.

```
Browser (User)
    │
    ▼
┌─────────────────────────────────────────────┐
│  Routes (application/config/routes.php)     │
│  Menentukan URL mana masuk ke Controller    │
│  mana                                       │
└──────────────────┬──────────────────────────┘
                   │
                   ▼
┌─────────────────────────────────────────────┐
│  Controller (application/controllers/*.php) │
│  Menerima request, proses logic, panggil    │
│  Model, kirim data ke View                  │
└───────┬─────────────────────────┬───────────┘
        │                         │
        ▼                         ▼
┌───────────────┐         ┌───────────────────┐
│  Model        │         │  View             │
│  (models/)    │         │  (views/)         │
│  Query ke DB  │         │  Render HTML      │
│  via Query    │         │  dengan AdminLTE  │
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
├── application/
│   ├── config/
│   │   ├── routes.php       ← Semua routing URL
│   │   ├── database.php     ← Konfigurasi database
│   │   └── autoload.php     ← Autoload library & helper
│   │
│   ├── controllers/          ← Logic bisnis
│   │   ├── Auth.php          ← Login, Register, Logout
│   │   ├── Dashboard.php     ← Dashboard
│   │   ├── Pemasukan.php     ← CRUD Pemasukan
│   │   ├── Pengeluaran.php   ← CRUD Pengeluaran
│   │   ├── Budgeting.php     ← CRUD Budget
│   │   ├── Wishlist.php      ← CRUD Wishlist
│   │   ├── Tabungan.php      ← CRUD Tabungan
│   │   └── Laporan.php       ← Laporan + Export
│   │
│   ├── models/               ← Akses database
│   │   ├── User_model.php
│   │   ├── Pemasukan_model.php
│   │   ├── Pengeluaran_model.php
│   │   ├── Kategori_model.php
│   │   ├── Budgeting_model.php
│   │   ├── Wishlist_model.php
│   │   └── Tabungan_model.php
│   │
│   ├── views/
│   │   ├── layout/           ← Template AdminLTE
│   │   │   ├── main.php      ← Layout utama (wrapper + sidebar + content)
│   │   │   ├── auth.php      ← Layout login/register (tanpa sidebar)
│   │   │   ├── sidebar.php   ← AdminLTE main-sidebar
│   │   │   ├── header.php    ← AdminLTE main-header navbar
│   │   │   └── footer.php    ← AdminLTE main-footer
│   │   │
│   │   ├── auth/             ← Halaman login & register
│   │   ├── dashboard/        ← Halaman dashboard
│   │   ├── pemasukan/        ← index, create, edit
│   │   ├── pengeluaran/      ← index, create, edit
│   │   ├── budgeting/        ← index, create, edit
│   │   ├── wishlist/         ← index, create, edit
│   │   ├── tabungan/         ← index, create, edit
│   │   └── laporan/          ← index, print
│   │
│   ├── helpers/
│   └── libraries/
│
├── assets/
│   ├── adminlte/              ← AdminLTE core files
│   │   ├── css/adminlte.min.css
│   │   ├── js/adminlte.min.js
│   │   └── img/
│   ├── plugins/               ← Plugin AdminLTE
│   │   ├── jquery/
│   │   ├── bootstrap/
│   │   ├── fontawesome-free/
│   │   ├── chart.js/
│   │   ├── datatables-bs4/
│   │   ├── sweetalert2/
│   │   └── bs-custom-file-input/
│   ├── css/
│   │   └── custom.css         ← Override minimal FinTrack
│   ├── js/
│   │   └── app.js             ← JavaScript custom FinTrack
│   ├── img/
│   └── uploads/               ← File nota yang diupload
│
└── .env
```

---

## 3. Alur Request (Dari Klik Sampai Tampil)

### Contoh: User membuka halaman Pemasukan

```
1. User klik menu "Pemasukan" di sidebar AdminLTE
   URL: http://localhost:8080/pemasukan

2. routes.php mencocokkan URL:
   $route['pemasukan'] = 'pemasukan/index';

3. Controller Pemasukan::index() dijalankan:
   - Cek session login (jika belum → redirect ke auth/login)
   - Ambil user_id dari session
   - Panggil Pemasukan_model->get_by_user($userId)
   - Kirim data ke view

4. View pemasukan/index.php di-render:
   - Dimuat dalam layout/main.php (AdminLTE wrapper)
   - Sidebar, header, footer sudah terintegrasi
   - Tampilkan DataTables dengan data pemasukan

5. HTML dikirim ke browser → User melihat halaman
```

---

## 4. Alur Autentikasi

### Register (Daftar Akun Baru)

```
┌──────────────────────────────────────────────────────┐
│  1. User buka /auth/register                         │
│  2. Halaman register AdminLTE (register-page layout) │
│  3. Isi form: Nama, Email, Password, Konfirmasi      │
│  4. Submit form → POST /auth/process_register        │
│                                                      │
│  Controller Auth::process_register():                │
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
│  2. Halaman login AdminLTE (login-page layout)       │
│  3. Isi Email + Password                             │
│  4. Submit → POST /auth/process_login                │
│                                                      │
│  Controller Auth::process_login():                   │
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
Auth::logout()  →  session_destroy()  →  redirect ke /auth/login
```

---

## 5. Sistem Layout (Template AdminLTE)

FinTrack menggunakan 2 layout:

### Layout `main.php` — Untuk halaman utama (setelah login)

Menggunakan struktur AdminLTE:

```
┌──────────────────────────────────────────────────┐
│ MAIN HEADER (navbar)                              │
│ [≡] Toggle          User Name  [Logout]           │
├──────────┬───────────────────────────────────────┤
│          │ CONTENT HEADER                         │
│ MAIN     │ Judul Halaman        Breadcrumb        │
│ SIDEBAR  ├───────────────────────────────────────┤
│          │                                       │
│ FinTrack │ SECTION CONTENT                        │
│          │ (konten halaman di sini)               │
│ • Dash   │                                       │
│ • Masuk  │                                       │
│ • Keluar │                                       │
│ • Budget │                                       │
│ • Wish   │                                       │
│ • Tabung │                                       │
│ • Lapor  │                                       │
│          ├───────────────────────────────────────┤
│          │ MAIN FOOTER                            │
│          │ © 2026 FinTrack                        │
└──────────┴───────────────────────────────────────┘
```

Cara kerja:
```php
// Di main.php:
// Layout AdminLTE wrapper dengan sidebar, header, footer
// Konten dimuat via $content variable

// Di controller:
$data['content'] = $this->load->view('pemasukan/index', $data, TRUE);
$this->load->view('layout/main', $data);
```

### Layout `auth.php` — Untuk halaman login/register (tanpa sidebar)

Menggunakan layout AdminLTE login-page:

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
| `users` | Data pengguna | id, name, email, password |
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
Controller: Dashboard::index()
│
├── Query total saldo (pemasukan - pengeluaran all time)
├── Query pemasukan bulan ini
├── Query pengeluaran bulan ini
├── Query sisa budget bulan ini
├── Query data chart bulanan (12 bulan)
├── Query 5 transaksi terakhir (gabungan)
│
└── Render: dashboard/index.php
    ├── 4 Small-box AdminLTE (Saldo, Pemasukan, Pengeluaran, Sisa Budget)
    ├── Card + Chart.js Bar Chart (Cashflow tahunan)
    ├── Card + Tabel transaksi terakhir
    ├── Card + Budget progress bar
    └── Card + Quick action buttons
```

### 7.2 Pemasukan (`/pemasukan`)

```
ALUR CRUD:

LIST (/pemasukan)
  Controller: Pemasukan::index()
  ├── Pemasukan_model->get_by_user() dengan filter
  └── Render DataTables dalam card AdminLTE

CREATE (/pemasukan/create → POST /pemasukan/store)
  1. Tampilkan form dalam card AdminLTE
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
│   ├── File disimpan ke assets/uploads/
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
   │       ├── ≤ 80%  → Badge "Normal" (badge-success)
   │       ├── 80-100% → Badge "Warning" (badge-warning)
   │       └── > 100% → Badge "Over Budget" (badge-danger)
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
   ├── Belum Mulai → badge-secondary
   ├── Menabung → badge-warning
   └── Tercapai → badge-success

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
   ├── Ditampilkan sebagai Bootstrap progress bar
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

3. Tampilan (dalam card AdminLTE):
   ├── 3 Summary cards (info-box)
   ├── Doughnut chart (pengeluaran per kategori)
   ├── Tabel detail pemasukan
   └── Tabel detail pengeluaran

4. Export/Print:
   ├── Klik "Cetak / Export PDF"
   ├── Buka halaman /laporan/export (layout khusus cetak)
   ├── Otomatis trigger window.print()
   └── User bisa save as PDF dari dialog print browser
```

---

## 8. Sistem Styling

### AdminLTE 3.2 Components

Semua komponen visual menggunakan class AdminLTE dan Bootstrap 4 standar.

| Komponen | Class | Fungsi |
|----------|-------|--------|
| Small Box | `.small-box` | Stat card dashboard |
| Info Box | `.info-box` | Info card alternatif |
| Card | `.card` | Container utama |
| Table | `.table .table-bordered .table-striped` | Tabel data |
| DataTables | `#id` + JS init | Tabel interaktif |
| Button | `.btn .btn-primary/danger/success` | Tombol aksi |
| Badge | `.badge .badge-success/danger/warning` | Label status |
| Progress | `.progress .progress-bar` | Progress bar |
| Alert | `.alert .alert-success/danger` | Flash message |
| Modal | `.modal` | Dialog konfirmasi |
| Form | `.form-group .form-control` | Input form |
| Sidebar | `.main-sidebar .nav-sidebar` | Navigasi samping |
| Navbar | `.main-header .navbar` | Header atas |

### Warna Utama

```
Primary    : Bootstrap primary (biru)
Success    : Bootstrap success (hijau) — pemasukan
Danger     : Bootstrap danger (merah) — pengeluaran
Warning    : Bootstrap warning (kuning) — budget warning
Info       : Bootstrap info (cyan) — informasi
```

### Ikon

```
Font Awesome 5 (bawaan AdminLTE)
Contoh: fas fa-wallet, fas fa-chart-bar, fas fa-piggy-bank
```

---

## 9. Alur Data: Dari Input Sampai Tampil di Dashboard

```
Contoh: User menambah pemasukan Rp 5.000.000

1. User klik "Tambah Pemasukan" di Dashboard
   → Buka /pemasukan/create

2. User isi form (dalam card AdminLTE):
   Tanggal: 2026-05-18
   Nominal: 5000000
   Sumber: Gaji
   Catatan: Gaji bulan Mei

3. Submit → POST /pemasukan/store
   → Controller validasi
   → Pemasukan_model->insert([...])
   → Data masuk ke tabel 'pemasukan'

4. Redirect ke /pemasukan → tampil di DataTables

5. User kembali ke Dashboard (/)
   → Dashboard::index() query ulang:
   ├── get_total_all() → Saldo terupdate (small-box)
   ├── get_total_bulan_ini() → Pemasukan bulan ini terupdate
   ├── get_monthly_totals() → Chart terupdate
   └── get_recent() → Transaksi terakhir terupdate

6. Dashboard menampilkan data terbaru
```

---

## 10. Keamanan

| Mekanisme | Implementasi |
|-----------|-------------|
| Password hashing | `password_hash()` + `password_verify()` |
| Session-based auth | CI3 Session library |
| Route protection | Cek session di constructor controller |
| Data ownership | Setiap query di-filter by `user_id` dari session |
| CSRF protection | CI3 CSRF token |
| Input escaping | `htmlspecialchars()` di output view |
| SQL injection | CI3 Query Builder (parameterized) |

---

## 11. Cara Menjalankan

```bash
# 1. Masuk ke folder project
cd /home/ichawfa/Documents/CodeOnFedora/fintrack

# 2. Pastikan database sudah ada
#    (sudah dibuat: fintrack_db)

# 3. Import tabel dari DATABASE.md

# 4. Pastikan AdminLTE assets sudah ada di assets/

# 5. Jalankan server development
php -S localhost:8080 -t .

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
```

---

*Dokumen ini dibuat untuk project FinTrack — Sistem Informasi Keuangan Pribadi menggunakan AdminLTE 3.2.*
