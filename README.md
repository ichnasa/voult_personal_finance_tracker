# FinTrack — Sistem Informasi Keuangan Pribadi

## Deskripsi

FinTrack adalah sistem informasi keuangan pribadi berbasis web yang membantu pengguna mengelola pemasukan, pengeluaran, budgeting, wishlist barang, target tabungan, dan laporan keuangan.

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8+ / CodeIgniter 4 |
| Database | MySQL |
| Template UI | Tabler (https://tabler.io) |
| CSS Framework | Bootstrap 5 (via Tabler) |
| Icons | Tabler Icons |
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
* 👤 Profile (Edit profil + ubah password + upload avatar)

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

# 2. Install dependencies
composer install

# 3. Buat database
mysql -u root -p -e "CREATE DATABASE fintrack_db;"

# 4. Konfigurasi database
#    Salin env ke .env, sesuaikan konfigurasi database

# 5. Import tabel (atau jalankan SQL dari DATABASE.md)

# 6. Tabler dimuat via CDN — tidak perlu download manual

# 7. Set permission upload
chmod -R 775 public/assets/uploads

# 8. Jalankan server
php spark serve --port 8080

# 9. Buka browser
#    http://localhost:8080
```

## Struktur Folder

```
fintrack/
├── app/
│   ├── Config/
│   ├── Controllers/
│   ├── Models/
│   ├── Filters/
│   ├── Views/
│   │   ├── layout/       ← Template Tabler
│   │   ├── auth/
│   │   ├── dashboard/
│   │   ├── pemasukan/
│   │   ├── pengeluaran/
│   │   ├── budgeting/
│   │   ├── wishlist/
│   │   ├── tabungan/
│   │   ├── profile/
│   │   └── laporan/
│   └── ...
├── public/
│   └── assets/
│       ├── css/custom.css     ← Custom CSS FinTrack
│       ├── js/app.js          ← Custom JS FinTrack
│       ├── img/
│       └── uploads/
└── .env
```

## Dokumentasi

* [DATABASE.md](DATABASE.md) — Skema database
* [DESIGN.md](DESIGN.md) — Panduan desain UI (Tabler)
* [DOCUMENTATION.md](DOCUMENTATION.md) — Alur aplikasi
* [FEATURES.md](FEATURES.md) — Daftar fitur
* [REQUIREMENT.md](REQUIREMENT.md) — Requirement system
* [SKILL.md](SKILL.md) — Panduan development
* [SRS.md](SRS.md) — Software Requirements Specification
* [TODO.md](TODO.md) — Checklist development

## Lisensi

Project skripsi — untuk keperluan akademik.
