# UserService — Event Management System

## Deskripsi

Layanan ini bertanggung jawab untuk mengelola data user/mahasiswa yang terdaftar dalam sistem manajemen event kampus.

- **Peran Provider:** Menyediakan data user dan profil mahasiswa untuk dikonsumsi oleh EventService dan RegistrationService
- **Peran Consumer:** Mengambil riwayat registrasi mahasiswa dari RegistrationService

## Teknologi

- **Framework:** Laravel 11 (PHP >= 8.2)
- **Database:** MySQL — `users_db`
- **Port:** `8001`

## Cara Menjalankan

```bash
# 1. Clone repository dan install dependency
git clone https://github.com/<org>/user-service.git
cd user-service
composer install

# 2. Konfigurasi environment
cp .env.example .env
# Edit .env:
#   DB_DATABASE=users_db
#   DB_USERNAME=root
#   DB_PASSWORD=your_password
#   REGISTRATION_SERVICE_URL=http://localhost:8003/api

# 3. Migrasi database & generate key
php artisan key:generate
php artisan migrate --seed

# 4. Jalankan server
php artisan serve --port=8001
```

> ⚠️ Jalankan UserService **pertama** karena tidak bergantung pada service lain.

## Endpoints

| Method | Endpoint | Peran | Deskripsi |
|--------|----------|-------|-----------|
| GET | `/api/users` | Provider | Ambil semua data user |
| GET | `/api/users/{id}` | Provider | Ambil detail user berdasarkan ID |
| POST | `/api/users` | Provider | Buat user baru |
| PUT | `/api/users/{id}` | Provider | Update data user |
| DELETE | `/api/users/{id}` | Provider | Hapus user |
| GET | `/api/users/{id}/registrations` | Consumer | Ambil riwayat registrasi user |

## Skema Database

Tabel: `users`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED | Primary Key, Auto Increment |
| name | VARCHAR(100) | Nama lengkap mahasiswa |
| email | VARCHAR(150) | Email universitas (unique) |
| nim | VARCHAR(20) | Nomor Induk Mahasiswa (unique) |
| phone | VARCHAR(20) | Nomor HP (nullable) |
| faculty | VARCHAR(100) | Nama fakultas |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diperbarui |

## Komunikasi ke Service Lain

- **Consume RegistrationService** (`http://localhost:8003/api`):
  - `GET /api/registrations/user/{id}` — Mengambil riwayat registrasi milik mahasiswa tertentu, dipanggil saat endpoint `/api/users/{id}/registrations` diakses

## Dokumentasi Postman

[Link Postman Documentation]
