<?php $this->extend('layout/template') ?>
<?php $this->section('content') ?>

<style>
    .form-section-card {
        border: 1px solid var(--tblr-border-color);
        border-radius: var(--tblr-border-radius);
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        background: var(--tblr-bg-surface);
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .form-section-title {
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--tblr-muted-color);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid var(--tblr-border-color);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .preview-nomor-display {
        font-family: var(--tblr-font-monospace);
        letter-spacing: 0.5px;
        word-break: break-all;
        line-height: 1.4;
        min-height: 2.25rem;
        display: flex;
        align-items: center;
    }
    .upload-dropzone {
        border: 2px dashed var(--tblr-border-color);
        border-radius: var(--tblr-border-radius);
        padding: 2.5rem 1.5rem;
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
        transform: scale(1.005);
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
        width: 2rem;
        height: 2rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.95rem;
        margin-right: 0.6rem;
        flex-shrink: 0;
    }
    .section-icon-primary { background: rgba(var(--tblr-primary-rgb, 13,110,253), 0.12); color: var(--tblr-primary); }
    .section-icon-green { background: rgba(var(--tblr-success-rgb, 25,135,84), 0.12); color: var(--tblr-success); }
    .section-icon-orange { background: rgba(var(--tblr-warning-rgb, 255,193,7), 0.12); color: var(--tblr-warning); }
    .section-icon-purple { background: rgba(130,77,223,0.12); color: #824ddf; }
    .form-selectgroup-label { padding: 0.75rem !important; }
</style>

<!-- HEADER HALAMAN -->
<div class="page-header mb-3">
    <div class="row align-items-center">
        <div class="col-auto">
            <span class="avatar avatar-lg bg-primary-lt">
                <i class="ti ti-mail-forward fs-2 text-primary"></i>
            </span>
        </div>
        <div class="col">
            <h2 class="page-title">Surat Keluar Baru</h2>
            <div class="page-pretitle text-muted">
                Lengkapi formulir di bawah ini untuk menerbitkan atau mencatat konsep surat keluar
            </div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <a href="<?= base_url('surat-keluar') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
</div>

<!-- FORM UTAMA: FULL WIDTH TANPA 2 KOLOM -->
<div class="row">
    <div class="col-12">

        <form action="<?= base_url('surat-keluar/store') ?>" method="POST" enctype="multipart/form-data" id="formSuratKeluar">
            <?= csrf_field(); ?>

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible show fade mb-3 shadow-sm" role="alert">
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

            <!-- LIVE PREVIEW BANNER -->
            <div class="card bg-primary-lt border border-primary-subtle shadow-sm mb-3">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="avatar avatar-lg bg-primary text-white rounded-3 shadow-sm flex-shrink-0">
                                <i class="ti ti-mail-forward fs-1"></i>
                            </span>
                            <div>
                                <div class="text-uppercase small fw-bold text-primary opacity-75 letter-spacing-1">
                                    <i class="ti ti-eye me-1"></i> Pratinjau Nomor Surat & Agenda
                                </div>
                                <div class="preview-nomor-display mt-1" id="preview-nomor">
                                    <span class="is-empty text-muted fs-4 fst-italic" id="preview-placeholder">Lengkapi format, nomor urut, dan bulan di bawah</span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap align-self-stretch align-self-md-center justify-content-end">
                            <?php if (!empty($latest_nomor_surat_keluar) && $latest_nomor_surat_keluar !== '-'): ?>
                                <div class="text-end me-2 d-none d-md-block">
                                    <div class="text-muted small" style="font-size: 0.75rem;">Nomor Terakhir:</div>
                                    <span class="badge bg-success-lt text-success font-monospace"><?= esc($latest_nomor_surat_keluar) ?></span>
                                </div>
                            <?php endif; ?>
                            <button type="button" class="btn btn-primary shadow-sm d-none" id="copyBtn" title="Salin nomor surat">
                                <i class="ti ti-copy me-1.5"></i> Salin Nomor
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 1: FORMAT & NOMOR SURAT -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <div>
                        <span class="section-icon section-icon-primary"><i class="ti ti-hash"></i></span>
                        Bagian 1: Format & Nomor Surat
                    </div>
                    <span class="badge bg-blue-lt">Tersusun 4 Komponen</span>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label required mb-1">Pilih Format Surat</label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('format_surat_id') ? 'is-invalid' : '' ?>"
                               name="format_surat_id" id="format_surat_id" required>
                            <option value="">-- Pilih Format Surat --</option>
                            <?php foreach ($format_surat_list as $f): ?>
                                <option value="<?= $f['id'] ?>" data-template="<?= esc($f['template'], 'attr') ?>" <?= (old('format_surat_id') == $f['id'] || (empty(old('format_surat_id')) && $f['id'] == 1)) ? 'selected' : '' ?>><?= esc($f['nama']) ?> (<?= esc($f['template']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <?php if(isset($validation) && $validation->hasError('format_surat_id')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('format_surat_id') ?></div>
                        <?php endif; ?>
                        <small class="text-muted mt-1 d-block">
                            <i class="ti ti-settings me-1"></i>
                            Kelola format di <a href="<?= base_url('pengaturan?active_tab=format-surat') ?>" target="_blank">Pengaturan &rarr; Format Surat Keluar</a>
                        </small>
                    </div>

                    <!-- Pembagian 4 Komponen Visual Sesuai Format -->
                    <div class="col-12">
                        <div class="card bg-light-subtle border p-3 p-md-4">
                            <div class="small fw-bold text-muted text-uppercase mb-3">
                                <i class="ti ti-layout-grid me-1"></i> 4 Komponen Pembentuk Nomor Surat Resmi
                            </div>
                            <div class="row g-3 align-items-start">
                                <!-- 1. Klasifikasi -->
                                <div class="col-md-3">
                                    <label class="form-label small mb-1 text-muted">1. Klasifikasi Surat</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="ti ti-tag text-muted"></i></span>
                                        <input type="text" class="form-control font-monospace fw-bold bg-white" id="segment_klasifikasi" readonly tabindex="-1" value="B-">
                                    </div>
                                    <small class="text-muted mt-1 d-block">Otomatis dari format</small>
                                </div>
                                <!-- 2. Nomor Urut -->
                                <div class="col-md-3">
                                    <label class="form-label required small mb-1 text-muted">2. Nomor Urut</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="ti ti-123 text-muted"></i></span>
                                        <input type="text" class="form-control font-monospace fw-bold text-primary <?= isset($validation) && $validation->hasError('nomor_urut') ? 'is-invalid' : '' ?>"
                                               name="nomor_urut" id="nomor_urut"
                                               value="<?= old('nomor_urut') ?? ($next_nomor_urut ?? '001') ?>"
                                               placeholder="001"
                                               required>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <small class="text-muted" id="nomor_urut_hint">Melanjutkan nomor terakhir</small>
                                        <div class="form-check m-0">
                                            <input class="form-check-input" type="checkbox" id="check_is_sisipan" style="cursor:pointer;">
                                            <label class="form-check-label small fw-bold text-warning" for="check_is_sisipan" style="cursor:pointer;">
                                                Sisipan (005-A)
                                            </label>
                                        </div>
                                    </div>
                                    <div id="sisipan_box" class="d-none mt-2 p-2 bg-yellow-lt rounded border border-warning-subtle shadow-sm">
                                        <div class="row g-1">
                                            <div class="col-7">
                                                <label class="small text-muted mb-0" style="font-size:0.75rem;">No. Induk:</label>
                                                <input type="text" class="form-control form-control-sm font-monospace fw-bold" id="sisipan_base" placeholder="005">
                                            </div>
                                            <div class="col-5">
                                                <label class="small text-muted mb-0" style="font-size:0.75rem;">Suffix:</label>
                                                <input type="text" class="form-control form-control-sm font-monospace text-uppercase fw-bold text-danger" id="sisipan_suffix" placeholder="A" maxlength="5">
                                            </div>
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size:0.7rem;"><i class="ti ti-info-circle me-1"></i>Format hasil: <span class="fw-bold font-monospace" id="sisipan_preview_text">005-A</span></small>
                                    </div>
                                </div>
                                <!-- 3. Kode Satker -->
                                <div class="col-md-3">
                                    <label class="form-label small mb-1 text-muted">3. Kode Satker</label>
                                    <div class="input-group">
                                        <span class="input-group-text font-monospace">/</span>
                                        <input type="text" class="form-control font-monospace bg-white text-muted" id="segment_satker" readonly tabindex="-1" value="<?= esc($kode_satker ?? 'MI.08.02/PP.00.4') ?>">
                                        <span class="input-group-text font-monospace">/</span>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Permanen dari Pengaturan</small>
                                </div>
                                <!-- 4. Bulan & Tahun -->
                                <div class="col-md-3">
                                    <label class="form-label required small mb-1 text-muted">4. Bulan Surat</label>
                                    <div class="input-group">
                                        <select class="form-select <?= isset($validation) && $validation->hasError('bulan') ? 'is-invalid' : '' ?>"
                                               name="bulan" id="bulan" required>
                                            <?php foreach ($bulan_list as $val => $label): ?>
                                                <option value="<?= $val ?>" <?= (old('bulan') ?? ($current_bulan ?? date('m'))) == $val ? 'selected' : '' ?>><?= $val ?> - <?= $label ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="input-group-text font-monospace text-muted">/<?= esc($tahun_anggaran) ?></span>
                                    </div>
                                    <small class="text-muted mt-1 d-block">Tahun anggaran: <?= esc($tahun_anggaran) ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="nomor_surat" id="hidden-nomor-surat">
            </div>

            <!-- SECTION 2: DETAIL SURAT -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <div>
                        <span class="section-icon section-icon-green"><i class="ti ti-pencil"></i></span>
                        Bagian 2: Detail Informasi Surat
                    </div>
                    <span class="badge bg-green-lt">Data Pengiriman & Isi</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?> mb-1">Tanggal Surat</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-calendar text-muted"></i></span>
                            <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_surat') ? 'is-invalid' : '' ?>"
                                   name="tanggal_surat" id="tanggal_surat"
                                   value="<?= old('tanggal_surat') ?>"
                                   <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?>>
                        </div>
                        <?php if(isset($validation) && $validation->hasError('tanggal_surat')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('tanggal_surat') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Tanggal tertera pada naskah surat</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?> mb-1">Tanggal Kirim</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-send text-muted"></i></span>
                            <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_kirim') ? 'is-invalid' : '' ?>"
                                   name="tanggal_kirim" id="tanggal_kirim"
                                   value="<?= old('tanggal_kirim') ?>"
                                   <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?>>
                        </div>
                        <?php if(isset($validation) && $validation->hasError('tanggal_kirim')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('tanggal_kirim') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Fisik / Ekspedisi pengiriman</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?> mb-1">Tujuan Surat</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-building-bank text-muted"></i></span>
                            <input type="text" class="form-control <?= isset($validation) && $validation->hasError('tujuan') ? 'is-invalid' : '' ?>"
                                   name="tujuan" id="tujuan"
                                   value="<?= old('tujuan') ?>"
                                   placeholder="Contoh: Kantor Kementerian Agama Kabupaten/Kota"
                                   <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?>>
                        </div>
                        <?php if(isset($validation) && $validation->hasError('tujuan')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('tujuan') ?></div>
                        <?php endif; ?>
                        <small class="text-muted">Instansi atau pihak penerima surat</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?> mb-1">Perihal / Ringkasan Isi</label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('perihal') ? 'is-invalid' : '' ?>"
                                  name="perihal" id="perihal"
                                  rows="3" placeholder="Tuliskan pokok perihal atau ringkasan isi surat secara jelas..."
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
                    <div>
                        <span class="section-icon section-icon-orange"><i class="ti ti-paperclip"></i></span>
                        Bagian 3: Dokumen & Lampiran
                    </div>
                    <span class="badge bg-warning-lt">File Konsep & Tautan</span>
                </div>

                <div class="row g-3 align-items-start">
                    <div class="col-md-3">
                        <label class="form-label mb-1">Jumlah Lampiran</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-files text-muted"></i></span>
                            <input type="number" class="form-control <?= isset($validation) && $validation->hasError('lampiran') ? 'is-invalid' : '' ?>"
                                   name="lampiran" id="lampiran"
                                   value="<?= old('lampiran') ?? 0 ?>"
                                   min="0">
                        </div>
                        <?php if(isset($validation) && $validation->hasError('lampiran')): ?>
                            <div class="invalid-feedback"><?= $validation->getError('lampiran') ?></div>
                        <?php endif; ?>
                        <small class="text-muted mt-1 d-block">Jumlah lembar / berkas fisik</small>
                    </div>

                    <?php
                        $allowedMethods = $allowedMethods ?? ['upload', 'link'];
                        $hanyaSatuMetode = count($allowedMethods) === 1;
                    ?>

                    <?php if (!$hanyaSatuMetode): ?>
                    <div class="col-md-9">
                        <label class="form-label mb-1">Pilihan Penyimpanan Dokumen</label>
                        <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column flex-sm-row">
                            <?php if (in_array('upload', $allowedMethods)): ?>
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="tipe_penyimpanan" value="lokal" class="form-selectgroup-input" <?= (old('tipe_penyimpanan') == 'lokal' || (!old('tipe_penyimpanan') && $defaultMethod == 'upload')) ? 'checked' : '' ?>>
                                <div class="form-selectgroup-label d-flex align-items-center">
                                    <span class="form-selectgroup-check"></span>
                                    <span class="ms-2"><i class="ti ti-upload me-1.5 text-primary fs-3"></i> <strong>Upload File</strong> (Server Lokal)</span>
                                </div>
                            </label>
                            <?php endif; ?>
                            <?php if (in_array('link', $allowedMethods)): ?>
                            <label class="form-selectgroup-item flex-fill">
                                <input type="radio" name="tipe_penyimpanan" value="cloud" class="form-selectgroup-input" <?= (old('tipe_penyimpanan') == 'cloud' || (!old('tipe_penyimpanan') && $defaultMethod == 'cloud')) ? 'checked' : '' ?>>
                                <div class="form-selectgroup-label d-flex align-items-center">
                                    <span class="form-selectgroup-check"></span>
                                    <span class="ms-2"><i class="ti ti-brand-google-drive me-1.5 text-green fs-3"></i> <strong>Google Drive / Cloud</strong> (Tautan Dokumen)</span>
                                </div>
                            </label>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <input type="hidden" name="tipe_penyimpanan" value="<?= in_array('upload', $allowedMethods) ? 'lokal' : 'cloud' ?>">
                    <?php endif; ?>
                </div>

                <!-- Area Dinamis Upload / Cloud Link -->
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
                            <div class="fw-bold mb-1 fs-4" id="dropzone-text">Klik atau seret file konsep surat ke sini</div>
                            <div class="text-muted small" id="dropzone-hint">Format file: PDF, DOCX, DOC &bull; Maksimal 5MB</div>
                            <?php if(isset($validation) && $validation->hasError('file_konsep')): ?>
                                <div class="text-danger small mt-2"><?= $validation->getError('file_konsep') ?></div>
                            <?php endif; ?>
                        </div>
                        <small class="form-hint text-muted mt-2 d-block">
                            <i class="ti ti-info-circle me-1"></i> File draft ini dapat ditinjau dan disetujui (approval) oleh pimpinan.
                        </small>
                    </div>

                    <!-- Cloud Area -->
                    <div id="area_cloud" style="display: <?= (old('tipe_penyimpanan') == 'cloud' || (!old('tipe_penyimpanan') && $defaultMethod == 'cloud')) ? 'block' : 'none' ?>;">
                        <label class="form-label mb-1">Tautan Dokumen (Google Drive / Cloud URL)</label>
                        <div class="input-icon mb-2">
                            <span class="input-icon-addon"><i class="ti ti-link text-muted"></i></span>
                            <input type="url" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('file_link') ? 'is-invalid' : '' ?>"
                                   name="file_link" id="file_link"
                                   placeholder="https://docs.google.com/document/d/... atau https://drive.google.com/..."
                                   value="<?= old('file_link') ?>"
                                   <?= (in_array('file_link', $required_fields ?? []) && $defaultMethod == 'cloud') ? 'required' : '' ?>
                                   data-required-mode="cloud" data-required="<?= in_array('file_link', $required_fields ?? []) ? '1' : '0' ?>">
                            <?php if(isset($validation) && $validation->hasError('file_link')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('file_link') ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mt-2">
                            <small class="text-muted">
                                <i class="ti ti-alert-triangle text-warning me-1"></i> Pastikan tautan memiliki akses <em>Viewer</em> atau <em>Comment/Edit</em> agar dapat dibuka.
                            </small>
                            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1 shadow-xs" id="tombolBukaDrive">
                                <i class="ti ti-folder-open"></i> Buka Folder Drive Instansi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: CATATAN INTERNAL -->
            <div class="form-section-card">
                <div class="form-section-title">
                    <div>
                        <span class="section-icon section-icon-purple"><i class="ti ti-notes"></i></span>
                        Bagian 4: Catatan Internal <span class="fw-normal text-muted text-capitalize">(Opsional)</span>
                    </div>
                </div>
                <textarea class="form-control <?= isset($validation) && $validation->hasError('keterangan') ? 'is-invalid' : '' ?>"
                          name="keterangan" id="keterangan"
                          rows="2" placeholder="Tambahkan catatan internal khusus untuk keperluan arsip jika diperlukan..."><?= old('keterangan') ?></textarea>
                <?php if(isset($validation) && $validation->hasError('keterangan')): ?>
                    <div class="text-danger small mt-1"><?= $validation->getError('keterangan') ?></div>
                <?php endif; ?>
            </div>

            <!-- FOOTER ACTION BAR -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                    <a href="<?= base_url('surat-keluar') ?>" class="btn btn-outline-secondary px-4 w-100 w-sm-auto">
                        <i class="ti ti-arrow-left me-1.5"></i> Kembali ke Daftar
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm w-100 w-sm-auto">
                        <i class="ti ti-device-floppy me-1.5"></i> Simpan Draft Surat Keluar
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function formatAgendaSeq(nomorUrut) {
        var mUrut = nomorUrut.match(/^0*(\d+)-([A-Za-z0-9]+)$/);
        if (mUrut) {
            var base = mUrut[1].padStart(2, '0');
            return base + mUrut[2].toUpperCase();
        }
        var n = parseInt(nomorUrut, 10);
        if (!isNaN(n)) {
            return String(n).padStart(2, '0');
        }
        return nomorUrut.length === 1 ? '0' + nomorUrut : nomorUrut;
    }

    function updatePreview() {
        var nomorUrut = document.getElementById('nomor_urut').value.trim();
        var bulan = document.getElementById('bulan').value;
        var formatSelect = document.getElementById('format_surat_id');
        var selectedOption = formatSelect.options[formatSelect.selectedIndex];
        var template = selectedOption && selectedOption.dataset.template ? selectedOption.dataset.template : '';
        var tahun = '<?= esc($tahun_anggaran, 'js') ?>';
        var previewEl = document.getElementById('preview-nomor');
        var copyBtn = document.getElementById('copyBtn');
        var hidden = document.getElementById('hidden-nomor-surat');

        // Update visual segment fields from template
        if (template) {
            var m = template.match(/^([^{]+)\{nomor\}\/(.+)\/\{bulan\}\/\{tahun\}$/);
            if (m) {
                var elKlas = document.getElementById('segment_klasifikasi');
                var elSatk = document.getElementById('segment_satker');
                if (elKlas) elKlas.value = m[1];
                if (elSatk) elSatk.value = m[2];
            }
        }

        if (nomorUrut && bulan && template) {
            var result = template.replace('{nomor}', nomorUrut)
                                 .replace('{bulan}', bulan)
                                 .replace('{tahun}', tahun);
            var agendaSeq = formatAgendaSeq(nomorUrut);
            var agendaPreview = 'OUT-' + bulan + '-' + tahun + '-' + agendaSeq;

            hidden.value = result;
            previewEl.innerHTML = '<div class="d-flex flex-wrap align-items-center gap-2 gap-md-3">' +
                                  '  <span class="text-primary fw-bold fs-2 font-monospace">' + escapeHtml(result) + '</span>' +
                                  '  <span class="badge bg-blue-lt text-blue px-2.5 py-1.5 fs-6 font-monospace shadow-xs">' +
                                  '    <i class="ti ti-bookmark me-1"></i>Agenda: ' + escapeHtml(agendaPreview) +
                                  '  </span>' +
                                  '</div>';
            copyBtn.classList.remove('d-none');
        } else {
            hidden.value = '';
            previewEl.innerHTML = '<span class="is-empty text-muted fs-4 fst-italic" id="preview-placeholder">Lengkapi format, nomor urut, dan bulan di bawah</span>';
            copyBtn.classList.add('d-none');
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function() {
        var defaultNomorUrut = '<?= esc($next_nomor_urut ?? '001', 'js') ?>';
        var nomorUrutInput = document.getElementById('nomor_urut');
        var checkSisipan = document.getElementById('check_is_sisipan');
        var sisipanBox = document.getElementById('sisipan_box');
        var sisipanBase = document.getElementById('sisipan_base');
        var sisipanSuffix = document.getElementById('sisipan_suffix');
        var sisipanPreviewText = document.getElementById('sisipan_preview_text');
        var hintEl = document.getElementById('nomor_urut_hint');

        // Deteksi awal jika nilai nomor_urut sudah ber-suffix
        if (nomorUrutInput.value && nomorUrutInput.value.indexOf('-') !== -1) {
            var parts = nomorUrutInput.value.split('-');
            sisipanBase.value = parts[0];
            sisipanSuffix.value = parts.slice(1).join('-').toUpperCase();
            checkSisipan.checked = true;
            sisipanBox.classList.remove('d-none');
            sisipanPreviewText.textContent = nomorUrutInput.value;
            hintEl.textContent = 'Mode surat sisipan aktif';
        }

        // Toggle Sisipan
        checkSisipan.addEventListener('change', function() {
            if (this.checked) {
                sisipanBox.classList.remove('d-none');
                hintEl.textContent = 'Mode surat sisipan aktif';
                if (!sisipanBase.value) {
                    var cur = nomorUrutInput.value.trim();
                    sisipanBase.value = (cur && cur.indexOf('-') === -1) ? cur : '001';
                }
                if (!sisipanSuffix.value) {
                    sisipanSuffix.value = 'A';
                }
                syncFromSisipan();
            } else {
                sisipanBox.classList.add('d-none');
                hintEl.textContent = 'Melanjutkan nomor terakhir';
                nomorUrutInput.value = defaultNomorUrut;
                updatePreview();
            }
        });

        function syncFromSisipan() {
            var base = sisipanBase.value.trim() || '001';
            var sfx = sisipanSuffix.value.trim().toUpperCase() || 'A';
            var combined = base + '-' + sfx;
            nomorUrutInput.value = combined;
            sisipanPreviewText.textContent = combined;
            updatePreview();
        }

        sisipanBase.addEventListener('input', syncFromSisipan);
        sisipanSuffix.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            syncFromSisipan();
        });

        // Direct input di nomor_urut
        nomorUrutInput.addEventListener('input', function() {
            var val = this.value.trim();
            if (val.indexOf('-') !== -1) {
                var p = val.split('-');
                sisipanBase.value = p[0];
                sisipanSuffix.value = p.slice(1).join('-').toUpperCase();
                sisipanPreviewText.textContent = val.toUpperCase();
                checkSisipan.checked = true;
                sisipanBox.classList.remove('d-none');
                hintEl.textContent = 'Mode surat sisipan aktif';
            }
            updatePreview();
        });

        // Preview listeners
        document.getElementById('bulan').addEventListener('change', updatePreview);
        document.getElementById('format_surat_id').addEventListener('change', updatePreview);

        if (nomorUrutInput.value || document.getElementById('bulan').value) {
            updatePreview();
        }

        // Auto-sync tanggal kirim = tanggal surat jika tanggal kirim belum terisi
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
                    icon.className = 'ti ti-check me-1.5';
                    setTimeout(function() { icon.className = 'ti ti-copy me-1.5'; }, 1500);
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
            dropzoneHint.textContent = sizeStr + ' • Klik untuk ganti file';
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
