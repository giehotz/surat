<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Prestasi Siswa</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('prestasi-siswa/store') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label required">Tanggal Prestasi (Hari/Tanggal)</label>
                        <input type="date" class="form-control <?= ($validation->hasError('tanggal')) ? 'is-invalid' : '' ?>" name="tanggal" value="<?= old('tanggal') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('tanggal') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Siswa</label>
                        <input type="text" class="form-control <?= ($validation->hasError('nama_siswa')) ? 'is-invalid' : '' ?>" name="nama_siswa" placeholder="Masukkan nama lengkap siswa" value="<?= old('nama_siswa') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('nama_siswa') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">NISN</label>
                        <input type="text" class="form-control <?= ($validation->hasError('nisn')) ? 'is-invalid' : '' ?>" name="nisn" placeholder="Nomor Induk Siswa Nasional" value="<?= old('nisn') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('nisn') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Jenis Prestasi</label>
                        <select class="form-select <?= ($validation->hasError('jenis_prestasi')) ? 'is-invalid' : '' ?>" name="jenis_prestasi" required>
                            <option value="">-- Pilih Jenis Prestasi --</option>
                            <option value="Akademik" <?= old('jenis_prestasi') == 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                            <option value="Non Akademik" <?= old('jenis_prestasi') == 'Non Akademik' ? 'selected' : '' ?>>Non Akademik</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('jenis_prestasi') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Tingkat</label>
                        <select class="form-select <?= ($validation->hasError('tingkat')) ? 'is-invalid' : '' ?>" name="tingkat" required>
                            <option value="">-- Pilih Tingkat Kejuaraan --</option>
                            <option value="Kecamatan" <?= old('tingkat') == 'Kecamatan' ? 'selected' : '' ?>>Kecamatan</option>
                            <option value="Kabupaten" <?= old('tingkat') == 'Kabupaten' ? 'selected' : '' ?>>Kabupaten</option>
                            <option value="Provinsi" <?= old('tingkat') == 'Provinsi' ? 'selected' : '' ?>>Provinsi</option>
                            <option value="Nasional" <?= old('tingkat') == 'Nasional' ? 'selected' : '' ?>>Nasional</option>
                            <option value="Internasional" <?= old('tingkat') == 'Internasional' ? 'selected' : '' ?>>Internasional</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= $validation->getError('tingkat') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Lomba / Kejuaraan / Kompetisi</label>
                        <input type="text" class="form-control <?= ($validation->hasError('nama_lomba')) ? 'is-invalid' : '' ?>" name="nama_lomba" placeholder="Contoh: Olimpiade Sains Nasional (OSN) Matematika" value="<?= old('nama_lomba') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('nama_lomba') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Peringkat Juara</label>
                        <input type="text" class="form-control <?= ($validation->hasError('peringkat')) ? 'is-invalid' : '' ?>" name="peringkat" placeholder="Contoh: Juara 1, Harapan 1, Medali Emas" value="<?= old('peringkat') ?>" required>
                        <div class="invalid-feedback">
                            <?= $validation->getError('peringkat') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3" placeholder="Deskripsi lomba/prestasi (opsional)"><?= old('keterangan') ?></textarea>
                    </div>

                    <div class="row align-items-end mb-3">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <?php
                            $allowedMethods = isset($appSettings['metode_lampiran']) && $appSettings['metode_lampiran'] !== ''
                                ? explode(',', $appSettings['metode_lampiran'])
                                : ['upload', 'link'];
                            $defaultMethod = $allowedMethods[0] ?? 'upload';
                            ?>
                            <div class="<?= count($allowedMethods) <= 1 ? 'd-none' : '' ?>">
                                <label class="form-label">Metode Penyimpanan Sertifikat</label>
                                <select class="form-select" name="tipe_penyimpanan" id="tipe_penyimpanan">
                                    <?php if (in_array('upload', $allowedMethods)): ?>
                                        <option value="lokal">Upload File ke Server Lokal</option>
                                    <?php endif; ?>
                                    <?php if (in_array('link', $allowedMethods)): ?>
                                        <option value="cloud">Link Cloud (Google Drive, dll)</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-0">
                            <div class="form-label">Upload Sertifikat / Bukti Prestasi</div>
                            <div id="area_upload" style="display: <?= $defaultMethod == 'upload' ? 'block' : 'none' ?>;">
                                <input type="file" class="form-control <?= ($validation->hasError('file_sertifikat')) ? 'is-invalid' : '' ?>" name="file_sertifikat" id="file_sertifikat" accept=".pdf,.jpeg,.jpg,.png">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('file_sertifikat') ?>
                                </div>
                                <small class="text-muted mt-2 d-block">Boleh dikosongkan jika belum ada sertifikat. max 5MB, format: PDF/JPG.</small>
                            </div>

                            <div id="area_cloud" style="display: <?= $defaultMethod == 'link' ? 'block' : 'none' ?>;">
                                <input type="url" class="form-control <?= ($validation->hasError('file_link')) ? 'is-invalid' : '' ?>" name="file_link" id="file_link" placeholder="https://drive.google.com/...">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('file_link') ?>
                                </div>
                                <small class="text-muted mt-2 d-block"><i class="ti ti-info-circle icon text-blue"></i> Masukkan link publik dokumen (Google Drive, dll).</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <a href="<?= base_url('prestasi-siswa') ?>" class="btn btn-link">
                            <i class="ti ti-arrow-left icon"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary ms-auto">
                            <i class="ti ti-device-floppy icon"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
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
            $('#file_sertifikat').prop('required', false);
        } else {
            $('#area_cloud').hide();
            $('#file_link').prop('required', false);
            $('#area_upload').show();
        }
    });

    $(document).ready(function() {
        $('#tipe_penyimpanan').trigger('change');
    });
</script>
<?= $this->endSection() ?>