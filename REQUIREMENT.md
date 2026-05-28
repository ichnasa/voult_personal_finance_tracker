# REQUIREMENTS.md — FinTrack

# 1. Functional Requirements

## Authentication

* User dapat register
* User dapat login
* User dapat logout

## Dashboard

* Menampilkan total saldo
* Menampilkan total pemasukan
* Menampilkan total pengeluaran
* Menampilkan grafik cashflow

## Pemasukan

* Tambah pemasukan
* Edit pemasukan
* Hapus pemasukan
* Filter data pemasukan

## Pengeluaran

* Tambah pengeluaran
* Edit pengeluaran
* Hapus pengeluaran
* Upload nota

## Budgeting

* Membuat budget bulanan
* Monitoring penggunaan budget
* Warning budget hampir habis

## Wishlist

* Menambahkan wishlist barang
* Menentukan target harga
* Menampilkan progress tabungan

## Tabungan

* Membuat target tabungan
* Menampilkan progress target

## Laporan

* Export laporan PDF
* Filter laporan berdasarkan tanggal

## Profile

* Melihat data profil pengguna
* Mengedit data profil (nama, email)
* Mengubah password
* Mengupload foto profil (avatar)

---

# 2. Non-Functional Requirements

## Performance

* Halaman load < 3 detik
* Query database optimal

## Security

* Password di-hash
* Validasi input
* Session authentication

## Usability

* Responsive
* Mudah digunakan
* UI konsisten

## Maintainability

* Menggunakan MVC
* Struktur code rapi
* Reusable component

## Compatibility

* Support Chrome
* Support Edge
* Support Firefox

---

# 3. Technical Requirements

## Backend

* PHP 8+
* CodeIgniter 4

## Frontend

* Tabler (Dashboard Template)
* Bootstrap 5 (via Tabler)
* Tabler Icons
* Chart.js
* DataTables

## Database

* MySQL

## Architecture

* MVC Pattern
* Query Builder
* Session-based authentication
* CI4 Filters (middleware)

## Hosting

* Apache / XAMPP
* Shared hosting compatible

---

# 4. User Requirements

## User

* Mengelola keuangan pribadi
* Melihat laporan keuangan
* Mengatur target tabungan

---

# 5. System Requirements

## Hardware

* RAM minimal 4GB
* Storage minimal 1GB

## Software

* PHP 8+
* MySQL
* Apache
* Browser modern

---

# 6. Constraints

* Sistem berbasis web
* Menggunakan template Tabler sebagai fondasi UI
* Menggunakan Bootstrap 5 (bawaan Tabler)
* Tidak mengubah file core Tabler
