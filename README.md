# EventService — Event Management System

## Deskripsi

Layanan ini bertanggung jawab untuk mengelola data event kampus seperti seminar, lomba, dan workshop.

- **Peran Provider:** Menyediakan data event dan informasi jadwal/kuota untuk dikonsumsi oleh RegistrationService
- **Peran Consumer:** Memvalidasi data penyelenggara event (organizer) ke UserService

## Teknologi

- **Framework:** Laravel 11 (PHP >= 8.2)
- **Database:** MySQL — `events_db`
- **Port:** `8002`

## Cara Menjalankan

```bash
# 1. Clone repository dan install dependency
git clone https://github.com/<org>/event-service.git
cd event-service
composer install

# 2. Konfigurasi environment
cp .env.example .env
# Edit .env:
#   DB_DATABASE=events_db
#   DB_USERNAME=root
#   DB_PASSWORD=your_password
#   USER_SERVICE_URL=http://localhost:8001/api

# 3. Migrasi database & generate key
php artisan key:generate
php artisan migrate --seed

# 4. Jalankan server
php artisan serve --port=8002
```

> ⚠️ Pastikan **UserService sudah berjalan** sebelum menjalankan EventService.

## Endpoints

| Method | Endpoint | Peran | Deskripsi |
|--------|----------|-------|-----------|
| GET | `/api/events` | Provider | Ambil semua event yang tersedia |
| GET | `/api/events/{id}` | Provider | Ambil detail event + cek kuota |
| GET | `/api/events?type={type}` | Provider | Filter event berdasarkan tipe (`seminar`, `lomba`, `workshop`) |
| POST | `/api/events` | Provider + Consumer | Buat event baru (validasi organizer ke UserService) |
| PUT | `/api/events/{id}` | Provider | Update data event |
| DELETE | `/api/events/{id}` | Provider | Hapus event |

## Skema Database

Tabel: `events`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT UNSIGNED | Primary Key, Auto Increment |
| title | VARCHAR(200) | Nama event |
| type | ENUM | `seminar`, `lomba`, `workshop` |
| description | TEXT | Deskripsi event (nullable) |
| organizer_user_id | BIGINT UNSIGNED | ID user penyelenggara (dari UserService) |
| quota | INT | Kapasitas peserta |
| registered_count | INT | Jumlah terdaftar (default: 0) |
| start_date | DATETIME | Waktu mulai |
| end_date | DATETIME | Waktu selesai |
| location | VARCHAR(200) | Tempat event |
| status | ENUM | `open`, `closed`, `cancelled` (default: `open`) |
| created_at | TIMESTAMP | Waktu dibuat |

## Komunikasi ke Service Lain

- **Consume UserService** (`http://localhost:8001/api`):
  - `GET /api/users/{id}` — Memvalidasi keberadaan user penyelenggara saat membuat event baru

## Dokumentasi Postman

[Link Postman Documentation]
