<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Header Halaman (Hanya Tampil di Layar) -->
<div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle text-muted text-uppercase tracking-wide mb-1">
                Layanan Administrasi
            </div>
            <h2 class="page-title fw-bold">
                <i class="ti ti-calendar-event me-2 text-primary"></i> Daftar Hadir Harian Pegawai
            </h2>
            <div class="text-muted mt-1">Presensi harian resmi bulanan perorangan atau seluruh guru berformat standar kertas F4 / Folio (Perdirjen Pendis 1/2013).</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="d-flex gap-2">
                <a href="<?= base_url('daftar-hadir/cetak-harian?' . http_build_query($_GET)) ?>" target="_blank" class="btn btn-outline-primary shadow-sm">
                    <i class="ti ti-external-link icon me-1"></i> Buka Tab Cetak Khusus
                </a>
                <a href="<?= base_url('daftar-hadir/cetak-harian?' . http_build_query(array_merge($_GET, ['autoprint' => 1]))) ?>" target="_blank" class="btn btn-primary shadow-sm">
                    <i class="ti ti-printer icon me-1"></i> Cetak Semua Pegawai (<?= $total_guru_lengkap ?> Lembar)
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Flash Message Alert -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible shadow-sm border-0 rounded-3 mb-3 d-print-none" role="alert">
        <div class="d-flex align-items-center">
            <i class="ti ti-circle-check fs-2 me-2 text-success"></i>
            <div class="flex-grow-1">
                <strong>Berhasil:</strong> <?= session()->getFlashdata('success') ?>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('info')): ?>
    <div class="alert alert-info alert-dismissible shadow-sm border-0 rounded-3 mb-3 d-print-none" role="alert">
        <div class="d-flex align-items-center">
            <i class="ti ti-info-circle fs-2 me-2 text-info"></i>
            <div class="flex-grow-1">
                <strong>Informasi:</strong> <?= session()->getFlashdata('info') ?>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-warning alert-dismissible shadow-sm border-0 rounded-3 mb-3 d-print-none" role="alert">
        <div class="d-flex align-items-center">
            <i class="ti ti-alert-triangle fs-2 me-2 text-warning"></i>
            <div class="flex-grow-1">
                <strong>Catatan:</strong> <?= session()->getFlashdata('error') ?>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
        </div>
    </div>
<?php endif; ?>

