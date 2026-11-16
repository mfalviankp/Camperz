# 🏕 Camperz — Sistem Penyewaan Alat Camping

Aplikasi web manajemen penyewaan alat camping berbasis **Laravel 12** untuk UMKM.

---

## 📋 Fitur Utama

- **User:** Register/Login, katalog item, keranjang sewa, checkout, pembayaran (simulasi), E-Tiket QR, riwayat transaksi, bayar denda, notifikasi, profil
- **Admin:** Dashboard statistik, manajemen stok, scan QR pickup/return, inspeksi barang, input denda, laporan keuangan + PDF

---

## ⚙️ Persyaratan

- **XAMPP** (PHP 8.2+, MySQL 8.0, Apache)
- **Composer** (global)
- **PHP GD Extension** (untuk QR Code)

---

## 🚀 Langkah Instalasi

### 1. Pastikan XAMPP Aktif
- Buka XAMPP Control Panel
- Start **Apache** dan **MySQL**

### 2. Buat Database di phpMyAdmin
1. Buka browser → `http://localhost/phpmyadmin`
2. Klik **"New"** di sidebar kiri
3. Nama database: `camperz`
4. Collation: `utf8mb4_unicode_ci`
5. Klik **"Create"**

### 3. Pastikan PHP GD Aktif
File `C:\xampp\php\php.ini`, cari dan hilangkan titik koma:
```ini
; Sebelum:
;extension=gd

; Sesudah:
extension=gd
```
Restart Apache di XAMPP Control Panel.

### 4. Jalankan Migration & Seeder
Buka Command Prompt, masuk ke folder project:
```bash
cd C:\xampp\htdocs\Camperz\Camperz

# Buat link storage (untuk upload foto)
php artisan storage:link

# Jalankan migration + seeder
php artisan migrate:fresh --seed
```

### 5. Jalankan Aplikasi
```bash
php artisan serve
```
Buka browser: **http://localhost:8000**

---

## 🔑 Akun Default

| Role  | Email               | Password  |
|-------|---------------------|-----------|
| Admin | admin@camperz.id    | admin123  |
| User  | user@camperz.id     | user123   |
| User  | siti@example.com    | user123   |

---

## 📁 Struktur Folder Penting

```
Camperz/
├── app/Http/Controllers/        # Semua controller
│   └── Admin/                   # Controller khusus admin
├── app/Http/Middleware/          # CekLogin, CekAdmin
├── app/Models/                   # User, Item, Reservation, Payment, Denda, Notifikasi
├── database/migrations/          # 6 file migration
├── database/seeders/             # DatabaseSeeder
├── resources/views/
│   ├── layouts/                  # app.blade.php, admin.blade.php
│   ├── auth/                     # login.blade.php, register.blade.php
│   ├── user/                     # Semua view user
│   └── admin/                    # Semua view admin
├── public/css/style.css          # CSS global
└── routes/web.php                # Semua route
```

---

## 🔄 Alur Transaksi

```
User pilih item → Tambah keranjang → Checkout → Pembayaran
→ Konfirmasi (simulasi) → E-Tiket QR
→ [Admin] Scan QR Pickup → Konfirmasi ambil → Status: Aktif
→ [Admin] Scan QR Return → Inspeksi kondisi
→ Jika OK: Selesai | Jika ada masalah: Input Denda
→ [User] Bayar Denda → Selesai
```

---

## 🛠️ Tech Stack

| Layer        | Pilihan                           |
|--------------|-----------------------------------|
| Framework    | Laravel 12                        |
| Frontend     | Blade + CSS3 Vanilla + JS         |
| Database     | MySQL (XAMPP)                     |
| Auth         | Session manual (tanpa Auth facade)|
| QR Code      | simplesoftwareio/simple-qrcode    |
| PDF          | barryvdh/laravel-dompdf           |
| Upload       | Laravel Storage (disk: public)    |

---

## 👥 Tim Pengembang
Dibuat sebagai proyek mata kuliah Pemrograman Web — Teknik Informatika
