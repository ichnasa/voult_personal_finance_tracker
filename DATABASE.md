# DATABASE.md — FinTrack

# 1. Database Overview

Nama database:

```sql
fintrack_db
```

Database digunakan untuk:

* menyimpan data pengguna
* mencatat pemasukan
* mencatat pengeluaran
* mengelola budgeting
* mengelola wishlist barang
* mengelola target tabungan
* menghasilkan laporan keuangan

Database menggunakan:

* MySQL
* Relational Database
* Foreign Key Relationship

---

# 2. Database Design Principles

## Rules

* Semua tabel menggunakan primary key `id`
* Semua relasi menggunakan foreign key
* Semua tabel menggunakan timestamp:

  * created_at
  * updated_at
* Semua nominal uang menggunakan:

```sql
DECIMAL(15,2)
```

---

# 3. Entity Relationship Overview

## Relasi utama

```text
users
 ├── pemasukan
 ├── pengeluaran
 ├── budgeting
 ├── wishlist
 └── tabungan

kategori
 ├── pengeluaran
 └── budgeting
```

---

# 4. Tables

# users

Menyimpan data pengguna.

| Field      | Type         | Description    |
| ---------- | ------------ | -------------- |
| id         | BIGINT       | Primary key    |
| name       | VARCHAR(100) | Nama pengguna  |
| email      | VARCHAR(100) | Email pengguna |
| password   | VARCHAR(255) | Password hash  |
| created_at | TIMESTAMP    | Waktu dibuat   |
| updated_at | TIMESTAMP    | Waktu diupdate |

## SQL

