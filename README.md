# RegistrationService — Event Management System

## Deskripsi

Layanan ini bertanggung jawab untuk mengelola proses pendaftaran mahasiswa ke event dan status transaksinya.

- **Peran Provider:** Menyediakan data pendaftaran dan status transaksi untuk dikonsumsi oleh UserService dan NotificationService
- **Peran Consumer:** Memvalidasi user ke UserService, memvalidasi event & kuota ke EventService, serta mengirim notifikasi ke NotificationService setelah pendaftaran berhasil

## Teknologi

- **Framework:** Node.js (Express)
- **Database:** MySQL — `registrations_db`
- **Port:** `8002`

## Cara Menjalankan

```bash
# 1. Clone repository dan install dependency
git clone https://github.com/<org>/registration-service.git
cd registration-service
npm install

# 2. Konfigurasi environment
cp .env.example .env
# Edit .env:
#   PORT=8003
#   DB_HOST=localhost
#   DB_NAME=registrations_db
#   DB_USER=root
#   DB_PASSWORD=your_password
#   USER_SERVICE_URL=http://localhost:8000/api
#   EVENT_SERVICE_URL=http://localhost:8001/api
#   NOTIF_SERVICE_URL=http://localhost:8003/api

# 3. Jalankan server
node app.js
# atau
npm run dev
```

> ⚠️ Pastikan **UserService, EventService, dan NotificationService sudah berjalan** sebelum menjalankan RegistrationService.

## Endpoints

| Method | Endpoint | Peran | Deskripsi |
|--------|----------|-------|-----------|
| POST | `/api/registrations` | Provider + Consumer | Daftar event (validasi ke UserService & EventService, lalu kirim notifikasi) |
| GET | `/api/registrations/{id}` | Provider | Ambil detail registrasi (dikonsumsi NotificationService) |
| GET | `/api/registrations/user/{userId}` | Provider | Riwayat registrasi user (dikonsumsi UserService) |
| GET | `/api/registrations/event/{eventId}` | Provider | Semua peserta event (dikonsumsi EventService) |
| PUT | `/api/registrations/{id}/status` | Provider | Update status registrasi |
| DELETE | `/api/registrations/{id}` | Provider | Batalkan registrasi |

## Skema Database

Tabel: `registrations`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | VARCHAR(36) | Primary Key (UUID) |
| user_id | BIGINT UNSIGNED | Referensi ke UserService |
| event_id | BIGINT UNSIGNED | Referensi ke EventService |
| user_name | VARCHAR(100) | Snapshot nama user |
| event_title | VARCHAR(200) | Snapshot judul event |
| status | ENUM | `pending`, `confirmed`, `cancelled` (default: `pending`) |
| registered_at | TIMESTAMP | Waktu pendaftaran |
| confirmed_at | TIMESTAMP | Waktu konfirmasi (nullable) |

## Komunikasi ke Service Lain

- **Consume UserService** (`http://localhost:8000/api`):
  - `GET /api/users/{id}` — Validasi keberadaan mahasiswa sebelum mendaftar

- **Consume EventService** (`http://localhost:8001/api`):
  - `GET /api/events/{id}` — Validasi event dan mengecek kuota tersisa sebelum mendaftar

- **Consume NotificationService** (`http://localhost:8003/api`):
  - `POST /api/notify` — Mengirim notifikasi konfirmasi setelah pendaftaran berhasil

## Dokumentasi Postman

[Link Postman Documentation]
