# NotificationService — Event Management System

## Deskripsi

Layanan ini bertanggung jawab untuk mengirimkan notifikasi konfirmasi kepada mahasiswa setelah berhasil mendaftar ke suatu event.

- **Peran Provider:** Menyediakan log notifikasi yang telah dikirim
- **Peran Consumer:** Mengambil detail registrasi dari RegistrationService untuk mengisi konten notifikasi

## Teknologi

- **Framework:** Python (Flask >= 3.11)
- **Database:** SQLite — `notifications_db`
- **Port:** `8003`

## Cara Menjalankan

```bash
# 1. Clone repository
git clone https://github.com/<org>/notification-service.git
cd notification-service

# 2. Buat virtual environment dan install dependency
python -m venv venv
source venv/bin/activate        # Linux/Mac
# venv\Scripts\activate         # Windows

pip install -r requirements.txt

# 3. Konfigurasi environment
cp .env.example .env
# Edit .env:
#   REGISTRATION_SERVICE_URL=http://localhost:8002/api

# 4. Jalankan server
python app.py
```

> ⚠️ Pastikan **RegistrationService sudah berjalan** sebelum menjalankan NotificationService.

## Endpoints

| Method | Endpoint | Peran | Deskripsi |
|--------|----------|-------|-----------|
| POST | `/api/notify` | Consumer | Terima trigger notifikasi dan ambil detail ke RegistrationService |
| GET | `/api/notifications` | Provider | Ambil semua log notifikasi |
| GET | `/api/notifications/{id}` | Provider | Ambil detail notifikasi berdasarkan ID |
| GET | `/api/notifications/user/{userId}` | Provider | Semua notifikasi untuk satu user |

## Skema Database

Tabel: `notifications`

| Field | Tipe | Keterangan |
|-------|------|------------|
| id | INTEGER | Primary Key, Auto Increment |
| registration_id | VARCHAR(36) | Referensi ke RegistrationService |
| user_id | INTEGER | ID mahasiswa penerima |
| type | VARCHAR(50) | `registration_confirmed`, `event_reminder` |
| message | TEXT | Isi notifikasi |
| sent_at | TIMESTAMP | Waktu dikirim |
| status | VARCHAR(20) | `sent`, `failed` (default: `sent`) |

## Komunikasi ke Service Lain

- **Consume RegistrationService** (`http://localhost:8002/api`):
  - `GET /api/registrations/{id}` — Mengambil detail registrasi untuk mengisi konten pesan notifikasi sebelum dikirim

## Dokumentasi Postman

[Link Postman Documentation]
