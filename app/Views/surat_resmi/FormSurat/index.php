<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
$isEdit = isset($surat);
$actionUrl = $isEdit ? base_url('surat-resmi/update/'.$surat['id']) : base_url('surat-resmi/store');
$validation = \Config\Services::validation();
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                <a href="<?= base_url('surat-resmi') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="<?= $actionUrl ?>" method="post" id="form-surat">
                    <?= csrf_field() ?>

                    <?= $this->include('surat_resmi/FormSurat/Partial/_template_selector') ?>

                    <div class="row mt-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nomor Surat</label>
                            <input type="text" class="form-control <?= $validation->getError('nomor_surat') ? 'is-invalid' : '' ?>" name="nomor_surat" value="<?= old('nomor_surat', $surat['nomor_surat'] ?? ($next_nomor ?? '')) ?>" required autocomplete="off" placeholder="Contoh: 01/SR/MI.02/VI/2026">
                            <?php if ($validation->getError('nomor_surat')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('nomor_surat') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Nomor otomatis dari data terakhir: <strong><?= esc($next_nomor ?? '-') ?></strong></small>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label required">Tanggal Surat</label>
                            <input type="date" class="form-control <?= $validation->getError('tanggal_surat') ? 'is-invalid' : '' ?>" name="tanggal_surat" value="<?= old('tanggal_surat', $surat['tanggal_surat'] ?? date('Y-m-d')) ?>" required>
                            <?php if ($validation->getError('tanggal_surat')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('tanggal_surat') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Lampiran</label>
                            <input type="text" class="form-control" name="lampiran" value="<?= old('lampiran', $surat['lampiran'] ?? '-') ?>" placeholder="1 (satu) berkas">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12 mb-3">
                            <label class="form-label required">Perihal</label>
                            <input type="text" class="form-control <?= $validation->getError('perihal') ? 'is-invalid' : '' ?>" name="perihal" value="<?= old('perihal', $surat['perihal'] ?? '') ?>" required placeholder="Contoh: Permohonan Bantuan">
                            <?php if ($validation->getError('perihal')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('perihal') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="ti ti-user me-2"></i>Tujuan Surat</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Kepada Yth. (Nama/Jabatan)</label>
                                    <input type="text" class="form-control <?= $validation->getError('tujuan_nama') ? 'is-invalid' : '' ?>" name="tujuan_nama" value="<?= old('tujuan_nama', $surat['tujuan_nama'] ?? '') ?>" placeholder="Contoh: Kepala Dinas Pendidikan" required>
                                    <?php if ($validation->getError('tujuan_nama')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('tujuan_nama') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Alamat Tujuan</label>
                                    <input type="text" class="form-control <?= $validation->getError('tujuan_alamat') ? 'is-invalid' : '' ?>" name="tujuan_alamat" value="<?= old('tujuan_alamat', $surat['tujuan_alamat'] ?? '') ?>" placeholder="Contoh: di Tempat" required>
                                    <?php if ($validation->getError('tujuan_alamat')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('tujuan_alamat') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="ti ti-file-text me-2"></i>Isi Surat</h5>
                            <div class="mb-3">
                                <label class="form-label required">Salam Pembuka</label>
                                <input type="text" class="form-control" name="salam_pembuka" value="<?= old('salam_pembuka', $surat['salam_pembuka'] ?? 'Dengan hormat,') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Isi Utama Surat</label>
                                <textarea id="isi_surat_editor" class="form-control <?= $validation->getError('isi_surat') ? 'is-invalid' : '' ?>" name="isi_surat" rows="12"><?= old('isi_surat', $surat['isi_surat'] ?? '') ?></textarea>
                                <?php if ($validation->getError('isi_surat')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('isi_surat') ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Salam Penutup</label>
                                <input type="text" class="form-control" name="salam_penutup" value="<?= old('salam_penutup', $surat['salam_penutup'] ?? 'Hormat kami,') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><i class="ti ti-signature me-2"></i>Pengirim / Penandatangan</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Jabatan Pengirim</label>
                                    <input type="text" class="form-control <?= $validation->getError('pengirim_jabatan') ? 'is-invalid' : '' ?>" name="pengirim_jabatan" value="<?= old('pengirim_jabatan', $surat['pengirim_jabatan'] ?? ($user_jabatan ?? '')) ?>" placeholder="Contoh: Kepala Madrasah" required>
                                    <?php if ($validation->getError('pengirim_jabatan')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('pengirim_jabatan') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label required">Nama Pengirim</label>
                                    <input type="text" class="form-control <?= $validation->getError('pengirim_nama') ? 'is-invalid' : '' ?>" name="pengirim_nama" value="<?= old('pengirim_nama', $surat['pengirim_nama'] ?? ($user_nama ?? '')) ?>" placeholder="Contoh: Nama Lengkap" required>
                                    <?php if ($validation->getError('pengirim_nama')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('pengirim_nama') ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">NIP / NIK (Opsional)</label>
                                    <input type="text" class="form-control" name="pengirim_nip" value="<?= old('pengirim_nip', $surat['pengirim_nip'] ?? '') ?>" placeholder="NIP. 197005272007011022">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tembusan (Opsional)</label>
                        <textarea class="form-control" name="tembusan" rows="3" placeholder="1. Kepala Kantor Kementerian Agama&#10;2. Arsip"><?= old('tembusan', $surat['tembusan'] ?? '') ?></textarea>
                    </div>

                    <div class="form-footer text-end border-top pt-3">
                        <a href="<?= base_url('surat-resmi') ?>" class="btn btn-outline-secondary me-2">
                            <i class="ti ti-x me-1"></i> Batal
                        </a>
                        <button type="button" class="btn btn-outline-info me-2" onclick="previewSurat()">
                            <i class="ti ti-eye me-1"></i> Preview
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.tiny.cloud/1/4wj73q4szc3rdlnlsj744qe11xih3cmbai3g0b7eoheca3d1/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
<?= $this->include('surat_resmi/FormSurat/Partial/_template_selector_js') ?>

<script>
    tinymce.init({
        selector: '#isi_surat_editor',
        height: 400,
        menubar: false,
        plugins: 'lists link table code',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | table link code',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    function previewSurat() {
        if (tinymce.get('isi_surat_editor')) {
            tinymce.get('isi_surat_editor').save();
        }
        var form = document.getElementById('form-surat');
        form.action = '<?= base_url('surat-resmi/previewPdf') ?>';
        form.target = '_blank';
        form.submit();
        form.action = '<?= $actionUrl ?>';
        form.removeAttribute('target');
    }
</script>
<?= $this->endSection() ?>
