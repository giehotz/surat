<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                <div>
                    <h3 class="card-title text-white mb-0">
                        <i class="ti ti-settings me-2"></i> Konfigurasi Field: <?= esc($template['nama']) ?>
                    </h3>
                    <div class="text-white-50 small">Sesuaikan label pertanyaan dan tipe input formulir untuk setiap tag placeholder yang ditemukan.</div>
                </div>
                <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-light btn-sm">
                    <i class="ti ti-arrow-left me-1"></i> Selesai
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success m-3">
                    <i class="ti ti-check me-1"></i> <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('dokumen-template/save-fields/' . $template['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-vcenter table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th style="width: 200px;">Tag di Dokumen Word</th>
                                    <th>Label Form (Pertanyaan)</th>
                                    <th style="width: 220px;">Tipe Input Form</th>
                                    <th style="width: 100px;" class="text-center">Wajib?</th>
                                    <th style="width: 80px;" class="text-center">Urutan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($fields)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Tidak ditemukan tag placeholder dalam dokumen ini.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php $no = 1; foreach ($fields as $f): ?>
                                        <tr>
                                            <td class="text-center fw-bold"><?= $no++ ?></td>
                                            <td>
                                                <code class="text-primary fw-bold font-monospace">${<?= esc($f['field_key']) ?>}</code>
                                            </td>
                                            <td>
                                                <input type="text" name="fields[<?= $f['id'] ?>][label]" class="form-control form-control-sm" required value="<?= esc($f['field_label']) ?>">
                                            </td>
                                            <td>
                                                <select name="fields[<?= $f['id'] ?>][type]" class="form-select form-select-sm">
                                                    <option value="text" <?= $f['field_type'] === 'text' ? 'selected' : '' ?>>Teks Singkat (Input Text)</option>
                                                    <option value="textarea" <?= $f['field_type'] === 'textarea' ? 'selected' : '' ?>>Teks Panjang (Textarea)</option>
                                                    <option value="date" <?= $f['field_type'] === 'date' ? 'selected' : '' ?>>Tanggal (Datepicker)</option>
                                                    <option value="guru_select" <?= $f['field_type'] === 'guru_select' ? 'selected' : '' ?>>Pilih Guru (Dropdown Master)</option>
                                                    <option value="number" <?= $f['field_type'] === 'number' ? 'selected' : '' ?>>Angka (Number)</option>
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input type="checkbox" name="fields[<?= $f['id'] ?>][is_required]" value="1" class="form-check-input" <?= $f['is_required'] ? 'checked' : '' ?>>
                                            </td>
                                            <td class="text-center">
                                                <input type="number" name="fields[<?= $f['id'] ?>][urutan]" class="form-control form-control-sm text-center" value="<?= esc($f['urutan']) ?>">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-between">
                    <a href="<?= base_url('dokumen-template') ?>" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali ke Katalog
                    </a>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="ti ti-device-floppy me-1"></i> Simpan Konfigurasi Field
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
