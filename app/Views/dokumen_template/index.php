<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- TAB NAVIGASI MODUL SURAT -->
<div class="row mb-3 align-items-center">
    <div class="col-md-8">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link fw-bold" href="<?= base_url('surat-resmi') ?>">
                    <i class="ti ti-file-certificate me-1"></i> Pembuat Surat Resmi (HTML / Cetak)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active fw-bold" href="<?= base_url('dokumen-template') ?>">
                    <i class="ti ti-file-word me-1"></i> Template Word (.docx)
                </a>
            </li>
        </ul>
    </div>
    <div class="col-md-4 text-md-end mt-2 mt-md-0">
        <a href="<?= base_url('surat-keluar') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Kembali ke Surat Keluar
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12 d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
        <div>
            <h2 class="page-title mb-1">
                <i class="ti ti-file-word text-blue me-2"></i> Generator Dokumen Word (.docx)
            </h2>
            <div class="text-muted">Pilih template dokumen kedinasan resmi, isi form terintegrasi, dan unduh dokumen siap cetak.</div>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= base_url('surat-resmi') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Surat Resmi
            </a>
            <?php if (in_array(session('role'), ['admin', 'operator'])): ?>
            <a href="<?= base_url('dokumen-template/upload') ?>" class="btn btn-primary">
                <i class="ti ti-upload me-1"></i> Upload Template Kustom
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible mb-3" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-check icon me-2"></i></div>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible mb-3" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-alert-triangle icon me-2"></i></div>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<!-- Nav Tabs: Kategori Template -->
<div class="card mb-4 shadow-sm border-0">
    <div class="card-header bg-transparent border-bottom">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
            <li class="nav-item">
                <a href="#tab-semua" class="nav-link active" data-bs-toggle="tab">
                    <i class="ti ti-layout-grid me-1"></i> Semua Template (<?= count($templates) ?>)
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-builtin" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-star me-1 text-yellow"></i> Template Utama / Bawaan
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-custom" class="nav-link" data-bs-toggle="tab">
                    <i class="ti ti-folder-plus me-1 text-cyan"></i> Template Kustom
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content">
            <!-- TAB SEMUA -->
            <div class="tab-pane active show" id="tab-semua">
                <div class="row g-3">
                    <?php if (empty($templates)): ?>
                        <div class="col-12 text-center py-5">
                            <i class="ti ti-file-search text-muted" style="font-size: 48px;"></i>
                            <h4 class="mt-3">Belum ada template yang terdaftar</h4>
                            <p class="text-muted">Silakan upload template .docx baru untuk memulai.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($templates as $t): ?>
                            <?php
                            $icon = match($t['kode']) {
                                'surat_tugas'    => 'ti-briefcase text-blue',
                                'spd'            => 'ti-car text-green',
                                'surat_kuasa'    => 'ti-certificate text-purple',
                                'sk'             => 'ti-gavel text-danger',
                                'gangguan_absen' => 'ti-clock-exclamation text-orange',
                                default          => 'ti-file-text text-secondary'
                            };
                            ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 card-stacked shadow-sm border">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <span class="avatar avatar-md rounded bg-blue-lt me-3">
                                                    <i class="ti <?= $icon ?>" style="font-size: 24px;"></i>
                                                </span>
                                                <div>
                                                    <h3 class="card-title mb-0"><?= esc($t['nama']) ?></h3>
                                                    <small class="text-muted"><?= esc($t['kode']) ?></small>
                                                </div>
                                            </div>
                                            <?php if ($t['kategori'] === 'builtin'): ?>
                                                <span class="badge bg-yellow-lt">Bawaan</span>
                                            <?php else: ?>
                                                <span class="badge bg-azure-lt">Kustom</span>
                                            <?php endif; ?>
                                        </div>

                                        <p class="text-secondary small mt-2 mb-3 flex-grow-1">
                                            <?= esc($t['deskripsi'] ?? 'Template dokumen resmi format Microsoft Word.') ?>
                                        </p>

                                        <?php if ($t['is_has_repeater']): ?>
                                            <div class="mb-3">
                                                <span class="badge bg-teal-lt small">
                                                    <i class="ti ti-users me-1"></i> Multi-Pegawai (Tabel Dinamis)
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <div class="d-flex gap-2 pt-2 border-top">
                                            <a href="<?= base_url('dokumen-template/buat/' . $t['kode']) ?>" class="btn btn-primary w-100 shadow-sm">
                                                <i class="ti ti-edit me-1"></i> Buat Dokumen
                                            </a>
                                            <div class="dropdown">
                                                <button class="btn btn-icon btn-outline-secondary" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <a class="dropdown-item" href="<?= base_url('dokumen-template/download-template/' . $t['id']) ?>">
                                                        <i class="ti ti-download me-2"></i> Unduh File Mentah (.docx)
                                                    </a>
                                                    <?php if ($t['kategori'] === 'custom' && in_array(session('role'), ['admin', 'operator'])): ?>
                                                        <a class="dropdown-item" href="<?= base_url('dokumen-template/configure-fields/' . $t['id']) ?>">
                                                            <i class="ti ti-settings me-2"></i> Konfigurasi Field
                                                        </a>
                                                        <div class="dropdown-divider"></div>
                                                        <form action="<?= base_url('dokumen-template/delete/' . $t['id']) ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus template kustom ini?');">
                                                            <?= csrf_field() ?>
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="ti ti-trash me-2"></i> Hapus Template
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- TAB BUILT-IN -->
            <div class="tab-pane" id="tab-builtin">
                <div class="row g-3">
                    <?php foreach ($templates as $t): if ($t['kategori'] !== 'builtin') continue; ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title"><?= esc($t['nama']) ?></h3>
                                    <p class="text-secondary small flex-grow-1"><?= esc($t['deskripsi']) ?></p>
                                    <a href="<?= base_url('dokumen-template/buat/' . $t['kode']) ?>" class="btn btn-primary w-100 mt-2">
                                        <i class="ti ti-edit me-1"></i> Buat Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- TAB CUSTOM -->
            <div class="tab-pane" id="tab-custom">
                <div class="row g-3">
                    <?php 
                    $hasCustom = false;
                    foreach ($templates as $t): 
                        if ($t['kategori'] !== 'custom') continue; 
                        $hasCustom = true;
                    ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border">
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title"><?= esc($t['nama']) ?></h3>
                                    <p class="text-secondary small flex-grow-1"><?= esc($t['deskripsi'] ?? 'Template kustom diupload oleh admin.') ?></p>
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="<?= base_url('dokumen-template/buat/' . $t['kode']) ?>" class="btn btn-primary flex-grow-1">
                                            <i class="ti ti-edit me-1"></i> Buat Dokumen
                                        </a>
                                        <a href="<?= base_url('dokumen-template/configure-fields/' . $t['id']) ?>" class="btn btn-outline-secondary btn-icon" title="Setting Field">
                                            <i class="ti ti-settings"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php if (!$hasCustom): ?>
                        <div class="col-12 text-center py-5">
                            <i class="ti ti-upload text-muted" style="font-size: 40px;"></i>
                            <h4 class="mt-2">Belum ada template kustom</h4>
                            <p class="text-muted">Admin dapat mengunggah file .docx baru kapan saja.</p>
                            <a href="<?= base_url('dokumen-template/upload') ?>" class="btn btn-primary btn-sm mt-2">
                                <i class="ti ti-plus me-1"></i> Upload Sekarang
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
