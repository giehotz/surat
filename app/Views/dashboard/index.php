<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- ========== HERO GREETING ========== -->
<div class="mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar avatar-lg rounded-circle shadow-sm bg-primary-lt">
                    <i class="ti ti-layout-dashboard fs-1 text-primary"></i>
                </div>
                <div>
                    <h2 class="page-title mb-1" style="font-size: 1.5rem;">
                        Selamat <?php
                            $hour = (int)date('H');
                            if ($hour >= 5 && $hour < 12) echo 'Pagi';
                            elseif ($hour >= 12 && $hour < 15) echo 'Siang';
                            elseif ($hour >= 15 && $hour < 18) echo 'Sore';
                            else echo 'Malam';
                        ?>, <?= esc(session('nama_lengkap') ?? session('username') ?? 'Pengguna') ?>! 👋
                    </h2>
                    <p class="text-muted mb-0">
                        <i class="ti ti-calendar-event me-1"></i><?= date('l, d F Y') ?>
                        <span class="mx-2">·</span>
                        <span class="badge bg-primary-lt text-primary"><?= ucfirst(esc((string)(session('role') ?? 'User'))) ?></span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== STAT CARDS ========== -->
<div class="row row-deck row-cards mb-4">
    <!-- Surat Keluar -->
    <div class="col-6 col-lg-3">
        <a href="<?= base_url('surat-keluar') ?>" class="card card-sm shadow-sm border-0 text-decoration-none card-link-hover" style="transition: all 0.2s ease;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3" style="background: linear-gradient(135deg, #2fb344 0%, #51cf66 100%);">
                        <i class="ti ti-mail-opened text-white fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-muted small text-uppercase fw-bold" style="letter-spacing: 0.05em; font-size: 0.65rem;">Surat Keluar</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <h3 class="mb-0 fw-bold" style="font-size: 1.6rem;"><?= esc($total_surat_keluar) ?></h3>
                            <span class="text-muted small">dokumen</span>
                        </div>
                    </div>
                </div>
                <?php if (!empty($latest_nomor_surat_keluar) && $latest_nomor_surat_keluar !== '-'): ?>
                    <div class="mt-2 pt-2 border-top">
                        <div class="d-flex align-items-center small">
                            <span class="text-muted me-1">Terakhir:</span>
                            <span class="badge bg-success-lt text-success text-truncate" style="max-width: 150px;" title="<?= esc($latest_nomor_surat_keluar) ?>">
                                <?= esc($latest_nomor_surat_keluar) ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-success-lt py-1 border-0">
                <div class="d-flex align-items-center justify-content-center small text-success">
                    <span>Lihat Detail</span>
                    <i class="ti ti-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                </div>
            </div>
        </a>
    </div>
 <!-- Surat Masuk -->
    <div class="col-6 col-lg-3">
        <a href="<?= base_url('surat-masuk') ?>" class="card card-sm shadow-sm border-0 text-decoration-none card-link-hover" style="transition: all 0.2s ease;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3" style="background: linear-gradient(135deg, #206bc4 0%, #4299e1 100%);">
                        <i class="ti ti-mail-forward text-white fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-muted small text-uppercase fw-bold" style="letter-spacing: 0.05em; font-size: 0.65rem;">Surat Masuk</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <h3 class="mb-0 fw-bold" style="font-size: 1.6rem;"><?= esc($total_surat_masuk) ?></h3>
                            <span class="text-muted small">dokumen</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-primary-lt py-1 border-0">
                <div class="d-flex align-items-center justify-content-center small text-primary">
                    <span>Lihat Detail</span>
                    <i class="ti ti-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                </div>
            </div>
        </a>
    </div>
    <!-- Disposisi Pending -->
    <div class="col-6 col-lg-3">
        <a href="<?= base_url('disposisi') ?>" class="card card-sm shadow-sm border-0 text-decoration-none card-link-hover" style="transition: all 0.2s ease;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3" style="background: linear-gradient(135deg, #f76707 0%, #fd7e14 100%);">
                        <i class="ti ti-clock-pause text-white fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-muted small text-uppercase fw-bold" style="letter-spacing: 0.05em; font-size: 0.65rem;">Disposisi Pending</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <h3 class="mb-0 fw-bold" style="font-size: 1.6rem;"><?= esc($total_disposisi_pending) ?></h3>
                            <span class="text-muted small">instruksi</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-warning-lt py-1 border-0">
                <div class="d-flex align-items-center justify-content-center small text-warning">
                    <span>Lihat Detail</span>
                    <i class="ti ti-chevron-right ms-1" style="font-size: 0.75rem;"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Pengguna -->
    <div class="col-6 col-lg-3">
        <div class="card card-sm shadow-sm border-0" style="transition: all 0.2s ease;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md rounded-3 shadow-sm me-3" style="background: linear-gradient(135deg, #ae3ec9 0%, #cc5de8 100%);">
                        <i class="ti ti-users text-white fs-2"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="text-muted small text-uppercase fw-bold" style="letter-spacing: 0.05em; font-size: 0.65rem;">Total Pengguna</div>
                        <div class="d-flex align-items-baseline gap-1">
                            <h3 class="mb-0 fw-bold" style="font-size: 1.6rem;"><?= esc($total_users) ?></h3>
                            <span class="text-muted small">akun aktif</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-purple-lt py-1 border-0">
                <div class="d-flex align-items-center justify-content-center small text-purple">
                    <i class="ti ti-shield-check me-1"></i>
                    <span>Terdaftar di sistem</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== CHART + RECENT MAIL ========== -->
