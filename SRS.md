# SRS.md — FinTrack

# Overview

FinTrack adalah sistem informasi keuangan pribadi berbasis web yang membantu pengguna mengelola:

* pemasukan
* pengeluaran
* budgeting
* wishlist barang
* target tabungan
* laporan keuangan

Sistem dibangun menggunakan:

* CodeIgniter 4
* MySQL
* Tabler (Dashboard Template)
* Bootstrap 5

---

# Core Features

# 1. Authentication System

## Login

Pengguna dapat masuk menggunakan:

* email
* password

### Features

* session login
* remember session
* validation form
* flash message

---

## Register

Pengguna dapat membuat akun baru.

### Features

* validasi email unik
* password hashing
* form validation

---

## Logout

Menghapus session login pengguna.

---

# 2. Dashboard

Dashboard menampilkan ringkasan kondisi keuangan pengguna.

## Dashboard Widgets

### Total Saldo

Menampilkan:

```text
Total Pemasukan - Total Pengeluaran
```

---

### Total Pemasukan

Total pemasukan bulan berjalan.

---

### Total Pengeluaran

Total pengeluaran bulan berjalan.

---

### Sisa Budget

Menampilkan sisa budget bulanan.

---

### Grafik Cashflow

Grafik:

* pemasukan
* pengeluaran

Menggunakan:

* Chart.js

---

### Progress Tabungan

Menampilkan:

* target tabungan
* progress
* persentase

---

### Transaksi Terbaru

Menampilkan:

* 5 transaksi terakhir

---

### Wishlist Prioritas

Menampilkan wishlist dengan prioritas tinggi.

---

# 3. Modul Pemasukan

Mengelola data pemasukan pengguna.

## Features

* tambah pemasukan
* edit pemasukan
* hapus pemasukan
* list pemasukan
* filter tanggal
* pagination (DataTables)

## Data

* tanggal
* nominal
* sumber pemasukan
* catatan

---

# 4. Modul Pengeluaran

Mengelola data pengeluaran pengguna.

## Features

* tambah pengeluaran
* edit pengeluaran
* hapus pengeluaran
* filter kategori
* filter tanggal
* upload nota
* pagination (DataTables)

## Data

* tanggal
* nominal
* kategori
* metode pembayaran
* catatan
* foto nota

---

# 5. Modul Budgeting

Mengelola budget bulanan.

## Features

* membuat budget
* edit budget
* monitoring penggunaan budget
* warning budget
* progress budget

## Status Budget

* normal
* warning (>80%)
* danger (>100%)

---

# 6. Modul Wishlist

Mengelola barang impian pengguna.

## Features

* tambah wishlist
* edit wishlist
* hapus wishlist
* status wishlist
* prioritas wishlist

## Status

* belum mulai
* menabung
* tercapai

---

# 7. Modul Tabungan

Mengelola target tabungan.

## Features

* tambah target tabungan
* update nominal tabungan
* progress tabungan
* deadline target

## Formula

```text
(nominal_terkumpul / target_nominal) * 100
```

---

# 8. Modul Laporan

Menghasilkan laporan keuangan.

## Features

* laporan pemasukan
* laporan pengeluaran
* filter tanggal
* export PDF

---

# 9. Modul Profile

Mengelola data profil pengguna dan menampilkan ringkasan finansial pribadi.

## Profile Dashboard Widgets

### Stat Cards

* total saldo
* pemasukan bulan ini (+ % vs bulan lalu)
* pengeluaran bulan ini (+ % vs bulan lalu)
* sisa budget (+ % tersisa)

### Financial Health

* spending ratio
* saving ratio
* budget discipline
* overall score (0-100)

### Cashflow History

* line chart 6 bulan terakhir

### Saving Goals

* tabungan aktif + progress bar

### Wishlist Priority

* wishlist prioritas tinggi + badge status

### Transaction History

* 10 transaksi terakhir gabungan

## Profile Management

* edit profil (nama, email, telepon, alamat) via modal
* upload foto profil (avatar) via modal
* ubah password via modal

## Data

* nama
* email
* telepon
* alamat
* foto profil (avatar)

---

# 10. UI/UX Features

## Design Style

* Tabler admin dashboard
* clean modern layout
* responsive design

## UI Components

* stat cards with card-stamp (Tabler)
* cards (Tabler / Bootstrap 5)
* Chart.js charts
* Bootstrap 5 progress bars
* DataTables
* Bootstrap 5 forms
* Tabler navbar-vertical navigation
* Tabler Icons

---

# 11. Security Features

## Authentication

* session authentication
* password hashing

## Validation

* server-side validation
* XSS filtering
* input sanitization

---

# 12. Future Features

Fitur pengembangan:

* export excel
* multiple wallet
* notification system
