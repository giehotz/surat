<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Page Header Baru -->
<div class="page-header d-print-none mb-4">
    <div class="container-xl p-0">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-muted text-uppercase tracking-wide mb-1">
                    Manajemen Siswa
                </div>
                <h2 class="page-title text-dark fw-bold">
                    <i class="ti ti-user-plus me-2 text-primary"></i> <?= esc($title) ?>
                </h2>
            </div>
            <!-- Tombol Aksi di Header -->
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="<?= base_url('siswa') ?>" class="btn btn-outline-secondary d-none d-sm-inline-block">
                        <i class="ti ti-arrow-left icon me-2"></i> Kembali
                    </a>
                    <!-- Fallback untuk Mobile -->
                    <a href="<?= base_url('siswa/import') ?>" class="btn btn-success d-sm-none btn-icon" aria-label="Import Excel" title="Import Excel">
                        <i class="ti ti-file-import icon"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom p-4">
                <h3 class="card-title mb-0 fw-semibold text-muted">
                    <i class="ti ti-edit-circle me-2"></i> Form Isian Siswa Baru
                </h3>
            </div>
            
            <div class="card-body p-4 p-md-5">
                <form action="<?= base_url('siswa/store') ?>" method="post" autocomplete="off">
                    <?= csrf_field() ?>

                    <!-- BAGIAN 1: Data Akademik -->
                    <div class="mb-4 text-primary fw-bold text-uppercase tracking-wide" style="font-size: 0.85rem;">
                        <i class="ti ti-school me-1"></i> 1. Informasi Akademik
                    </div>
                    <div class="row bg-light rounded-3 p-3 mb-5 mx-0 border">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label required">Nomor Induk Siswa (NIS)</label>
                            <input type="text" class="form-control <?= (session('errors.nis')) ? 'is-invalid' : '' ?>" name="nis" value="<?= old('nis') ?>" placeholder="Masukkan NIS" required>
                            <div class="invalid-feedback">
                                <?= session('errors.nis') ?>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label required">Kelas</label>
                            <select class="form-select <?= (session('errors.kelas_id')) ? 'is-invalid' : '' ?>" name="kelas_id" required>
                                <option value="" hidden>-- Pilih Kelas --</option>
                                <?php foreach ($kelasList as $kelas): ?>
                                    <option value="<?= $kelas['id'] ?>" <?= old('kelas_id') == $kelas['id'] ? 'selected' : '' ?>><?= esc($kelas['nama_kelas']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.kelas_id') ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label required">Status</label>
                            <select class="form-select <?= (session('errors.status')) ? 'is-invalid' : '' ?>" name="status" required>
                                <option value="aktif" <?= old('status', 'aktif') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="nonaktif" <?= old('status') == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                <option value="lulus" <?= old('status') == 'lulus' ? 'selected' : '' ?>>Lulus</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.status') ?>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 2: Data Pribadi -->
                    <div class="mb-4 text-primary fw-bold text-uppercase tracking-wide" style="font-size: 0.85rem;">
                        <i class="ti ti-user me-1"></i> 2. Identitas Pribadi
                    </div>
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nama Lengkap</label>
                            <input type="text" class="form-control <?= (session('errors.nama')) ? 'is-invalid' : '' ?>" name="nama" value="<?= old('nama') ?>" placeholder="Nama lengkap siswa sesuai ijazah" required>
                            <div class="invalid-feedback">
                                <?= session('errors.nama') ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Jenis Kelamin</label>
                            <select class="form-select <?= (session('errors.jenis_kelamin')) ? 'is-invalid' : '' ?>" name="jenis_kelamin" required>
                                <option value="" hidden>-- Pilih Jenis Kelamin --</option>
                                <option value="L" <?= old('jenis_kelamin') == 'L' ? 'selected' : '' ?>>Laki-laki (L)</option>
                                <option value="P" <?= old('jenis_kelamin') == 'P' ? 'selected' : '' ?>>Perempuan (P)</option>
                            </select>
                            <div class="invalid-feedback">
                                <?= session('errors.jenis_kelamin') ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required">Tempat Lahir</label>
                            <input type="text" class="form-control <?= (session('errors.tempat_lahir')) ? 'is-invalid' : '' ?>" name="tempat_lahir" value="<?= old('tempat_lahir') ?>" placeholder="Kota/Kabupaten kelahiran" required>
                            <div class="invalid-feedback">
                                <?= session('errors.tempat_lahir') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Tanggal Lahir</label>
                            <input type="date" class="form-control <?= (session('errors.tanggal_lahir')) ? 'is-invalid' : '' ?>" name="tanggal_lahir" value="<?= old('tanggal_lahir') ?>" required>
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal_lahir') ?>
                            </div>
                        </div>
                    </div>

                    <!-- BAGIAN 3: Kontak & Alamat -->
                    <div class="mb-4 text-primary fw-bold text-uppercase tracking-wide" style="font-size: 0.85rem;">
                        <i class="ti ti-address-book me-1"></i> 3. Kontak & Alamat
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                                <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : '' ?>" name="email" value="<?= old('email') ?>" placeholder="email@contoh.com">
                            </div>
                            <div class="invalid-feedback d-block">
                                <?= session('errors.email') ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon / HP Aktif</label>
                            <div class="input-group input-group-flat">
                                <span class="input-group-text"><i class="ti ti-phone"></i></span>
                                <input type="text" class="form-control <?= (session('errors.telepon')) ? 'is-invalid' : '' ?>" name="telepon" value="<?= old('telepon') ?>" placeholder="08xxxxxxxxx">
                            </div>
                            <div class="invalid-feedback d-block">
                                <?= session('errors.telepon') ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label required">Alamat Domisili Lengkap</label>
                            <textarea class="form-control <?= (session('errors.alamat')) ? 'is-invalid' : '' ?>" name="alamat" rows="3" placeholder="Masukkan jalan, RT/RW, desa, kecamatan..." required><?= old('alamat') ?></textarea>
                            <div class="invalid-feedback">
                                <?= session('errors.alamat') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <hr class="mt-5 mb-4">
                    <div class="d-flex justify-content-end align-items-center gap-2">
                        <a href="<?= base_url('siswa') ?>" class="btn btn-link text-muted">Batal</a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="ti ti-device-floppy"></i> Simpan Data Siswa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>