<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row row-cards">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Detail Instruksi Disposisi</h3>
            </div>
            <div class="card-status-top bg-info"></div>
            <div class="card-body p-0">
                <table class="table table-vcenter table-striped card-table">
                    <tr>
                        <th class="w-25">Surat Terkait</th>
                        <td><?= esc($disposisi['nomor_agenda']) ?> - <?= esc($disposisi['perihal']) ?></td>
                    </tr>
                    <tr>
                        <th>Penerima Instruksi</th>
                        <td><?= esc($disposisi['penerima_nama']) ?> (<?= esc($disposisi['penerima_jabatan']) ?>)</td>
                    </tr>
                    <tr>
                        <th>Tanggal Disposisi</th>
                        <td><?= format_tanggal_indo($disposisi['created_at']) ?></td>
                    </tr>
                    <tr>
                        <th>Tenggat Waktu</th>
                        <td><span class="text-danger font-weight-bold"><?= format_tanggal_indo($disposisi['batas_waktu']) ?></span></td>
                    </tr>
                    <tr>
                        <th>Isi Instruksi Pimpinan</th>
                        <td><?= nl2br((string) esc($disposisi['instruksi'])) ?></td>
                    </tr>
                    <tr>
                        <th>Status Disposisi</th>
                        <td>
                            <?php if ($disposisi['status'] == 'pending'): ?>
                                <span class="badge bg-warning text-warning-fg"><i class="ti ti-clock icon-sm me-1"></i> Pending (Menunggu Pemrosesan)</span>
                            <?php elseif ($disposisi['status'] == 'diproses'): ?>
                                <span class="badge bg-info text-info-fg"><i class="ti ti-loader icon-sm me-1"></i> Sedang Diproses</span>
                            <?php elseif ($disposisi['status'] == 'selesai'): ?>
                                <span class="badge bg-success text-success-fg"><i class="ti ti-check icon-sm me-1"></i> Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-secondary text-secondary-fg"><?= esc($disposisi['status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('disposisi') ?>" class="btn btn-link link-secondary"><i class="ti ti-arrow-left icon"></i> Kembali ke Daftar</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tindak Lanjut Penerima</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('disposisi/updateStatus/' . $disposisi['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Update Status Penyelesaian</label>
                        <select class="form-select" name="status">
                            <option value="pending" <?= $disposisi['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="diproses" <?= $disposisi['status'] == 'diproses' ? 'selected' : '' ?>>Sedang Diproses</option>
                            <option value="selesai" <?= $disposisi['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggapan / Catatan Balasan</label>
                        <textarea class="form-control" name="tanggapan" rows="3" placeholder="Tuliskan laporan jika sudah selesai..."><?= esc($disposisi['catatan']) ?></textarea>
                    </div>
                    <?php if (session()->get('user_id') == $disposisi['ke_user_id'] || session()->get('role') == 'admin'): ?>
                        <button class="btn btn-primary w-100" type="submit"><i class="ti ti-check icon"></i> Simpan Laporan</button>
                    <?php else: ?>
                        <button class="btn btn-primary w-100" type="button" disabled><i class="ti ti-lock icon"></i> Hanya Penerima Yang Bisa Akses</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>