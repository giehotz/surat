<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<?php
$status_surat = esc($surat['status']);

// Tampilan Badge Tabler
$badgeColor = 'bg-warning text-warning-fg';
$badgeLabel = 'Menunggu Approval';

if ($status_surat == 'disetujui') {
    $badgeColor = 'bg-success text-success-fg';
    $badgeLabel = 'Telah Disetujui';
} elseif ($status_surat == 'ditolak') {
    $badgeColor = 'bg-danger text-danger-fg';
    $badgeLabel = 'Ditolak/Direvisi';
} elseif ($status_surat == 'dikirim') {
    $badgeColor = 'bg-primary text-primary-fg';
    $badgeLabel = 'Sudah Dikirim';
} elseif ($status_surat == 'draft') {
    $badgeColor = 'bg-secondary text-secondary-fg';
    $badgeLabel = 'Draft (Belum Diajukan)';
}
?>

<div class="row row-cards">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Detail Surat Keluar</h3>
            </div>
            <div class="card-status-top bg-success"></div>
            <div class="card-body p-0">
                <table class="table table-vcenter table-striped card-table">
                    <tr>
                        <th class="w-25">Nomor Agenda</th>
                        <td><?= esc($surat['nomor_agenda']) ?></td>
                    </tr>
                    <tr>
                        <th>Nomor Surat Eksternal</th>
                        <td><?= esc($surat['nomor_surat']) ?: '<i class="text-muted">Menunggu nomor resmi</i>' ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Surat</th>
                        <td><?= format_tanggal_indo($surat['tanggal_surat']) ?></td>
                    </tr>
                    <tr>
                        <th>Tujuan</th>
                        <td><?= esc($surat['tujuan']) ?></td>
                    </tr>
                    <tr>
                        <th>Perihal Utama</th>
                        <td><?= esc($surat['perihal']) ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Lampiran</th>
                        <td><?= esc($surat['lampiran']) ?> Berkas</td>
                    </tr>
                    <tr>
                        <th>Status Terkini</th>
                        <td><span class="badge <?= $badgeColor ?>"><?= $badgeLabel ?></span></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('surat-keluar') ?>" class="btn btn-link link-secondary"><i class="ti ti-arrow-left icon"></i> Kembali</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">File Konsep Surat (Draft)</h3>
            </div>
            <?php $isCloud = ($surat['tipe_penyimpanan']) === 'cloud'; ?>
            <div class="card-body text-center py-5">
                <?php if ($isCloud): ?>
                    <i class="ti ti-cloud-lock icon text-info mb-3" style="font-size: 64px;"></i>
                    <p class="h4">Draft via Cloud Doc</p>
                    <?php if (!empty($surat['file_link'])): ?>
                        <a href="<?= esc($surat['file_link']) ?>" target="_blank" class="btn btn-info w-100 mt-3"><i class="ti ti-external-link icon"></i> Buka Link Cloud</a>
                    <?php else: ?>
                        <p class="text-muted small">Link tidak tersedia.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <i class="ti ti-file-word icon text-primary mb-3" style="font-size: 64px;"></i>
                    <?php if (!empty($surat['file_name'])): ?>
                        <p class="h4 text-truncate" title="<?= esc($surat['file_name']) ?>"><?= esc($surat['file_name']) ?></p>
                        <p class="text-muted small"><?= esc($surat['file_size']) ?> KB</p>
                        <div class="btn-group w-100 mt-3" role="group">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewModal">
                                <i class="ti ti-eye icon"></i> Preview
                            </button>
                            <a href="<?= base_url(esc($surat['file_path'], 'url')) ?>" target="_blank" class="btn btn-primary"><i class="ti ti-download icon"></i> Download</a>
                        </div>
                    <?php else: ?>
                        <p class="h4 text-muted">Tidak Ada File</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <label class="form-label">Aksi Approval:</label>
                <form action="<?= base_url('surat-keluar/approve/' . esc($surat['id'])) ?>" method="post">
                    <?= csrf_field() ?>
                    <?php if (in_array($status_surat, ['disetujui', 'ditolak'])): ?>
                        <button type="submit" name="action_type" value="cancel" class="btn btn-warning w-100 mb-2"><i class="ti ti-arrow-back-up icon"></i> Batalkan Persetujuan</button>
                    <?php else: ?>
                        <button type="submit" name="action_type" value="approve" class="btn btn-success w-100 mb-2"><i class="ti ti-check icon"></i> Setujui (Approve)</button>
                        <button type="submit" name="action_type" value="reject" class="btn btn-danger w-100"><i class="ti ti-x icon"></i> Tolak / Kembalikan</button>
                    <?php endif; ?>
                </form>
                <small class="text-secondary text-center d-block mt-3 bg-light p-2 rounded">Hanya bisa dilakukan pimpinan.</small>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<?php if (!empty($surat['file_name']) && !$isCloud): ?>
    <div class="modal modal-blur fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Konsep Surat: <?= esc($surat['file_name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="<?= base_url(esc($surat['file_path'], 'url')) ?>" width="100%" height="600px" style="border:none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Tutup</button>
                    <a href="<?= base_url(esc($surat['file_path'], 'url')) ?>" target="_blank" class="btn btn-primary"><i class="ti ti-download icon"></i> Download Draft</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>