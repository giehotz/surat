# Surat - Sistem Manajemen Surat dan Buku Tamu

Aplikasi web manajemen surat dan buku tamu berbasis CodeIgniter 4, khusus dibuat untuk kebutuhan madrasah atau sekolah.

## Ringkasan Proyek

Aplikasi ini menangani:
- Autentikasi pengguna dengan proteksi brute-force login
- Dashboard ringkasan surat masuk, surat keluar, disposisi, aktivitas dan statistik
- Modul Surat Masuk dan Surat Keluar dengan import/export Excel/PDF
- Alur persetujuan Surat Keluar untuk pimpinan
- Sistem Disposisi untuk tindak lanjut Surat Masuk
- Buku Tamu publik terintegrasi dengan formulir umum dan dinas
- Manajemen data siswa, kelas, data guru, dan data madrasah
- Pengaturan aplikasi dan manajemen pengguna berbasis peran
- Backup database dan pengaturan tahun anggaran

## Teknologi

- PHP 8.1+ (direkomendasikan)
- CodeIgniter 4
- MySQL/MariaDB atau database yang didukung CodeIgniter
- Composer

## Arsitektur Utama

- `app/Controllers` - logika kontrol aplikasi
- `app/Models` - akses data ke tabel database
- `app/Views` - tampilan HTML dan antarmuka pengguna
- `app/Config/Routes.php` - definisi rute aplikasi
- `app/Config/Filters.php` - manajemen autentikasi dan role filter
- `public/` - root web server dan asset publik
- `writable/` dan `uploads/` - direktori runtime dan file upload

## Alur Utama Aplikasi

### 1. Akses Publik
- `/` atau `/auth/login` menampilkan halaman login.
- Jika sudah login, pengguna diarahkan ke `/dashboard`.

### 2. Autentikasi
- Login diproses oleh `App\Controllers\Auth`.
- Proteksi brute-force menggunakan `LoginAttemptModel`.
- Akun dapat login dengan username/email dan password yang tersimpan.
- Terdapat akun dummy `admin/admin` dan `staf/staf` untuk pengujian lokal jika DB belum tersedia.
- Logout menghapus sesi dan mencatat aktivitas login/logout.

### 3. Dashboard
- Dashboard dikelola oleh `App\Controllers\Dashboard`.
- Untuk `admin_tamu`, dashboard menampilkan statistik buku tamu, kunjungan, dan grafik.
- Untuk pengguna lain, dashboard menampilkan ringkasan surat masuk/keluar, disposisi, pengguna, dan log aktivitas.

### 4. Modul Utama

#### Surat Masuk
- URL: `surat-masuk/*`
- Fungsionalitas: daftar, buat, edit, detail, hapus, impor, ekspor Excel/PDF.
- Hanya `admin` dan `operator` boleh membuat, mengedit, dan mengimpor.

#### Surat Keluar
- URL: `surat-keluar/*`
- Fungsionalitas: daftar, buat, edit, detail, hapus, persetujuan, impor, ekspor Excel/PDF.
- Proses approval untuk pimpinan.
- Hanya `admin` dan `operator` boleh membuat dan mengedit.

#### Disposisi
- URL: `disposisi/*`
- Fungsionalitas: buat disposisi dari surat masuk, lihat detail, ubah status.
- Akses dasar tersedia untuk pengguna yang sudah login.

#### Prestasi Siswa
- URL: `prestasi-siswa/*`
- Fungsionalitas: CRUD prestasi, impor, dan ekspor data.

#### Data Siswa & Kelas
- URL: `siswa/*` dan `kelas/*`
- Fungsionalitas: manajemen siswa, kelas, dan penunjukan siswa ke kelas.
- Terbatas untuk `admin` dan `operator`.

#### Data Guru
- URL: `data-guru/*`
- Fungsionalitas: CRUD data guru, unggah berkas, impor data.
- Terbatas untuk `admin` dan `operator`.

#### Data Madrasah
- URL: `/data-madrasah`
- Menampilkan informasi madrasah, hanya dapat diakses oleh `admin` dan `operator`.

#### Buku Tamu
- URL publik: `/buku-tamu`, `/buku-tamu/umum`, `/buku-tamu/dinas`
- Formulir tamu umum dan dinas.
- Logika buka/tutup berdasarkan pengaturan dan jadwal kerja.
- Penyimpanan data tamu, kunjungan, foto wajah, tanda tangan, dan dokumen pendukung.

#### Admin Buku Tamu
- URL: `/admin-buku-tamu/*`
- Fungsionalitas: lihat tamu, update status, tindak lanjut, ekspor, hapus.
- Akses untuk `admin`, `operator`, dan `admin_tamu`.

#### Pengaturan Aplikasi
- URL: `/pengaturan/*`
- Fungsionalitas: identitas sekolah, pimpinan, preferensi, wajib field, buku tamu, dan tahun anggaran.
- Akses untuk `admin`; beberapa pengaturan buku tamu dapat diubah oleh `admin_tamu`.
- API tambahan: `/pengaturan/get-link-drive`

### 5. Keamanan & Role

- Filter global `isLoggedIn` memastikan hanya pengguna login yang mengakses bagian internal.
- `role` filter mengatur hak akses untuk route tertentu.
- `honeypot` aktif khusus pada route `buku-tamu/*`.
- Aplikasi menggunakan session CodeIgniter untuk manajemen pengguna.

