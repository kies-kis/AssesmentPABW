# SmartDrain

Sistem Pelaporan dan Pemantauan Drainase Berbasis Web untuk Kabupaten Bandung.
Dibangun dengan **Laravel 12**, **MySQL**, arsitektur **HMVC** (`nwidart/laravel-modules`),
**Blade + Bootstrap 5**, dan **jQuery AJAX** (Jalur A).

## Prasyarat

- PHP 8.2 atau lebih baru (ekstensi `pdo_mysql`, `fileinfo`, `gd`/`exif` bila tersedia)
- Composer 2
- MySQL / MariaDB
- Node.js dan npm (opsional, hanya bila ingin memakai Vite)

## Instalasi

```bash
# 1. Pasang dependensi
composer install

# 2. Siapkan environment
copy .env.example .env        # Windows
# cp .env.example .env        # Linux/macOS
php artisan key:generate

# 3. Buat database MySQL bernama smartdrain
#    Konfigurasi ada di .env:
#    DB_CONNECTION=mysql
#    DB_HOST=127.0.0.1
#    DB_PORT=3306
#    DB_DATABASE=smartdrain
#    DB_USERNAME=root
#    DB_PASSWORD=

# 4. Jalankan migration dan seeder
php artisan migrate --seed

# 5. Aktifkan tautan Storage publik
php artisan storage:link

# 6. Jalankan aplikasi
php artisan serve
```

Aplikasi tersedia di `http://127.0.0.1:8000`.

## Akun Uji

| Role  | Email                     | Password   |
|-------|---------------------------|------------|
| Admin | `admin@smartdrain.test`   | `password` |
| Warga | `warga@smartdrain.test`   | `password` |
| Warga | `rizqi@smartdrain.test`   | `password` |

Akun admin hanya dibuat melalui `DatabaseSeeder`, bukan dari form registrasi.
Registrasi publik selalu menghasilkan role `warga`.

## Struktur HMVC

```
Modules/
├── TitikDrainase/
│   ├── app/
│   │   ├── Http/Controllers/TitikDrainaseController.php
│   │   ├── Http/Requests/TitikDrainaseRequest.php
│   │   ├── Models/TitikDrainase.php
│   │   └── Providers/
│   ├── database/migrations/2026_11_15_000002_create_titik_drainases_table.php
│   ├── database/seeders/TitikDrainaseDatabaseSeeder.php
│   ├── resources/views/
│   └── routes/web.php
└── Laporan/
    ├── app/
    │   ├── Http/Controllers/LaporanController.php
    │   ├── Http/Requests/LaporanRequest.php
    │   ├── Models/Laporan.php
    │   └── Providers/
    ├── database/migrations/2026_11_15_000003_create_laporans_table.php
    ├── database/seeders/LaporanDatabaseSeeder.php
    ├── resources/views/
    └── routes/web.php
```

Modul diaktifkan pada `modules_statuses.json` dan didaftarkan otomatis oleh
`nwidart/laravel-modules` melalui `module.json` masing-masing.

## Fitur

1. **HMVC** — modul `TitikDrainase` dan `Laporan` terpisah (Model, Controller, Route, View, Migration, Seeder).
2. **Autentikasi dan role** — register, login, logout; kolom `role` default `warga`; seeder admin dan warga.
3. **Middleware dan otorisasi** — alias `role` (`role:admin`), pengecekan kepemilikan laporan di controller, HTTP 403 untuk akses terlarang.
4. **CRUD Titik Drainase** — tambah, lihat, ubah, hapus; pencarian nama titik/kecamatan; pagination 10 data; penolakan hapus bila sudah dirujuk laporan.
5. **CRUD Laporan + AJAX + upload** — tambah, muat data edit, ubah, hapus tanpa reload; validasi 422 per field; upload JPG/JPEG/PNG maksimal 2 MB ke disk `public`; tautan **Lihat Bukti**.
6. **Layout dan navigasi** — layout Blade bersama, menu Dashboard, Titik Drainase, Laporan, Profil, Logout; menu menyesuaikan role.
7. **Validasi Form Request** — `TitikDrainaseRequest` dan `LaporanRequest`; pesan error ditampilkan di dekat field.
8. **UI/UX responsif** — Bootstrap 5, navbar collapse, tabel responsif, indikator loading pada tombol AJAX.
9. **Bonus** — pagination 10 data per halaman, dark mode, filter laporan berdasarkan status, ringkasan status pada dashboard.

## Hak Akses

| Fitur                          | Admin | Warga |
|--------------------------------|:-----:|:-----:|
| Melihat titik drainase         | Ya    | Ya    |
| Menambah/mengubah/menghapus titik | Ya | Tidak (403) |
| Melihat seluruh laporan        | Ya    | Tidak |
| Melihat laporan sendiri        | Ya    | Ya    |
| Membuat laporan                | Ya    | Ya    |
| Mengubah/menghapus laporan sendiri | Ya | Ya |
| Menghapus laporan mana pun     | Ya    | Tidak (403) |

## Alur Data Singkat

- Form laporan mengirim `FormData` melalui `$.ajax()` ke `POST /laporan`.
- `LaporanRequest` memvalidasi input; jika gagal, Laravel mengembalikan HTTP 422 berisi `errors`.
- Controller menyimpan `user_id` dari `$request->user()`, menyimpan foto ke `storage/app/public/laporan`, lalu mengembalikan JSON berisi HTML baris tabel.
- JavaScript menambahkan/mengganti baris pada DOM tanpa reload.
- Edit memuat data melalui `GET /laporan/{id}` (JSON), lalu mengirim `POST` dengan `_method=PUT`.
- Hapus mengirim `DELETE /laporan/{id}` melalui AJAX.

## Pengujian

```bash
php artisan test
```

Tes mencakup pembatasan role, registrasi yang selalu menjadi warga, kepemilikan laporan,
penghapusan laporan oleh admin, dan penolakan hapus titik drainase yang memiliki laporan.

## Catatan Pengumpulan

- Isi identitas (nama, NRP/NIM, kelas) dan tautan repository GitHub pada laporan PDF.
- Tambahkan tangkapan layar dashboard admin/warga, daftar dan form titik drainase,
  halaman laporan (desktop dan mobile), serta bukti akses 403.
- Jangan mengunggah folder `vendor`, `node_modules`, atau file `.env` asli.
