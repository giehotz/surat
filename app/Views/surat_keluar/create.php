    <?php $this->extend('layout/template') ?>
<?php $this->section('content') ?>

<style>
    .form-section-card {
        border: 1px solid var(--tblr-border-color);
        border-radius: var(--tblr-border-radius);
        padding: 1.25rem;
        margin-bottom: 1rem;
        background: var(--tblr-bg-surface);
    }
    .form-section-title {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--tblr-muted-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--tblr-border-color);
    }
    .preview-sidebar {
        position: sticky;
        top: 80px;
    }
    .preview-nomor-display {
        font-family: var(--tblr-font-monospace);
        font-size: 1.15rem;
        letter-spacing: 0.5px;
        word-break: break-all;
        line-height: 1.6;
        min-height: 2.5rem;
        display: flex;
        align-items: center;
    }
    .preview-nomor-display.is-empty {
        color: var(--tblr-muted-color);
        font-style: italic;
        font-family: var(--tblr-font-sans-serif);
        font-size: 0.875rem;
    }
    .upload-dropzone {
        border: 2px dashed var(--tblr-border-color);
        border-radius: var(--tblr-border-radius);
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background: var(--tblr-bg-surface);
    }
    .upload-dropzone:hover {
        border-color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb, 13,110,253), 0.04);
    }
    .upload-dropzone.dragover {
        border-color: var(--tblr-primary);
        background: rgba(var(--tblr-primary-rgb, 13,110,253), 0.08);
        transform: scale(1.01);
    }
    .upload-dropzone.has-file {
        border-color: var(--tblr-success);
        border-style: solid;
        background: rgba(var(--tblr-success-rgb, 25,135,84), 0.04);
    }
    .upload-dropzone .dropzone-icon {
        font-size: 2.5rem;
        color: var(--tblr-muted-color);
        transition: color 0.2s;
    }
    .upload-dropzone:hover .dropzone-icon,
    .upload-dropzone.dragover .dropzone-icon {
        color: var(--tblr-primary);
    }
    .upload-dropzone.has-file .dropzone-icon {
        color: var(--tblr-success);
    }
    .section-icon {
        width: 1.75rem;
        height: 1.75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.8rem;
        margin-right: 0.5rem;
        flex-shrink: 0;
    }
    .section-icon-primary { background: rgba(var(--tblr-primary-rgb, 13,110,253), 0.12); color: var(--tblr-primary); }
    .section-icon-green { background: rgba(var(--tblr-success-rgb, 25,135,84), 0.12); color: var(--tblr-success); }
    .section-icon-orange { background: rgba(var(--tblr-warning-rgb, 255,193,7), 0.12); color: var(--tblr-warning); }
    .section-icon-purple { background: rgba(130,77,223,0.12); color: #824ddf; }
    .sidebar-info-card {
        background: var(--tblr-bg-surface);
        border: 1px solid var(--tblr-border-color);
        border-radius: var(--tblr-border-radius);
        padding: 1rem;
        margin-bottom: 0.75rem;
    }
    .form-selectgroup-label { padding: 0.75rem !important; }
    .copy-btn {
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.2s;
    }
    .copy-btn:hover { opacity: 1; }
    @media (max-width: 991.98px) {
        .preview-sidebar { position: static; margin-top: 1rem; }
    }
</style>

<div class="page-header mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <span class="avatar avatar-lg bg-primary-lt">
                <i class="ti ti-mail-forward fs-2 text-primary"></i>
            </span>
        </div>
        <div class="col">
            <h2 class="page-title">Surat Keluar Baru</h2>
            <div class="page-pretitle">
                Lengkapi data di bawah ini untuk membuat konsep surat keluar
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- KOLOM KIRI: Form Fields -->
    <div class="col-lg-8">

        <form action="<?= base_url('surat-keluar/store') ?>" method="POST" enctype="multipart/form-data" id="formSuratKeluar">
            <?= csrf_field(); ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade" role="alert">
                    <div class="d-flex">
                        <div><i class="ti ti-alert-circle icon alert-icon me-3"></i></div>
                        <div>
                            <h4 class="alert-title">Terdapat Kesalahan!</h4>
                            <div class="text-secondary"><?= session()->getFlashdata('error'); ?></div>
                            <?php if(isset($validation)): ?>
                                <ul class="mb-0 mt-2 text-danger">
                                <?php foreach($validation->getErrors() as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                </div>
            <?php endif; ?>

            <!-- SECTION 1: FORMAT & NOMOR SURAT -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <span class="section-icon section-icon-primary"><i class="ti ti-hash"></i></span>
                    Format & Nomor Surat
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label required mb-1">Format Surat</label>
                        <select class="form-select <?= isset($validation) && $validation->hasError('format_surat_id') ? 'is-invalid' : '' ?>"
                               name="format_surat_id" id="format_surat_id" required>
                            <option value="">-- Pilih Format Surat --</option>
                            <?php foreach ($format_surat_list as $f): ?>
                                <option value="<?= $f['id'] ?>" data-template="<?= esc($f['template'], 'attr') ?>" <?= old('format_surat_id') == $f['id'] ? 'selected' : '' ?>><?= esc($f['nama']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($validation) && $validation->hasError('format_surat_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('format_surat_id') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">
                            <i class="ti ti-settings me-1"></i>
                            Kelola format di <a href="<?= base_url('pengaturan?active_tab=format-surat') ?>">Pengaturan &rarr; Format Surat Keluar</a>
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?> mb-1">Nomor Urut</label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('nomor_urut') ? 'is-invalid' : '' ?>"
                               name="nomor_urut" id="nomor_urut"
                               value="<?= old('nomor_urut') ?>"
                               placeholder="036"
                               inputmode="numeric"
                               <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>>
                        <?php if(isset($validation) && $validation->hasError('nomor_urut')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('nomor_urut') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?> mb-1">Bulan</label>
                        <select class="form-select <?= isset($validation) && $validation->hasError('bulan') ? 'is-invalid' : '' ?>"
                               name="bulan" id="bulan"
                               <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>>
                            <option value="">-- Pilih Bulan --</option>
                            <?php foreach ($bulan_list as $val => $label): ?>
                                <option value="<?= $val ?>" <?= old('bulan') == $val ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($validation) && $validation->hasError('bulan')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('bulan') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <input type="hidden" name="nomor_surat" id="hidden-nomor-surat">
            </div>

            <!-- SECTION 2: DETAIL SURAT -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <span class="section-icon section-icon-green"><i class="ti ti-pencil"></i></span>
                    Detail Surat
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?> mb-1">Tujuan Surat</label>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('tujuan') ? 'is-invalid' : '' ?>"
                               name="tujuan" id="tujuan"
                               value="<?= old('tujuan') ?>"
                               placeholder="Contoh: Kantor Kementerian Agama Kota Surabaya"
                               <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?>>
                        <?php if(isset($validation) && $validation->hasError('tujuan')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('tujuan') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?> mb-1">Tanggal Surat</label>
                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_surat') ? 'is-invalid' : '' ?>"
                               name="tanggal_surat" id="tanggal_surat"
                               value="<?= old('tanggal_surat') ?>"
                               <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?>>
                        <?php if(isset($validation) && $validation->hasError('tanggal_surat')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('tanggal_surat') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Tertera di isi surat</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?> mb-1">Tanggal Kirim</label>
                        <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_kirim') ? 'is-invalid' : '' ?>"
                               name="tanggal_kirim" id="tanggal_kirim"
                               value="<?= old('tanggal_kirim') ?>"
                               <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?>>
                        <?php if(isset($validation) && $validation->hasError('tanggal_kirim')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('tanggal_kirim') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Fisik / Ekspedisi</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?> mb-1">Perihal / Ringkasan Isi</label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('perihal') ? 'is-invalid' : '' ?>"
                                  name="perihal" id="perihal"
                                  rows="3" placeholder="Tuliskan perihal atau ringkasan isi surat di sini..."
                                  <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?>><?= old('perihal') ?></textarea>
                        <?php if(isset($validation) && $validation->hasError('perihal')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('perihal') ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: DOKUMEN & LAMPIRAN -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <span class="section-icon section-icon-orange"><i class="ti ti-paperclip"></i></span>
                    Dokumen & Lampiran
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label mb-1">Jumlah Lampiran</label>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('lampiran') ? 'is-invalid' : '' ?>"
                               name="lampiran" id="lampiran"
                               value="<?= old('lampiran') ?? 0 ?>"
                               min="0">
                        <?php if(isset($validation) && $validation->hasError('lampiran')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('lampiran') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Lembar / berkas</small>
                    </div>

                    <?php
                        $allowedMethods = $allowedMethods ?? ['upload', 'link'];
                        $hanyaSatuMetode = count($allowedMethods) === 1;
                    ?>

                    <?php if (!$hanyaSatuMetode): ?>
                    <div class="col-md-8">
                        <label class="form-label mb-1">Tipe Penyimpanan</label>
                        <div class="form-selectgroup form-selectgroup-boxes d-flex">
                            <?php if (in_array('upload', $allowedMethods)): ?>
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="tipe_penyimpanan" value="lokal" class="form-selectgroup-input" <?= (old('tipe_penyimpanan') == 'lokal' || (!old('tipe_penyimpanan') && $defaultMethod == 'upload')) ? 'checked' : '' ?>>
                                <div class="form-selectgroup-label d-flex align-items-center">
                                    <span class="form-selectgroup-check"></span>
                                    <span class="ms-2"><i class="ti ti-upload me-1 text-primary"></i> Upload File</span>
                                </div>
                            </label>
                            <?php endif; ?>
                            <?php if (in_array('link', $allowedMethods)): ?>
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="tipe_penyimpanan" value="cloud" class="form-selectgroup-input" <?= (old('tipe_penyimpanan') == 'cloud' || (!old('tipe_penyimpanan') && $defaultMethod == 'cloud')) ? 'checked' : '' ?>>
                                <div class="form-selectgroup-label d-flex align-items-center">
                                    <span class="form-selectgroup-check"></span>
                                    <span class="ms-2"><i class="ti ti-link me-1 text-green"></i> Link Cloud</span>
                                </div>
                            </label>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="tipe_penyimpanan" value="<?= in_array('upload', $allowedMethods) ? 'lokal' : 'cloud' ?>">
                    <?php endif; ?>
                </div>

                <!-- Area Dinamis -->
                <div class="mt-3">
                    <!-- Upload Area -->
                    <div id="area_upload" style="display: <?= (old('tipe_penyimpanan') == 'lokal' || (!old('tipe_penyimpanan') && $defaultMethod == 'upload')) ? 'block' : 'none' ?>;">
                        <div class="upload-dropzone" id="dropzone">
                            <input type="file" name="file_konsep" id="file_konsep"
                                   accept=".pdf,.doc,.docx" class="d-none"
                                   <?= (in_array('file_konsep', $required_fields ?? []) && $defaultMethod == 'upload') ? 'required' : '' ?>
                                   data-required-mode="lokal" data-required="<?= in_array('file_konsep', $required_fields ?? []) ? '1' : '0' ?>">
                            <div class="dropzone-icon mb-2">
                                <i class="ti ti-cloud-upload"></i>
                            </div>
                            <div class="fw-bold mb-1" id="dropzone-text">Klik atau seret file ke sini</div>
                            <div class="text-muted small" id="dropzone-hint">Format: PDF, DOCX &bull; Maksimal 5MB</div>
                            <?php if(isset($validation) && $validation->hasError('file_konsep')): ?>
                                <div class="text-danger small mt-2"><?= $validation->getError('file_konsep') ?></div>
                            <?php endif; ?>
                        </div>
                        <small class="form-hint text-muted mt-2 d-block">
                            <i class="ti ti-info-circle me-1"></i> File ini akan direview (approval) oleh pimpinan.
                        </small>
                    </div>

                    <!-- Cloud Area -->
                    <div id="area_cloud" style="display: <?= (old('tipe_penyimpanan') == 'cloud' || (!old('tipe_penyimpanan') && $defaultMethod == 'cloud')) ? 'block' : 'none' ?>;">
                        <label class="form-label mb-1">Tautan Dokumen (URL)</label>
                        <div class="input-icon mb-2">
                            <span class="input-icon-addon"><i class="ti ti-link text-muted"></i></span>
                            <input type="url" class="form-control <?= isset($validation) && $validation->hasError('file_link') ? 'is-invalid' : '' ?>"
                                   name="file_link" id="file_link"
                                   placeholder="https://docs.google.com/document/d/..."
                                   value="<?= old('file_link') ?>"
                                   <?= (in_array('file_link', $required_fields ?? []) && $defaultMethod == 'cloud') ? 'required' : '' ?>
                                   data-required-mode="cloud" data-required="<?= in_array('file_link', $required_fields ?? []) ? '1' : '0' ?>">
                            <?php if(isset($validation) && $validation->hasError('file_link')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('file_link') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <small class="text-muted">
                                <i class="ti ti-alert-triangle text-warning me-1"></i> Pastikan link memiliki akses <em>Comment/Edit</em>.
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" id="tombolBukaDrive">
                                <i class="ti ti-folder-open"></i> Buka Folder Drive
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: CATATAN -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <span class="section-icon section-icon-purple"><i class="ti ti-notes"></i></span>
                    Catatan Internal <span class="fw-normal text-capitalize">(Opsional)</span>
                </div>
                <textarea class="form-control <?= isset($validation) && $validation->hasError('keterangan') ? 'is-invalid' : '' ?>"
                          name="keterangan" id="keterangan"
                          rows="2" placeholder="Tambahkan catatan internal jika diperlukan..."><?= old('keterangan') ?></textarea>
                <?php if(isset($validation) && $validation->hasError('keterangan')): ?>
                    <div class="text-danger small mt-1"><?= $validation->getError('keterangan') ?></div>
                <?php endif; ?>
            </div>

            <!-- FOOTER ACTIONS -->
            <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
                <a href="<?= base_url('surat-keluar') ?>" class="btn btn-ghost-secondary d-flex align-items-center gap-1">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center gap-2 px-4">
                    <i class="ti ti-device-floppy"></i> Simpan Draft Surat
                </button>
            </div>
        </form>

    </div>

    <!-- KOLOM KANAN: Sidebar Preview -->
    <div class="col-lg-4">
        <div class="preview-sidebar">

            <!-- Preview Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-status-top bg-primary"></div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h4 class="card-title mb-0 fs-5">
                            <i class="ti ti-eye me-1 text-primary"></i> Preview
                        </h4>
                        <button type="button" class="btn btn-sm btn-ghost-secondary copy-btn d-none" id="copyBtn" title="Salin nomor surat">
                            <i class="ti ti-copy"></i>
                        </button>
                    </div>

                    <div class="preview-nomor-display" id="preview-nomor">
                        <span class="is-empty" id="preview-placeholder">Isi format, nomor, dan bulan untuk melihat preview</span>
                    </div>

                    <?php if (!empty($latest_nomor_surat_keluar) && $latest_nomor_surat_keluar !== '-'): ?>
                        <div class="mt-3 pt-3 border-top">
                            <div class="text-muted small mb-1">Nomor terakhir digunakan:</div>
                            <span class="badge bg-success-lt text-success" title="<?= esc($latest_nomor_surat_keluar) ?>">
                                <?= esc($latest_nomor_surat_keluar) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info Card -->
            <div class="sidebar-info-card">
                <div class="d-flex align-items-start gap-2">
                    <i class="ti ti-info-circle text-blue mt-1"></i>
                    <div class="small text-muted">
                        <strong class="text-secondary d-block mb-1">Tentang Nomor Surat</strong>
                        Nomor surat digenerate otomatis berdasarkan format, nomor urut, dan bulan yang dipilih.
                        Pastikan nomor urut belum digunakan pada bulan yang sama.
                    </div>
                </div>
            </div>

            <!-- Ringkasan Form -->
            <div class="card border-0 shadow-sm" id="ringkasan-card" style="display: none;">
                <div class="card-body">
                    <h5 class="card-title fs-6 mb-3">
                        <i class="ti ti-list-check me-1 text-green"></i> Ringkasan
                    </h5>
                    <div class="small">
                        <div class="d-flex justify-content-between py-1 border-bottom" id="ring-tujuan">
                            <span class="text-muted">Tujuan</span>
                            <span class="fw-semibold text-end" id="val-tujuan">-</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom" id="ring-tgl">
                            <span class="text-muted">Tanggal</span>
                            <span class="fw-semibold" id="val-tanggal">-</span>
                        </div>
                        <div class="d-flex justify-content-between py-1 border-bottom" id="ring-lampiran">
                            <span class="text-muted">Lampiran</span>
                            <span class="fw-semibold" id="val-lampiran">0 lembar</span>
                        </div>
                        <div class="d-flex justify-content-between py-1" id="ring-perihal">
                            <span class="text-muted">Perihal</span>
                            <span class="fw-semibold text-end" id="val-perihal" style="max-width: 60%;">-</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function updatePreview() {
        var nomorUrut = document.getElementById('nomor_urut').value.trim();
        var bulan = document.getElementById('bulan').value;
        var formatSelect = document.getElementById('format_surat_id');
        var selectedOption = formatSelect.options[formatSelect.selectedIndex];
        var template = selectedOption && selectedOption.dataset.template ? selectedOption.dataset.template : '';
        var tahun = '<?= esc($tahun_anggaran, 'js') ?>';
        var previewEl = document.getElementById('preview-nomor');
        var placeholder = document.getElementById('preview-placeholder');
        var copyBtn = document.getElementById('copyBtn');
        var hidden = document.getElementById('hidden-nomor-surat');

        if (nomorUrut && bulan && template) {
            var result = template.replace('{nomor}', nomorUrut)
                                 .replace('{bulan}', bulan)
                                 .replace('{tahun}', tahun);
            hidden.value = result;
            previewEl.innerHTML = '<span class="text-primary fw-bold">' + escapeHtml(result) + '</span>';
            copyBtn.classList.remove('d-none');
        } else {
            hidden.value = '';
            previewEl.innerHTML = '<span class="is-empty" id="preview-placeholder">Isi format, nomor, dan bulan untuk melihat preview</span>';
            copyBtn.classList.add('d-none');
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    function updateRingkasan() {
        var card = document.getElementById('ringkasan-card');
        var tujuan = document.getElementById('tujuan').value.trim();
        var tglSurat = document.getElementById('tanggal_surat').value;
        var lampiran = document.getElementById('lampiran').value;
        var perihal = document.getElementById('perihal').value.trim();

        if (tujuan || tglSurat || lampiran > 0 || perihal) {
            card.style.display = '';

            document.getElementById('val-tujuan').textContent = tujuan || '-';
            document.getElementById('val-tanggal').textContent = tglSurat ? new Date(tglSurat).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
            document.getElementById('val-lampiran').textContent = (lampiran || 0) + ' lembar';
            document.getElementById('val-perihal').textContent = perihal ? (perihal.length > 40 ? perihal.substring(0, 40) + '...' : perihal) : '-';
        } else {
            card.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Preview listeners
        document.getElementById('nomor_urut').addEventListener('input', updatePreview);
        document.getElementById('bulan').addEventListener('change', updatePreview);
        document.getElementById('format_surat_id').addEventListener('change', updatePreview);

        if (document.getElementById('nomor_urut').value || document.getElementById('bulan').value) {
            updatePreview();
        }

        // Ringkasan listeners
        document.getElementById('tujuan').addEventListener('input', updateRingkasan);
        document.getElementById('tanggal_surat').addEventListener('change', updateRingkasan);
        document.getElementById('lampiran').addEventListener('input', updateRingkasan);
        document.getElementById('perihal').addEventListener('input', updateRingkasan);
        updateRingkasan();

        // Auto-sync tanggal kirim = tanggal surat
        document.getElementById('tanggal_surat').addEventListener('change', function() {
            var tglKirim = document.getElementById('tanggal_kirim');
            if (!tglKirim.value) {
                tglKirim.value = this.value;
            }
        });

        // Copy to clipboard
        document.getElementById('copyBtn').addEventListener('click', function() {
            var val = document.getElementById('hidden-nomor-surat').value;
            if (val) {
                navigator.clipboard.writeText(val).then(function() {
                    var icon = document.querySelector('#copyBtn i');
                    icon.className = 'ti ti-check';
                    setTimeout(function() { icon.className = 'ti ti-copy'; }, 1500);
                });
            }
        });

        // === Logika Tipe Penyimpanan ===
        var radios = document.querySelectorAll('input[name="tipe_penyimpanan"][type="radio"]');
        var hiddenInput = document.querySelector('input[name="tipe_penyimpanan"][type="hidden"]');
        var areaUpload = document.getElementById('area_upload');
        var areaCloud = document.getElementById('area_cloud');

        function toggleArea() {
            var checkedRadio = document.querySelector('input[name="tipe_penyimpanan"]:checked');
            var selectedValue = checkedRadio ? checkedRadio.value : (hiddenInput ? hiddenInput.value : 'lokal');

            if (selectedValue === 'cloud') {
                if (areaUpload) areaUpload.style.display = 'none';
                if (areaCloud) areaCloud.style.display = 'block';
            } else {
                if (areaCloud) areaCloud.style.display = 'none';
                if (areaUpload) areaUpload.style.display = 'block';
            }

            document.querySelectorAll('[data-required-mode]').forEach(function(el) {
                var needsRequired = el.dataset.required === '1' && el.dataset.requiredMode === selectedValue;
                if (needsRequired) {
                    el.setAttribute('required', '');
                } else {
                    el.removeAttribute('required');
                }
            });
        }

        toggleArea();
        radios.forEach(function(radio) {
            radio.addEventListener('change', toggleArea);
        });

        // === Drag & Drop Upload ===
        var dropzone = document.getElementById('dropzone');
        var fileInput = document.getElementById('file_konsep');
        var dropzoneText = document.getElementById('dropzone-text');
        var dropzoneHint = document.getElementById('dropzone-hint');

        dropzone.addEventListener('click', function() {
            fileInput.click();
        });

        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                handleFileSelect(this.files[0]);
            }
        });

        function handleFileSelect(file) {
            dropzone.classList.add('has-file');
            var sizeKB = (file.size / 1024).toFixed(1);
            var sizeStr = sizeKB > 1024 ? (file.size / (1024 * 1024)).toFixed(2) + ' MB' : sizeKB + ' KB';
            dropzoneText.innerHTML = '<i class="ti ti-file-check text-green me-1"></i> ' + escapeHtml(file.name);
            dropzoneHint.textContent = sizeStr + ' &bull; Klik untuk ganti file';
        }

        // === Tombol Buka Folder Google Drive ===
        var tombolDrive = document.getElementById('tombolBukaDrive');
        if (tombolDrive) {
            tombolDrive.addEventListener('click', function() {
                fetch('<?= base_url("pengaturan/get-link-drive") ?>')
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.link && data.link.trim() !== '') {
                            window.open(data.link, '_blank');
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Link Belum Tersedia',
                                text: 'Folder Google Drive belum ditentukan oleh admin. Silakan hubungi admin untuk mengatur link folder.',
                                confirmButtonText: 'Mengerti'
                            });
                        }
                    })
                    .catch(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Memuat',
                            text: 'Terjadi kesalahan saat mengambil data dari server. Coba lagi nanti.',
                            confirmButtonText: 'OK'
                        });
                    });
            });
        }
    });
</script>
<?= $this->endSection() ?>
