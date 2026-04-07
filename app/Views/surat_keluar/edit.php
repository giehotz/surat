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
                                <input type="text" class="form-control bg-light text-muted fw-bold" name="nomor_agenda" id="nomor_agenda" value="<?= esc($surat['nomor_agenda']) ?>" readonly tabindex="-1" data-bs-toggle="tooltip" title="Nomor agenda dibuat otomatis oleh sistem dan tidak dapat diubah.">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>">
                                Nomor Surat
                            </label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="ti ti-123 text-muted"></i></span>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nomor_surat') ? 'is-invalid' : '' ?>" 
                                       name="nomor_surat" id="nomor_surat" 
                                       value="<?= old('nomor_surat') ?? $surat['nomor_surat'] ?>" 
                                       <?= in_array('nomor_surat', $required_fields ?? []) ? 'required' : '' ?>>
                                <?php if(isset($validation) && $validation->hasError('nomor_surat')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('nomor_surat') ?></div>
                                <?php endif; ?>
                            </div>
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
                                                    <div class="text-muted small"><?= $surat['file_size'] > 0 ? round($surat['file_size']/1024, 2).' MB' : $surat['file_size'].' KB' ?></div>
                                                </div>
                                                <div class="ms-2">
                                                    <a href="<?= base_url($surat['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Lihat/Unduh File"><i class="ti ti-download"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <label class="form-label mb-2"><?= ($surat['tipe_penyimpanan'] == 'lokal' && $surat['file_path']) ? 'Ganti File Konsep Baru (Opsional)' : 'Pilih File Konsep' ?></label>
                                    <input type="file" class="form-control <?= isset($validation) && $validation->hasError('file_konsep') ? 'is-invalid' : '' ?>" 
                                           name="file_konsep" id="file_konsep" 
                                           accept=".pdf,.doc,.docx" 
                                           <?= in_array('file_konsep', $required_fields ?? []) && empty($surat['file_path']) ? 'required' : '' ?>>
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
                                               <?= in_array('file_link', $required_fields ?? []) ? 'required' : '' ?>>
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
    document.addEventListener('DOMContentLoaded', function() {
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