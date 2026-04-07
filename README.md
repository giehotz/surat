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

## Setup dan Jalankan

1. Install dependencies:
   ```bash
   composer install
   ```
2. Salin `env` ke `.env` dan atur konfigurasi:
   - `app.baseURL`
   - database
   - env, debug, dan session
3. Pastikan web server diarahkan ke folder `public/`.
4. Pastikan direktori `writable/` dan `public/uploads/` dapat ditulis.
5. Jalankan migrasi database jika tersedia.

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
