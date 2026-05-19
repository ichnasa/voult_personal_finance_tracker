# FinTrack — Sistem Informasi Keuangan Pribadi

## Deskripsi

FinTrack adalah sistem informasi keuangan pribadi berbasis web yang membantu pengguna mengelola pemasukan, pengeluaran, budgeting, wishlist barang, target tabungan, dan laporan keuangan.

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8+ / CodeIgniter 3 |
| Database | MySQL |
| Template UI | AdminLTE 3.2 |
| CSS Framework | Bootstrap 4 (via AdminLTE) |
| Icons | Font Awesome 5 |
| Charts | Chart.js |
| Data Tables | DataTables |
| Dialogs | SweetAlert2 |

## Fitur Utama

* 🔐 Autentikasi (Login, Register, Logout)
* 📊 Dashboard (Stat cards, grafik cashflow, transaksi terbaru)
* 💰 Manajemen Pemasukan (CRUD + filter)
* 💸 Manajemen Pengeluaran (CRUD + upload nota)
* 📋 Budgeting (Budget per kategori + monitoring)
* ⭐ Wishlist (Barang impian + prioritas)
* 🐷 Tabungan (Target + progress tracking)
* 📄 Laporan Keuangan (Filter + export PDF)

## Cara Menjalankan

### Prerequisites

* PHP 8+
* MySQL
* Apache (XAMPP/LAMP)
* Composer

### Instalasi

```bash
# 1. Clone atau download project
cd /path/to/fintrack

# 2. Buat database
mysql -u root -p -e "CREATE DATABASE fintrack_db;"

# 3. Konfigurasi database
#    Edit application/config/database.php
#    Sesuaikan hostname, username, password, database

# 4. Import tabel (atau jalankan SQL dari DATABASE.md)

# 5. Download AdminLTE 3.2
#    https://github.com/ColorlibHQ/AdminLTE/releases/tag/v3.2.0
#    Letakkan di assets/adminlte/ dan assets/plugins/

# 6. Set permission upload
chmod -R 775 assets/uploads

# 7. Jalankan server
php -S localhost:8080 -t .

# 8. Buka browser
#    http://localhost:8080
```

## Struktur Folder

```
fintrack/
├── application/
│   ├── config/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   │   ├── layout/       ← Template AdminLTE
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── pemasukan/
│   │   ├── pengeluaran/
│   │   ├── budgeting/
│   │   ├── wishlist/
│   │   ├── tabungan/
│   │   └── laporan/
│   └── ...
├── assets/
│   ├── adminlte/          ← AdminLTE core (css, js, img)
│   ├── plugins/           ← Plugin AdminLTE (jQuery, Bootstrap, dll)
│   ├── css/custom.css     ← Custom CSS FinTrack
│   ├── js/app.js          ← Custom JS FinTrack
│   ├── img/
│   └── uploads/
└── .env
```

## Dokumentasi

* [DATABASE.md](DATABASE.md) — Skema database
* [DESIGN.md](DESIGN.md) — Panduan desain UI (AdminLTE)
* [DOCUMENTATION.md](DOCUMENTATION.md) — Alur aplikasi
* [FEATURES.md](FEATURES.md) — Daftar fitur
* [REQUIREMENT.md](REQUIREMENT.md) — Requirement system
* [SKILL.md](SKILL.md) — Panduan development
* [SRS.md](SRS.md) — Software Requirements Specification
* [TODO.md](TODO.md) — Checklist development

## Lisensi

Project skripsi — untuk keperluan akademik.
