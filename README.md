# 🚀 Event Management System (Microservices Architecture)

> Sistem pendaftaran & manajemen event kampus berbasis **microservices**  
> Dibangun untuk kebutuhan integrasi sistem pada mata kuliah **Enterprise Application Integration (EAI)**

---

## 📌 Overview

Project ini merupakan sistem yang memungkinkan mahasiswa untuk:
- Mendaftar event (seminar, lomba, workshop)
- Melihat riwayat registrasi
- Mendapatkan notifikasi setelah pendaftaran

Sistem menggunakan pendekatan **microservices** dengan komunikasi:
- 🌐 HTTP REST API
- 📦 Format JSON
- 🔗 Service-to-service (tanpa API Gateway)

---

## 🧠 Arsitektur Sistem

Terdapat 4 service utama:

| Service | Deskripsi | Teknologi | Port |
|---------|-----------|-----------|------|
| 👤 UserService | Manajemen data user | Laravel 11 (PHP) | 8001 |
| 📅 EventService | Manajemen event | Laravel 11 (PHP) | 8002 |
| 📝 RegistrationService | Pendaftaran event | Express (Node.js) | 8003 |
| 🔔 NotificationService | Notifikasi | Flask (Python) | 8004 |

### 🔄 Alur Komunikasi Antar Service

```
RegistrationService ──► UserService        (validasi user)
RegistrationService ──► EventService       (validasi event & kuota)
RegistrationService ──► NotificationService (kirim notifikasi)
EventService        ──► UserService        (validasi organizer)
UserService         ──► RegistrationService (ambil riwayat registrasi)
NotificationService ──► RegistrationService (ambil detail registrasi)
```

---

## ⚙️ Tech Stack

- **Backend**
  - Laravel 11 (PHP >= 8.2)
  - Express (Node.js >= 18.x)
  - Flask (Python >= 3.11)
- **Database**
  - MySQL (UserService, EventService, RegistrationService)
  - SQLite (NotificationService)
- **Tools**
  - Postman
  - Git & GitHub

---

## 📂 Struktur Project

```bash
event-management-system/
│
├── user-service/           # Laravel — Port 8001
├── event-service/          # Laravel — Port 8002
├── registration-service/   # Node.js Express — Port 8003
└── notification-service/   # Python Flask — Port 8004
```

---

## 🚀 Cara Menjalankan

### 1. Clone Repository
```bash
git clone https://github.com/username/event-management-system.git
cd event-management-system
```

### 2. Jalankan Service (Urutan Penting ⚠️)

> Jalankan sesuai urutan berikut untuk menghindari dependency error.

**1. UserService** (tidak bergantung pada service lain)
```bash
cd user-service
composer install
cp .env.example .env
# Edit .env: DB_DATABASE=users_db
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8001
```

**2. EventService** (bergantung pada UserService)
```bash
cd event-service
composer install
cp .env.example .env
# Edit .env: DB_DATABASE=events_db, USER_SERVICE_URL=http://localhost:8001/api
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8002
```

**3. NotificationService** (bergantung pada RegistrationService)
```bash
cd notification-service
python -m venv venv && source venv/bin/activate
pip install -r requirements.txt
cp .env.example .env
# Edit .env: REGISTRATION_SERVICE_URL=http://localhost:8003/api
python app.py
```

**4. RegistrationService** (bergantung pada semua service lain)
```bash
cd registration-service
npm install
cp .env.example .env
# Edit .env: PORT=8003, USER_SERVICE_URL, EVENT_SERVICE_URL, NOTIF_SERVICE_URL
node app.js
```

---

## 📡 API Endpoints

### 👤 UserService — `http://localhost:8001/api`
```http
GET    /api/users
GET    /api/users/{id}
POST   /api/users
PUT    /api/users/{id}
DELETE /api/users/{id}
GET    /api/users/{id}/registrations
```

### 📅 EventService — `http://localhost:8002/api`
```http
GET    /api/events
GET    /api/events/{id}
GET    /api/events?type={type}
POST   /api/events
PUT    /api/events/{id}
DELETE /api/events/{id}
```

### 📝 RegistrationService — `http://localhost:8003/api`
```http
POST   /api/registrations
GET    /api/registrations/{id}
GET    /api/registrations/user/{userId}
GET    /api/registrations/event/{eventId}
PUT    /api/registrations/{id}/status
DELETE /api/registrations/{id}
```

### 🔔 NotificationService — `http://localhost:8004/api`
```http
POST   /api/notify
GET    /api/notifications
GET    /api/notifications/{id}
GET    /api/notifications/user/{userId}
```

---

## 📮 Postman Documentation

| Service | Link |
|---------|------|
| UserService | 👉 [Tambahkan link Postman] |
| EventService | 👉 [Tambahkan link Postman] |
| RegistrationService | 👉 [Tambahkan link Postman] |
| NotificationService | 👉 [Tambahkan link Postman] |

---

## 🎥 Demo

👉 [Tambahkan link video demo di sini]

---

## 👥 Tim Pengembang

| Nama | NIM | Service |
|------|-----|---------|
| Jingga Amelia Putri | - | 👤 UserService |
| Keysha Putri Azzahra | - | 📅 EventService |
| Ahmad Nurtajala | - | 📝 RegistrationService |
| Muhammad Zacky | - | 🔔 NotificationService |

---

## ⭐ Penutup

Project ini dibuat sebagai implementasi nyata dari:
> **Enterprise Application Integration (EAI)**  
> dengan pendekatan modern berbasis microservices.