<div class="row row-deck row-cards mb-4">
    <!-- Chart Tren Persuratan -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h3 class="card-title d-flex align-items-center gap-2 mb-0">
                    <i class="ti ti-chart-area text-primary"></i>
                    Tren Persuratan Tahun <?= date('Y') ?>
                </h3>
                <div class="d-flex gap-3 align-items-center small">
                    <span class="d-flex align-items-center gap-1">
                        <span class="legend-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #206bc4; display: inline-block;"></span>
                        Masuk
                    </span>
                    <span class="d-flex align-items-center gap-1">
                        <span class="legend-dot" style="width: 8px; height: 8px; border-radius: 50%; background: #2fb344; display: inline-block;"></span>
                        Keluar
                    </span>
                </div>
            </div>
            <div class="card-body" style="min-height: 320px;">
                <div id="chart-persuratan"></div>
            </div>
        </div>
    </div>

    <!-- Surat Masuk Terbaru -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0" style="max-height: 435px;">
            <div class="card-header border-bottom">
                <h3 class="card-title d-flex align-items-center gap-2 mb-0">
                    <i class="ti ti-mail-forward text-primary"></i>
                    Surat Masuk Terbaru
                </h3>
            </div>
            <div class="card-body card-body-scrollable card-body-scrollable-shadow p-0">
                <?php if (empty($latest_surat_masuk_by_pengirim)) : ?>
                    <div class="empty py-5">
                        <div class="empty-icon text-muted mb-3">
                            <i class="ti ti-inbox-off" style="font-size: 2.5rem; opacity: 0.4;"></i>
                        </div>
                        <p class="empty-title h4">Belum ada surat</p>
                        <p class="empty-subtitle text-muted">Data surat masuk akan tampil di sini.</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($latest_surat_masuk_by_pengirim as $surat) : ?>
                            <div class="list-group-item list-group-item-action px-3 py-3">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="avatar avatar-sm rounded-circle shadow-sm flex-shrink-0" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode((string)esc($surat['pengirim'])) ?>&background=206bc4&color=fff&bold=true&size=40')"></span>
                                    <div class="min-width-0 flex-grow-1">
                                        <div class="fw-semibold text-truncate" style="font-size: 0.85rem;"><?= esc($surat['pengirim']) ?></div>
                                        <div class="text-muted small text-truncate mt-1" title="<?= esc($surat['perihal']) ?>">
                                            <i class="ti ti-file-description me-1" style="font-size: 0.7rem;"></i><?= esc($surat['perihal']) ?>
                                        </div>
                                        <div class="mt-1 text-muted" style="font-size: 0.7rem;">
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
                <div class="card-footer bg-light-subtle text-center py-2 border-top">
                    <a href="<?= base_url('surat-masuk') ?>" class="small text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                        Lihat semua surat masuk <i class="ti ti-arrow-right" style="font-size: 0.75rem;"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ========== AKTIVITAS LOG (Hanya Admin & Pimpinan) ========== -->
