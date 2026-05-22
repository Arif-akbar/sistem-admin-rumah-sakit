# SIMRS — Sistem Informasi Manajemen Rumah Sakit
### RS Harapan Sehat · Laravel 11 + Livewire 3 + Tailwind CSS

---

## Daftar Isi

1. [Tentang Proyek](#tentang-proyek)
2. [Fitur Utama](#fitur-utama)
3. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
4. [Struktur Database](#struktur-database)
5. [Struktur Direktori](#struktur-direktori)
6. [Persyaratan Sistem](#persyaratan-sistem)
7. [Cara Instalasi](#cara-instalasi)
8. [Konfigurasi](#konfigurasi)
9. [Akun Default](#akun-default)
10. [Role & Hak Akses](#role--hak-akses)
11. [Halaman & Modul](#halaman--modul)
12. [Packages yang Digunakan](#packages-yang-digunakan)
13. [Desain UI](#desain-ui)
14. [Kontribusi](#kontribusi)
15. [Lisensi](#lisensi)

---

## Tentang Proyek

SIMRS adalah sistem informasi manajemen rumah sakit berbasis web yang dibangun menggunakan framework Laravel 11. Sistem ini dirancang untuk mengelola seluruh operasional administrasi rumah sakit mulai dari pendaftaran pasien, manajemen dokter dan poli, antrian, hingga rekam medis digital.

Sistem ini dikembangkan sebagai tugas akademik dengan kode database `ars_230217058` dan dapat dikembangkan lebih lanjut menjadi sistem produksi yang siap pakai.

---

## Fitur Utama

- **Dashboard Interaktif** — statistik kunjungan harian, grafik tren mingguan, dan ringkasan antrian real-time
- **Manajemen Pasien** — pendaftaran pasien baru, pencarian berdasarkan No. RM atau NIK, riwayat kunjungan lengkap
- **Manajemen Dokter** — profil dokter, spesialis, jadwal praktik per hari, dan kuota pasien per sesi
- **Manajemen Poli** — kelola unit pelayanan, status aktif/non-aktif, dan distribusi dokter per poli
- **Pendaftaran Pasien** — alur 3 langkah (cari pasien → pilih poli & dokter → konfirmasi), generate nomor antrian otomatis
- **Monitor Antrian** — tampilan layar antrian real-time untuk ruang tunggu, panel kontrol pemanggilan antrian
- **Rekam Medis Digital** — input vital sign, diagnosis dengan kode ICD-10, resep obat, dan riwayat kunjungan berbasis timeline
- **Laporan & Statistik** — rekap kunjungan per poli, tren bulanan, statistik dokter, export ke Excel dan PDF
- **Manajemen User & RBAC** — kelola akun pengguna, role (Super Admin / Admin / Dokter), dan hak akses per modul
- **Log Aktivitas (Audit Trail)** — rekam semua perubahan data beserta user, waktu, dan IP address
- **Pengaturan Sistem** — konfigurasi informasi rumah sakit, format penomoran, jadwal operasional, dan backup

---

## Teknologi yang Digunakan

| Komponen | Teknologi | Versi |
|---|---|---|
| Framework Backend | Laravel | 11.x |
| Bahasa Pemrograman | PHP | 8.2+ |
| Frontend Reaktif | Livewire | 3.x |
| CSS Framework | Tailwind CSS | 3.x |
| JavaScript | Alpine.js | 3.x |
| Database | MariaDB / MySQL | 10.4+ / 8.0+ |
| Autentikasi | Laravel Breeze | — |
| Authorization | Spatie Permission | 6.x |
| Export Excel | Maatwebsite Excel | 3.x |
| Cetak PDF | Barryvdh DomPDF | 2.x |
| Audit Trail | Spatie Activity Log | 4.x |
| Backup | Spatie Backup | 8.x |
| Charts | ApexCharts (CDN) | — |

---

## Struktur Database

Proyek ini menggunakan **17 tabel** yang saling berelasi:

```
pasien          — data rekam medis pasien (No. RM, NIK, dll.)
dokter          — profil dokter beserta spesialis dan poli
poli            — unit pelayanan / klinik
spesialis       — lookup spesialis dokter
agama           — lookup agama pasien
jadwal_dokter   — jadwal praktik dokter per hari dan kuota
pendaftaran     — registrasi pasien per kunjungan
antrian         — manajemen nomor antrian per poli per hari
rekam_medis     — catatan medis: diagnosis, ICD-10, resep, vital sign
users           — akun pengguna sistem
roles           — role pengguna (Spatie)
permissions     — hak akses per modul (Spatie)
role_has_permissions — relasi role ke permission
model_has_roles — relasi user ke role
activity_log    — audit trail semua aktivitas
notifications   — notifikasi Laravel
settings        — konfigurasi sistem rumah sakit
```

File SQL lengkap tersedia di: `database/hospital_admin_complete.sql`

Untuk melihat relasi antar tabel secara visual, jalankan perintah berikut setelah instalasi:

```bash
php artisan migrate --seed
```

---

## Struktur Direktori

```
hospital-admin/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── DashboardController.php
│   │   │   ├── PasienController.php
│   │   │   ├── DokterController.php
│   │   │   ├── PoliController.php
│   │   │   ├── PendaftaranController.php
│   │   │   ├── AntrianController.php
│   │   │   ├── RekamMedisController.php
│   │   │   ├── LaporanController.php
│   │   │   ├── UserController.php
│   │   │   └── SettingController.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   └── AuditTrailMiddleware.php
│   │   └── Requests/
│   │       ├── StorePasienRequest.php
│   │       ├── StoreDokterRequest.php
│   │       ├── StorePendaftaranRequest.php
│   │       └── StoreRekamMedisRequest.php
│   ├── Models/
│   │   ├── Pasien.php
│   │   ├── Dokter.php
│   │   ├── Poli.php
│   │   ├── Spesialis.php
│   │   ├── Agama.php
│   │   ├── JadwalDokter.php
│   │   ├── Pendaftaran.php
│   │   ├── Antrian.php
│   │   ├── RekamMedis.php
│   │   ├── Setting.php
│   │   └── User.php
│   ├── Services/
│   │   ├── PasienService.php
│   │   ├── DokterService.php
│   │   ├── PendaftaranService.php
│   │   ├── AntrianService.php
│   │   └── ReportService.php
│   └── Livewire/
│       ├── PasienTable.php
│       ├── DokterTable.php
│       ├── PendaftaranForm.php
│       ├── AntrianMonitor.php
│       └── RekamMedisForm.php
│
├── database/
│   ├── migrations/
│   │   ├── 2026_01_01_000001_create_agama_table.php
│   │   ├── 2026_01_01_000002_create_poli_table.php
│   │   ├── 2026_01_01_000003_create_spesialis_table.php
│   │   ├── 2026_01_01_000004_create_dokter_table.php
│   │   ├── 2026_01_01_000005_create_jadwal_dokter_table.php
│   │   ├── 2026_01_01_000006_create_pasien_table.php
│   │   ├── 2026_01_01_000007_create_pendaftaran_table.php
│   │   ├── 2026_01_01_000008_create_antrian_table.php
│   │   ├── 2026_01_01_000009_create_rekam_medis_table.php
│   │   └── 2026_01_01_000010_create_settings_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── AgamaSeeder.php
│   │   ├── PoliSeeder.php
│   │   ├── SpesialisSeeder.php
│   │   ├── DokterSeeder.php
│   │   ├── JadwalDokterSeeder.php
│   │   ├── PasienSeeder.php
│   │   ├── RolePermissionSeeder.php
│   │   ├── UserSeeder.php
│   │   └── SettingSeeder.php
│   └── hospital_admin_complete.sql   ← import langsung tanpa artisan
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php           ← layout utama (sidebar + header)
│       │   ├── auth.blade.php          ← layout halaman login
│       │   └── print.blade.php         ← layout cetak
│       ├── dashboard/
│       │   └── index.blade.php
│       ├── pasien/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── show.blade.php
│       ├── dokter/
│       │   ├── index.blade.php
│       │   └── jadwal.blade.php
│       ├── poli/index.blade.php
│       ├── pendaftaran/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       ├── antrian/
│       │   ├── monitor.blade.php       ← tampilan layar TV
│       │   └── kontrol.blade.php       ← panel admin
│       ├── rekam-medis/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── laporan/
│       │   └── index.blade.php
│       ├── user/
│       │   └── index.blade.php
│       ├── setting/
│       │   └── index.blade.php
│       └── log/
│           └── index.blade.php
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── public/
├── storage/
├── .env.example
├── composer.json
└── README.md
```

---

## Persyaratan Sistem

Sebelum instalasi, pastikan server atau komputer Anda memenuhi syarat berikut:

- PHP `>= 8.2` dengan ekstensi: `BCMath`, `Ctype`, `Fileinfo`, `JSON`, `Mbstring`, `OpenSSL`, `PDO`, `Tokenizer`, `XML`
- Composer `>= 2.x`
- Node.js `>= 18.x` dan NPM `>= 9.x`
- MariaDB `>= 10.4` atau MySQL `>= 8.0`
- Web server: Apache (dengan `mod_rewrite`) atau Nginx

---

## Cara Instalasi

### Langkah 1 — Clone atau extract proyek

```bash
git clone https://github.com/username/hospital-admin.git
cd hospital-admin
```

### Langkah 2 — Install dependensi PHP

```bash
composer install
```

### Langkah 3 — Install dependensi Node.js

```bash
npm install
```

### Langkah 4 — Salin file konfigurasi

```bash
cp .env.example .env
php artisan key:generate
```

### Langkah 5 — Buat database

Buka MySQL/MariaDB dan jalankan:

```sql
CREATE DATABASE hospital_admin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Langkah 6 — Konfigurasi koneksi database

Edit file `.env` dan sesuaikan bagian berikut:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_admin
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 7 — Pilih salah satu cara migrasi database

**Opsi A — Menggunakan file SQL langsung (direkomendasikan):**

```bash
mysql -u root -p hospital_admin < database/hospital_admin_complete.sql
```

**Opsi B — Menggunakan Laravel artisan:**

```bash
php artisan migrate --seed
```

### Langkah 8 — Build aset frontend

```bash
npm run build
```

### Langkah 9 — Jalankan server

```bash
php artisan serve
```

Akses sistem di browser: `http://localhost:8000`

---

## Konfigurasi

### Storage & Upload

```bash
php artisan storage:link
```

Pastikan direktori `storage/app/public` dapat ditulis oleh web server.

### Konfigurasi Tambahan di `.env`

```env
APP_NAME="SIMRS RS Harapan Sehat"
APP_URL=http://localhost:8000

# Zona waktu (sesuaikan dengan lokasi RS)
APP_TIMEZONE=Asia/Jakarta

# Mail (untuk notifikasi)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@rshospital.id"
MAIL_FROM_NAME="SIMRS RS Harapan Sehat"

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Konfigurasi Backup (Opsional)

Edit `config/backup.php` untuk menentukan tujuan backup dan jadwal otomatis. Tambahkan ke cron server:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

---

## Akun Default

Setelah menjalankan seeder, akun berikut tersedia untuk login:

| Nama | Email | Password | Role |
|---|---|---|---|
| Super Admin | admin@rshospital.id | password | Super Admin |
| Admin Poli | adminpoli@rshospital.id | password | Admin |
| dr. Jea Kurniawan | jea@rshospital.id | password | Dokter |

> **Penting:** Segera ganti password default setelah login pertama melalui menu Profil.

---

## Role & Hak Akses

Sistem menggunakan tiga role dengan hak akses berbeda:

### Super Admin
Memiliki akses penuh ke seluruh sistem termasuk manajemen user, role, pengaturan sistem, dan log aktivitas.

### Admin
Dapat mengelola data pasien, dokter, poli, pendaftaran, antrian, dan melihat laporan. Tidak dapat mengakses manajemen user dan pengaturan sistem.

### Dokter
Hanya dapat melihat jadwal dan antrian pasiennya sendiri, mengisi rekam medis, dan melihat laporan terbatas.

| Modul | Super Admin | Admin | Dokter |
|---|---|---|---|
| Dashboard | ✅ | ✅ | ✅ |
| Data Pasien | ✅ Full | ✅ Full | 👁 View |
| Dokter & Poli | ✅ Full | ✅ Full | 👁 View |
| Pendaftaran | ✅ Full | ✅ Full | ❌ |
| Antrian | ✅ Full | ✅ Full | 👁 Sendiri |
| Rekam Medis | ✅ Full | ✅ Full | ✅ Sendiri |
| Laporan | ✅ Full | ✅ Full | 👁 Terbatas |
| Manajemen User | ✅ Full | ❌ | ❌ |
| Pengaturan | ✅ Full | ❌ | ❌ |
| Log Aktivitas | ✅ Full | ❌ | ❌ |

---

## Halaman & Modul

| No | Halaman | URL | Role |
|---|---|---|---|
| 1 | Login | `/login` | Semua |
| 2 | Dashboard Admin | `/dashboard` | Super Admin, Admin |
| 3 | Dashboard Dokter | `/dashboard/dokter` | Dokter |
| 4 | Data Pasien | `/pasien` | Super Admin, Admin |
| 5 | Tambah Pasien | `/pasien/create` | Super Admin, Admin |
| 6 | Detail Pasien | `/pasien/{no_rm}` | Super Admin, Admin |
| 7 | Master Dokter | `/dokter` | Super Admin, Admin |
| 8 | Master Poli | `/poli` | Super Admin, Admin |
| 9 | Master Spesialis | `/spesialis` | Super Admin |
| 10 | Pendaftaran Pasien | `/pendaftaran` | Super Admin, Admin |
| 11 | Monitor Antrian | `/antrian/monitor` | Semua (publik) |
| 12 | Kontrol Antrian | `/antrian/kontrol` | Super Admin, Admin |
| 13 | Rekam Medis | `/rekam-medis` | Super Admin, Admin, Dokter |
| 14 | Laporan | `/laporan` | Super Admin, Admin |
| 15 | Manajemen User | `/user` | Super Admin |
| 16 | Pengaturan | `/setting` | Super Admin |
| 17 | Log Aktivitas | `/log` | Super Admin |

---

## Packages yang Digunakan

```json
{
  "require": {
    "laravel/framework": "^11.0",
    "laravel/breeze": "^2.0",
    "livewire/livewire": "^3.0",
    "spatie/laravel-permission": "^6.0",
    "spatie/laravel-activitylog": "^4.0",
    "spatie/laravel-backup": "^8.0",
    "maatwebsite/excel": "^3.1",
    "barryvdh/laravel-dompdf": "^2.0"
  },
  "require-dev": {
    "laravel/pint": "^1.0",
    "fakerphp/faker": "^1.9"
  }
}
```

Install semua packages sekaligus:

```bash
composer require livewire/livewire spatie/laravel-permission spatie/laravel-activitylog spatie/laravel-backup maatwebsite/excel barryvdh/laravel-dompdf
```

Publish konfigurasi Spatie Permission:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider"
```

---

## Desain UI

Desain antarmuka sistem ini dirancang menggunakan **Google Stitch** dengan pendekatan modern AI-era design. Referensi desain tersedia dalam 9 prompt yang mencakup seluruh halaman sistem:

| Prompt | Halaman |
|---|---|
| Prompt 1 | Login & Register |
| Prompt 2 | Data Pasien |
| Prompt 3 | Pendaftaran Pasien |
| Prompt 4 | Laporan & Statistik |
| Prompt 5 | Rekam Medis |
| Prompt 6 | Master Data (Dokter, Poli, Spesialis) |
| Prompt 7 | Manajemen User & Role |
| Prompt 8 | Monitor Antrian & Pengaturan Sistem |
| Prompt 9 | Log Aktivitas |

Desain menggunakan sistem warna konsisten:

| Warna | Kode | Kegunaan |
|---|---|---|
| Navy | `#0D2137` | Sidebar, header |
| Biru | `#1D4ED8` | Aksi utama, tombol primary |
| Hijau | `#10B981` | Status selesai, sukses |
| Amber | `#F59E0B` | Peringatan, status menunggu |
| Merah | `#EF4444` | Hapus, bahaya, error |

---

## Kontribusi

Proyek ini dikembangkan untuk keperluan akademik. Jika ingin berkontribusi atau mengembangkan lebih lanjut:

1. Fork repositori ini
2. Buat branch fitur baru: `git checkout -b fitur/nama-fitur`
3. Commit perubahan: `git commit -m 'Tambah fitur: nama fitur'`
4. Push ke branch: `git push origin fitur/nama-fitur`
5. Buat Pull Request

---

## Lisensi

Proyek ini dibuat untuk keperluan pendidikan. Bebas digunakan dan dimodifikasi untuk pengembangan lebih lanjut dengan tetap mencantumkan atribusi kepada pengembang asli.

---

**Database:** `ars_230217058` · **Framework:** Laravel 11 · **Versi:** 1.0.0 · **Tahun:** 2026