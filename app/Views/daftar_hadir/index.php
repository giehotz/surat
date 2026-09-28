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
                <i class="ti ti-clipboard-list me-2 text-teal"></i> Daftar Hadir & Notulen Rapat
            </h2>
            <div class="text-muted mt-1">Kelola presensi guru, notulen rapat siap cetak A4, dan dokumentasi arsip digital.</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="d-flex gap-2">
                <button type="submit" form="form-simpan-notulen" class="btn btn-success shadow-sm">
                    <i class="ti ti-device-floppy icon me-1"></i> Simpan Notulen ke Arsip
                </button>
                <div class="btn-group shadow-sm">
                    <button type="button" class="btn btn-primary" onclick="printSemua()">
                        <i class="ti ti-printer icon me-1"></i> Cetak Lengkap
                    </button>
                    <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Pilihan Cetak</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="printHanyaDaftarHadir()">
                                <i class="ti ti-clipboard-check me-2 text-teal"></i> Cetak Hanya Daftar Hadir
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="printHanyaNotulen()">
                                <i class="ti ti-notes me-2 text-primary"></i> Cetak Hanya Lembar Notulen
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi Flash Message -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-important alert-success alert-dismissible d-print-none mb-3" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-check icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-important alert-danger alert-dismissible d-print-none mb-3" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-alert-triangle icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-important alert-danger alert-dismissible d-print-none mb-3" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-alert-triangle icon alert-icon"></i></div>
            <div>
                <ul class="mb-0 ps-3">
                    <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<!-- Nav Tabs: Buat Presensi/Notulen vs Riwayat Arsip (Theme-Adaptive) -->
<div class="card shadow-sm border rounded-3 mb-4 d-print-none">
    <div class="card-header p-0 border-bottom">
        <ul class="nav nav-tabs card-header-tabs nav-fill w-100 flex-column flex-md-row m-0" id="daftarHadirTabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <a href="#tab-buat" id="nav-tab-buat" class="nav-link py-3 fw-semibold text-center border-0 border-bottom <?= ($active_tab ?? 'buat') === 'buat' ? 'active' : '' ?>" data-bs-toggle="tab" role="tab">
                    <i class="ti ti-edit icon me-2 fs-3 text-teal"></i> Buat & Cetak Presensi / Notulen
                </a>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <a href="#tab-riwayat" id="nav-tab-riwayat" class="nav-link py-3 fw-semibold text-center border-0 border-bottom <?= ($active_tab ?? 'buat') === 'riwayat' ? 'active' : '' ?>" data-bs-toggle="tab" role="tab">
                    <i class="ti ti-archive icon me-2 fs-3 text-primary"></i> Daftar Arsip & Riwayat Notulen
                    <?php if (!empty($riwayat_notulen)): ?>
                        <span class="badge bg-blue-lt ms-2 rounded-pill"><?= count($riwayat_notulen) ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content">
    
    <!-- ========================================== -->
    <!-- TAB 1: FORM BUAT, EDIT & CETAK DOKUMEN     -->
    <!-- ========================================== -->
    <div class="tab-pane <?= ($active_tab ?? 'buat') === 'buat' ? 'active show' : '' ?>" id="tab-buat" role="tabpanel">
        
        <!-- Panel Konfigurasi Agenda & Form Input -->
        <?= $this->include('daftar_hadir/partials/form_agenda') ?>

        <!-- Container Pratinjau Dokumen Siap Cetak (A4) -->
        <div class="card shadow-sm border rounded-3 mb-5">
            <div class="card-header py-2 d-print-none d-flex justify-content-between align-items-center">
                <span class="text-secondary small fw-medium">
                    <i class="ti ti-file-text me-1"></i> Pratinjau Dokumen Siap Cetak (Ukuran Kertas A4)
                </span>
                <div class="btn-list">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printHanyaDaftarHadir()">
                        <i class="ti ti-clipboard-check me-1"></i> Hadir Saja
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="printHanyaNotulen()">
                        <i class="ti ti-notes me-1"></i> Notulen Saja
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="printSemua()">
                        <i class="ti ti-printer me-1"></i> Cetak Semua
                    </button>
                </div>
            </div>
            
            <div class="card-body p-2 p-md-4 d-flex flex-column align-items-center gap-4" style="background-color: var(--tblr-bg-surface-secondary, rgba(var(--tblr-body-color-rgb), 0.04));">
                
                <!-- LEMBAR 1: DAFTAR HADIR (A4) -->
                <?= $this->include('daftar_hadir/partials/lembar_daftar_hadir') ?>

                <!-- CONTAINER LEMBAR NOTULEN RAPAT (A4 DINAMIS VIA JS) -->
                <div id="container-lembar-notulen" class="w-100 d-flex flex-column align-items-center gap-4">
                    <!-- Lembar notulen di-generate secara dinamis oleh JavaScript -->
                </div>

            </div>
        </div>

    </div>

    <!-- ========================================== -->
    <!-- TAB 2: DAFTAR ARSIP & RIWAYAT NOTULEN      -->
    <!-- ========================================== -->
    <div class="tab-pane <?= ($active_tab ?? 'buat') === 'riwayat' ? 'active show' : '' ?>" id="tab-riwayat" role="tabpanel">
        <?= $this->include('daftar_hadir/partials/tabel_riwayat') ?>
    </div>

</div>

<!-- Modal Pratinjau Naskah Notulen -->
<?= $this->include('daftar_hadir/partials/modal_naskah') ?>

<!-- Custom CSS Styling Khusus Cetak & Pratinjau Kertas A4 -->
<?= $this->include('daftar_hadir/partials/styles') ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Skrip Interaktivitas & TinyMCE -->
<?= $this->include('daftar_hadir/partials/scripts') ?>
<?= $this->endSection() ?>
