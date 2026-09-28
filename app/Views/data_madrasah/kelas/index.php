<!-- Toolbar Kelas -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="text-secondary small">
        Kelola rombongan belajar (rombel), tingkat, dan alokasi peserta didik.
    </div>
    <div class="btn-list">
        <a href="<?= base_url('kelas/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus icon me-1"></i> Tambah Kelas
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap table-hover datatable-kelas">
        <thead>
            <tr>
                <th class="w-1">No.</th>
                <th>Nama Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Deskripsi</th>
                <th>Jumlah Siswa</th>
                <th class="w-1 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($kelas)) : ?>
                <?php $i = 1; foreach ($kelas as $row) : ?>
                    <tr>
                        <td class="text-secondary"><?= $i++ ?></td>
                        <td class="fw-bold"><?= esc($row['nama_kelas']) ?></td>
                        <td>
                            <span class="badge bg-secondary-lt fw-medium">Tingkat <?= esc($row['tingkat']) ?></span>
                        </td>
                        <td><?= esc($row['jurusan'] ?: '-') ?></td>
                        <td class="text-secondary text-truncate" style="max-width: 200px;" title="<?= esc($row['deskripsi']) ?>">
                            <?= esc($row['deskripsi'] ?: '-') ?>
                        </td>
                        <td>
                            <a href="<?= base_url('kelas/siswa/' . $row['id']) ?>" class="badge bg-blue-lt text-decoration-none px-2 py-1" data-bs-toggle="tooltip" title="Lihat daftar siswa kelas ini">
                                <i class="ti ti-users me-1"></i> <?= (int)($row['jumlah_siswa'] ?? 0) ?> Siswa
                            </a>
                        </td>
                        <td class="text-center">
                            <div class="btn-list flex-nowrap justify-content-center">
                                <a href="<?= base_url('kelas/siswa/' . $row['id']) ?>" class="btn btn-icon btn-sm btn-ghost-info" data-bs-toggle="tooltip" title="Lihat Siswa">
                                    <i class="ti ti-users icon"></i>
                                </a>
                                <a href="<?= base_url('kelas/edit/' . $row['id']) ?>" class="btn btn-icon btn-sm btn-ghost-primary" data-bs-toggle="tooltip" title="Edit Kelas">
                                    <i class="ti ti-edit icon"></i>
                                </a>
                                <form action="<?= base_url('kelas/delete/' . $row['id']) ?>" method="post" class="d-inline form-delete-kelas">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-danger btn-delete-item" data-title="Hapus Kelas <?= esc($row['nama_kelas']) ?>?" data-text="Data kelas akan dihapus dari sistem." data-bs-toggle="tooltip" title="Hapus Kelas">
                                        <i class="ti ti-trash icon"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="p-0">
                        <div class="empty py-5">
                            <div class="empty-icon">
                                <i class="ti ti-door-off text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <p class="empty-title">Belum ada data kelas</p>
                            <p class="empty-subtitle text-muted">
                                Rombongan belajar (rombel) belum ditambahkan ke dalam database madrasah.
                            </p>
                            <div class="empty-action">
                                <a href="<?= base_url('kelas/create') ?>" class="btn btn-primary">
                                    <i class="ti ti-plus icon me-1"></i> Tambah Kelas Pertama
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>