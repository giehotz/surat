<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- ========== HERO GREETING SECTION ========== -->
<div class="card border-0 shadow-sm rounded-4 mb-4 hero-greeting-card overflow-hidden">
    <div class="card-body p-3 p-md-4">
        <div class="row align-items-center g-3">
            <div class="col-12 col-md-auto">
                <div class="position-relative d-inline-block">
                    <?php
                    $fotoProfile = session('foto_profile');
                    $nama = session('nama_lengkap') ?? session('username') ?? 'Pengguna';
                    $inisial = strtoupper(substr($nama, 0, 1));
                    ?>
                    <?php if (!empty($fotoProfile) && file_exists(FCPATH . 'uploads/profiles/' . $fotoProfile)): ?>
                        <img class="avatar avatar-xl rounded-circle shadow-sm border border-2 border-primary object-cover hero-avatar" src="<?= base_url('uploads/profiles/' . $fotoProfile) ?>" alt="Foto Profil">
                    <?php else: ?>
                        <div class="avatar avatar-xl rounded-circle shadow-sm bg-primary-lt border border-2 border-primary d-flex align-items-center justify-content-center hero-avatar">
                            <span class="fw-bold text-primary fs-2"><?= esc($inisial) ?></span>
                        </div>
                    <?php endif; ?>
                    <span class="badge bg-green position-absolute bottom-0 end-0 p-1 rounded-circle status-pulse-dot" title="Online"></span>
                </div>
            </div>
            
            <div class="col-12 col-md">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <span class="badge bg-blue-lt px-2 py-1 rounded-pill small font-tabular">
                        <i class="ti ti-clock-filled me-1 text-primary"></i>
                        <span id="live-clock"><?= format_tanggal_indo(date('Y-m-d')) ?> | <?= date('H:i:s') ?></span>
                    </span>
                    <span class="badge bg-primary-lt text-primary px-2 py-1 rounded-pill small">
                        <i class="ti ti-shield-check me-1"></i><?= ucfirst(esc((string)(session('role') ?? 'User'))) ?>
                    </span>
                </div>
                
                <h2 class="page-title mb-1 fw-bold fs-2 d-flex align-items-center gap-2 flex-wrap">
                    <span><span id="live-greeting">Selamat Datang</span>, <?= esc($nama) ?>!</span>
                    <span class="hand-wave-icon" role="img" aria-label="Waving hand">👋</span>
                </h2>
                
                <p class="text-secondary small mb-0">
                    <i class="ti ti-building me-1"></i><?= esc($appSettings['sekolah_nama'] ?? 'Sistem Informasi Manajemen Surat & Kearsipan') ?>
                    <span class="mx-2 text-muted-lt d-none d-sm-inline">•</span>
                    <span class="d-none d-sm-inline"><i class="ti ti-calendar-event me-1"></i><?= date('l, d F Y') ?></span>
                </p>
            </div>

            <!-- Quick Action Shortcuts -->
            <div class="col-12 col-md-auto ms-auto d-print-none">
                <div class="d-flex gap-2 flex-wrap">
                    <?php if (in_array(session('role'), ['admin', 'operator'])): ?>
                        <a href="<?= base_url('surat-masuk/create') ?>" class="btn btn-outline-primary btn-sm rounded-pill shadow-sm hover-scale">
                            <i class="ti ti-mail-forward me-1"></i> Catat Surat Masuk
                        </a>
                        <a href="<?= base_url('surat-keluar/create') ?>" class="btn btn-primary btn-sm rounded-pill shadow-sm hover-scale">
                            <i class="ti ti-send me-1"></i> Buat Surat Keluar
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== STAT CARDS (COUNTUP & PHYSICS HOVER) ========== -->
<div class="row row-deck row-cards g-3 mb-4">
    <!-- Surat Keluar -->
    <div class="col-6 col-lg-3">
        <a href="<?= base_url('surat-keluar') ?>" class="card card-sm shadow-sm border rounded-3 text-decoration-none stat-card card-keluar">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3 stat-icon-badge bg-green-lt text-green">
                        <i class="ti ti-mail-opened fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-secondary small text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Surat Keluar</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="h1 mb-0 fw-bold font-tabular stat-number" data-countup="<?= (int)($total_surat_keluar ?? 0) ?>">0</span>
                            <span class="text-secondary small">dokumen</span>
                        </div>
                    </div>
                </div>
                <?php if (!empty($latest_nomor_surat_keluar) && $latest_nomor_surat_keluar !== '-'): ?>
                    <div class="mt-2 pt-2 border-top">
                        <div class="d-flex align-items-center small">
                            <span class="text-secondary me-1" style="font-size: 0.72rem;">Terakhir:</span>
                            <span class="badge bg-green-lt text-green text-truncate font-monospace" style="max-width: 140px; font-size: 0.7rem;" title="<?= esc($latest_nomor_surat_keluar) ?>">
                                <?= esc($latest_nomor_surat_keluar) ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-green-lt py-1 px-3 border-0 d-flex align-items-center justify-content-between small text-green">
                <span class="fw-medium">Buka Surat Keluar</span>
                <i class="ti ti-arrow-narrow-right stat-arrow-icon fs-3"></i>
            </div>
        </a>
    </div>

    <!-- Surat Masuk -->
    <div class="col-6 col-lg-3">
        <a href="<?= base_url('surat-masuk') ?>" class="card card-sm shadow-sm border rounded-3 text-decoration-none stat-card card-masuk">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3 stat-icon-badge bg-blue-lt text-blue">
                        <i class="ti ti-mail-forward fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-secondary small text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Surat Masuk</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="h1 mb-0 fw-bold font-tabular stat-number" data-countup="<?= (int)($total_surat_masuk ?? 0) ?>">0</span>
                            <span class="text-secondary small">dokumen</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-blue-lt py-1 px-3 border-0 d-flex align-items-center justify-content-between small text-blue">
                <span class="fw-medium">Buka Surat Masuk</span>
                <i class="ti ti-arrow-narrow-right stat-arrow-icon fs-3"></i>
            </div>
        </a>
    </div>

    <!-- Disposisi Pending -->
    <div class="col-6 col-lg-3">
        <a href="<?= base_url('disposisi') ?>" class="card card-sm shadow-sm border rounded-3 text-decoration-none stat-card card-disposisi">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3 stat-icon-badge bg-orange-lt text-orange">
                        <i class="ti ti-clock-pause fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-secondary small text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Disposisi Pending</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="h1 mb-0 fw-bold font-tabular stat-number text-orange" data-countup="<?= (int)($total_disposisi_pending ?? 0) ?>">0</span>
                            <span class="text-secondary small">instruksi</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-orange-lt py-1 px-3 border-0 d-flex align-items-center justify-content-between small text-orange">
                <span class="fw-medium">Tindak Lanjut</span>
                <i class="ti ti-arrow-narrow-right stat-arrow-icon fs-3"></i>
            </div>
        </a>
    </div>

    <!-- Total Pengguna -->
    <div class="col-6 col-lg-3">
        <div class="card card-sm shadow-sm border rounded-3 stat-card card-pengguna">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3 stat-icon-badge bg-purple-lt text-purple">
                        <i class="ti ti-users fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-secondary small text-uppercase fw-bold ls-1" style="font-size: 0.65rem;">Total Pengguna</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <span class="h1 mb-0 fw-bold font-tabular stat-number" data-countup="<?= (int)($total_users ?? 0) ?>">0</span>
                            <span class="text-secondary small">akun aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-purple-lt py-1 px-3 border-0 d-flex align-items-center justify-content-between small text-purple">
                <span class="fw-medium">Terdaftar di sistem</span>
                <i class="ti ti-shield-check fs-3"></i>
            </div>
        </div>
    </div>
