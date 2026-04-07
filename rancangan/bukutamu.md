# Rancangan Arsitektur MVC Buku Tamu Digital (CodeIgniter 4)

Dokumen ini memaparkan rancangan teknis lengkap berdasarkan paradigma **Model-View-Controller (MVC)** untuk membangun fitur Buku Tamu Digital di dalam sistem CodeIgniter 4.

---

## 1. STRUKTUR DATABASE & MODELS (Model)

Pemisahan data entitas agar lebih terstruktur dan menghindari duplikasi data pengunjung yang sering datang berkali-kali.

### Tabel Database
1. **`tamu`**: Menyimpan identitas tunggal/unik setiap tamu.
2. **`kunjungan`**: Menyimpan histori kedatangan tamu ke madrasah.
3. **`tamu_dokumen`**: Menyimpan path ke file foto (webcam) dan tanda tangan (canvas).

### Model Components (`app/Models`)
*   **`TamuModel.php`**
    *   **Table:** `tamu`
    *   **Primary Key:** `id_tamu`
    *   **Allowed Fields:** `jenis_tamu`, `nama_lengkap`, `alamat_instansi`, `nip`, `jabatan`, `no_hp`, `consent_wa`
    *   **Fungsi Khusus:** `findOrCreateTamu(array $data)` -> Mengecek apakah tamu dengan nama/No.HP yang sama sudah ada, jika ada ambil ID-nya, jika tidak buat baru.

*   **`KunjunganModel.php`**
    *   **Table:** `kunjungan`
    *   **Primary Key:** `id_kunjungan`
    *   **Allowed Fields:** `id_tamu`, `tanggal_waktu`, `tujuan_kunjungan`, `id_pegawai_dituju`, `id_siswa_dituju`, `pesan_kesan`, `dokumen_pendukung`, `status_kunjungan`, `tindak_lanjut`, `foto_wajah`, `tanda_tangan`
    *   **Fungsi Khusus:** 
        *   `getKunjunganWithDetails($status = null)` -> Query kustom dengan `JOIN` tabel `tamu` dan `pegawai` untuk memunculkan data utuh di Datatables Admin.

---

## 2. PENGKODEAN LOGIKA (Controller)

Sistem akan dipisah menjadi 2 Controller utama: **Sisi Publik (Kiosk/Resepsionis)** dan **Sisi Manajemen (Dashboard Admin/Piket)**.

### A. Controller Publik (`app/Controllers/BukuTamu.php`)
Menangani antarmuka layar sentuh atau tablet resepsionis yang diisi langsung oleh tamu. Tidak ada *authentication* di area ini.

*   `index()`: Merender halaman selamat datang dengan 2 tombol besar (Tamu Umum & Tamu Dinas).
*   `formUmum()`: Merender antarmuka form bagi tamu umum/wali murid.
*   `formDinas()`: Merender antarmuka form bagi instansi/pejabat dinas (ada input NIP, Jabatan).
*   `store()`: Menangani `$_POST` dari kedua form, melakukan validasi rule CI4, menjalankan `Database::transStart()`, menyimpan data ke `TamuModel` lalu `KunjunganModel`, memproses unggahan file Base64 (foto webcam/ttd), lalu `Database::transComplete()`.
*   `successPage()`: Menampilkan halaman peringatan "Terima Kasih, silakan tunggu..." (di-_redirect_ kembali otomatis dalam 5 detik).

### B. Controller Admin (`app/Controllers/AdminBukuTamu.php`)
Dilindungi oleh Filter `role:admin,operator,piket`.

*   `index()`: Menampilkan *DataTables* berisikan daftar semua kunjungan pengunjung hari ini atau *all-time*.
*   `show($id)`: Menampilkan *Modal* atau halaman detail lengkap foto tamu, TTD, dan tujuan.
*   `updateStatus($id)`: Mengubah status `kunjungan` (Menunggu -> Diterima -> Selesai).
*   `addTindakLanjut($id)`: Aksi bagi admin untuk menyimpan catatan hasil pertemuan dengan tamu.
*   `exportData()`: Menghasilkan unduhan Spreadsheet (Excel/PDF) berisi rekap buku tamu bulanan.

---

## 3. ANTARMUKA PENGGUNA (Views)

### A. Folder Publik (`app/Views/buku_tamu/publik/`)
Tidak menggunakan layout backend, melainkan layout *full-screen* modern (Kiosk Mode).
*   `layout.php`: Master template publik (Header tanpa menu navigasi besar, hanya logo madrasah).
*   `index.php`: Pilihan kategori tamu. Memiliki script jam & tanggal _realtime_.
*   `form_umum.php`: Input *wizard* atau satu halaman panjang vertikal. 
    *   Termasuk pengaktifan `navigator.mediaDevices.getUserMedia` untuk *Webcam Foto*.
*   `form_dinas.php`: Sama dengan form umum, namun ada input tipe `file` untuk dokumen_pendukung dan pad goresan `canvas` JS untuk tanda tangan.
*   `success.php`: Pesan centang hijau raksasa (mirip gaya SweetAlert).

### B. Folder Admin (`app/Views/buku_tamu/admin/`)
Diintegrasikan *(extend)* dengan layout Tabler utama `layout/template.php`.
*   `index.php`: Tabel list kunjungan. Terdapat komponen filter rentang kalender (DateRangePicker).
*   `detail_modal.php`: Form pop-up detail, berisikan pratinjau wajah *webcam capture* sang tamu, form update tindak lanjut, dan status.

---

## 4. ROUTING (`app/Config/Routes.php`)

```php
// --- ROUTE PUBLIK (KIOSK BUKU TAMU) ---
$routes->group('buku-tamu', function ($routes) {
    $routes->get('/', 'BukuTamu::index');
    $routes->get('umum', 'BukuTamu::formUmum');
    $routes->get('dinas', 'BukuTamu::formDinas');
    $routes->post('store', 'BukuTamu::store');
    $routes->get('success', 'BukuTamu::successPage');
});

// --- ROUTE ADMIN & PIKET (BACKEND) ---
$routes->group('admin-buku-tamu', ['filter' => 'role:admin,operator,piket'], function ($routes) {
    $routes->get('/', 'AdminBukuTamu::index');
    $routes->get('show/(:num)', 'AdminBukuTamu::show/$1');
    $routes->post('update-status/(:num)', 'AdminBukuTamu::updateStatus/$1');
    $routes->post('tindak-lanjut/(:num)', 'AdminBukuTamu::addTindakLanjut/$1');
    $routes->get('export', 'AdminBukuTamu::exportData');
});
```

---

## 5. REKOMENDASI LIBRARY FRONT-END KHUSUS
Untuk mendukung fitur spesifik Buku Tamu:
1. **`webcam.js`** atau API native **`MediaDevices`**: Untuk integrasi kamera secara live dari browser, lalu diubah ke format _Base64 String_ untuk _payload_ POST.
2. **`signature_pad.js`**: Modul Javascript ringan untuk merekam usapan HTML5 Canvas menjadi gambar TTD digital.
3. **`select2` / `TomSelect`**: Untuk _Dropdown Dinamis_ pencarian nama guru/pegawai yang dituju (agar jika pegawainya ratusan, sistem tetap ringan).