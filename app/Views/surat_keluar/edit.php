<?php $this->extend('layout/template') ?>
<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="ti ti-edit me-2 text-primary fs-2"></i>Edit Surat Keluar
                </h3>
                <span class="badge bg-primary-lt">ID: <?= esc($surat['id']) ?></span>
            </div>
            <div class="card-status-top bg-primary"></div>

            <form action="<?= base_url('surat-keluar/update/' . $surat['id']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="card-body">
                    
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

                    <!-- SECTION 1: DATA UTAMA -->
                    <h4 class="subheader text-muted mb-3">Informasi Dasar Surat</h4>
                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor Agenda (Otomatis)</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-hash text-muted"></i></span>
                                <input type="text" class="form-control bg-light text-muted fw-bold font-monospace fs-5" name="nomor_agenda" id="nomor_agenda" value="<?= esc($surat['nomor_agenda']) ?>" readonly tabindex="-1" data-bs-toggle="tooltip" title="Nomor agenda dibuat otomatis oleh sistem dan tidak dapat diubah.">
                            </div>
                            <small class="text-muted">Format: <code>OUT-{bulan}-{tahun}-{nomor}</code></small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required mb-1">Pilih Format Surat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-file-description text-muted"></i></span>
                                <select class="form-select <?= isset($validation) && $validation->hasError('format_surat_id') ? 'is-invalid' : '' ?>" 
                                       name="format_surat_id" id="format_surat_id" required>
                                    <option value="">-- Pilih Format --</option>
                                    <?php foreach ($format_surat_list as $f): ?>
                                        <option value="<?= $f['id'] ?>" data-template="<?= esc($f['template'], 'attr') ?>" <?= (old('format_surat_id') ?? $surat['format_surat_id']) == $f['id'] ? 'selected' : '' ?>><?= esc($f['nama']) ?> (<?= esc($f['template']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($validation) && $validation->hasError('format_surat_id')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('format_surat_id') ?></div>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">
                                <i class="ti ti-settings me-1"></i>
                                Kelola format di <a href="<?= base_url('pengaturan?active_tab=format-surat') ?>" target="_blank">Pengaturan &rarr; Format Surat Keluar</a>
                            </small>
                        </div>

                        <!-- 4 Komponen Pembentuk Nomor Surat -->
                        <div class="col-12">
                            <div class="card bg-light-subtle border p-3">
                                <div class="small fw-bold text-muted text-uppercase mb-2">
                                    <i class="ti ti-layout-grid me-1"></i> 4 Komponen Pembentuk Nomor Surat
                                </div>
                                <div class="row g-2 align-items-center">
                                    <!-- 1. Klasifikasi -->
                                    <div class="col-md-3">
                                        <label class="form-label small mb-1 text-muted">1. Klasifikasi Surat</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="ti ti-tag text-muted"></i></span>
                                            <input type="text" class="form-control font-monospace fw-bold bg-white" id="segment_klasifikasi" readonly tabindex="-1" value="B-">
                                        </div>
                                        <small class="text-muted">Dari format terpilih</small>
                                    </div>
                                    <!-- 2. Nomor Urut -->
                                    <div class="col-md-3">
                                        <label class="form-label required small mb-1 text-muted">2. Nomor Urut</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i class="ti ti-123 text-muted"></i></span>
                                            <input type="text" class="form-control form-control-lg font-monospace fw-bold text-primary <?= isset($validation) && $validation->hasError('nomor_urut') ? 'is-invalid' : '' ?>" 
                                                   name="nomor_urut" id="nomor_urut" 
                                                   value="<?= old('nomor_urut') ?? $surat['nomor_urut'] ?>" 
                                                   placeholder="036"
                                                   required>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-1">
                                            <small class="text-muted" id="nomor_urut_hint">Nomor urut surat</small>
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
                                        <small class="text-muted">Permanen dari Pengaturan</small>
                                    </div>
                                    <!-- 4. Bulan & Tahun -->
                                    <div class="col-md-3">
                                        <label class="form-label required small mb-1 text-muted">4. Bulan Surat</label>
                                        <div class="input-group">
                                            <select class="form-select <?= isset($validation) && $validation->hasError('bulan') ? 'is-invalid' : '' ?>" 
                                                   name="bulan" id="bulan" required>
                                                <?php foreach ($bulan_list as $val => $label): ?>
                                                    <option value="<?= $val ?>" <?= (old('bulan') ?? $surat['bulan']) == $val ? 'selected' : '' ?>><?= $val ?> - <?= $label ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <span class="input-group-text font-monospace text-muted">/<?= esc($tahun_anggaran) ?></span>
                                        </div>
                                        <small class="text-muted">Tahun anggaran: <?= esc($tahun_anggaran) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Preview Nomor Surat</label>
                            <div class="alert alert-info bg-info-lt py-2 px-3 mb-1" id="preview-container">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-eye me-2 text-info fs-4"></i>
                                    <div>
                                        <strong class="me-2 text-secondary">Hasil Nomor Surat:</strong>
                                        <span id="preview-nomor" class="fw-bold text-primary fs-5"></span>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="nomor_surat" id="hidden-nomor-surat" value="<?= old('nomor_surat') ?? $surat['nomor_surat'] ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?>">
                                Tanggal Surat (Tertera di surat)
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-calendar text-muted"></i></span>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_surat') ? 'is-invalid' : '' ?>" 
                                       name="tanggal_surat" id="tanggal_surat" 
                                       value="<?= old('tanggal_surat') ?? $surat['tanggal_surat'] ?>" 
                                       <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('tanggal_surat')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tanggal_surat') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?>">
                                Tanggal Kirim (Fisik / Ekspedisi)
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-send text-muted"></i></span>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_kirim') ? 'is-invalid' : '' ?>" 
                                       name="tanggal_kirim" id="tanggal_kirim" 
                                       value="<?= old('tanggal_kirim') ?? $surat['tanggal_kirim'] ?>" 
                                       <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('tanggal_kirim')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tanggal_kirim') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?>">
                                Tujuan Surat
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-building-bank text-muted"></i></span>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('tujuan') ? 'is-invalid' : '' ?>" 
                                       name="tujuan" id="tujuan" 
                                       value="<?= old('tujuan') ?? $surat['tujuan'] ?>" 
                                       <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('tujuan')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tujuan') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?>">
                                Perihal / Ringkasan Isi
                            </label>
                            <textarea class="form-control <?= isset($validation) && $validation->hasError('perihal') ? 'is-invalid' : '' ?>" 
                                      name="perihal" id="perihal" 
                                      rows="3" placeholder="Tuliskan perihal atau ringkasan isi surat di sini..."
                                      <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?>><?= old('perihal') ?? $surat['perihal'] ?></textarea>
                            <?php if(isset($validation) && $validation->hasError('perihal')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('perihal') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="hr-text my-4">Dokumen & Lampiran</div>

                    <!-- SECTION 2: LAMPIRAN & DOKUMEN -->
                    <div class="row mb-4 g-3 align-items-start">
                        <div class="col-md-3">
                            <label class="form-label">Jumlah Lampiran (Lembar/Berkas)</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-paperclip text-muted"></i></span>
                                <input type="number" class="form-control <?= isset($validation) && $validation->hasError('lampiran') ? 'is-invalid' : '' ?>" 
                                       name="lampiran" id="lampiran" 
                                       value="<?= old('lampiran') ?? $surat['lampiran'] ?>" 
                                       min="0">
                            </div>
                            <?php if(isset($validation) && $validation->hasError('lampiran')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('lampiran') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-9">
                            <?php
                                $allowedMethods = $allowedMethods ?? ['upload', 'link'];
                                $hanyaSatuMetode = count($allowedMethods) === 1;
                            ?>

                            <?php if (!$hanyaSatuMetode): ?>
                                <label class="form-label">Tipe Penyimpanan Konsep Surat</label>
                            <?php endif; ?>
                            
                            <?php if ($hanyaSatuMetode): ?>
                                <input type="hidden" name="tipe_penyimpanan" value="<?= in_array('upload', $allowedMethods) ? 'lokal' : 'cloud' ?>">
                            <?php else: ?>
                                <!-- Dua metode aktif, tampilkan Radio Cards -->
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column flex-md-row mb-3">
                                    <?php if (in_array('upload', $allowedMethods)): ?>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="tipe_penyimpanan" value="lokal" class="form-selectgroup-input" <?= $surat['tipe_penyimpanan'] == 'lokal' ? 'checked' : '' ?>>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-upload text-primary fs-3 me-2"></i> 
                                                    <span class="fw-bold">Upload File ke Server Lokal</span>
                                                </div>
                                                <div class="text-muted small mt-1">Unggah file fisik langsung ke sistem.</div>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endif; ?>
                                    <?php if (in_array('link', $allowedMethods)): ?>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="tipe_penyimpanan" value="cloud" class="form-selectgroup-input" <?= $surat['tipe_penyimpanan'] == 'cloud' ? 'checked' : '' ?>>
                                        <div class="form-selectgroup-label d-flex align-items-center p-3">
                                            <div class="me-3">
                                                <span class="form-selectgroup-check"></span>
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center">
                                                    <i class="ti ti-brand-google-drive text-success fs-3 me-2"></i> 
                                                    <span class="fw-bold">Link Cloud (Google Drive, dll)</span>
                                                </div>
                                                <div class="text-muted small mt-1">Gunakan URL tautan dokumen online.</div>
                                            </div>
                                        </div>
                                    </label>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Area Dinamis (Background ditekankan agar terlihat sebagai Sub-Form) -->
                            <div class="p-3 bg-light-subtle border rounded-3">
                                <div id="area_upload" style="display: <?= $surat['tipe_penyimpanan'] == 'lokal' ? 'block' : 'none' ?>;">
                                    
                                    <?php if($surat['tipe_penyimpanan'] == 'lokal' && $surat['file_path']): ?>
                                        <div class="mb-3">
                                            <label class="form-label text-muted mb-2">File Konsep Saat Ini</label>
                                            <div class="d-flex align-items-center bg-white border rounded p-2 shadow-sm">
                                                <div class="bg-primary-lt rounded p-2 me-3">
                                                    <i class="ti ti-file-text text-primary fs-2"></i>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="fw-bold text-truncate"><a href="<?= base_url($surat['file_path']) ?>" target="_blank" class="text-reset text-decoration-none hover-primary"><?= $surat['file_name'] ?></a></div>
                                                    <div class="text-muted small"><?= esc($surat['file_size']) > 0 ? round(esc($surat['file_size'])/1024, 2).' MB' : esc($surat['file_size']).' KB' ?></div>
                                                </div>
                                                <div class="ms-2">
                                                    <a href="<?= base_url($surat['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Lihat/Unduh File"><i class="ti ti-download"></i></a>
                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <label class="form-label mb-2"><?= ($surat['tipe_penyimpanan'] == 'lokal' && $surat['file_path']) ? 'Ganti File Konsep Baru (Opsional)' : 'Pilih File Konsep' ?></label>
                                    <input type="file" class="form-control <?= isset($validation) && $validation->hasError('file_konsep') ? 'is-invalid' : '' ?>" 
                                           name="file_konsep" id="file_konsep" 
                                            accept=".pdf,.doc,.docx" 
                                            <?= (in_array('file_konsep', $required_fields ?? []) && empty($surat['file_path']) && $surat['tipe_penyimpanan'] == 'lokal') ? 'required' : '' ?>
                                            data-required-mode="lokal" data-required="<?= (in_array('file_konsep', $required_fields ?? []) && empty($surat['file_path'])) ? '1' : '0' ?>">
                                    <?php if(isset($validation) && $validation->hasError('file_konsep')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('file_konsep') ?></div>
                                    <?php endif; ?>
                                    <small class="form-hint mt-2 text-muted">
                                        <i class="ti ti-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengganti file. Format didukung: PDF, DOCX. Maksimal ukuran: 5MB.
                                    </small>
                                </div>

                                <div id="area_cloud" style="display: <?= $surat['tipe_penyimpanan'] == 'cloud' ? 'block' : 'none' ?>;">
                                    <label class="form-label mb-2">Tautan Dokumen (URL)</label>
                                    <div class="input-icon mb-2">
                                        <span class="input-icon-addon"><i class="ti ti-link text-muted"></i></span>
                                        <input type="url" class="form-control <?= isset($validation) && $validation->hasError('file_link') ? 'is-invalid' : '' ?>" 
                                               name="file_link" id="file_link" 
                                               placeholder="https://docs.google.com/document/d/..." 
                                                value="<?= old('file_link') ?? ($surat['tipe_penyimpanan'] == 'cloud' ? $surat['file_link'] : '') ?>" 
                                                <?= (in_array('file_link', $required_fields ?? []) && $surat['tipe_penyimpanan'] == 'cloud') ? 'required' : '' ?>
                                                data-required-mode="cloud" data-required="<?= in_array('file_link', $required_fields ?? []) ? '1' : '0' ?>">
                                        <?php if(isset($validation) && $validation->hasError('file_link')): ?>
                                            <div class="invalid-feedback"><?= $validation->getError('file_link') ?></div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mt-2 gap-2">
                                        <small class="form-hint text-muted m-0">
                                            <i class="ti ti-alert-triangle text-warning me-1"></i> Pastikan link dokumen memiliki akses <em>Comment/Edit</em>.
                                        </small>
                                        <div class="btn-list">
                                            <?php if($surat['tipe_penyimpanan'] == 'cloud' && $surat['file_link']): ?>
                                                <a href="<?= $surat['file_link'] ?>" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                                    <i class="ti ti-external-link"></i> Buka Link Saat Ini
                                                </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" id="tombolBukaDrive">
                                                <i class="ti ti-folder-open"></i> Buka Folder Drive Instansi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hr-text my-4">Informasi Tambahan</div>

                    <!-- SECTION 3: KETERANGAN -->
                    <div class="mb-2">
                        <label class="form-label">Keterangan / Catatan Ekstra (Opsional)</label>
                        <div class="input-icon">
                            <span class="input-icon-addon align-items-start mt-2"><i class="ti ti-notes text-muted"></i></span>
                            <textarea class="form-control <?= isset($validation) && $validation->hasError('keterangan') ? 'is-invalid' : '' ?>" 
                                      name="keterangan" id="keterangan" 
                                      rows="2" placeholder="Tambahkan catatan jika diperlukan..."><?= old('keterangan') ?? $surat['keterangan'] ?></textarea>
                        </div>
                        <?php if(isset($validation) && $validation->hasError('keterangan')): ?>
                            <div class="text-danger small mt-1"><?= $validation->getError('keterangan') ?></div>
                        <?php endif; ?>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer bg-light-subtle d-flex justify-content-between align-items-center py-3">
                    <a href="<?= base_url('surat-keluar') ?>" class="btn btn-ghost-secondary d-flex align-items-center gap-1">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-4">
                        <i class="ti ti-device-floppy fs-3"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
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
        var container = document.getElementById('preview-container');
        var hidden = document.getElementById('hidden-nomor-surat');

        // Update visual segment fields from template
        if (template) {
            var m = template.match(/^([^{]+)\{nomor\}\/(.+)\/\{bulan\}\/\{tahun\}$/);
            if (m) {
                var klasEl = document.getElementById('segment_klasifikasi');
                var satkEl = document.getElementById('segment_satker');
                if (klasEl) klasEl.value = m[1];
                if (satkEl) satkEl.value = m[2];
            }
        }

        if (nomorUrut && bulan && template) {
            var result = template.replace('{nomor}', nomorUrut)
                                 .replace('{bulan}', bulan)
                                 .replace('{tahun}', tahun);
            var agendaSeq = formatAgendaSeq(nomorUrut);
            var agendaPreview = 'OUT-' + bulan + '-' + tahun + '-' + agendaSeq;

            previewEl.innerHTML = '<span class="text-primary fw-bold fs-5">' + escapeHtml(result) + '</span>' +
                                  '<span class="text-muted small ms-3"><i class="ti ti-bookmark me-1"></i>Agenda: <strong>' + escapeHtml(agendaPreview) + '</strong></span>';
            hidden.value = result;
            container.style.display = '';
        } else {
            previewEl.textContent = hidden.value || '-';
            if (!hidden.value) container.style.display = 'none';
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function() {
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
                hintEl.textContent = 'Nomor urut surat';
                var cur = nomorUrutInput.value.trim();
                if (cur.indexOf('-') !== -1) {
                    nomorUrutInput.value = cur.split('-')[0];
                }
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

        document.getElementById('bulan').addEventListener('change', updatePreview);
        document.getElementById('format_surat_id').addEventListener('change', updatePreview);

        updatePreview();

        // === Logika Tipe Penyimpanan (Radio Button atau Hidden Input) ===
        const radios = document.querySelectorAll('input[name="tipe_penyimpanan"][type="radio"]');
        const hiddenInput = document.querySelector('input[name="tipe_penyimpanan"][type="hidden"]');
        const areaUpload = document.getElementById('area_upload');
        const areaCloud = document.getElementById('area_cloud');

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

            // Toggle required attribute on conditional inputs
            document.querySelectorAll('[data-required-mode]').forEach(function(el) {
                var needsRequired = el.dataset.required === '1' && el.dataset.requiredMode === selectedValue;
                if (needsRequired) {
                    el.setAttribute('required', '');
                } else {
                    el.removeAttribute('required');
                }
            });
        }

        // Jalankan saat pertama load
        toggleArea();

        // Dengarkan perubahan pada radio button (jika ada)
        radios.forEach(radio => {
            radio.addEventListener('change', toggleArea);
        });

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