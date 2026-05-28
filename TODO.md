# TODO.md — FinTrack

# Environment Information

## Development Environment

* OS: Linux
* Framework: CodeIgniter 4
* Database: MySQL
* Database Username: root
* Database Password: Qwerty12!
* Database Name: fintrack_db

## Stack

* PHP 8+
* Apache
* Tabler (Admin Dashboard Template)
* Bootstrap 5 (via Tabler)
* Chart.js
* DataTables
* Tabler Icons

---

# Phase 0 — Initial Setup

## Environment Setup

* [ ] Verify PHP installation
* [ ] Verify Apache installation
* [ ] Verify MySQL installation
* [ ] Verify CodeIgniter 4 project running
* [ ] Configure base_url
* [ ] Configure timezone Asia/Jakarta

## Database Setup

* [ ] Login MySQL using:

```bash
mysql -u root -p
```

* [ ] Create database:

```sql
CREATE DATABASE fintrack_db;
```

* [ ] Configure `.env` file (database settings)
* [ ] Test database connection

## Linux Permissions

* [ ] Create uploads directory
* [ ] Set upload permission:

```bash
chmod -R 775 public/assets/uploads
```

---

# Phase 1 — Project Structure

## Folder Structure

* [ ] Setup Tabler via CDN (atau download ke public/assets/)
* [ ] Setup public/assets/css/custom.css
* [ ] Setup public/assets/js/app.js
* [ ] Setup public/assets/uploads
* [ ] Setup public/assets/img

## View Structure

* [ ] Create layout folder
* [ ] Create dashboard folder
* [ ] Create auth folder
* [ ] Create pemasukan folder
* [ ] Create pengeluaran folder
* [ ] Create budgeting folder
* [ ] Create wishlist folder
* [ ] Create tabungan folder
* [ ] Create profile folder
* [ ] Create laporan folder

## Shared Layout

* [ ] main.php layout (menggunakan struktur Tabler page-wrapper)
* [ ] sidebar.php (Tabler navbar-vertical)
* [ ] header.php (Tabler navbar)
* [ ] footer.php (Tabler footer)
* [ ] auth.php layout (Tabler page page-center)

---

# Phase 2 — Tabler Integration

## Setup Tabler

* [ ] Load Tabler CSS di layout (CDN atau lokal)
* [ ] Load Tabler JS di layout
* [ ] Load Bootstrap 5 JS (bundled dalam Tabler)
* [ ] Load Tabler Icons (CDN atau webfont)
* [ ] Test layout Tabler berjalan

## Layout Components

* [ ] Navbar vertical / sidebar navigation
* [ ] Navbar header
* [ ] Page wrapper + page body
* [ ] Footer
* [ ] Responsive sidebar toggle

## Plugin Integration

* [ ] DataTables Bootstrap 5
* [ ] Chart.js
* [ ] SweetAlert2

## Custom Styling

* [ ] Buat custom.css untuk override minimal
* [ ] Test responsiveness

---

# Phase 3 — Database Development

## Tables

* [ ] users
* [ ] kategori
* [ ] pemasukan
* [ ] pengeluaran
* [ ] budgeting
* [ ] wishlist
* [ ] tabungan

## Relationships

* [ ] Foreign key users
* [ ] Foreign key kategori
* [ ] Foreign key wishlist

## Optimization

* [ ] Add indexes
* [ ] Add dummy seed data

---

# Phase 4 — Authentication Module

## Authentication Logic

* [ ] Login
* [ ] Register
* [ ] Logout
* [ ] Session authentication

## Validation

* [ ] Email validation
* [ ] Password validation
* [ ] Unique email validation

## Security

* [ ] Password hashing
* [ ] Password verify
* [ ] Session protection

## Views

* [ ] Login page (Tabler page-center layout)
* [ ] Register page (Tabler page-center layout)
* [ ] Flash message (Bootstrap 5 alert)

---

# Phase 5 — Main Layout

## Sidebar (Tabler navbar-vertical)

* [ ] Brand logo
* [ ] Dashboard menu
* [ ] Pemasukan menu
* [ ] Pengeluaran menu
* [ ] Budgeting menu
* [ ] Wishlist menu
* [ ] Tabungan menu
* [ ] Profile menu
* [ ] Laporan menu
* [ ] Logout menu

## Navbar (Tabler navbar)

* [ ] Sidebar toggle button
* [ ] User info
* [ ] Logout button

## Content

* [ ] Page header
* [ ] Page body

---

# Phase 6 — Dashboard

## Statistics (Tabler stat card)

* [ ] Total saldo
* [ ] Total pemasukan
* [ ] Total pengeluaran
* [ ] Sisa budget

## Charts (Card + Chart.js)

* [ ] Cashflow chart (bar)
* [ ] Expense category chart (doughnut)

## Widgets (Card Tabler)

* [ ] Recent transactions (table)
* [ ] Wishlist progress (list)
* [ ] Savings progress (progress bar)

---

# Phase 7 — Pemasukan Module

## CRUD

* [ ] List pemasukan (DataTables)
* [ ] Create pemasukan (form dalam card)
* [ ] Edit pemasukan (form dalam card)
* [ ] Delete pemasukan (SweetAlert2 konfirmasi)

