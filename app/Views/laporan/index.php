<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row g-3">
    <!-- Header Halaman -->
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between pb-3 border-bottom gap-3">
            <div>
                <h3 class="mb-1 d-flex align-items-center gap-2">
                    <i class="ti ti-file-analytics text-primary"></i>
                    Laporan Rekapitulasi Surat
                </h3>
                <p class="text-muted small mb-0">
                    Laporan resmi pencatatan arsip surat masuk dan keluar berdasar rentang periode bulan
                </p>
            </div>
            <div>
                <a href="<?= base_url('laporan/export-pdf?start_month=' . $start_month . '&end_month=' . $end_month . '&tahun=' . $tahun . '&tanggal_cetak=' . $tanggal_cetak) ?>" 
                   id="btnCetakHeader"
                   target="_blank" 
                   class="btn btn-danger d-inline-flex align-items-center gap-2 shadow-sm">
                    <i class="ti ti-file-type-pdf fs-2"></i>
                    <span>Cetak Dokumen Resmi PDF</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent py-3">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
                    <h4 class="card-title mb-0 d-flex align-items-center gap-2">
                        <i class="ti ti-filter text-secondary"></i>
                        Filter Periode Laporan
                    </h4>
                    <!-- Quick Presets -->
                    <div class="d-flex align-items-center gap-1 flex-wrap">
                        <span class="text-muted small me-1">Pilihan Cepat:</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreset(<?= date('n') ?>, <?= date('n') ?>)">Bulan Ini</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreset(1, 3)">Triwulan I</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreset(4, 6)">Triwulan II</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreset(7, 9)">Triwulan III</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPreset(10, 12)">Triwulan IV</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPreset(1, 6)">Semester I</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="setPreset(7, 12)">Semester II</button>
                        <button type="button" class="btn btn-sm btn-outline-dark" onclick="setPreset(1, 12)">1 Tahun Penuh</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="filterForm" method="get" action="<?= base_url('laporan') ?>">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-sm-6 col-lg-2">
                            <label class="form-label fw-bold small text-secondary">Dari Bulan</label>
                            <select name="start_month" id="start_month" class="form-select">
                                <?php foreach ($bulan_list as $num => $nama): ?>
                                    <option value="<?= $num ?>" <?= ($start_month == $num) ? 'selected' : '' ?>>
                                        <?= sprintf('%02d', $num) ?> - <?= esc($nama) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-2">
                            <label class="form-label fw-bold small text-secondary">Sampai Bulan</label>
                            <select name="end_month" id="end_month" class="form-select">
                                <?php foreach ($bulan_list as $num => $nama): ?>
                                    <option value="<?= $num ?>" <?= ($end_month == $num) ? 'selected' : '' ?>>
                                        <?= sprintf('%02d', $num) ?> - <?= esc($nama) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-2">
                            <label class="form-label fw-bold small text-secondary">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select">
                                <?php foreach ($tahun_list as $thn): ?>
                                    <option value="<?= esc($thn['tahun']) ?>" <?= ($tahun == $thn['tahun']) ? 'selected' : '' ?>>
                                        Tahun <?= esc($thn['tahun']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label class="form-label fw-bold small text-secondary">Tanggal Cetak Dokumen</label>
                            <input type="date" name="tanggal_cetak" id="tanggal_cetak" class="form-control" value="<?= esc($tanggal_cetak) ?>">
                        </div>
                        <div class="col-12 col-lg-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100 d-flex align-items-center justify-content-center gap-1">
                                <i class="ti ti-search"></i>
                                Terapkan Filter
                            </button>
                            <a href="<?= base_url('laporan') ?>" class="btn btn-outline-secondary" title="Reset Filter">
                                <i class="ti ti-refresh"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Kartu -->
    <div class="col-12 col-md-4">
        <div class="card card-sm shadow-sm border-0 border-start border-danger border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-red-lt avatar avatar-md rounded">
                            <i class="ti ti-mail-down fs-2 text-danger"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-bold text-uppercase">Surat Masuk Tercatat</div>
                        <div class="h2 mb-0 fw-bold text-danger"><?= number_format($total_masuk, 0, ',', '.') ?></div>
                        <div class="text-muted small mt-1">
                            <?php 
                            $pctMasuk = $total_semua > 0 ? round(($total_masuk / $total_semua) * 100, 1) : 0;
                            ?>
                            <span class="badge bg-danger-lt"><?= $pctMasuk ?>%</span> dari total surat periode ini
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card card-sm shadow-sm border-0 border-start border-success border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-green-lt avatar avatar-md rounded">
                            <i class="ti ti-mail-forward fs-2 text-success"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-bold text-uppercase">Surat Keluar Tercatat</div>
                        <div class="h2 mb-0 fw-bold text-success"><?= number_format($total_keluar, 0, ',', '.') ?></div>
                        <div class="text-muted small mt-1">
                            <?php 
                            $pctKeluar = $total_semua > 0 ? round(($total_keluar / $total_semua) * 100, 1) : 0;
                            ?>
                            <span class="badge bg-success-lt"><?= $pctKeluar ?>%</span> dari total surat periode ini
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-4">
        <div class="card card-sm shadow-sm border-0 border-start border-primary border-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary-lt avatar avatar-md rounded">
                            <i class="ti ti-folders fs-2 text-primary"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-secondary small fw-bold text-uppercase">Total Keseluruhan</div>
                        <div class="h2 mb-0 fw-bold text-primary"><?= number_format($total_semua, 0, ',', '.') ?></div>
                        <div class="text-muted small mt-1">
                            Periode: <strong><?= esc($bulan_list[$start_month]) ?></strong> s.d. <strong><?= esc($bulan_list[$end_month]) ?> <?= esc($tahun) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tampilan Utama: Nav Tabs -->
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent p-0 border-bottom">
                <ul class="nav nav-tabs card-header-tabs m-0" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="#tab-rekap" class="nav-link active py-3 px-4 d-flex align-items-center gap-2" data-bs-toggle="tab">
                            <i class="ti ti-table"></i>
                            <strong>Tabel Rekapitulasi Bulanan</strong>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-masuk" class="nav-link py-3 px-4 d-flex align-items-center gap-2" data-bs-toggle="tab">
                            <i class="ti ti-mail-down text-danger"></i>
                            <span>Rincian Surat Masuk (<?= count($surat_masuk) ?>)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#tab-keluar" class="nav-link py-3 px-4 d-flex align-items-center gap-2" data-bs-toggle="tab">
                            <i class="ti ti-mail-forward text-success"></i>
                            <span>Rincian Surat Keluar (<?= count($surat_keluar) ?>)</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-0">
                <div class="tab-content">
                    <!-- Tab Rekap Bulanan -->
                    <div class="tab-pane active show" id="tab-rekap">
                        <div class="table-responsive">
                            <table class="table table-vcenter table-hover table-striped card-table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center">No</th>
                                        <th>Bulan & Tahun</th>
                                        <th class="text-center" style="width: 180px;">Surat Masuk</th>
                                        <th class="text-center" style="width: 180px;">Surat Keluar</th>
                                        <th class="text-center" style="width: 180px;">Total Surat</th>
                                        <th style="width: 250px;">Komposisi (Masuk / Keluar)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $noRekap = 1;
                                    foreach ($rekap as $row): 
                                        $rowTotal = $row['total'];
                                        $pctM = $rowTotal > 0 ? round(($row['surat_masuk'] / $rowTotal) * 100) : 0;
                                        $pctK = $rowTotal > 0 ? (100 - $pctM) : 0;
                                    ?>
                                        <tr>
                                            <td class="text-center text-muted"><?= $noRekap++ ?></td>
                                            <td>
                                                <div class="fw-bold fs-3 mb-0"><?= esc($row['nama_bulan']) ?></div>
                                                <div class="text-muted small">Tahun Anggaran <?= esc($tahun) ?></div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger-lt px-3 py-2 fs-3">
                                                    <?= number_format($row['surat_masuk'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success-lt px-3 py-2 fs-3">
                                                    <?= number_format($row['surat_keluar'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary-lt px-3 py-2 fs-3 fw-bold">
                                                    <?= number_format($row['total'], 0, ',', '.') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($rowTotal > 0): ?>
                                                    <div class="progress progress-sm mb-1" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" style="width: <?= $pctM ?>%" title="Masuk: <?= $pctM ?>%"></div>
                                                        <div class="progress-bar bg-success" style="width: <?= $pctK ?>%" title="Keluar: <?= $pctK ?>%"></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                                        <span>M: <?= $row['surat_masuk'] ?> (<?= $pctM ?>%)</span>
                                                        <span>K: <?= $row['surat_keluar'] ?> (<?= $pctK ?>%)</span>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-muted small fst-italic">Belum ada surat</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-light fw-bold">
                                        <td colspan="2" class="text-center text-uppercase py-3">TOTAL PERIODE TERPILIH</td>
                                        <td class="text-center text-danger fs-3 py-3">
                                            <?= number_format($total_masuk, 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center text-success fs-3 py-3">
                                            <?= number_format($total_keluar, 0, ',', '.') ?>
                                        </td>
                                        <td class="text-center text-primary fs-3 py-3 fw-bold">
                                            <?= number_format($total_semua, 0, ',', '.') ?>
                                        </td>
                                        <td class="py-3">
                                            <?php if ($total_semua > 0): ?>
                                                <div class="progress progress-sm mb-1" style="height: 10px;">
                                                    <div class="progress-bar bg-danger" style="width: <?= $pctMasuk ?>%"></div>
                                                    <div class="progress-bar bg-success" style="width: <?= $pctKeluar ?>%"></div>
                                                </div>
                                                <div class="d-flex justify-content-between text-muted" style="font-size: 11px;">
                                                    <span class="text-danger fw-semibold">Masuk: <?= $pctMasuk ?>%</span>
                                                    <span class="text-success fw-semibold">Keluar: <?= $pctKeluar ?>%</span>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Detail Surat Masuk -->
                    <div class="tab-pane" id="tab-masuk">
                        <div class="p-3 bg-light-subtle border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="text-muted small">
                                Menampilkan sampel agenda surat masuk yang tercatat pada rentang periode terpilih.
                            </div>
                            <span class="badge bg-secondary-lt">Total: <?= count($surat_masuk) ?> Data</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>No Agenda</th>
                                        <th>No Surat</th>
                                        <th>Tgl Surat</th>
                                        <th>Tgl Terima</th>
                                        <th>Pengirim</th>
                                        <th>Perihal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($surat_masuk)): ?>
                                        <?php $noSM = 1; foreach ($surat_masuk as $sm): ?>
                                            <tr>
                                                <td class="text-muted"><?= $noSM++ ?></td>
                                                <td><span class="badge bg-blue-lt"><?= esc($sm['nomor_agenda'] ?? '-') ?></span></td>
                                                <td class="fw-medium"><?= esc($sm['nomor_surat'] ?? '-') ?></td>
                                                <td><?= format_tanggal_indo($sm['tanggal_surat']) ?></td>
                                                <td><?= format_tanggal_indo($sm['tanggal_terima']) ?></td>
                                                <td><?= esc($sm['pengirim']) ?></td>
                                                <td><?= esc($sm['perihal']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                Tidak ada catatan surat masuk dalam periode ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab Detail Surat Keluar -->
                    <div class="tab-pane" id="tab-keluar">
                        <div class="p-3 bg-light-subtle border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="text-muted small">
                                Menampilkan sampel agenda surat keluar yang tercatat pada rentang periode terpilih.
                            </div>
                            <span class="badge bg-secondary-lt">Total: <?= count($surat_keluar) ?> Data</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 40px;">No</th>
                                        <th>No Agenda</th>
                                        <th>No Surat</th>
                                        <th>Tgl Surat</th>
                                        <th>Tgl Kirim</th>
                                        <th>Tujuan</th>
                                        <th>Perihal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($surat_keluar)): ?>
                                        <?php $noSK = 1; foreach ($surat_keluar as $sk): ?>
                                            <tr>
                                                <td class="text-muted"><?= $noSK++ ?></td>
                                                <td><span class="badge bg-green-lt"><?= esc($sk['nomor_agenda'] ?? '-') ?></span></td>
                                                <td class="fw-medium"><?= esc($sk['nomor_surat'] ?? '-') ?></td>
                                                <td><?= format_tanggal_indo($sk['tanggal_surat']) ?></td>
                                                <td><?= format_tanggal_indo($sk['tanggal_kirim']) ?></td>
                                                <td><?= esc($sk['tujuan'] ?? '-') ?></td>
                                                <td><?= esc($sk['perihal']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                Tidak ada catatan surat keluar dalam periode ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                <div class="text-muted small">
                    <i class="ti ti-info-circle me-1"></i>
                    Dokumen resmi PDF akan menyertakan Kop Madrasah, Tabel Rekapitulasi Eksekutif, seluruh lampiran rincian, dan lembar pengesahan.
                </div>
                <a href="<?= base_url('laporan/export-pdf?start_month=' . $start_month . '&end_month=' . $end_month . '&tahun=' . $tahun . '&tanggal_cetak=' . $tanggal_cetak) ?>" 
                   id="btnCetakFooter"
                   target="_blank" 
                   class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
                    <i class="ti ti-printer"></i>
                    Unduh PDF Resmi
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function updateExportLinks() {
    const sm = document.getElementById('start_month').value;
    const em = document.getElementById('end_month').value;
    const th = document.getElementById('tahun').value;
    const tc = document.getElementById('tanggal_cetak').value;
    const url = '<?= base_url('laporan/export-pdf') ?>?start_month=' + sm + '&end_month=' + em + '&tahun=' + th + '&tanggal_cetak=' + tc;

    const btnHeader = document.getElementById('btnCetakHeader');
    if (btnHeader) btnHeader.href = url;

    const btnFooter = document.getElementById('btnCetakFooter');
    if (btnFooter) btnFooter.href = url;
}

document.getElementById('start_month').addEventListener('change', updateExportLinks);
document.getElementById('end_month').addEventListener('change', updateExportLinks);
document.getElementById('tahun').addEventListener('change', updateExportLinks);
document.getElementById('tanggal_cetak').addEventListener('change', updateExportLinks);
document.getElementById('tanggal_cetak').addEventListener('input', updateExportLinks);

function setPreset(start, end) {
    document.getElementById('start_month').value = start;
    document.getElementById('end_month').value = end;
    updateExportLinks();
    document.getElementById('filterForm').submit();
}
</script>

<?= $this->endSection() ?>
