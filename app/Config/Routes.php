<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');

// Authentication Routes
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('process', 'Auth::process');
    $routes->get('logout', 'Auth::logout');
});

// API Routes
$routes->group('api', function ($routes) {
    $routes->get('notifications/unread-count', 'Notification::getUnreadCount');
});

// Protected Routes (Group with Auth Filter soon)
$routes->get('/dashboard', 'Dashboard::index');
$routes->post('dashboard/delete-log/(:num)', 'Dashboard::deleteLog/$1');
$routes->post('dashboard/delete-all-logs', 'Dashboard::deleteAllLogs');

// Surat Masuk Routes
$routes->group('surat-masuk', function ($routes) {
    $routes->get('/', 'SuratMasuk::index');
    $routes->get('create', 'SuratMasuk::create', ['filter' => 'role:admin,operator']);
    $routes->post('store', 'SuratMasuk::store', ['filter' => 'role:admin,operator']);
    $routes->post('ajax-list', 'SuratMasuk::ajaxList');
    $routes->get('edit/(:num)', 'SuratMasuk::edit/$1', ['filter' => 'role:admin,operator']);
    $routes->post('update/(:num)', 'SuratMasuk::update/$1', ['filter' => 'role:admin,operator']);
    $routes->get('show/(:num)', 'SuratMasuk::show/$1');
    $routes->post('delete/(:num)', 'SuratMasuk::delete/$1', ['filter' => 'role:admin,operator']);
    $routes->get('export-excel', 'SuratMasuk::exportExcel');
    $routes->get('export-pdf', 'SuratMasuk::exportPdf');
    $routes->get('import', 'SuratMasuk::import', ['filter' => 'role:admin,operator']);
    $routes->get('download-template', 'SuratMasuk::downloadTemplate', ['filter' => 'role:admin,operator']);
    $routes->post('preview', 'SuratMasuk::preview', ['filter' => 'role:admin,operator']);
    $routes->post('store-import', 'SuratMasuk::storeImport', ['filter' => 'role:admin,operator']);
    $routes->post('reassign-agenda', 'SuratMasuk::reassignAgenda', ['filter' => 'role:admin']);
});

// Surat Keluar Routes
$routes->group('surat-keluar', function ($routes) {
    $routes->get('/', 'SuratKeluar::index');
    $routes->get('create', 'SuratKeluar::create', ['filter' => 'role:admin,operator']);
    $routes->post('store', 'SuratKeluar::store', ['filter' => 'role:admin,operator']);
    $routes->post('ajax-list', 'SuratKeluar::ajaxList');
    $routes->get('edit/(:num)', 'SuratKeluar::edit/$1', ['filter' => 'role:admin,operator']);
    $routes->post('update/(:num)', 'SuratKeluar::update/$1', ['filter' => 'role:admin,operator']);
    $routes->get('show/(:num)', 'SuratKeluar::show/$1');
    $routes->post('approve/(:num)', 'SuratKeluar::approve/$1'); // pimpinan can approve
    $routes->post('delete/(:num)', 'SuratKeluar::delete/$1', ['filter' => 'role:admin,operator']);
    $routes->get('export-excel', 'SuratKeluar::exportExcel');
    $routes->get('export-pdf', 'SuratKeluar::exportPdf');
    $routes->get('import', 'SuratKeluar::import', ['filter' => 'role:admin,operator']);
    $routes->get('download-template', 'SuratKeluar::downloadTemplate', ['filter' => 'role:admin,operator']);
    $routes->post('preview', 'SuratKeluar::preview', ['filter' => 'role:admin,operator']);
    $routes->post('store-import', 'SuratKeluar::storeImport', ['filter' => 'role:admin,operator']);
    $routes->post('renumber', 'SuratKeluar::renumber', ['filter' => 'role:admin,operator']);
});

// Disposisi Routes
$routes->group('disposisi', function ($routes) {
    $routes->get('/', 'Disposisi::index');
    $routes->get('create/(:num)', 'Disposisi::create/$1', ['filter' => 'role:admin,operator']); // parameter: surat_masuk_id
    $routes->post('store', 'Disposisi::store', ['filter' => 'role:admin,operator']);
    $routes->get('show/(:num)', 'Disposisi::show/$1');
    $routes->post('updateStatus/(:num)', 'Disposisi::updateStatus/$1'); // update status (no strict role filter, handled in controller/view)
});

// Prestasi Siswa
$routes->group('prestasi-siswa', function ($routes) {
    $routes->get('/', 'PrestasiSiswa::index');
    $routes->get('create', 'PrestasiSiswa::create');
    $routes->post('store', 'PrestasiSiswa::store');
    $routes->get('edit/(:num)', 'PrestasiSiswa::edit/$1');
    $routes->post('update/(:num)', 'PrestasiSiswa::update/$1');
    $routes->post('delete/(:num)', 'PrestasiSiswa::delete/$1');
    $routes->get('import', 'PrestasiSiswa::import');
    $routes->get('download-template', 'PrestasiSiswa::downloadTemplate');
    $routes->post('preview', 'PrestasiSiswa::preview');
    $routes->post('store-import', 'PrestasiSiswa::storeImport');
    $routes->get('export-excel', 'PrestasiSiswa::exportExcel');
    $routes->get('export-pdf', 'PrestasiSiswa::exportPdf');
});



// Admin Routes (Users & Setting)
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    // User Management
    $routes->group('users', function ($routes) {
        $routes->get('/', 'User::index');
        $routes->get('create', 'User::create');
        $routes->post('store', 'User::store');
        $routes->get('edit/(:num)', 'User::edit/$1');
        $routes->post('update/(:num)', 'User::update/$1');
        $routes->post('delete/(:num)', 'User::delete/$1');
    });

    // Database Backup
    $routes->get('backup-db', 'Admin\BackupDB::download');
});

