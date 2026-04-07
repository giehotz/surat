<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Daftar Instruksi Disposisi</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter datatable" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Surat Terkait</th>
                            <th>Penerima Instruksi</th>
                            <th>Batas Waktu</th>
                            <th>Status</th>
                            <th>Dibuat Oleh</th>
                            <th>Diupdate Oleh</th>
                            <th class="text-center w-5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($disposisi)): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada data disposisi.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1;
                            foreach ($disposisi as $dsp): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td class="text-wrap">
                                        <div class="font-weight-bold"><?= esc($dsp['nomor_agenda']) ?></div>
                                        <div class="text-secondary"><?= esc($dsp['perihal']) ?></div>
                                    </td>
                                    <td>
                                        <strong><?= esc($dsp['penerima']) ?></strong>
                                        <div class="text-muted small"><?= esc($dsp['jabatan'] ?? '') ?></div>
                                    </td>
                                    <td><?= format_tanggal_indo($dsp['batas_waktu']) ?></td>
                                    <td>
                                        <?php if ($dsp['status'] == 'pending'): ?>
                                            <span class="badge bg-warning text-warning-fg"><i class="ti ti-clock icon-sm me-1"></i> Pending</span>
                                        <?php elseif ($dsp['status'] == 'diproses'): ?>
                                            <span class="badge bg-info text-info-fg"><i class="ti ti-loader icon-sm me-1"></i> Sedang Diproses</span>
                                        <?php elseif ($dsp['status'] == 'selesai'): ?>
                                            <span class="badge bg-success text-success-fg"><i class="ti ti-check icon-sm me-1"></i> Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary text-secondary-fg"><?= esc($dsp['status']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($dsp['pembuat'] ?? '-') ?></td>
                                    <td><?= esc($dsp['pengupdate'] ?? '-') ?></td>
                                    <td class="text-end">
                                        <div class="btn-list flex-nowrap justify-content-center">
                                            <a href="<?= base_url('disposisi/show/' . $dsp['id']) ?>" class="btn btn-outline-info btn-icon" title="Detail Instruksi" data-bs-toggle="tooltip">
                                                <i class="ti ti-eye icon"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>