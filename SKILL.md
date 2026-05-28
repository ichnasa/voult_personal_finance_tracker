# SKILL.md — FinTrack (Sistem Informasi Keuangan Pribadi)

## Role

Kamu adalah senior fullstack developer yang ahli dalam:

* CodeIgniter 4
* MySQL
* Tabler (Admin Dashboard Template)
* Bootstrap 5
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

Menggunakan template Tabler yang sudah teruji dan modern.

---

# Tech Stack

## Backend

* PHP 8+
* CodeIgniter 4
* MySQL

## Frontend

* Tabler (template dashboard — https://tabler.io)
* Bootstrap 5 (via Tabler)
* Tabler Icons
* Chart.js
* DataTables
* SweetAlert2

---

# Arsitektur Wajib

Gunakan pattern MVC CodeIgniter 4 secara bersih.

## Struktur folder

app/
├── Controllers/
├── Models/
├── Filters/
├── Views/
│   ├── layout/
│   ├── dashboard/
│   ├── pemasukan/
│   ├── pengeluaran/
│   ├── budgeting/
│   ├── wishlist/
│   ├── tabungan/
│   ├── profile/
│   ├── laporan/
│   └── auth/
├── Helpers/
└── Config/

public/assets/
├── css/
│   └── custom.css
├── js/
│   └── app.js
├── img/
└── uploads/

---

# Aturan Frontend (WAJIB)

BACA DESIGN.md DAN IKUTI SEPENUHNYA.

## Tabler Rules

Gunakan komponen Tabler standar:

* `.card` untuk container
* `.card` + `.card-stamp` untuk stat cards
* `.table` + `.table-vcenter` + DataTables untuk tabel data
* `.btn` + varian Bootstrap untuk tombol
* `.badge` + `.bg-*` untuk status label
* `.alert` untuk flash message
* `.modal` + `.modal-blur` untuk dialog
* `.progress` untuk progress bar
* `.mb-3` + `.form-label` + `.form-control` untuk form

Gunakan Bootstrap 5 utilities:

* `row`, `col-md-*` untuk grid
* `d-flex`, `justify-content-*` untuk flex
* `mb-*`, `mt-*`, `p-*` untuk spacing
* `text-success`, `text-danger` untuk warna teks
* `bg-info`, `bg-success`, `bg-danger`, `bg-warning` untuk background
* `ms-*`, `me-*` untuk margin start/end (bukan `ml-*`, `mr-*`)

---

# Design Direction

Aesthetic:

* modern admin dashboard
* professional
* terorganisir
* Tabler standard look
* konsisten di semua halaman

Karakter visual:

* dark sidebar (navbar-vertical dark theme)
* clean navbar header
* card-based layout
* DataTables untuk semua tabel
* Tabler Icons
* Inter font (default Tabler)

---

# Typography Rules

Gunakan:

* Inter → semua teks (default Tabler)

Font default Tabler, jangan diubah.

---

# Color Rules

Warna hijau (success / green):

* hanya untuk positif
* pemasukan
* CTA utama

Warna merah (danger / red):

* pengeluaran
* error
* budget berbahaya

Warna kuning (warning / yellow):

* budget hampir habis
* deadline mendekat

Tidak boleh dibalik.

---

# Coding Style

## PHP

* Gunakan clean code
* Hindari query langsung di controller
* Semua query di model
* Gunakan Query Builder CodeIgniter 4
* Validasi menggunakan CI4 validation

## Naming

Controller:

* Home.php (Dashboard)
* Pemasukan.php
* Pengeluaran.php
* Profile.php

Model:

* PemasukanModel.php
* PengeluaranModel.php

View:

* index.php
* create.php
* edit.php

---

# Authentication Rules

Gunakan:

* CI4 session
* CI4 Filters (AuthFilter)

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

Gunakan `.card` + `.card-stamp` Tabler:

* total saldo (bg-azure / bg-info)
* total pemasukan (bg-green / bg-success)
* total pengeluaran (bg-red / bg-danger)
* sisa budget (bg-yellow / bg-warning)

## Charts

Gunakan Chart.js:

* cashflow bulanan (bar chart)
* pengeluaran kategori (doughnut)

## Widgets

Gunakan `.card` Tabler:

* transaksi terbaru (table)
* wishlist prioritas (list)
* progress tabungan (progress bar)

---

# Financial Rules

Saldo dihitung:
total pemasukan - total pengeluaran

Budget warning:

* > 80% = warning (bg-warning)
* > 100% = danger (bg-danger)

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

* `.mb-3`
* `<label class="form-label">`
* `.form-control`

Form dalam `.card` Tabler dengan `.card-header`, `.card-body`, `.card-footer`.

---

# Table Rules

Semua tabel:

* `.table .table-vcenter`
* DataTables plugin (BS5)
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

* mengubah file core Tabler
* membuat komponen custom yang sudah ada di Tabler
* menggunakan framework CSS lain
* menggunakan Bootstrap 4 attributes (`data-toggle` dll — gunakan `data-bs-toggle`)

---

# Responsive Rules

Tabler sudah responsive secara bawaan:

Desktop:

* sidebar expanded

Tablet:

* sidebar auto-collapse

Mobile:

* sidebar hidden, toggle via hamburger

Gunakan class `navbar-vertical navbar-expand-lg` pada sidebar.

---

# Output Rules

Saat generate code:

* selalu lengkap
* langsung runnable
* jangan pseudo code
* gunakan syntax CodeIgniter 4 asli

Jika membuat view:

* gunakan komponen Tabler sesuai DESIGN.md

Jika membuat CSS:

* masukkan ke custom.css
* jangan ubah file Tabler core

Jika membuat query:

* gunakan CI4 query builder

Jika membuat controller:

* validasi form (CI4 validation)
* sanitasi input
* redirect dengan flashdata

---

# Prioritas Kualitas

Prioritas utama:

1. Struktur code rapi
2. Konsistensi komponen Tabler
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
2. gunakan komponen Tabler standar
3. gunakan Bootstrap 5 utilities
4. pertahankan konsistensi visual di seluruh halaman
5. ikuti struktur layout Tabler