</div>

<!-- ========== CHART + RECENT MAIL ROW ========== -->
<div class="row row-deck row-cards g-3 mb-4">
    <!-- Chart Tren Persuratan -->
    <div class="col-lg-8">
        <div class="card shadow-sm border rounded-3 overflow-hidden dashboard-panel">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom py-3">
                <h3 class="card-title d-flex align-items-center gap-2 mb-0 fw-bold">
                    <span class="avatar avatar-xs bg-primary-lt text-primary rounded-2">
                        <i class="ti ti-chart-bar fs-3"></i>
                    </span>
                    Tren Persuratan Tahun <?= date('Y') ?>
                </h3>
                <div class="d-flex gap-3 align-items-center small">
                    <span class="d-flex align-items-center gap-1">
                        <span class="chart-legend-indicator bg-primary"></span>
                        <span class="text-secondary">Surat Masuk</span>
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="chart-legend-indicator bg-success"></span>
                        <span class="text-secondary">Surat Keluar</span>
                    </span>
                </div>
            </div>
            <div class="card-body p-3" style="min-height: 330px;">
                <div id="chart-persuratan" class="w-100"></div>
            </div>
        </div>
    </div>

    <!-- Surat Masuk Terbaru -->
    <div class="col-lg-4">
        <div class="card shadow-sm border rounded-3 overflow-hidden dashboard-panel" style="max-height: 450px;">
            <div class="card-header border-bottom py-3 d-flex justify-content-between align-items-center">
                <h3 class="card-title d-flex align-items-center gap-2 mb-0 fw-bold">
                    <span class="avatar avatar-xs bg-blue-lt text-blue rounded-2">
                        <i class="ti ti-inbox fs-3"></i>
                    </span>
                    Surat Masuk Terbaru
                </h3>
                <span class="badge bg-blue-lt rounded-pill"><?= count($latest_surat_masuk_by_pengirim ?? []) ?></span>
            </div>
            
            <div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
                <?php if (empty($latest_surat_masuk_by_pengirim)) : ?>
                    <div class="empty py-5">
                        <div class="empty-icon text-muted mb-3">
                            <i class="ti ti-inbox-off" style="font-size: 2.5rem; opacity: 0.4;"></i>
                        </div>
                        <p class="empty-title h4">Belum ada surat masuk</p>
                        <p class="empty-subtitle text-secondary">Data surat yang baru diagendakan akan tampil di sini.</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($latest_surat_masuk_by_pengirim as $index => $surat) : ?>
                            <?php 
                            $pengirim = (string)esc($surat['pengirim']);
                            $avatarInitial = strtoupper(substr($pengirim, 0, 1));
                            ?>
                            <div class="list-group-item list-group-item-action px-3 py-3 border-bottom animate-feed-item" style="animation-delay: <?= min($index * 0.05, 0.3) ?>s;">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="avatar avatar-sm rounded-circle bg-blue-lt text-blue fw-bold shadow-sm flex-shrink-0">
                                        <?= $avatarInitial ?>
                                    </span>
                                    <div class="min-width-0 flex-grow-1">
                                        <div class="fw-bold text-truncate text-body" style="font-size: 0.85rem;"><?= $pengirim ?></div>
                                        <div class="text-secondary small text-truncate mt-1" title="<?= esc($surat['perihal']) ?>">
                                            <i class="ti ti-file-description me-1"></i><?= esc($surat['perihal']) ?>
                                        </div>
                                        <div class="mt-1 text-secondary font-tabular" style="font-size: 0.72rem;">
                                            <i class="ti ti-calendar-event me-1"></i><?= format_tanggal_indo($surat['tanggal_terima']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($latest_surat_masuk_by_pengirim)) : ?>
                <div class="card-footer text-center py-2 border-top">
                    <a href="<?= base_url('surat-masuk') ?>" class="small text-primary text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 hover-arrow-right">
                        <span>Lihat semua surat masuk</span>
                        <i class="ti ti-arrow-right icon-slide"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ========== AKTIVITAS LOG TERBARU (Hanya Admin & Pimpinan) ========== -->
