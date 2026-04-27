# RegistrationService — Event Management System

## Deskripsi
Layanan ini bertanggung jawab untuk mengelola pendaftaran mahasiswa ke event kampus (seminar, lomba, workshop).

- **Peran Provider**: Menyediakan data pendaftaran, riwayat registrasi user, dan daftar peserta event — dikonsumsi oleh UserService, EventService, dan NotificationService.
- **Peran Consumer**: Memanggil UserService untuk validasi user, EventService untuk validasi event & kuota, dan NotificationService untuk mengirim notifikasi konfirmasi.

## Teknologi
- **Framework**: Node.js + Express
- **Database**: MySQL (`registrations_db`)
- **Port**: `8003`

## Prasyarat
- Node.js >= 18.x
- npm
- MySQL >= 8.0

## Cara Menjalankan

```bash
# 1. Clone repository
git clone https://github.com/<org>/registration-service.git
cd registration-service

# 2. Install dependencies
npm install

# 3. Konfigurasi environment
cp .env.example .env
# Edit .env sesuai konfigurasi lokal

# 4. Jalankan service
node app.js
# atau development mode:
npm run dev
```

> **Pastikan** UserService (8001), EventService (8002), dan NotificationService (8004) sudah berjalan terlebih dahulu.

## Endpoints

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| POST | `/api/registrations` | Daftar event (consume User & Event Service) |
| GET | `/api/registrations/:id` | Detail registrasi (dikonsumsi NotificationService) |
| GET | `/api/registrations/user/:userId` | Riwayat registrasi user (dikonsumsi UserService) |
| GET | `/api/registrations/event/:eventId` | Semua peserta suatu event |
| PUT | `/api/registrations/:id/status` | Update status registrasi |
| DELETE | `/api/registrations/:id` | Batalkan registrasi |

## Contoh Request

### POST /api/registrations
```json
{
  "user_id": 1,
  "event_id": 2
}
```

### Response sukses
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "id": "uuid-v4",
    "user_id": 1,
    "event_id": 2,
    "user_name": "Budi Santoso",
    "event_title": "Workshop UI/UX 2025",
    "status": "confirmed",
    "registered_at": "2025-01-01T10:00:00.000Z",
    "confirmed_at": "2025-01-01T10:00:00.000Z"
  }
}
```

## Komunikasi ke Service Lain

| Service | Endpoint yang dipanggil | Tujuan |
|---------|------------------------|--------|
| UserService | `GET /api/users/:id` | Validasi user sebelum mendaftar |
| EventService | `GET /api/events/:id` | Validasi event & cek kuota |
| EventService | `PUT /api/events/:id/quota` | Update kuota setelah pendaftaran |
| NotificationService | `POST /api/notify` | Kirim notifikasi konfirmasi |

## Dokumentasi Postman
[Link Postman Documentation] _(isi setelah export)_
