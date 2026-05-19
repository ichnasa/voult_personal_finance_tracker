# TODO.md — FinTrack

# Environment Information

## Development Environment

* OS: Linux
* Framework: CodeIgniter 3
* Database: MySQL
* Database Username: root
* Database Password: Qwerty12!
* Database Name: fintrack_db

## Stack

* PHP 8+
* Apache
* AdminLTE 3.2
* Bootstrap 4
* jQuery 3.x
* Chart.js
* DataTables
* Font Awesome 5

---

# Phase 0 — Initial Setup

## Environment Setup

* [ ] Verify PHP installation
* [ ] Verify Apache installation
* [ ] Verify MySQL installation
* [ ] Verify CodeIgniter project running
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

* [ ] Configure application/config/database.php
* [ ] Test database connection

## Linux Permissions

* [ ] Create uploads directory
* [ ] Set upload permission:

```bash
chmod -R 775 assets/uploads
```

---

# Phase 1 — Project Structure

## Folder Structure

* [ ] Download AdminLTE 3.2 dari GitHub releases
* [ ] Setup assets/adminlte/ (css, js, img)
* [ ] Setup assets/plugins/ (jquery, bootstrap, fontawesome, dll)
* [ ] Setup assets/css/custom.css
* [ ] Setup assets/js/app.js
* [ ] Setup assets/uploads
* [ ] Setup assets/img

## View Structure

* [ ] Create layout folder
* [ ] Create dashboard folder
* [ ] Create auth folder
* [ ] Create pemasukan folder
* [ ] Create pengeluaran folder
* [ ] Create budgeting folder
* [ ] Create wishlist folder
* [ ] Create tabungan folder
* [ ] Create laporan folder

## Shared Layout

* [ ] main.php layout (menggunakan struktur AdminLTE)
* [ ] sidebar.php (AdminLTE main-sidebar)
* [ ] header.php (AdminLTE main-header navbar)
* [ ] footer.php (AdminLTE main-footer)
* [ ] auth.php layout (AdminLTE login-page)

---

# Phase 2 — AdminLTE Integration

## Setup AdminLTE

* [ ] Load AdminLTE CSS di layout
* [ ] Load AdminLTE JS di layout
* [ ] Load jQuery
* [ ] Load Bootstrap 4 JS
* [ ] Load Font Awesome 5
* [ ] Test layout AdminLTE berjalan

## Layout Components

* [ ] Sidebar navigation (nav-sidebar)
* [ ] Navbar header (main-header)
* [ ] Content wrapper
* [ ] Footer
* [ ] Sidebar toggle (pushmenu)

## Plugin Integration

* [ ] DataTables Bootstrap 4
* [ ] Chart.js
* [ ] SweetAlert2
* [ ] bs-custom-file-input (upload nota)

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

* [ ] Login page (AdminLTE login-page layout)
* [ ] Register page (AdminLTE register-page layout)
* [ ] Flash message (Bootstrap alert)

---

# Phase 5 — Main Layout

## Sidebar (AdminLTE main-sidebar)

* [ ] Brand logo
* [ ] Dashboard menu
* [ ] Pemasukan menu
* [ ] Pengeluaran menu
* [ ] Budgeting menu
* [ ] Wishlist menu
* [ ] Tabungan menu
* [ ] Laporan menu
* [ ] Logout menu

## Navbar (AdminLTE main-header)

* [ ] Sidebar toggle button
* [ ] User info
* [ ] Logout button

## Content Header

* [ ] Page title
* [ ] Breadcrumb

---

# Phase 6 — Dashboard

## Statistics (small-box AdminLTE)

* [ ] Total saldo (bg-info)
* [ ] Total pemasukan (bg-success)
* [ ] Total pengeluaran (bg-danger)
* [ ] Sisa budget (bg-warning)

## Charts (Card + Chart.js)

* [ ] Cashflow chart (bar)
* [ ] Expense category chart (doughnut)

## Widgets (Card AdminLTE)

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

* [ ] Test mobile layout (AdminLTE sidebar-mini)
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

* Use MVC architecture
* Use Query Builder
* Use AdminLTE components
* Follow DESIGN.md
* Use Bootstrap 4 utilities
* Use DataTables for data tables
* Use Font Awesome 5 for icons

## DON'T

* Don't modify AdminLTE core files
* Don't use Bootstrap 5 (AdminLTE 3 uses Bootstrap 4)
* Don't remove jQuery
* Don't create custom components if AdminLTE has them
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
* AdminLTE-based professional application
