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

| Service | Deskripsi | Teknologi |
|--------|----------|----------|
| 👤 UserService | Manajemen data user | Laravel (PHP) |
| 📅 EventService | Manajemen event | Laravel (PHP) |
| 📝 RegistrationService | Pendaftaran event | Flask (Python) |
| 🔔 NotificationService | Notifikasi | Flask (Python) |

---

## ⚙️ Tech Stack

- **Backend**
  - Laravel 11 (PHP)
  - Flask (Python)
- **Database**
  - MySQL
  - SQLite
- **Tools**
  - Postman
  - Git & GitHub

---

## 📂 Struktur Project

```bash
event-management-system/
│
├── user-service/
├── event-service/
├── registration-service/
└── notification-service/
```

---

## 🚀 Cara Menjalankan

### 1. Clone Repository
```bash
git clone https://github.com/username/event-management-system.git
cd event-management-system
```

### 2. Jalankan Service (Urutan Penting ⚠️)

1. **UserService**
```bash
cd user-service
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve --port=8001
```

2. **EventService**
```bash
cd event-service
composer install
php artisan serve --port=8002
```

3. **NotificationService**
```bash
cd notification-service
pip install -r requirements.txt
python app.py
```

4. **RegistrationService**
```bash
cd registration-service
pip install -r requirements.txt
python app.py
```

---

## 📡 API Endpoint (Contoh)

### UserService
```http
GET    /api/users
GET    /api/users/{id}
POST   /api/users
PUT    /api/users/{id}
DELETE /api/users/{id}
```

### EventService
```http
GET    /api/events
POST   /api/events
PUT    /api/events/{id}
DELETE /api/events/{id}
```

### RegistrationService
```http
POST   /api/registrations
GET    /api/registrations/{id}
```

### NotificationService
```http
POST   /api/notify
GET    /api/notifications
```

---

## 📮 Postman Documentation

👉 [Tambahkan link Postman kamu di sini]

---

## 🎥 Demo

👉 [Tambahkan link video demo di sini]

---

## 👥 Tim Pengembang

| Nama | Role |
|-----|------|
| Nama 1 | UserService |
| Nama 2 | EventService |
| Nama 3 | RegistrationService |
| Nama 4 | NotificationService |

---

## 💡 Future Improvement

- Tambah API Gateway
- Implementasi authentication (JWT)
- Frontend (React / Next.js)
- Message broker (RabbitMQ / Kafka)

---

## ⭐ Penutup

Project ini dibuat sebagai implementasi nyata dari:
> **Enterprise Application Integration (EAI)**  
dengan pendekatan modern berbasis microservices.
