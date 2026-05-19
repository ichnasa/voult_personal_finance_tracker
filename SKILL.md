# SKILL.md — FinTrack (Sistem Informasi Keuangan Pribadi)

## Role

Kamu adalah senior fullstack developer yang ahli dalam:

* CodeIgniter 3
* MySQL
* AdminLTE 3.2
* Bootstrap 4
* Admin Dashboard Template
* Sistem Informasi Keuangan
* Clean architecture MVC

Project ini adalah:

> Sistem Informasi Keuangan Pribadi untuk skripsi mahasiswa.

Nama sistem:

> FinTrack — Personal Finance Information System

---

# Tujuan Sistem

Membantu pengguna mengelola:

* pemasukan
* pengeluaran
* budgeting
* wishlist barang
* target tabungan
* laporan keuangan

Sistem harus terlihat:

* profesional
* rapi
* modern
* terorganisir
* seperti admin dashboard

Menggunakan template AdminLTE 3.2 yang sudah teruji.

---

# Tech Stack

## Backend

* PHP 8+
* CodeIgniter 3
* MySQL

## Frontend

* AdminLTE 3.2 (template dashboard)
* Bootstrap 4 (via AdminLTE)
* jQuery 3.x
* Chart.js
* DataTables
* Font Awesome 5
* SweetAlert2

---

# Arsitektur Wajib

Gunakan pattern MVC CodeIgniter secara bersih.

## Struktur folder

application/
├── controllers/
├── models/
├── views/
│   ├── layout/
│   ├── dashboard/
│   ├── pemasukan/
│   ├── pengeluaran/
│   ├── budgeting/
│   ├── wishlist/
│   ├── tabungan/
│   ├── laporan/
│   └── auth/
├── helpers/
├── libraries/
└── config/

assets/
├── adminlte/
│   ├── css/
│   ├── js/
│   └── img/
├── plugins/
│   ├── bootstrap/
│   ├── jquery/
│   ├── fontawesome-free/
│   ├── chart.js/
│   ├── datatables-bs4/
│   └── sweetalert2/
├── css/
│   └── custom.css
├── js/
│   └── app.js
├── img/
└── uploads/

---

# Aturan Frontend (WAJIB)

BACA DESIGN.md DAN IKUTI SEPENUHNYA.

## AdminLTE Rules

Gunakan komponen AdminLTE standar:

* `.card` untuk container
* `.small-box` / `.info-box` untuk stat cards
* `.table` + DataTables untuk tabel data
* `.btn` + varian Bootstrap untuk tombol
* `.badge` untuk status label
* `.alert` untuk flash message
* `.modal` untuk dialog
* `.progress` untuk progress bar
* `.form-group` + `.form-control` untuk form

Gunakan Bootstrap 4 utilities:

* `row`, `col-md-*` untuk grid
* `d-flex`, `justify-content-*` untuk flex
* `mb-*`, `mt-*`, `p-*` untuk spacing
* `text-success`, `text-danger` untuk warna teks
* `bg-info`, `bg-success`, `bg-danger`, `bg-warning` untuk background

---

# Design Direction

Aesthetic:

* clean admin dashboard
* professional
* terorganisir
* AdminLTE standard look
* konsisten di semua halaman

Karakter visual:

* sidebar gelap (sidebar-dark-primary)
* navbar terang
* card-based layout
* DataTables untuk semua tabel
* Font Awesome icons

---

# Typography Rules

Gunakan:

* Source Sans Pro → semua teks (default AdminLTE)

Font default AdminLTE, jangan diubah.

---

# Color Rules

Warna hijau (success):

* hanya untuk positif
* pemasukan
* CTA utama

Warna merah (danger):

* pengeluaran
* error
* budget berbahaya

Warna kuning (warning):

* budget hampir habis
* deadline mendekat

Tidak boleh dibalik.

---

# Coding Style

## PHP

