<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row row-cards">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Informasi Surat Masuk</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-vcenter card-table table-striped">
                    <tr>
                        <th class="w-25">Nomor Agenda</th>
                        <td><?= esc($surat['nomor_agenda']) ?></td>
                    </tr>
                    <tr>
                        <th>Nomor Surat Pengirim</th>
                        <td><?= esc($surat['nomor_surat']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Surat</th>
                        <td><?= format_tanggal_indo($surat['tanggal_surat']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Diterima</th>
                        <td><?= format_tanggal_indo($surat['tanggal_terima']) ?></td>
                    </tr>
                    <tr>
                        <th>Pengirim</th>
                        <td><?= esc($surat['pengirim']) ?></td>
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
                        <th>Keterangan Tambahan</th>
                        <td><?= esc($surat['keterangan'] ?: '-') ?></td>
                    </tr>
                    <tr>
                        <th>Status Terkini</th>
                        <td>
                            <?php if ($surat['status'] == 'tercatat'): ?>
                                <span class="badge bg-blue text-blue-fg">Tercatat</span>
                            <?php elseif ($surat['status'] == 'didisposisikan'): ?>
                                <span class="badge bg-warning text-warning-fg">Didisposisikan</span>
                            <?php elseif ($surat['status'] == 'selesai'): ?>
                                <span class="badge bg-success text-success-fg">Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-secondary-fg"><?= esc($surat['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('surat-masuk') ?>" class="btn btn-link link-secondary"><i class="ti ti-arrow-left icon"></i> Kembali</a>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">Riwayat Disposisi Surat Ini</h3>
            </div>
            <div class="card-body">
                <div class="text-secondary"><i class="ti ti-info-circle icon text-blue"></i> (Fitur riwayat disposisi akan disempurnakan di modul Disposisi)</div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">File Attachement</h3>
            </div>
            <?php $isCloud = ($surat['tipe_penyimpanan']) === 'cloud'; ?>
            <div class="card-body text-center py-5">
                <?php if ($isCloud): ?>
                    <i class="ti ti-cloud-lock icon text-info mb-3" style="font-size: 64px;"></i>
                    <p class="h4">Tersimpan via Cloud Link</p>
                    <?php if (!empty($surat['file_link'])): ?>
                        <a href="<?= esc($surat['file_link']) ?>" target="_blank" class="btn btn-info w-100 mt-3"><i class="ti ti-external-link icon"></i> Buka Link Cloud</a>
                    <?php else: ?>
                        <p class="text-muted small">Link tidak tersedia.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <i class="ti ti-file-type-pdf icon text-red mb-3" style="font-size: 64px;"></i>
                    <?php if (!empty($surat['file_name'])): ?>
                        <p class="h4 text-truncate" title="<?= esc($surat['file_name']) ?>"><?= esc($surat['file_name']) ?></p>
                        <p class="text-muted small"><?= esc($surat['file_size']) ?> KB</p>
                        <div class="btn-group w-100 mt-3" role="group">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#previewModal">
                                <i class="ti ti-eye icon"></i> Preview
                            </button>
                            <a href="<?= base_url(esc($surat['file_path'])) ?>" target="_blank" class="btn btn-primary"><i class="ti ti-download icon"></i> Download</a>
                        </div>
                    <?php else: ?>
                        <p class="h4 text-muted">Tidak Ada File</p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <a href="<?= base_url('disposisi/create/' . $surat['id']) ?>" class="btn btn-warning w-100 mt-4">
            <i class="ti ti-share icon"></i> Buat Disposisi Surat Ini
        </a>
    </div>
</div>

<!-- Modal Preview -->
<?php if (!empty($surat['file_name']) && !$isCloud): ?>
    <div class="modal modal-blur fade" id="previewModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Berkas: <?= esc($surat['file_name']) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="<?= base_url(esc($surat['file_path'])) ?>" width="100%" height="600px" style="border:none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Tutup</button>
                    <a href="<?= base_url(esc($surat['file_path'])) ?>" target="_blank" class="btn btn-primary"><i class="ti ti-download icon"></i> Download Berkas</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>