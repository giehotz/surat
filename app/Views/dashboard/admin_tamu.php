<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row row-cards">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card card-md shadow-sm border-0 bg-primary-lt">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="h1 mb-1">Selamat Datang, <?= esc(session('nama_lengkap')) ?>! 👋</h2>
                        <p class="text-secondary mb-0">Hari ini ada <strong><?= $stats['hari_ini'] ?> tamu</strong> yang berkunjung. Pantau dan kelola data tamu dengan mudah di sini.</p>
                    </div>
                    <div class="col-auto">
                        <div class="btn-list">
                            <a href="<?= base_url('buku-tamu') ?>" target="_blank" class="btn btn-primary d-none d-sm-inline-flex">
                                <i class="ti ti-external-link icon me-2"></i> Buka Kiosk Tamu
                            </a>
                            <a href="<?= base_url('admin-buku-tamu') ?>" class="btn btn-success">
                                <i class="ti ti-list-check icon me-2"></i> Rekap Tamu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-md-4">
        <div class="card card-sm shadow-sm border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar shadow">
                            <i class="ti ti-users icon"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">Tamu Hari Ini</div>
                        <div class="text-secondary"><?= $stats['hari_ini'] ?> orang</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-sm shadow-sm border-0">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-yellow text-white avatar shadow">
                            <i class="ti ti-clock icon"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">Menunggu Tindak Lanjut</div>
                        <div class="text-secondary"><?= $stats['menunggu'] ?> tamu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-sm shadow-sm border-0 border-start border-azure">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-azure text-white avatar shadow">
                            <i class="ti ti-calendar icon"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="font-weight-medium">Total Bulan Ini</div>
                        <div class="text-secondary"><?= $stats['bulan_ini'] ?> tamu</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Recent Guests -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="card-title">Tren Kunjungan (7 Hari Terakhir)</h3>
                <div id="chart-guests" style="min-height: 250px;"></div>
            </div>
        </div>
        
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header border-0">
                <h3 class="card-title">Aksi Cepat Manajemen</h3>
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <a href="<?= base_url('admin-buku-tamu') ?>" class="text-decoration-none">
                            <div class="p-3 bg-light rounded-3 hover-shadow transition-all">
                                <i class="ti ti-table text-primary h1 mb-2"></i>
                                <div class="font-weight-bold text-dark">Data Rekap</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="<?= base_url('pengaturan#tab-buku-tamu') ?>" class="text-decoration-none">
                            <div class="p-3 bg-light rounded-3 hover-shadow transition-all">
                                <i class="ti ti-settings text-green h1 mb-2"></i>
                                <div class="font-weight-bold text-dark">Atur Jadwal</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-4">
                        <a href="<?= base_url('admin-buku-tamu/export') ?>" class="text-decoration-none">
                            <div class="p-3 bg-light rounded-3 hover-shadow transition-all">
                                <i class="ti ti-file-spreadsheet text-azure h1 mb-2"></i>
                                <div class="font-weight-bold text-dark">Export Data</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header border-0">
                <h3 class="card-title">Kunjungan Terbaru</h3>
            </div>
            <div class="list-group list-group-flush">
                <?php if (!empty($latest_guests)): ?>
                    <?php foreach ($latest_guests as $guest): ?>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <span class="avatar avatar-sm rounded-circle" style="background-image: url(<?= $guest['foto_wajah'] ? base_url('uploads/kunjungan/' . $guest['foto_wajah']) : 'https://ui-avatars.com/api/?name='.urlencode($guest['nama_lengkap']) ?>)"></span>
                            </div>
                            <div class="col text-truncate">
                                <a href="<?= base_url('admin-buku-tamu?id=' . $guest['id_kunjungan']) ?>" class="text-reset d-block font-weight-bold"><?= esc($guest['nama_lengkap']) ?></a>
                                <div class="d-block text-secondary text-truncate mt-n1 text-muted small">
                                    Tujuan: <?= esc($guest['tujuan_kunjungan']) ?>
                                </div>
                            </div>
                            <div class="col-auto">
                                <?php if ($guest['status_kunjungan'] == 'menunggu'): ?>
                                    <span class="badge bg-yellow-lt">Menunggu</span>
                                <?php else: ?>
                                    <span class="badge bg-green-lt">Ok</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted italic">Belum ada kunjungan terbaru.</div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('admin-buku-tamu') ?>" class="btn btn-sm btn-link text-primary p-0">Lihat semua kunjungan...</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        window.ApexCharts && (new ApexCharts(document.getElementById('chart-guests'), {
            chart: {
                type: "area",
                fontFamily: 'inherit',
                height: 240,
                parentHeightOffset: 0,
                toolbar: { show: false },
                animations: { enabled: true },
                sparkline: { enabled: false },
            },
            dataLabels: { enabled: false },
            fill: { opacity: .16, type: 'solid' },
            stroke: { width: 2, lineCap: "round", curve: "smooth" },
            series: [{
                name: "Jumlah Tamu",
                data: <?= json_encode($chart['values']) ?>
            }],
            grid: {
                padding: { top: -20, right: 0, left: -4, bottom: -4 },
                strokeDashArray: 4,
            },
            xaxis: {
                labels: { padding: 0 },
                tooltip: { enabled: false },
                axisBorder: { show: false },
                categories: <?= json_encode($chart['labels']) ?>,
            },
            yaxis: { labels: { padding: 4 } },
            colors: [tabler.getColor("primary")],
            legend: { show: false },
        })).render();
    });
</script>
<style>
    .transition-all { transition: all 0.3s ease; }
    .hover-shadow:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
<?= $this->endSection() ?>