## Features

* [ ] DataTables pagination & search
* [ ] Date filter
* [ ] Search

## Validation

* [ ] Required field validation
* [ ] Numeric validation

---

# Phase 8 — Pengeluaran Module

## CRUD

* [ ] List pengeluaran (DataTables)
* [ ] Create pengeluaran (form dalam card)
* [ ] Edit pengeluaran (form dalam card)
* [ ] Delete pengeluaran (SweetAlert2 konfirmasi)

## Features

* [ ] Upload nota
* [ ] Category filter
* [ ] Date filter
* [ ] DataTables pagination & search

## Upload

* [ ] Upload validation
* [ ] File restriction
* [ ] File storage

---

# Phase 9 — Budgeting Module

## Features

* [ ] Create budget (form dalam card)
* [ ] Edit budget
* [ ] Delete budget
* [ ] Budget monitoring (progress bar)
* [ ] Budget warning (badge)

## Logic

* [ ] Budget percentage
* [ ] Remaining budget

---

# Phase 10 — Wishlist Module

## CRUD

* [ ] Create wishlist
* [ ] Edit wishlist
* [ ] Delete wishlist

## Features

* [ ] Priority status (badge)
* [ ] Wishlist progress
* [ ] Wishlist status

---

# Phase 11 — Tabungan Module

## CRUD

* [ ] Create tabungan
* [ ] Edit tabungan
* [ ] Delete tabungan

## Features

* [ ] Progress calculation (progress bar)
* [ ] Deadline tracking
* [ ] Monthly simulation

---

# Phase 12 — Laporan Module

## Reports

* [ ] Income report
* [ ] Expense report
* [ ] Budget report

## Export

* [ ] Export PDF
* [ ] Printable report

## Filters

* [ ] Date filter
* [ ] Category filter

---

# Phase 12.5 — Profile Module

## Views

* [ ] Profile header card (avatar + nama + email + actions)
* [ ] Stat cards (saldo, pemasukan, pengeluaran, sisa budget)
* [ ] Financial health card (donut chart + ratios)
* [ ] Cashflow history card (line chart 6 bulan)
* [ ] Saving goals card (progress bar tabungan aktif)
* [ ] Wishlist priority card (badge prioritas + status)
* [ ] Transaction history card (tabel 10 transaksi terakhir)
* [ ] Modal edit profil
* [ ] Modal ubah password
* [ ] Modal upload avatar

## Profile Dashboard Data

* [ ] Total saldo (pemasukan - pengeluaran all time)
* [ ] Pemasukan bulan ini + % perubahan vs bulan lalu
* [ ] Pengeluaran bulan ini + % perubahan vs bulan lalu
* [ ] Sisa budget + % tersisa
* [ ] Financial health score (spending ratio, saving ratio, discipline)
* [ ] Cashflow history data 6 bulan
* [ ] Tabungan aktif (max 3)
* [ ] Wishlist prioritas tinggi (max 3)
* [ ] Transaksi terakhir gabungan (max 10)

## Profile Management

* [ ] Edit profil (nama, email, telepon, alamat)
* [ ] Upload foto profil (avatar)
* [ ] Ubah password (password lama, baru, konfirmasi)

## Validation

* [ ] Nama minimal 3 karakter
* [ ] Email unik (kecuali milik sendiri)
* [ ] Password lama harus benar
* [ ] Password baru minimal 8 karakter
* [ ] Avatar max 2MB (JPG/PNG)

## Database

* [ ] Tambah kolom phone di tabel users
* [ ] Tambah kolom address di tabel users
* [ ] Tambah kolom avatar di tabel users

---

# Phase 13 — Security & Validation

## Security

* [ ] XSS filtering
* [ ] CSRF protection
* [ ] Session validation
* [ ] Route protection

## Validation

* [ ] Form validation
* [ ] Upload validation
* [ ] Sanitization

---

# Phase 14 — Responsive & Optimization

## Responsive

* [ ] Test mobile layout (Tabler responsive sidebar)
* [ ] Test tablet layout
* [ ] Test responsive DataTables
* [ ] Test responsive charts

## Optimization

* [ ] Query optimization
* [ ] Remove duplicate code
* [ ] Reusable component

---

# Phase 15 — Finalization

## Testing

* [ ] CRUD testing
* [ ] Authentication testing
* [ ] Responsive testing
* [ ] Validation testing

## Documentation

* [ ] Installation guide
* [ ] Database import guide
* [ ] User guide

## Deployment

* [ ] Production config
* [ ] Hosting upload
* [ ] Database migration

---

# Important Rules

## DO

* Use MVC architecture (CodeIgniter 4)
* Use Query Builder
* Use Tabler components
* Follow DESIGN.md
* Use Bootstrap 5 utilities
* Use DataTables for data tables
* Use Tabler Icons for icons

## DON'T

* Don't modify Tabler core files
* Don't use Bootstrap 4 (Tabler uses Bootstrap 5)
* Don't create custom components if Tabler has them
* Don't use inline CSS
* Don't use direct query in controller

---

# Final Goal

Build:

* professional admin dashboard
* scalable architecture
* responsive UI
* maintainable codebase
* thesis-ready information system
* Tabler-based professional application