```sql
CREATE TABLE users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

# kategori

Menyimpan kategori pengeluaran.

Contoh:

* Makanan
* Transportasi
* Hiburan
* Pendidikan

| Field      | Type         |
| ---------- | ------------ |
| id         | BIGINT       |
| name       | VARCHAR(100) |
| created_at | TIMESTAMP    |
| updated_at | TIMESTAMP    |

## SQL

```sql
CREATE TABLE kategori (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

# pemasukan

Menyimpan data pemasukan pengguna.

| Field      | Type          |
| ---------- | ------------- |
| id         | BIGINT        |
| user_id    | BIGINT        |
| tanggal    | DATE          |
| nominal    | DECIMAL(15,2) |
| sumber     | VARCHAR(100)  |
| catatan    | TEXT          |
| created_at | TIMESTAMP     |
| updated_at | TIMESTAMP     |

## SQL

```sql
CREATE TABLE pemasukan (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    tanggal DATE NOT NULL,
    nominal DECIMAL(15,2) NOT NULL,
    sumber VARCHAR(100) NOT NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_pemasukan_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
);
```

---

# pengeluaran

Menyimpan data pengeluaran pengguna.

| Field             | Type          |
| ----------------- | ------------- |
| id                | BIGINT        |
| user_id           | BIGINT        |
| kategori_id       | BIGINT        |
| tanggal           | DATE          |
| nominal           | DECIMAL(15,2) |
| metode_pembayaran | VARCHAR(50)   |
| catatan           | TEXT          |
| nota              | VARCHAR(255)  |
| created_at        | TIMESTAMP     |
| updated_at        | TIMESTAMP     |

## SQL

```sql
CREATE TABLE pengeluaran (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    kategori_id BIGINT NOT NULL,
    tanggal DATE NOT NULL,
    nominal DECIMAL(15,2) NOT NULL,
    metode_pembayaran VARCHAR(50) NULL,
    catatan TEXT NULL,
    nota VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_pengeluaran_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_pengeluaran_kategori
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
    ON DELETE CASCADE
);
```

---

# budgeting

Menyimpan budget bulanan per kategori.

| Field          | Type          |
| -------------- | ------------- |
| id             | BIGINT        |
| user_id        | BIGINT        |
| kategori_id    | BIGINT        |
| bulan          | VARCHAR(20)   |
| tahun          | YEAR          |
| nominal_budget | DECIMAL(15,2) |
| created_at     | TIMESTAMP     |
| updated_at     | TIMESTAMP     |

## SQL

```sql
CREATE TABLE budgeting (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    kategori_id BIGINT NOT NULL,
    bulan VARCHAR(20) NOT NULL,
    tahun YEAR NOT NULL,
    nominal_budget DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_budget_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_budget_kategori
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
    ON DELETE CASCADE
);
```

---

# wishlist

Menyimpan barang impian pengguna.

| Field        | Type          |
| ------------ | ------------- |
| id           | BIGINT        |
| user_id      | BIGINT        |
| nama_barang  | VARCHAR(150)  |
| harga_target | DECIMAL(15,2) |
| prioritas    | ENUM          |
| status       | ENUM          |
| catatan      | TEXT          |
| created_at   | TIMESTAMP     |
| updated_at   | TIMESTAMP     |

## SQL

```sql
CREATE TABLE wishlist (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    nama_barang VARCHAR(150) NOT NULL,
    harga_target DECIMAL(15,2) NOT NULL,
    prioritas ENUM('rendah','sedang','tinggi') DEFAULT 'sedang',
    status ENUM('belum_mulai','menabung','tercapai') DEFAULT 'belum_mulai',
    catatan TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_wishlist_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
);
```

---

# tabungan

Menyimpan target tabungan pengguna.

| Field             | Type          |
| ----------------- | ------------- |
| id                | BIGINT        |
| user_id           | BIGINT        |
| wishlist_id       | BIGINT        |
| nama_tabungan     | VARCHAR(150)  |
| target_nominal    | DECIMAL(15,2) |
| nominal_terkumpul | DECIMAL(15,2) |
| deadline          | DATE          |
| status            | ENUM          |
| created_at        | TIMESTAMP     |
| updated_at        | TIMESTAMP     |

## SQL

```sql
CREATE TABLE tabungan (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    wishlist_id BIGINT NULL,
    nama_tabungan VARCHAR(150) NOT NULL,
    target_nominal DECIMAL(15,2) NOT NULL,
    nominal_terkumpul DECIMAL(15,2) DEFAULT 0,
    deadline DATE NULL,
    status ENUM('proses','tercapai') DEFAULT 'proses',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_tabungan_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE,

    CONSTRAINT fk_tabungan_wishlist
    FOREIGN KEY (wishlist_id) REFERENCES wishlist(id)
    ON DELETE SET NULL
);
```

---

# 5. Index Recommendation

Gunakan index untuk optimasi query.

```sql
CREATE INDEX idx_pemasukan_user
ON pemasukan(user_id);

CREATE INDEX idx_pengeluaran_user
ON pengeluaran(user_id);

CREATE INDEX idx_pengeluaran_tanggal
ON pengeluaran(tanggal);

CREATE INDEX idx_budget_user
ON budgeting(user_id);
```

---

# 6. Data Flow

## Pemasukan

User → Input pemasukan → Tersimpan → Dashboard update

## Pengeluaran

User → Input pengeluaran → Budget terpotong → Dashboard update

## Wishlist

User → Tambah wishlist → Hubungkan dengan tabungan

## Tabungan

User → Tambah target → Progress dihitung otomatis

---

# 7. Financial Formula

## Total Saldo

```text
Total Pemasukan - Total Pengeluaran
```

## Progress Tabungan

```text
(nominal_terkumpul / target_nominal) * 100
```

## Persentase Budget

```text
(total_pengeluaran / nominal_budget) * 100
```

---

# 8. Recommended Seeder Data

## Kategori

```text
- Makanan
- Transportasi
- Hiburan
- Pendidikan
- Belanja
- Tagihan
```

## Metode Pembayaran

```text
- Cash
- Transfer
- E-Wallet
- Debit
- Kredit
```

---

# 9. Security Notes

* Password wajib di-hash
* Gunakan prepared statement / query builder
* Validasi semua input
* Sanitasi upload file nota
* Batasi ukuran upload file

---

# 10. Database Naming Convention

## Table

Gunakan lowercase:

```text
users
pengeluaran
pemasukan
```

## Column

Gunakan snake_case:

```text
created_at
updated_at
harga_target
```

---

# 11. Future Scalability

Database dapat dikembangkan untuk:

* multi currency
* recurring transaction
* financial AI recommendation
* export excel
* OCR nota
* mobile app API
* multiple wallet/account

---

# 12. Recommended Engine

Gunakan:

```sql
ENGINE=InnoDB
```

Karena:

* support foreign key
* transactional
* lebih aman untuk data finansial

---

# 13. Backup Recommendation

Disarankan:

* backup database harian
* export SQL mingguan
* gunakan migration versioning

---

# 14. Final Notes

Prinsip utama database:

* normalized
* scalable
* maintainable
* secure
* mudah dipahami untuk skripsi

Database harus:

* konsisten
* relational
* mudah dikembangkan
* optimal untuk dashboard finansial
