<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
$isEdit = isset($template);
$actionUrl = $isEdit ? base_url('surat-resmi/template/update/' . $template['id']) : base_url('surat-resmi/template/store');
$validation = \Config\Services::validation();
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                <a href="<?= base_url('surat-resmi/template') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="<?= $actionUrl ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label required">Nama Template</label>
                            <input type="text" class="form-control <?= $validation->getError('nama') ? 'is-invalid' : '' ?>" name="nama" value="<?= old('nama', $template['nama'] ?? '') ?>" required placeholder="Contoh: Undangan Rapat">
                            <?php if ($validation->getError('nama')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Slug</label>
                            <input type="text" class="form-control <?= $validation->getError('slug') ? 'is-invalid' : '' ?>" name="slug" value="<?= old('slug', $template['slug'] ?? '') ?>" required placeholder="Contoh: undangan_rapat">
                            <?php if ($validation->getError('slug')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('slug') ?></div>
                            <?php endif; ?>
                            <small class="text-muted">Slug digunakan sebagai identifikasi template. Gunakan huruf kecil dan underscore. Kosongi untuk generate otomatis dari nama.</small>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label required">Perihal Surat</label>
                            <input type="text" class="form-control <?= $validation->getError('perihal') ? 'is-invalid' : '' ?>" name="perihal" value="<?= old('perihal', $template['perihal'] ?? '') ?>" required>
                            <?php if ($validation->getError('perihal')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('perihal') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lampiran</label>
                            <input type="text" class="form-control" name="lampiran" value="<?= old('lampiran', $template['lampiran'] ?? '-') ?>">
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Tujuan Surat</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kepada Yth.</label>
                                    <input type="text" class="form-control" name="tujuan_nama" value="<?= old('tujuan_nama', $template['tujuan_nama'] ?? '') ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Alamat Tujuan</label>
                                    <input type="text" class="form-control" name="tujuan_alamat" value="<?= old('tujuan_alamat', $template['tujuan_alamat'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Isi Surat</h5>
                            <div class="mb-3">
                                <label class="form-label">Salam Pembuka</label>
                                <input type="text" class="form-control" name="salam_pembuka" value="<?= old('salam_pembuka', $template['salam_pembuka'] ?? 'Dengan hormat,') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Isi Surat (HTML)</label>
                                <textarea class="form-control <?= $validation->getError('isi_surat') ? 'is-invalid' : '' ?>" name="isi_surat" rows="12"><?= old('isi_surat', $template['isi_surat'] ?? '') ?></textarea>
                                <?php if ($validation->getError('isi_surat')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('isi_surat') ?></div>
                                <?php endif; ?>
                                <small class="text-muted">Gunakan tag HTML untuk format teks. Gunakan <strong>[Teks dalam kurung siku]</strong> untuk menandai field yang diisi manual.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Salam Penutup</label>
                                <input type="text" class="form-control" name="salam_penutup" value="<?= old('salam_penutup', $template['salam_penutup'] ?? 'Hormat kami,') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Pengirim / Penandatangan</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Jabatan Pengirim</label>
                                    <input type="text" class="form-control" name="pengirim_jabatan" value="<?= old('pengirim_jabatan', $template['pengirim_jabatan'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nama Pengirim</label>
                                    <input type="text" class="form-control" name="pengirim_nama" value="<?= old('pengirim_nama', $template['pengirim_nama'] ?? '') ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">NIP Pengirim</label>
                                    <input type="text" class="form-control" name="pengirim_nip" value="<?= old('pengirim_nip', $template['pengirim_nip'] ?? '') ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer text-end border-top pt-3">
                        <a href="<?= base_url('surat-resmi/template') ?>" class="btn btn-outline-secondary me-2">
                            <i class="ti ti-x me-1"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i> Simpan Template
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
