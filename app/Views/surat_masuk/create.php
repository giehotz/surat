<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Form Surat Masuk Baru</h3>
            </div>
            <div class="card-status-top bg-primary"></div>

            <form action="<?= base_url('surat-masuk/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required">Nomor Surat (dari pengirim)</label>
                            <input type="text" class="form-control" name="nomor_surat" id="nomor_surat" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Pengirim</label>
                            <input type="text" class="form-control" name="pengirim" id="pengirim" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required">Tanggal Surat</label>
                            <input type="date" class="form-control" name="tanggal_surat" id="tanggal_surat" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Tanggal Diterima</label>
                            <input type="date" class="form-control" name="tanggal_terima" id="tanggal_terima" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Perihal Utama</label>
                        <textarea class="form-control" name="perihal" id="perihal" rows="3" required></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label">Jumlah Lampiran (opsional)</label>
                            <input type="number" class="form-control" name="lampiran" id="lampiran" min="0" value="0">
                        </div>
                        <div class="col-md-8">
                            <?php
                            $allowedMethods = isset($appSettings['metode_lampiran']) && $appSettings['metode_lampiran'] !== ''
                                ? explode(',', $appSettings['metode_lampiran'])
                                : ['upload', 'link'];
                            $defaultMethod = $allowedMethods[0] ?? 'upload';
                            ?>
                            <div class="mb-2 <?= count($allowedMethods) <= 1 ? 'd-none' : '' ?>">
                                <label class="form-label">Metode Penyimpanan Lampiran</label>
                                <select class="form-select" name="tipe_penyimpanan" id="tipe_penyimpanan">
                                    <?php if (in_array('upload', $allowedMethods)): ?>
                                        <option value="lokal">Upload File ke Server Lokal</option>
                                    <?php endif; ?>
                                    <?php if (in_array('link', $allowedMethods)): ?>
                                        <option value="cloud">Link Cloud (Google Drive, dll)</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div id="area_upload" style="display: <?= $defaultMethod == 'upload' ? 'block' : 'none' ?>;">
                                <input type="file" class="form-control" name="file_surat" id="file_surat" accept=".pdf,.jpeg,.jpg,.png">
                                <small class="form-hint">Format PDF/JPG, Max 5MB</small>
                            </div>

                            <div id="area_cloud" style="display: <?= $defaultMethod == 'link' ? 'block' : 'none' ?>;">
                                <input type="url" class="form-control" name="file_link" id="file_link" placeholder="https://drive.google.com/...">
                                <small class="form-hint"><i class="ti ti-info-circle icon text-blue"></i> Pastikan link memiliki akses Publik/Viewers.</small>
                                <!-- Tombol Buka Folder Google Drive -->
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-warning d-inline-flex align-items-center gap-1" id="tombolBukaDrive">
                                        <i class="ti ti-brand-google-drive"></i> Buka Folder Google Drive
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea class="form-control" name="keterangan" id="keterangan" rows="2"></textarea>
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer text-end">
                    <a href="<?= base_url('surat-masuk') ?>" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy icon"></i> Simpan Surat</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $('#tipe_penyimpanan').on('change', function() {
        if ($(this).val() == 'cloud') {
            $('#area_upload').hide();
            $('#area_cloud').show();
            $('#file_link').prop('required', true);
        } else {
            $('#area_cloud').hide();
            $('#file_link').prop('required', false);
            $('#area_upload').show();
        }
    });

    $(document).ready(function() {
        $('#tipe_penyimpanan').trigger('change');

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