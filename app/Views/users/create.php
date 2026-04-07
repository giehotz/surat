<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Tambah Pengguna Baru</h3>
            </div>
            <div class="card-status-top bg-primary"></div>

            <form action="<?= base_url('admin/users/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" placeholder="Masukkan nama lengkap" value="<?= old('nama_lengkap') ?>" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Username untuk login" value="<?= old('username') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Alamat email aktif" value="<?= old('email') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Minimal 5 karakter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Hak Akses (Role)</label>
                            <select class="form-select" name="role" required>
                                <option value="" disabled selected>Pilih peran...</option>
                                <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>Super Admin</option>
                                <option value="pimpinan" <?= old('role') == 'pimpinan' ? 'selected' : '' ?>>Pimpinan</option>
                                <option value="admin_tamu" <?= old('role') == 'admin_tamu' ? 'selected' : '' ?>>Admin Buku Tamu</option>
                                <option value="operator" <?= old('role') == 'operator' ? 'selected' : '' ?>>Operator/Resepsionis</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jabatan Struktural / Posisi</label>
                        <input type="text" class="form-control" name="jabatan" placeholder="Cth: Kepala Bagian Keuangan" value="<?= old('jabatan') ?>">
                    </div>

                </div>
                <!-- /.card-body -->

                <div class="card-footer text-end">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-link link-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary"><i class="ti ti-user-plus icon"></i> Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>