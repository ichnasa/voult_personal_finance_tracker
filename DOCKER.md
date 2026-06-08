# 🐳 Docker Setup — PLOOM Finance Tracker

Panduan menjalankan aplikasi **PLOOM** menggunakan Docker dan Docker Compose.

## Prasyarat

- [Docker](https://docs.docker.com/get-docker/) v24+
- [Docker Compose](https://docs.docker.com/compose/) v2.20+

---

## Struktur File Docker

```
fintrack/
├── Dockerfile                    # PHP 8.2-FPM image
├── docker-compose.yml            # Orkestrator semua service
├── .env.docker                   # Template variabel lingkungan
└── docker/
    ├── entrypoint.sh             # Startup script (migrasi, permission)
    ├── nginx/
    │   ├── nginx.conf            # Konfigurasi utama Nginx
    │   └── default.conf          # Virtual host CodeIgniter 4
    ├── php/
    │   ├── php.ini               # Konfigurasi PHP
    │   └── www.conf              # Konfigurasi PHP-FPM pool
    └── mysql/
        └── init/
            └── 01_init.sql       # SQL inisialisasi database
```

---

## Cara Menjalankan

### 1. Salin & edit konfigurasi environment

```bash
cp .env.docker .env
```

Edit file `.env` sesuai kebutuhan (Google OAuth, SMTP, dll).

### 2. Build dan jalankan semua service

```bash
# Mode development (termasuk phpMyAdmin)
docker compose --profile dev up -d --build

# Mode production (tanpa phpMyAdmin)
docker compose up -d --build
```

### 3. Akses aplikasi

| Service         | URL                          |
|-----------------|------------------------------|
| **Aplikasi**    | http://localhost:8080        |
| **phpMyAdmin**  | http://localhost:8081        |

---

## Perintah Berguna

```bash
# Lihat log semua container
docker compose logs -f

# Lihat log container tertentu
docker compose logs -f app
docker compose logs -f nginx
docker compose logs -f db

# Masuk ke container PHP
docker compose exec app bash

# Jalankan spark CLI (migrasi, seeder, dll)
docker compose exec app php spark migrate
docker compose exec app php spark db:seed NamaSeeder

# Stop semua container
docker compose down

# Stop dan hapus volume database (HATI-HATI: data hilang!)
docker compose down -v
```

---

## Service yang Tersedia

| Service      | Container        | Port          | Keterangan                        |
|--------------|------------------|---------------|------------------------------------|
| `app`        | `ploom_app`      | 9000 (intern) | PHP 8.2-FPM                        |
| `nginx`      | `ploom_nginx`    | **8080**      | Web server                         |
| `db`         | `ploom_db`       | **3306**      | MySQL 8.4                          |
| `phpmyadmin` | `ploom_phpmyadmin`| **8081**     | DB GUI (hanya profil `dev`)        |

---

## Troubleshooting

### Permission denied pada folder `writable/`

```bash
docker compose exec app chown -R www-data:www-data /var/www/html/writable
```

### Container `app` tidak bisa konek ke database

Pastikan `db` sudah sehat (`healthy`):

```bash
docker compose ps
```

### Reset database sepenuhnya

```bash
docker compose down -v
docker compose up -d --build
```