## Prasyarat Sistem

Sebelum melakukan clone dan instalasi, pastikan sistem Anda memenuhi persyaratan berikut:
- **PHP**: Versi 8.1+ (disarankan PHP 8.2 atau 8.4)
- **Ekstensi PHP Wajib**: `php-intl`, `php-mbstring`, `php-gd` (untuk logo & gambar), `php-curl`, `php-mysqli`, `php-fileinfo`
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Composer**: Versi 2.x
- **Web Server**: Apache / Nginx / PHP Built-in Server

---

## Panduan Instalasi Step-by-Step (Setelah Clone)

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan proyek setelah di-clone dari repository:

### 1. Clone Repository
Buka terminal dan clone repository ke direktori lokal Anda:
```bash
git clone https://github.com/giehotz/surat.git
cd surat
```

### 2. Install Dependensi Composer
Jalankan composer untuk mengunduh semua library dan vendor yang dibutuhkan:
```bash
composer install
```

### 3. Salin dan Atur Konfigurasi Environment (`.env`)
Salin file template `.env.example` menjadi `.env`:
- **Windows (CMD / PowerShell):**
  ```bash
  copy .env.example .env
  ```
- **Linux / macOS:**
  ```bash
  cp .env.example .env
  ```

Buka file `.env` menggunakan text editor dan sesuaikan:
```ini
# Ubah ke 'development' untuk lokal atau 'production' untuk live server
CI_ENVIRONMENT = development

# Sesuaikan URL lokal Anda (akhiri dengan garis miring '/')
app.baseURL = 'http://localhost:8080/'

# Konfigurasi Database
database.default.hostname = localhost
database.default.database = nama_database_anda
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 4. Buat Database MySQL
Buat database baru di MySQL (melalui phpMyAdmin, MySQL Workbench, atau terminal):
```sql
CREATE DATABASE nama_database_anda CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Jalankan Migrasi Database
Jalankan perintah spark untuk membuat seluruh struktur tabel:
```bash
php spark migrate
```

### 6. Isi Data Awal (Seeding)
Jalankan seeder untuk mengisi akun pengguna bawaan dan identitas madrasah/sekolah:
```bash
php spark db:seed UserSeeder
php spark db:seed PengaturanSeeder
```

> **Akun Default untuk Login:**
> | Role | Username | Password Default | Keterangan |
> | :--- | :--- | :--- | :--- |
> | **Admin** | `admin` | `admin123` | Hak akses penuh & pengaturan sistem |
> | **Pimpinan** | `pimpinan` | `pimpinan123` | Persetujuan surat keluar & disposisi |
> | **Operator** | `operator` | `operator123` | Pencatatan surat masuk & buku tamu |

*Catatan: Segera ubah password akun default setelah berhasil masuk ke sistem melalui menu profil.*

### 7. Atur Izin Akses Direktori (Permissions)
Pastikan folder `writable/` dan `public/uploads/` memiliki izin baca dan tulis:
- **Linux / macOS / VPS / Shared Hosting:**
  ```bash
  chmod -R 775 writable public/uploads
  # Atau jika web server menggunakan user www-data:
  chown -R www-data:www-data writable public/uploads
  ```
- **Windows (Laragon / XAMPP):** Secara umum izin sudah otomatis terbuka, pastikan folder tidak berstatus *Read-Only*.

### 8. Jalankan Aplikasi

- **Menggunakan Local Spark Server (Rekomendasi Cepat):**
  ```bash
  php spark serve
  ```
  Buka browser Anda dan akses: **[http://localhost:8080](http://localhost:8080)**

- **Menggunakan Laragon / XAMPP / Apache VirtualHost:**
  Arahkan **DocumentRoot** langsung ke direktori `public/` (bukan root folder proyek). Contoh konfigurasi VirtualHost:
  ```apache
  <VirtualHost *:80>
      ServerName surat.local
      DocumentRoot "D:/surat/public"
      <Directory "D:/surat/public">
          AllowOverride All
          Require all granted
      </Directory>
  </VirtualHost>
  ```

## Catatan Penting

- `index.php` berada di dalam `public/`.
- `app/Config/App.php` memiliki logika baseURL otomatis yang hanya mengizinkan host tertentu.
- Upload file disimpan dalam `public/uploads/`.
- Role yang digunakan di aplikasi: `admin`, `operator`, `admin_tamu`, dan kemungkinan lainnya.

## Struktur Fitur

- `app/Controllers/Auth.php` - login/logout dan proteksi login
- `app/Controllers/Dashboard.php` - ringkasan data dan dashboard role-spesifik
- `app/Controllers/BukuTamu.php` - alur pengiriman tamu publik dan penyimpanan kunjungan
- `app/Controllers/Pengaturan.php` - pusat konfigurasi sistem
- `app/Config/Routes.php` - peta rute utama dan otorisasi per modul
- `app/Config/Filters.php` - aturan keamanan dan filter global

## Pelajari Alurnya

1. Pengguna mengunjungi `/auth/login`.
2. Setelah login, pengguna diarahkan ke `/dashboard`.
3. Dashboard menampilkan ringkasan data sesuai peran.
4. Pengguna dapat mengakses modul surat, disposisi, data akademik, dan pengaturan.
5. Pengguna publik bisa mengisi buku tamu tanpa login.

---

Dokumentasi ini menggantikan README bawaan dan menyesuaikan dengan alur aplikasi `surat` yang ada.
