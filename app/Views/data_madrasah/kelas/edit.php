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
        <form action="<?= base_url('kelas/update/' . $kelas['id']) ?>" method="post" autocomplete="off">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label required">Nama Kelas</label>
                <input type="text" class="form-control <?= (session('errors.nama_kelas')) ? 'is-invalid' : '' ?>" name="nama_kelas" value="<?= old('nama_kelas', $kelas['nama_kelas']) ?>" placeholder="Misal: X IPA 1" required>
                <div class="invalid-feedback">
                    <?= session('errors.nama_kelas') ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label required">Tingkat</label>
                <input type="text" class="form-control <?= (session('errors.tingkat')) ? 'is-invalid' : '' ?>" name="tingkat" value="<?= old('tingkat', $kelas['tingkat']) ?>" placeholder="Misal: 10, 11, 12, atau VII, VIII, IX" required>
                <div class="invalid-feedback">
                    <?= session('errors.tingkat') ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jurusan</label>
                <input type="text" class="form-control <?= (session('errors.jurusan')) ? 'is-invalid' : '' ?>" name="jurusan" value="<?= old('jurusan', $kelas['jurusan']) ?>" placeholder="Misal: IPA, IPS, Keagamaan, dll">
                <div class="invalid-feedback">
                    <?= session('errors.jurusan') ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea class="form-control <?= (session('errors.deskripsi')) ? 'is-invalid' : '' ?>" name="deskripsi" rows="3"><?= old('deskripsi', $kelas['deskripsi']) ?></textarea>
                <div class="invalid-feedback">
                    <?= session('errors.deskripsi') ?>
                </div>
            </div>

            <div class="form-footer text-end mt-4">
                <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>