<?php if (session('role') !== 'operator'): ?>
<div class="row row-cards g-3">
    <div class="col-12">
        <div class="card shadow-sm border rounded-3 overflow-hidden dashboard-panel" style="max-height: 520px;">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom py-3">
                <h3 class="card-title d-flex align-items-center gap-2 mb-0 fw-bold">
                    <span class="avatar avatar-xs bg-teal-lt text-teal rounded-2">
                        <i class="ti ti-activity fs-3"></i>
                    </span>
                    Aktivitas Pengguna Terbaru
                </h3>
                
                <div class="d-flex align-items-center gap-2">
                    <?php if (session('role') === 'admin' && !empty($logs)) : ?>
                        <form id="form-delete-all-logs" action="<?= base_url('dashboard/delete-all-logs') ?>" method="post" class="m-0">
                            <?= csrf_field() ?>
                            <button type="button" class="btn btn-outline-danger btn-sm rounded-pill d-inline-flex align-items-center gap-1" id="btn-delete-all-logs">
                                <i class="ti ti-trash"></i> 
                                <span class="d-none d-sm-inline">Bersihkan Semua</span>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
                <?php if (empty($logs)) : ?>
                    <div class="empty py-5">
                        <div class="empty-icon text-muted mb-3">
                            <i class="ti ti-history-off" style="font-size: 2.5rem; opacity: 0.4;"></i>
                        </div>
                        <p class="empty-title h4">Belum ada aktivitas</p>
                        <p class="empty-subtitle text-secondary">Log aktivitas pengguna akan dicatat secara otomatis di sini.</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($logs as $idx => $log) : ?>
                            <?php 
                            $uName = esc($log['username'] ?? 'User');
                            $uInitial = strtoupper(substr($uName, 0, 1));
                            ?>
                            <div class="list-group-item px-3 py-3 border-bottom animate-feed-item" style="animation-delay: <?= min($idx * 0.04, 0.3) ?>s;">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- User Avatar -->
                                    <div class="flex-shrink-0">
                                        <?php if (!empty($log['foto_profile']) && file_exists(FCPATH . 'uploads/profiles/' . $log['foto_profile'])): ?>
                                            <span class="avatar avatar-sm rounded-circle shadow-sm" style="background-image: url('<?= base_url('uploads/profiles/' . $log['foto_profile']) ?>')"></span>
                                        <?php else: ?>
                                            <span class="avatar avatar-sm rounded-circle shadow-sm bg-primary-lt text-primary fw-bold">
                                                <?= $uInitial ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <strong class="text-body" style="font-size: 0.85rem;"><?= $uName ?></strong>
                                            <?php 
                                                $aksi = strtolower($log['aksi'] ?? '');
                                                $aksiBadge = 'bg-secondary-lt text-secondary';
                                                $aksiIcon = 'ti-pencil';
                                                if (strpos($aksi, 'tambah') !== false || strpos($aksi, 'buat') !== false || strpos($aksi, 'create') !== false) { 
                                                    $aksiBadge = 'bg-green-lt text-green'; $aksiIcon = 'ti-plus';
                                                } elseif (strpos($aksi, 'edit') !== false || strpos($aksi, 'update') !== false || strpos($aksi, 'ubah') !== false) { 
                                                    $aksiBadge = 'bg-blue-lt text-blue'; $aksiIcon = 'ti-edit';
                                                } elseif (strpos($aksi, 'hapus') !== false || strpos($aksi, 'delete') !== false) { 
                                                    $aksiBadge = 'bg-danger-lt text-danger'; $aksiIcon = 'ti-trash';
                                                } elseif (strpos($aksi, 'approve') !== false || strpos($aksi, 'setuju') !== false) { 
                                                    $aksiBadge = 'bg-yellow-lt text-yellow'; $aksiIcon = 'ti-check';
                                                }
                                            ?>
                                            <span class="badge <?= $aksiBadge ?> d-inline-flex align-items-center gap-1 rounded-pill" style="font-size: 0.7rem;">
                                                <i class="ti <?= $aksiIcon ?>"></i>
                                                <?= esc($log['aksi']) ?>
                                            </span>
                                        </div>
                                        <div class="text-secondary small mt-1">
                                            Surat <?= esc($log['tipe_surat']) ?>
                                            <span class="badge bg-secondary-lt text-secondary ms-1 font-monospace" style="font-size: 0.65rem;">#<?= esc($log['surat_id']) ?></span>
                                        </div>
                                    </div>

                                    <!-- Time & Delete Action -->
                                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1">
                                        <span class="text-secondary font-tabular" style="font-size: 0.72rem; white-space: nowrap;">
                                            <i class="ti ti-clock me-1"></i><?= format_tanggal_waktu_indo($log['created_at']) ?>
                                        </span>
                                        <?php if (session('role') === 'admin'): ?>
                                            <form action="<?= base_url('dashboard/delete-log/' . $log['id']) ?>" method="post" class="m-0 form-delete-single-log">
                                                <?= csrf_field() ?>
                                                <button type="button" class="btn btn-icon btn-sm btn-ghost-danger p-0 border-0 btn-delete-log" data-bs-toggle="tooltip" title="Hapus aktivitas ini">
                                                    <i class="ti ti-x fs-3"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    /* ========================================================
       IMPECCABLE MOTION SYSTEM & MODERN REDESIGN STYLING
       ======================================================== */

    .font-tabular {
        font-variant-numeric: tabular-nums;
    }
    .min-width-0 {
        min-width: 0;
    }
    .ls-1 {
        letter-spacing: 0.05em;
    }

    /* 1. Hero Greeting Card */
    .hero-greeting-card {
        background: linear-gradient(135deg, rgba(var(--tblr-primary-rgb), 0.04) 0%, rgba(var(--tblr-primary-rgb), 0.01) 100%), var(--tblr-card-bg);
        border: 1px solid var(--tblr-border-color) !important;
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
    }

    .hero-avatar {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .hero-avatar:hover {
        transform: scale(1.05);
    }

    /* Status pulse dot */
    .status-pulse-dot {
        width: 12px;
        height: 12px;
        box-shadow: 0 0 0 2px var(--tblr-card-bg);
        animation: pulseAnimation 2s infinite cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes pulseAnimation {
        0% { box-shadow: 0 0 0 0 rgba(47, 179, 68, 0.7); }
        70% { box-shadow: 0 0 0 7px rgba(47, 179, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(47, 179, 68, 0); }
    }

    /* Waving hand micro-interaction */
    .hand-wave-icon {
        display: inline-block;
        transform-origin: 70% 70%;
        animation: waveMotion 2.2s infinite ease-in-out;
        cursor: default;
    }
    .hand-wave-icon:hover {
        animation: waveMotion 0.8s infinite ease-in-out;
    }
    @keyframes waveMotion {
        0% { transform: rotate(0deg); }
        10% { transform: rotate(14deg); }
        20% { transform: rotate(-8deg); }
        30% { transform: rotate(14deg); }
        40% { transform: rotate(-4deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(0deg); }
        100% { transform: rotate(0deg); }
    }

    /* 2. Stat Cards - Physics Hover & Micro-interactions */
    .stat-card {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    .stat-card:active {
        transform: translateY(-1px);
    }

    .stat-icon-badge {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .stat-card:hover .stat-icon-badge {
        transform: scale(1.1) rotate(-4deg);
    }

    .stat-arrow-icon {
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .stat-card:hover .stat-arrow-icon {
        transform: translateX(4px);
    }

    /* 3. Panel & List Items */
    .dashboard-panel {
        transition: box-shadow 0.25s ease;
    }
    .dashboard-panel:hover {
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.06) !important;
    }

    .chart-legend-indicator {
        width: 10px;
        height: 10px;
        border-radius: 3px;
        display: inline-block;
    }

    .hover-arrow-right {
        transition: gap 0.2s ease;
    }
    .hover-arrow-right .icon-slide {
        transition: transform 0.2s ease;
    }
    .hover-arrow-right:hover .icon-slide {
        transform: translateX(4px);
    }

    /* Staggered entry animation for feed items */
    .animate-feed-item {
        animation: feedItemEnter 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        transition: background-color 0.15s ease, border-left-color 0.15s ease;
        border-left: 3px solid transparent;
    }
    .animate-feed-item:hover {
        background-color: rgba(var(--tblr-primary-rgb), 0.04);
        border-left-color: var(--tblr-primary);
    }
    @keyframes feedItemEnter {
        0% {
            opacity: 0;
            transform: translateY(6px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: translateY(-1px);
    }

    /* Reduced Motion Accessibility */
    @media (prefers-reduced-motion: reduce) {
        .hand-wave-icon,
        .status-pulse-dot,
        .animate-feed-item,
        .stat-card,
        .hero-avatar,
        .hover-scale {
            animation: none !important;
            transition: none !important;
            transform: none !important;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ==========================================
        // 1. REAL-TIME CLOCK & GREETING
        // ==========================================
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const day = String(now.getDate()).padStart(2, '0');
            const monthName = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const hour = now.getHours();
            let greeting = 'Malam';
            if (hour >= 5 && hour < 11) greeting = 'Pagi';
            else if (hour >= 11 && hour < 15) greeting = 'Siang';
            else if (hour >= 15 && hour < 18) greeting = 'Sore';
            
            const greetingEl = document.getElementById('live-greeting');
            const clockEl = document.getElementById('live-clock');
            if (greetingEl) greetingEl.textContent = `Selamat ${greeting}`;
            if (clockEl) clockEl.textContent = `${dayName}, ${day} ${monthName} ${year} | ${hours}:${minutes}:${seconds}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ==========================================
        // 2. COUNTUP CHOREOGRAPHY FOR STAT NUMBERS
        // ==========================================
        function animateCountUp(el, target, duration = 1200) {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                el.textContent = target.toLocaleString('id-ID');
                return;
            }

            let start = 0;
            const startTime = performance.now();

            function easeOutExpo(t) {
                return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
            }

            function updateCounter(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const currentVal = Math.floor(easeOutExpo(progress) * (target - start) + start);

                el.textContent = currentVal.toLocaleString('id-ID');

                if (progress < 1) {
                    requestAnimationFrame(updateCounter);
                } else {
                    el.textContent = target.toLocaleString('id-ID');
                }
            }

            requestAnimationFrame(updateCounter);
        }

        document.querySelectorAll('.stat-number[data-countup]').forEach(function(counterEl) {
            const target = parseInt(counterEl.getAttribute('data-countup'), 10) || 0;
            animateCountUp(counterEl, target, 1000);
        });

        // ==========================================
        // 3. APEXCHARTS DYNAMIC THEME ENGINE
        // ==========================================
        var chartEl = document.getElementById('chart-persuratan');
        if (chartEl && typeof ApexCharts !== 'undefined') {
            function isDarkMode() {
                return document.documentElement.getAttribute('data-bs-theme') === 'dark';
            }

            var dataMasuk = <?= json_encode(array_values($chart_data['masuk'] ?? [])) ?>;
            var dataKeluar = <?= json_encode(array_values($chart_data['keluar'] ?? [])) ?>;

            function getChartOptions(darkMode) {
                return {
                    chart: {
                        type: "bar",
                        fontFamily: 'inherit',
                        height: 310,
                        parentHeightOffset: 0,
                        toolbar: { show: false },
                        animations: { 
                            enabled: true,
                            easing: 'easeinout',
                            speed: 800,
                            animateGradually: { enabled: true, delay: 90 },
                            dynamicAnimation: { enabled: true, speed: 350 }
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%',
                            borderRadius: 5,
                            borderRadiusApplication: 'end',
                            dataLabels: { position: 'top' }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) { return val > 0 ? val : ''; },
                        offsetY: -18,
                        style: {
                            fontSize: '11px',
                            fontWeight: 600,
                            colors: [darkMode ? '#a0aec0' : '#4a5568']
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: darkMode ? 'dark' : 'light',
                            type: 'vertical',
                            shadeIntensity: 0.25,
                            opacityFrom: 0.95,
                            opacityTo: 0.8,
                            stops: [0, 100]
                        }
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    series: [{
                        name: "Surat Masuk",
                        data: dataMasuk
                    }, {
                        name: "Surat Keluar",
                        data: dataKeluar
                    }],
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            style: {
                                colors: darkMode ? '#869ab8' : '#6c7a91',
                                fontSize: '11px',
                                fontWeight: 500
                            }
                        }
                    },
                    yaxis: {
                        min: 0,
                        tickAmount: 4,
                        labels: {
                            style: {
                                colors: darkMode ? '#869ab8' : '#6c7a91',
                                fontSize: '11px'
                            },
                            formatter: function(val) { return Number.isInteger(val) ? val : ''; }
                        }
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        theme: darkMode ? 'dark' : 'light',
                        y: {
                            formatter: function(val) { return val + ' surat'; }
                        },
                        custom: function({ series, seriesIndex, dataPointIndex, w }) {
                            var masuk = series[0][dataPointIndex];
                            var keluar = series[1][dataPointIndex];
                            var bulan = w.globals.labels[dataPointIndex];
                            var total = masuk + keluar;
                            var selisih = masuk - keluar;
                            var selisihColor = selisih >= 0 ? '#2fb344' : '#d63939';
                            var selisihIcon = selisih >= 0 ? '▲' : '▼';
                            
                            return '<div style="padding: 10px 14px; font-size: 12px; line-height: 1.6;">' +
                                '<div style="font-weight: 700; margin-bottom: 6px; font-size: 13px;">' + bulan + ' <?= date("Y") ?></div>' +
                                '<div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 3px; background: #206bc4;"></span> Masuk: <strong>' + masuk + '</strong></div>' +
                                '<div style="display: flex; align-items: center; gap: 8px;"><span style="width: 10px; height: 10px; border-radius: 3px; background: #2fb344;"></span> Keluar: <strong>' + keluar + '</strong></div>' +
                                '<div style="border-top: 1px solid ' + (darkMode ? '#334155' : '#e2e8f0') + '; margin-top: 6px; padding-top: 6px; display: flex; justify-content: space-between; gap: 12px;">' +
                                '<span>Total: <strong>' + total + '</strong></span>' +
                                '<span style="color: ' + selisihColor + '; font-weight: 600;">' + selisihIcon + ' ' + Math.abs(selisih) + '</span>' +
                                '</div></div>';
                        }
                    },
                    colors: ["#206bc4", "#2fb344"],
                    legend: { show: false },
                    grid: {
                        strokeDashArray: 4,
                        borderColor: darkMode ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)',
                        padding: { top: -5, right: 0, bottom: -5, left: 0 }
                    }
                };
            }

            var currentChart = new ApexCharts(chartEl, getChartOptions(isDarkMode()));
            currentChart.render();

            // Observe theme changes on documentElement so ApexCharts re-themes smoothly
            var themeObserver = new MutationObserver(function() {
                currentChart.updateOptions(getChartOptions(isDarkMode()));
            });
            themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['data-bs-theme']
            });
        }

        // ==========================================
        // 4. UNIFIED SWEETALERT2 CONFIRMATION MODALS
        // ==========================================
        // Delete all logs confirmation
        const btnDeleteAllLogs = document.getElementById('btn-delete-all-logs');
        if (btnDeleteAllLogs) {
            btnDeleteAllLogs.addEventListener('click', function(e) {
                e.preventDefault();
                const form = document.getElementById('form-delete-all-logs');
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Bersihkan Seluruh Log?',
                        text: 'Semua rekaman aktivitas pengguna akan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d63939',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Bersihkan!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed && form) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm('Apakah Anda yakin ingin menghapus SELURUH log aktivitas? Tindakan ini tidak dapat dibatalkan.')) {
                        form.submit();
                    }
                }
            });
        }

        // Single log deletion confirmation
        $(document).on('click', '.btn-delete-log', function(e) {
            e.preventDefault();
            const btn = $(this);
            const form = btn.closest('form');

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Hapus Log Aktivitas?',
                    text: 'Catatan aktivitas ini akan dihapus dari riwayat.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d63939',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm('Hapus log aktivitas ini?')) {
                    form.submit();
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>