<?php $this->extend('layout/template') ?>
<?php $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header border-bottom-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="ti ti-mail-forward me-2 text-primary fs-2"></i>Form Surat Keluar Baru
                </h3>
            </div>
            <div class="card-status-top bg-primary"></div>

            <form action="<?= base_url('surat-keluar/store') ?>" method="POST" enctype="multipart/form-data">
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
                    <h4 class="subheader text-muted mb-3">
                        <i class="ti ti-info-circle me-1"></i>Informasi Dasar Surat
                    </h4>
                    <div class="row mb-4 g-3">
                        <div class="col-md-4">
                            <label class="form-label required mb-0">Format Surat</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-file-description text-muted"></i></span>
                                <select class="form-select <?= isset($validation) && $validation->hasError('format_surat_id') ? 'is-invalid' : '' ?>" 
                                       name="format_surat_id" id="format_surat_id" required>
                                    <option value="">-- Pilih Format --</option>
                                    <?php foreach ($format_surat_list as $f): ?>
                                        <option value="<?= $f['id'] ?>" data-template="<?= esc($f['template'], 'attr') ?>" <?= old('format_surat_id') == $f['id'] ? 'selected' : '' ?>><?= esc($f['nama']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($validation) && $validation->hasError('format_surat_id')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('format_surat_id') ?></div>
                                <?php endif; ?>
                            </div>
                            <p class="text-muted small mb-1 mt-1">
                                <i class="ti ti-settings me-1"></i>
                                Kelola format di <a href="<?= base_url('pengaturan?active_tab=format-surat') ?>">Pengaturan &rarr; Format Surat Keluar</a>
                            </p>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>">Nomor Urut</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-123 text-muted"></i></span>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nomor_urut') ? 'is-invalid' : '' ?>" 
                                       name="nomor_urut" id="nomor_urut" 
                                       value="<?= old('nomor_urut') ?>" 
                                       placeholder="036"
                                       <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('nomor_urut')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('nomor_urut') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>">Bulan</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-calendar-month text-muted"></i></span>
                                <select class="form-select <?= isset($validation) && $validation->hasError('bulan') ? 'is-invalid' : '' ?>" 
                                       name="bulan" id="bulan"
                                       <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>>
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($bulan_list as $val => $label): ?>
                                        <option value="<?= $val ?>" <?= old('bulan') == $val ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if(isset($validation) && $validation->hasError('bulan')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('bulan') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Preview Nomor Surat</label>
                            <div class="alert alert-info bg-info-lt py-2 px-3 mb-1" id="preview-container" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-eye me-2 text-info"></i>
                                    <strong class="me-2">Hasil:</strong>
                                    <span id="preview-nomor" class="fw-bold text-info">-</span>
                                </div>
                            </div>
                            <input type="hidden" name="nomor_surat" id="hidden-nomor-surat">
                            <?php if (!empty($latest_nomor_surat_keluar) && $latest_nomor_surat_keluar !== '-'): ?>
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex align-items-center small">
                                        <span class="text-muted me-1">Terakhir:</span>
                                        <span class="badge bg-success-lt text-success text-truncate" style="max-width: 200px;" title="<?= esc($latest_nomor_surat_keluar) ?>">
                                            <?= esc($latest_nomor_surat_keluar) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <label class="form-label <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?>">Tujuan Surat</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-building-bank text-muted"></i></span>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('tujuan') ? 'is-invalid' : '' ?>" 
                                       name="tujuan" id="tujuan" 
                                       value="<?= old('tujuan') ?>" 
                                       placeholder="Instansi / Orang yang dituju"
                                       <?= in_array('tujuan', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('tujuan')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tujuan') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?>">Tanggal Surat</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-calendar text-muted"></i></span>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_surat') ? 'is-invalid' : '' ?>" 
                                       name="tanggal_surat" id="tanggal_surat" 
                                       value="<?= old('tanggal_surat') ?>" 
                                       <?= in_array('tanggal_surat', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('tanggal_surat')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tanggal_surat') ?></div>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">Tertera di surat</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?>">Tanggal Kirim</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-send text-muted"></i></span>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_kirim') ? 'is-invalid' : '' ?>" 
                                       name="tanggal_kirim" id="tanggal_kirim" 
                                       value="<?= old('tanggal_kirim') ?>" 
                                       <?= in_array('tanggal_kirim', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('tanggal_kirim')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tanggal_kirim') ?></div>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted">Fisik / Ekspedisi</small>
                        </div>
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-12">
                            <label class="form-label <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?>">Perihal / Ringkasan Isi</label>
                            <textarea class="form-control <?= isset($validation) && $validation->hasError('perihal') ? 'is-invalid' : '' ?>" 
                                      name="perihal" id="perihal" 
                                      rows="3" placeholder="Tuliskan perihal atau ringkasan isi surat di sini..."
                                      <?= in_array('perihal', $required_fields ?? []) ? 'required' : '' ?>><?= old('perihal') ?></textarea>
                            <?php if(isset($validation) && $validation->hasError('perihal')): ?>
                                <div class="invalid-feedback"><?= $validation->getError('perihal') ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="hr-text my-4">
                        <i class="ti ti-paperclip me-1"></i> Dokumen & Lampiran
                    </div>

                    <!-- SECTION 2: LAMPIRAN & DOKUMEN -->
                    <div class="row mb-4 g-3 align-items-start">
                        <div class="col-md-3">
                            <label class="form-label">Jumlah Lampiran (Lembar/Berkas)</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-paperclip text-muted"></i></span>
                                <input type="number" class="form-control <?= isset($validation) && $validation->hasError('lampiran') ? 'is-invalid' : '' ?>" 
                                       name="lampiran" id="lampiran" 
                                       value="<?= old('lampiran') ?? 0 ?>" 
                                       min="0">
                            </div>
                            <?php if(isset($validation) && $validation->hasError('lampiran')): ?>
                                <div class="text-danger small mt-1"><?= $validation->getError('lampiran') ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-9">
                            <?php
                                // Tentukan metode yang diizinkan admin
                                $allowedMethods = $allowedMethods ?? ['upload', 'link'];
                                $hanyaSatuMetode = count($allowedMethods) === 1;
                            ?>

                            <?php if (!$hanyaSatuMetode): ?>
                                <label class="form-label">Tipe Penyimpanan Konsep Surat</label>
                            <?php endif; ?>
                            
                            <?php if ($hanyaSatuMetode): ?>
                                <!-- Hanya satu metode aktif, gunakan hidden input -->
                                <input type="hidden" name="tipe_penyimpanan" value="<?= in_array('upload', $allowedMethods) ? 'lokal' : 'cloud' ?>">
                            <?php else: ?>
                                <!-- Dua metode aktif, tampilkan Radio Cards -->
                                <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column flex-md-row mb-3">
                                    <?php if (in_array('upload', $allowedMethods)): ?>
                                    <label class="form-selectgroup-item flex-fill">
                                        <input type="radio" name="tipe_penyimpanan" value="lokal" class="form-selectgroup-input" <?= (old('tipe_penyimpanan') == 'lokal' || (!old('tipe_penyimpanan') && $defaultMethod == 'upload')) ? 'checked' : '' ?>>
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
                                        <input type="radio" name="tipe_penyimpanan" value="cloud" class="form-selectgroup-input" <?= (old('tipe_penyimpanan') == 'cloud' || (!old('tipe_penyimpanan') && $defaultMethod == 'cloud')) ? 'checked' : '' ?>>
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
                                <div id="area_upload" style="display: <?= (old('tipe_penyimpanan') == 'lokal' || (!old('tipe_penyimpanan') && $defaultMethod == 'upload')) ? 'block' : 'none' ?>;">
                                    <label class="form-label mb-2">Pilih File Konsep</label>
                                    <input type="file" class="form-control <?= isset($validation) && $validation->hasError('file_konsep') ? 'is-invalid' : '' ?>" 
                                           name="file_konsep" id="file_konsep" 
                                        accept=".pdf,.doc,.docx" 
                                            <?= (in_array('file_konsep', $required_fields ?? []) && $defaultMethod == 'upload') ? 'required' : '' ?>
                                            data-required-mode="lokal" data-required="<?= in_array('file_konsep', $required_fields ?? []) ? '1' : '0' ?>">
                                    <?php if(isset($validation) && $validation->hasError('file_konsep')): ?>
                                        <div class="invalid-feedback"><?= $validation->getError('file_konsep') ?></div>
                                    <?php endif; ?>
                                    <small class="form-hint mt-2 text-muted">
                                        <i class="ti ti-info-circle me-1"></i> File ini akan direview (approval) oleh pimpinan. Format didukung: PDF, DOCX. Maksimal ukuran: 5MB.
                                    </small>
                                </div>

                                <div id="area_cloud" style="display: <?= (old('tipe_penyimpanan') == 'cloud' || (!old('tipe_penyimpanan') && $defaultMethod == 'cloud')) ? 'block' : 'none' ?>;">
                                    <label class="form-label mb-2">Tautan Dokumen (URL)</label>
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
                                    
                                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mt-2 gap-2">
                                        <small class="form-hint text-muted m-0">
                                            <i class="ti ti-alert-triangle text-warning me-1"></i> Pastikan link dokumen memiliki akses <em>Comment/Edit</em>.
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1" id="tombolBukaDrive">
                                            <i class="ti ti-folder-open"></i> Buka Folder Drive Instansi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="hr-text my-4">
                        <i class="ti ti-notes me-1"></i> Keterangan Tambahan
                    </div>

                    <!-- SECTION 3: KETERANGAN -->
                    <div class="mb-4">
                        <label class="form-label">Catatan Internal <span class="text-muted fw-normal">(Opsional)</span></label>
                        <div class="input-icon">
                            <span class="input-icon-addon align-items-start mt-2"><i class="ti ti-notes text-muted"></i></span>
                            <textarea class="form-control <?= isset($validation) && $validation->hasError('keterangan') ? 'is-invalid' : '' ?>" 
                                      name="keterangan" id="keterangan" 
                                      rows="2" placeholder="Tambahkan catatan internal jika diperlukan..."><?= old('keterangan') ?></textarea>
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
                        <i class="ti ti-device-floppy fs-3"></i> Simpan Draft Surat
                    </button>
                </div>
            </form>
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
        var container = document.getElementById('preview-container');
        var hidden = document.getElementById('hidden-nomor-surat');

        if (nomorUrut && bulan && template) {
            var result = template.replace('{nomor}', nomorUrut)
                                 .replace('{bulan}', bulan)
                                 .replace('{tahun}', tahun);
            previewEl.textContent = result;
            hidden.value = result;
            container.style.display = '';
        } else {
            previewEl.textContent = '-';
            hidden.value = '';
            container.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('nomor_urut').addEventListener('input', updatePreview);
        document.getElementById('bulan').addEventListener('change', updatePreview);
        document.getElementById('format_surat_id').addEventListener('change', updatePreview);

        if (document.getElementById('nomor_urut').value || document.getElementById('bulan').value) {
            updatePreview();
        }

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
                // Ambil link folder drive dari server via AJAX
                fetch('<?= base_url("pengaturan/get-link-drive") ?>')
                    .then(function(response) { return response.json(); })
                    .then(function(data) {
                        if (data.link && data.link.trim() !== '') {
                            // Buka link di tab baru
                            window.open(data.link, '_blank');
                        } else {
                            // Tampilkan peringatan jika link belum diatur admin
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