// Profile Routes
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->post('profile/delete-photo', 'Profile::deletePhoto');
// Data Madrasah Route
$routes->get('data-madrasah', 'DataMadrasah::index', ['filter' => 'role:admin,operator']);

// Kelas Routes
$routes->group('kelas', ['filter' => 'role:admin,operator'], function ($routes) {
    $routes->get('/', 'Kelas::index');
    $routes->get('create', 'Kelas::create');
    $routes->post('store', 'Kelas::store');
    $routes->get('edit/(:num)', 'Kelas::edit/$1');
    $routes->post('update/(:num)', 'Kelas::update/$1');
    $routes->post('delete/(:num)', 'Kelas::delete/$1');
    $routes->get('siswa/(:num)', 'Kelas::siswa/$1');
});

// Siswa Routes
$routes->group('siswa', ['filter' => 'role:admin,operator'], function ($routes) {
    $routes->get('/', 'Siswa::index');
    $routes->get('create', 'Siswa::create');
    $routes->post('store', 'Siswa::store');
    $routes->get('edit/(:num)', 'Siswa::edit/$1');
    $routes->post('update/(:num)', 'Siswa::update/$1');
    $routes->post('delete/(:num)', 'Siswa::delete/$1');
    $routes->get('import', 'Siswa::import');
    $routes->get('download-template', 'Siswa::downloadTemplate');
    $routes->post('store-import', 'Siswa::storeImport');
});

// Data Guru Routes
$routes->group('data-guru', ['filter' => 'role:admin,operator'], function ($routes) {
    $routes->get('/', 'DataGuru::index');
    $routes->get('create', 'DataGuru::create');
    $routes->post('store', 'DataGuru::store');
    $routes->get('edit/(:num)', 'DataGuru::edit/$1');
    $routes->post('update/(:num)', 'DataGuru::update/$1');
    $routes->post('delete/(:num)', 'DataGuru::delete/$1');
    $routes->get('berkas/(:num)', 'DataGuru::berkas/$1');
    $routes->get('import', 'DataGuru::import');
});

// --- BUKU TAMU TERINTEGRASI ---
$routes->group('buku-tamu', function ($routes) {
    $routes->get('/', 'BukuTamu::index');
    $routes->get('umum', 'BukuTamu::formUmum');
    $routes->get('dinas', 'BukuTamu::formDinas');
    $routes->post('store', 'BukuTamu::store');
    $routes->get('success', 'BukuTamu::successPage');
});

$routes->get('test-admin-buku-tamu', 'AdminBukuTamu::index');
$routes->group('admin-buku-tamu', ['filter' => 'role:admin,operator,admin_tamu'], function ($routes) {
    $routes->get('/', 'AdminBukuTamu::index');
    $routes->get('show/(:num)', 'AdminBukuTamu::show/$1');
    $routes->post('update-kunjungan/(:num)', 'AdminBukuTamu::updateKunjungan/$1');
    $routes->post('export-excel', 'AdminBukuTamu::exportExcel');
    $routes->post('export-pdf', 'AdminBukuTamu::exportPdf');
    $routes->post('delete/(:num)', 'AdminBukuTamu::delete/$1');
});

$routes->group('surat-resmi', ['filter' => 'isLoggedIn'], function ($routes) {
    $routes->get('/', 'SuratResmi::index');
    $routes->get('create', 'SuratResmi::create');
    $routes->post('store', 'SuratResmi::store');
    $routes->get('edit/(:num)', 'SuratResmi::edit/$1');
    $routes->post('update/(:num)', 'SuratResmi::update/$1');
    $routes->post('delete/(:num)', 'SuratResmi::delete/$1');
    $routes->post('save-kop', 'SuratResmi::saveKop');
    $routes->post('previewPdf', 'SuratResmi::previewPdf');
    $routes->get('printPdf/(:num)', 'SuratResmi::printPdf/$1');
});

// Pengaturan Routes
$routes->group('pengaturan', ['filter' => 'role:admin,admin_tamu'], function ($routes) {
    $routes->get('/', 'Pengaturan::index');
    $routes->post('update-identitas', 'Pengaturan::updateIdentitas');
    $routes->post('update-pimpinan', 'Pengaturan::updatePimpinan');
    $routes->post('update-preferensi', 'Pengaturan::updatePreferensi');
    $routes->post('update-wajib-field', 'Pengaturan::updateWajibField');
    $routes->post('update-buku-tamu', 'Pengaturan::updateBukuTamu');
    $routes->get('delete-logo', 'Pengaturan::deleteLogo');
    $routes->post('store-tahun-anggaran', 'Pengaturan::storeTahunAnggaran');
    $routes->post('delete-tahun-anggaran/(:num)', 'Pengaturan::deleteTahunAnggaran/$1');
    $routes->post('aktifkan-tahun-anggaran/(:any)', 'Pengaturan::aktifkanTahunAnggaran/$1');
    $routes->post('store-format-surat', 'Pengaturan::storeFormatSurat');
    $routes->post('update-format-surat/(:num)', 'Pengaturan::updateFormatSurat/$1');
    $routes->post('delete-format-surat/(:num)', 'Pengaturan::deleteFormatSurat/$1');
});

// API Pengaturan (diakses oleh semua user yang sudah login)
$routes->get('pengaturan/get-link-drive', 'Pengaturan::getLinkDrive');