<!-- Panel Filter Kalender & Pegawai -->
<div class="card shadow-sm border rounded-3 mb-4 d-print-none">
    <div class="card-header bg-surface py-2 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <span class="fw-semibold text-secondary small">
            <i class="ti ti-adjustments me-1 text-primary"></i> Parameter Filter & Konfigurasi Presensi
        </span>
        <div class="d-flex align-items-center gap-2">
            <?php if (($libur_bulan_ini_count ?? 0) > 0): ?>
                <span class="badge bg-green-lt text-green small">
                    <i class="ti ti-calendar-event me-1"></i> <?= $libur_bulan_ini_count ?> Hari Libur Nasional di Bulan Ini
                </span>
            <?php else: ?>
                <span class="badge bg-secondary-lt text-muted small">
                    <i class="ti ti-calendar me-1"></i> Tidak Ada Libur Nasional Bulan Ini (Hanya Ahad)
                </span>
            <?php endif; ?>
            <a href="<?= base_url('daftar-hadir/sync-libur?' . http_build_query($_GET)) ?>" class="btn btn-outline-secondary btn-sm py-0 px-2 text-nowrap" title="Sinkronkan data hari libur dari API">
                <i class="ti ti-refresh me-1"></i> Sinkronkan API
            </a>
        </div>
    </div>
    <div class="card-body p-3">
        <form method="get" action="<?= base_url('daftar-hadir/harian') ?>" id="form-filter-harian">
            <div class="row g-3 align-items-end">
                <!-- Pilihan Bulan -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-bold text-muted mb-1">Bulan Presensi</label>
                    <select name="bulan" class="form-select form-select-sm">
                        <?php foreach ($bulan_list as $num => $nama): ?>
                            <option value="<?= $num ?>" <?= (int)$bulan === $num ? 'selected' : '' ?>>
                                <?= str_pad($num, 2, '0', STR_PAD_LEFT) ?> - <?= ucfirst(strtolower($nama)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Input Tahun -->
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-bold text-muted mb-1">Tahun</label>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="<?= esc($tahun) ?>" min="2020" max="2035">
                </div>

                <!-- Filter Status Kepegawaian -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-bold text-muted mb-1">Status Kepegawaian</label>
                    <select name="status" class="form-select form-select-sm" onchange="document.getElementById('form-filter-harian').submit()">
                        <option value="all" <?= $status === 'all' ? 'selected' : '' ?>>Semua Status (PNS, PPPK, Honorer)</option>
                        <option value="pns" <?= $status === 'pns' ? 'selected' : '' ?>>Hanya PNS</option>
                        <option value="pppk" <?= $status === 'pppk' ? 'selected' : '' ?>>Hanya PPPK</option>
                        <option value="honorer" <?= $status === 'honorer' ? 'selected' : '' ?>>Hanya Honorer / Non-ASN</option>
                    </select>
                </div>

                <!-- Pilihan Pegawai Spesifik / Semua -->
                <div class="col-md-3 col-sm-6">
                    <label class="form-label small fw-bold text-muted mb-1">Pilih Pegawai / Guru</label>
                    <select name="guru_id" class="form-select form-select-sm">
                        <option value="all" <?= $guru_id === 'all' ? 'selected' : '' ?>>-- Semua Pegawai (<?= count($semua_guru) ?> Orang) --</option>
                        <?php foreach ($semua_guru as $g): ?>
                            <option value="<?= $g['id'] ?>" <?= (string)$guru_id === (string)$g['id'] ? 'selected' : '' ?>>
                                <?= esc($g['nama_pegawai']) ?> (<?= !empty($g['nip']) ? esc($g['nip']) : 'Non-NIP' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="col-md-1 col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100" title="Terapkan Filter">
                        <i class="ti ti-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- Ringkasan Statistik Kalender -->
    <div class="card-footer bg-surface-secondary py-2 border-top">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 text-muted small">
            <div class="d-flex align-items-center gap-3">
                <span><i class="ti ti-users me-1 text-primary"></i> Total Pegawai: <strong class="text-dark"><?= $total_guru_lengkap ?> Lembar</strong></span>
                <span><i class="ti ti-briefcase me-1 text-success"></i> Hari Efektif: <strong class="text-dark"><?= $total_kerja ?> Hari</strong></span>
                <span><i class="ti ti-calendar-off me-1 text-danger"></i> Ahad & Libur: <strong class="text-dark"><?= $total_libur ?> Hari</strong></span>
            </div>
            <div>
                <span class="text-muted"><i class="ti ti-file-certificate me-1"></i> Standar Kertas: <strong>F4 / Folio (215 &times; 330 mm)</strong></span>
            </div>
        </div>
    </div>
</div>

<!-- Container Pratinjau Dokumen F4 -->
<div class="card shadow-sm border rounded-3 mb-5 d-print-clean">
    <div class="card-header py-2 d-print-none d-flex justify-content-between align-items-center bg-surface">
        <span class="text-secondary small fw-medium d-flex align-items-center">
            <i class="ti ti-eye me-1 text-primary"></i> Pratinjau Lembar Dokumen Siap Cetak (Kertas F4 / Folio)
            <span class="badge bg-blue-lt ms-2">
                <i class="ti ti-file-text me-1"></i> Sampel 1 Lembar (Total: <?= $total_guru_lengkap ?> Lembar)
            </span>
        </span>
        <div class="btn-list">
            <a href="<?= base_url('daftar-hadir/cetak-harian?' . http_build_query(array_merge($_GET, ['autoprint' => 1]))) ?>" target="_blank" class="btn btn-sm btn-primary">
                <i class="ti ti-printer me-1"></i> Cetak Semua (<?= $total_guru_lengkap ?> Lembar)
            </a>
        </div>
    </div>
    
    <div class="card-body p-2 p-md-4 d-flex flex-column align-items-center f4-preview-stage" style="background-color: #d1d5db;">
        <!-- Banner Informasi Mode Pratinjau 1 Guru -->
        <div class="alert alert-info border-0 shadow-sm d-flex flex-wrap align-items-center justify-content-between p-2 px-3 mb-2 w-100 rounded-3 d-print-none" style="max-width: 215mm;">
            <div class="d-flex align-items-center gap-2">
                <i class="ti ti-info-circle fs-2 text-info"></i>
                <div class="small">
                    <strong>Mode Pratinjau Layar:</strong> Menampilkan sampel 1 lembar (<strong><?= esc($daftar_guru_preview[0]['nama_pegawai'] ?? '') ?></strong>).
                    Seluruh <strong><?= $total_guru_lengkap ?> lembar pegawai</strong> akan dimuat otomatis saat membuka lembar cetak.
                </div>
            </div>
            <a href="<?= base_url('daftar-hadir/cetak-harian?' . http_build_query($_GET)) ?>" target="_blank" class="btn btn-sm btn-info text-nowrap ms-auto my-1">
                <i class="ti ti-external-link me-1"></i> Lihat Semua (<?= $total_guru_lengkap ?> Lembar)
            </a>
        </div>

        <!-- Include Template Lembar F4 (Hanya 1 Guru untuk Preview Cepat di Layar) -->
        <?= $this->setData(['daftar_guru' => $daftar_guru_preview])->include('daftar_hadir/partials/lembar_hadir_harian') ?>
    </div>
</div>

<!-- Styling Tambahan Khusus Integrasi Template Tabler & Media Print -->
<style>
    /* CSS Khusus Media Print Tabler Layout */
    @media print {
        header, footer, nav, .page-header, .card-header, .card-footer, .d-print-none, .navbar, #navbar-menu, .alert, .btn {
            display: none !important;
        }

        .page-wrapper, .page-body, .container-xl, .card, .card-body, .f4-preview-stage {
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        body {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            color: #000000 !important;
        }
    }
</style>

<?= $this->endSection() ?>