* Gunakan clean code
* Hindari query langsung di controller
* Semua query di model
* Gunakan Query Builder CodeIgniter
* Validasi menggunakan form_validation

## Naming

Controller:

* Dashboard.php
* Pemasukan.php
* Pengeluaran.php

Model:

* Pemasukan_model.php
* Pengeluaran_model.php

View:

* index.php
* create.php
* edit.php

---

# Authentication Rules

Gunakan:

* session login
* middleware sederhana
* helper auth

Fitur:

* login
* register
* logout

Password:

* password_hash()
* password_verify()

---

# Database Rules

Gunakan relasi jelas.

Tabel utama:

* users
* pemasukan
* pengeluaran
* kategori
* budgeting
* wishlist
* tabungan

Semua tabel:

* id
* created_at
* updated_at

Gunakan foreign key.

---

# Dashboard Rules

Dashboard WAJIB memiliki:

## Stat Cards

Gunakan `.small-box` AdminLTE:

* total saldo (bg-info)
* total pemasukan (bg-success)
* total pengeluaran (bg-danger)
* sisa budget (bg-warning)

## Charts

Gunakan Chart.js:

* cashflow bulanan (bar chart)
* pengeluaran kategori (doughnut)

## Widgets

Gunakan `.card` AdminLTE:

* transaksi terbaru (table)
* wishlist prioritas (list)
* progress tabungan (progress bar)

---

# Financial Rules

Saldo dihitung:
total pemasukan - total pengeluaran

Budget warning:

* > 80% = warning (badge-warning)
* > 100% = danger (badge-danger)

Progress tabungan:
(current / target) * 100

---

# Form Rules

Semua form:

* validasi
* error message
* old input
* flash message

Gunakan:

* `.form-group`
* `<label>`
* `.form-control`

Form dalam `.card` AdminLTE dengan `.card-header`, `.card-body`, `.card-footer`.

---

# Table Rules

Semua tabel:

* `.table .table-bordered .table-striped`
* DataTables plugin
* responsive
* search dan pagination otomatis

Nominal:

* hijau (text-success) = pemasukan
* merah (text-danger) = pengeluaran

---

# UX Rules

UI harus terasa:

* cepat
* terorganisir
* profesional
* konsisten
* mudah digunakan

Tidak boleh:

* mengubah file core AdminLTE
* membuat komponen custom yang sudah ada di AdminLTE
* menggunakan framework CSS lain
* menghapus jQuery

---

# Responsive Rules

AdminLTE sudah responsive secara bawaan:

Desktop:

* sidebar expanded

Tablet:

* sidebar auto-collapse

Mobile:

* sidebar hidden, toggle via hamburger

Gunakan class `sidebar-mini` di `<body>`.

---

# Output Rules untuk Claude

Saat generate code:

* selalu lengkap
* langsung runnable
* jangan pseudo code
* gunakan syntax CodeIgniter asli

Jika membuat view:

* gunakan komponen AdminLTE sesuai DESIGN.md

Jika membuat CSS:

* masukkan ke custom.css
* jangan ubah adminlte.min.css

Jika membuat query:

* gunakan query builder

Jika membuat controller:

* validasi form
* sanitasi input
* redirect dengan flashdata

---

# Prioritas Kualitas

Prioritas utama:

1. Struktur code rapi
2. Konsistensi komponen AdminLTE
3. MVC bersih
4. UI profesional
5. Readability
6. Responsiveness

Bukan:

* custom design system
* efek fancy
* styling random

---

# Expected Feel

Aplikasi harus terasa seperti:

* admin dashboard profesional
* sistem manajemen terstruktur
* panel admin modern

BUKAN seperti:

* desain random tanpa template
* UI tanpa framework
* landing page marketing

---

# Rule Final

SEBELUM menulis code:

1. baca DESIGN.md
2. gunakan komponen AdminLTE standar
3. gunakan Bootstrap 4 utilities
4. pertahankan konsistensi visual di seluruh halaman
5. ikuti struktur layout AdminLTE
