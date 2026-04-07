<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row g-2 align-items-center mb-4">
    <div class="col">
        <h2 class="page-title">
            <?= esc($title) ?>
        </h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('siswa/update/' . $siswa['id']) ?>" method="post" autocomplete="off">
            <?= csrf_field() ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Nomor Induk Siswa (NIS)</label>
                        <input type="text" class="form-control <?= (session('errors.nis')) ? 'is-invalid' : '' ?>" name="nis" value="<?= old('nis', $siswa['nis']) ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.nis') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" class="form-control <?= (session('errors.nama')) ? 'is-invalid' : '' ?>" name="nama" value="<?= old('nama', $siswa['nama']) ?>" required>
                        <div class="invalid-feedback">
                            <?= session('errors.nama') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Jenis Kelamin</label>
                        <select class="form-select <?= (session('errors.jenis_kelamin')) ? 'is-invalid' : '' ?>" name="jenis_kelamin" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" <?= old('jenis_kelamin', $siswa['jenis_kelamin']) == 'L' ? 'selected' : '' ?>>Laki-laki (L)</option>
                            <option value="P" <?= old('jenis_kelamin', $siswa['jenis_kelamin']) == 'P' ? 'selected' : '' ?>>Perempuan (P)</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.jenis_kelamin') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Kelas</label>
                        <select class="form-select <?= (session('errors.kelas_id')) ? 'is-invalid' : '' ?>" name="kelas_id" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($kelasList as $kelas): ?>
                                <option value="<?= $kelas['id'] ?>" <?= old('kelas_id', $siswa['kelas_id']) == $kelas['id'] ? 'selected' : '' ?>><?= esc($kelas['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.kelas_id') ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3 row">
                        <div class="col-6">
                            <label class="form-label required">Tempat Lahir</label>
                            <input type="text" class="form-control <?= (session('errors.tempat_lahir')) ? 'is-invalid' : '' ?>" name="tempat_lahir" value="<?= old('tempat_lahir', $siswa['tempat_lahir']) ?>" required>
                            <div class="invalid-feedback">
                                <?= session('errors.tempat_lahir') ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label required">Tanggal Lahir</label>
                            <input type="date" class="form-control <?= (session('errors.tanggal_lahir')) ? 'is-invalid' : '' ?>" name="tanggal_lahir" value="<?= old('tanggal_lahir', $siswa['tanggal_lahir']) ?>" required>
                            <div class="invalid-feedback">
                                <?= session('errors.tanggal_lahir') ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Alamat Domisili</label>
                        <textarea class="form-control <?= (session('errors.alamat')) ? 'is-invalid' : '' ?>" name="alamat" rows="2" required><?= old('alamat', $siswa['alamat']) ?></textarea>
                        <div class="invalid-feedback">
                            <?= session('errors.alamat') ?>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control <?= (session('errors.email')) ? 'is-invalid' : '' ?>" name="email" value="<?= old('email', $siswa['email']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Telepon / HP</label>
                            <input type="text" class="form-control <?= (session('errors.telepon')) ? 'is-invalid' : '' ?>" name="telepon" value="<?= old('telepon', $siswa['telepon']) ?>">
                            <div class="invalid-feedback">
                                <?= session('errors.telepon') ?>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Status</label>
                        <select class="form-select <?= (session('errors.status')) ? 'is-invalid' : '' ?>" name="status" required>
                            <option value="aktif" <?= old('status', $siswa['status']) == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="nonaktif" <?= old('status', $siswa['status']) == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                            <option value="lulus" <?= old('status', $siswa['status']) == 'lulus' ? 'selected' : '' ?>>Lulus</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.status') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-footer text-end mt-4">
                <a href="<?= base_url('siswa') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>