<?php if (session('role') !== 'operator'): ?>
<div class="row row-cards">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="max-height: 500px;">
            <div class="card-header d-flex justify-content-between align-items-center border-bottom">
                <h3 class="card-title d-flex align-items-center gap-2 mb-0">
                    <i class="ti ti-activity text-primary"></i>
                    Aktivitas Terbaru
                </h3>
                <div class="d-flex align-items-center gap-2">
                    <?php if (session('role') === 'admin' && !empty($logs)) : ?>
                        <form action="<?= base_url('dashboard/delete-all-logs') ?>" method="post" class="m-0">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1" onclick="return confirm('Apakah Anda yakin ingin menghapus SELURUH log aktivitas? Tindakan ini tidak dapat dibatalkan.');">
                                <i class="ti ti-trash" style="font-size: 0.85rem;"></i> 
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
                        <p class="empty-subtitle text-muted">Log aktivitas pengguna akan muncul di sini.</p>
                    </div>
                <?php else : ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($logs as $log) : ?>
                            <div class="list-group-item px-3 py-3">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Avatar -->
                                    <div class="flex-shrink-0">
                                        <?php if (!empty($log['foto_profile'])): ?>
                                            <span class="avatar avatar-sm rounded-circle shadow-sm" style="background-image: url('<?= base_url('uploads/profiles/' . $log['foto_profile']) ?>')"></span>
                                        <?php else: ?>
                                            <span class="avatar avatar-sm rounded-circle shadow-sm" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode((string)esc($log['username'] ?? 'User')) ?>&background=206bc4&color=fff&bold=true&size=40')"></span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <strong style="font-size: 0.85rem;"><?= esc($log['username'] ?? 'User') ?></strong>
                                            <?php 
                                                $aksi = strtolower($log['aksi'] ?? '');
                                                $aksiBadge = 'bg-secondary-lt text-secondary';
                                                $aksiIcon = 'ti-pencil';
                                                if (strpos($aksi, 'tambah') !== false || strpos($aksi, 'buat') !== false || strpos($aksi, 'create') !== false) { 
                                                    $aksiBadge = 'bg-success-lt text-success'; $aksiIcon = 'ti-plus';
                                                } elseif (strpos($aksi, 'edit') !== false || strpos($aksi, 'update') !== false || strpos($aksi, 'ubah') !== false) { 
                                                    $aksiBadge = 'bg-info-lt text-info'; $aksiIcon = 'ti-edit';
                                                } elseif (strpos($aksi, 'hapus') !== false || strpos($aksi, 'delete') !== false) { 
                                                    $aksiBadge = 'bg-danger-lt text-danger'; $aksiIcon = 'ti-trash';
                                                } elseif (strpos($aksi, 'approve') !== false || strpos($aksi, 'setuju') !== false) { 
                                                    $aksiBadge = 'bg-warning-lt text-warning'; $aksiIcon = 'ti-check';
                                                }
                                            ?>
                                            <span class="badge <?= $aksiBadge ?> d-inline-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                                <i class="ti <?= $aksiIcon ?>" style="font-size: 0.65rem;"></i>
                                                <?= esc($log['aksi']) ?>
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-1">
                                            Surat <?= esc($log['tipe_surat']) ?>
                                            <span class="badge bg-primary-lt text-primary ms-1" style="font-size: 0.65rem;">#<?= esc($log['surat_id']) ?></span>
                                        </div>
                                    </div>

                                    <!-- Time & Actions -->
                                    <div class="flex-shrink-0 text-end d-flex flex-column align-items-end gap-1">
                                        <span class="text-muted" style="font-size: 0.7rem; white-space: nowrap;">
                                            <i class="ti ti-clock me-1"></i><?= format_tanggal_waktu_indo($log['created_at']) ?>
                                        </span>
                                        <?php if (session('role') === 'admin'): ?>
                                            <form action="<?= base_url('dashboard/delete-log/' . $log['id']) ?>" method="post" class="m-0">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-ghost-danger btn-sm p-0 border-0" style="line-height: 1;" onclick="return confirm('Hapus log ini?');" data-bs-toggle="tooltip" title="Hapus log">
                                                    <i class="ti ti-x" style="font-size: 0.8rem;"></i>
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
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    /* Card Hover Lift Effect */
    .card-link-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    .card-link-hover:active {
        transform: translateY(-1px);
    }

    /* Smooth list group items */
    .list-group-item {
        transition: background-color 0.15s ease;
    }
    .list-group-item:hover {
        background-color: rgba(32, 107, 196, 0.03);
    }

    /* Min width utility */
    .min-width-0 {
        min-width: 0;
    }

    /* Responsive stat cards text */
    @media (max-width: 576px) {
        .card .card-body h3 {
            font-size: 1.3rem !important;
        }
        .card .card-body .text-uppercase {
            font-size: 0.6rem !important;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // ===== ApexCharts Configuration =====
        var el = document.getElementById('chart-persuratan');
        if (!el || typeof ApexCharts === 'undefined') return;

        // Detect dark mode
        var isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        
        var chart = new ApexCharts(el, {
            chart: {
                type: "area",
                fontFamily: 'inherit',
                height: 300,
                parentHeightOffset: 0,
                toolbar: { show: false },
                animations: { 
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: { enabled: true, delay: 150 },
                    dynamicAnimation: { enabled: true, speed: 350 }
                },
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 0,
                    blur: 4,
                    opacity: 0.1
                }
            },
            dataLabels: { enabled: false },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.5,
                    opacityTo: 0.05,
                    stops: [0, 95, 100]
                }
            },
            stroke: {
                width: [2.5, 2.5],
                curve: 'smooth'
            },
            series: [{
                name: "Surat Masuk",
                data: <?= json_encode(array_values($chart_data['masuk'] ?? [])) ?>
            }, {
                name: "Surat Keluar",
                data: <?= json_encode(array_values($chart_data['keluar'] ?? [])) ?>
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false },
                labels: {
                    style: {
                        colors: isDark ? '#999' : '#666',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                min: 0,
                tickAmount: 4,
                labels: {
                    style: {
                        colors: isDark ? '#999' : '#666',
                        fontSize: '11px'
                    },
                    formatter: function(val) {
                        return Number.isInteger(val) ? val : '';
                    }
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: isDark ? 'dark' : 'light',
                y: {
                    formatter: function(val) {
                        return val + ' surat';
                    }
                }
            },
            colors: ["#206bc4", "#2fb344"],
            legend: {
                show: false
            },
            grid: {
                strokeDashArray: 4,
                borderColor: isDark ? '#333' : '#e9ecef',
                padding: { top: -20, right: 0, bottom: -5, left: 0 }
            },
            markers: {
                size: 0,
                hover: {
                    size: 5,
                    sizeOffset: 3
                }
            }
        });
        chart.render();
    });
</script>
<?= $this->endSection() ?>