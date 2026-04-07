<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Daftar Pengguna Sistem</h3>
                <div class="card-actions">
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">
                        <i class="ti ti-user-plus icon"></i> Tambah Pengguna
                    </a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Pengguna</th>
                            <th>Username</th>
                            <th>Jabatan</th>
                            <th>Peran (Role)</th>
                            <th class="text-center w-5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($users) > 0): ?>
                            <?php $i = 1;
                            foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            <span class="avatar me-2" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode($user['nama_lengkap']) ?>')"></span>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium"><?= esc($user['nama_lengkap']) ?></div>
                                                <div class="text-secondary"><a href="mailto:<?= esc($user['email']) ?>" class="text-reset"><?= esc($user['email']) ?></a></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary"><?= esc($user['username']) ?></td>
                                    <td class="text-secondary"><?= esc($user['jabatan']) ?></td>
                                    <td>
                                        <?php if ($user['role'] == 'admin'): ?>
                                            <span class="badge bg-purple text-purple-fg">Administrator</span>
                                        <?php elseif ($user['role'] == 'pimpinan'): ?>
                                            <span class="badge bg-blue text-blue-fg">Pimpinan</span>
                                        <?php else: ?>
                                            <span class="badge bg-green text-green-fg">Staf / Pegawai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="btn-list flex-nowrap justify-content-center">
                                            <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-outline-primary btn-icon" title="Edit Data" data-bs-toggle="tooltip">
                                                <i class="ti ti-edit icon"></i>
                                            </a>
                                            <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" class="btn btn-outline-danger btn-icon" title="Hapus Akun" data-bs-toggle="tooltip" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                                <i class="ti ti-trash icon"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data pengguna yang tersimpan kecuali data di database Anda kosong.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>