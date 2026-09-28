<!-- Toolbar & Action Siswa -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
    <div class="text-secondary small">
        Kelola basis data peserta didik, penempatan rombel, dan status keaktifan siswa.
    </div>
    <div class="btn-list">
        <a href="<?= base_url('siswa/import') ?>" class="btn btn-outline-success">
            <i class="ti ti-file-import icon me-1"></i> Import Excel
        </a>
        <a href="<?= base_url('siswa/create') ?>" class="btn btn-primary">
            <i class="ti ti-plus icon me-1"></i> Tambah Siswa
        </a>
    </div>
</div>

<!-- Filter Bar (Preserves Tab State, Theme-Adaptive) -->
<div class="p-3 rounded-2 border mb-3" style="background-color: var(--tblr-card-header-bg, rgba(var(--tblr-body-color-rgb), 0.025));">
    <form action="<?= base_url('data-madrasah') ?>" method="get" class="row g-2 align-items-center">
        <!-- Hidden tab input agar filter tetap di tab siswa -->
        <input type="hidden" name="tab" value="siswa">

        <div class="col-12 col-md-4">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="ti ti-search text-secondary"></i>
                </span>
                <input type="text" class="form-control" name="keyword" placeholder="Cari NIS atau Nama Siswa..." value="<?= esc($keyword ?? '') ?>">
            </div>
        </div>

        <div class="col-6 col-md-3">
            <select class="form-select" name="kelas_id">
                <option value="">-- Semua Kelas --</option>
                <?php if (!empty($kelasList)) : ?>
                    <?php foreach ($kelasList as $k) : ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($selected_kelas) && $selected_kelas == $k['id']) ? 'selected' : '' ?>>
                            <?= esc($k['nama_kelas']) ?> (Tingkat <?= esc($k['tingkat']) ?>)
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-6 col-md-3">
            <select class="form-select" name="status">
                <option value="">-- Semua Status --</option>
                <option value="aktif" <?= (isset($selected_status) && $selected_status === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                <option value="nonaktif" <?= (isset($selected_status) && $selected_status === 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                <option value="lulus" <?= (isset($selected_status) && $selected_status === 'lulus') ? 'selected' : '' ?>>Lulus</option>
            </select>
        </div>

        <div class="col-12 col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-fill">
                <i class="ti ti-filter icon me-1"></i> Filter
            </button>
            <?php if (!empty($keyword) || !empty($selected_kelas) || !empty($selected_status)) : ?>
                <a href="<?= base_url('data-madrasah?tab=siswa') ?>" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="Reset Filter">
                    <i class="ti ti-refresh icon"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabel Siswa -->
<div class="table-responsive">
    <table class="table card-table table-vcenter text-nowrap table-hover">
        <thead>
            <tr>
                <th class="w-1">No.</th>
                <th>NIS</th>
                <th>Nama Lengkap</th>
                <th>L/P</th>
                <th>Kelas / Rombel</th>
                <th>Status</th>
                <th class="w-1 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $currentPage = (int)($pager ? $pager->getCurrentPage('siswa') : 1);
            $perPage = 20;
            $no = 1 + ($perPage * ($currentPage - 1));
            ?>
            <?php if (!empty($siswa)) : ?>
                <?php foreach ($siswa as $row) : ?>
                    <tr>
                        <td class="text-secondary"><?= $no++ ?></td>
                        <td class="font-monospace"><?= esc($row['nis']) ?></td>
                        <td class="fw-bold"><?= esc($row['nama']) ?></td>
                        <td>
                            <?php if ($row['jenis_kelamin'] === 'L') : ?>
                                <span class="badge bg-blue-lt px-2" title="Laki-laki">L</span>
                            <?php else : ?>
                                <span class="badge bg-pink-lt px-2" title="Perempuan">P</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['nama_kelas'])) : ?>
                                <a href="<?= base_url('kelas/siswa/' . $row['kelas_id']) ?>" class="badge bg-azure-lt text-decoration-none" data-bs-toggle="tooltip" title="Lihat kelas">
                                    <i class="ti ti-door me-1"></i><?= esc($row['nama_kelas']) ?>
                                </a>
                            <?php else : ?>
                                <span class="text-muted small"><em>Belum ada kelas</em></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row['status'] === 'aktif') : ?>
                                <span class="badge bg-success-lt text-success fw-medium">Aktif</span>
                            <?php elseif ($row['status'] === 'nonaktif') : ?>
                                <span class="badge bg-danger-lt text-danger fw-medium">Nonaktif</span>
                            <?php else : ?>
                                <span class="badge bg-secondary-lt fw-medium">Lulus</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="btn-list flex-nowrap justify-content-center">
                                <a href="<?= base_url('siswa/edit/' . $row['id']) ?>" class="btn btn-icon btn-sm btn-ghost-primary" data-bs-toggle="tooltip" title="Edit Siswa">
                                    <i class="ti ti-edit icon"></i>
                                </a>
                                <form action="<?= base_url('siswa/delete/' . $row['id']) ?>" method="post" class="d-inline form-delete-siswa">
                                    <?= csrf_field() ?>
                                    <button type="button" class="btn btn-icon btn-sm btn-ghost-danger btn-delete-item" data-title="Hapus Siswa <?= esc($row['nama']) ?>?" data-text="Data siswa dan riwayat akademik akan dihapus." data-bs-toggle="tooltip" title="Hapus Siswa">
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
                                <i class="ti ti-school-off text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <p class="empty-title">
                                <?= (!empty($keyword) || !empty($selected_kelas) || !empty($selected_status)) ? 'Siswa tidak ditemukan' : 'Belum ada data siswa' ?>
                            </p>
                            <p class="empty-subtitle text-muted">
                                <?= (!empty($keyword) || !empty($selected_kelas) || !empty($selected_status)) 
                                    ? 'Coba sesuaikan kata kunci pencarian atau reset filter di atas.' 
                                    : 'Tambahkan data peserta didik secara manual atau gunakan fitur import Excel.' ?>
                            </p>
                            <div class="empty-action">
                                <?php if (!empty($keyword) || !empty($selected_kelas) || !empty($selected_status)) : ?>
                                    <a href="<?= base_url('data-madrasah?tab=siswa') ?>" class="btn btn-outline-secondary">
                                        <i class="ti ti-refresh icon me-1"></i> Reset Filter
                                    </a>
                                <?php else : ?>
                                    <a href="<?= base_url('siswa/create') ?>" class="btn btn-primary me-2">
                                        <i class="ti ti-plus icon me-1"></i> Tambah Siswa Pertama
                                    </a>
                                    <a href="<?= base_url('siswa/import') ?>" class="btn btn-outline-success">
                                        <i class="ti ti-file-import icon me-1"></i> Import Excel
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Pagination Footer -->
<?php if (isset($pager) && $pager->getPageCount('siswa') > 1) : ?>
    <div class="d-flex align-items-center justify-content-between p-3 border-top">
        <div class="text-secondary small">
            Menampilkan data halaman <?= (int)$pager->getCurrentPage('siswa') ?> dari <?= (int)$pager->getPageCount('siswa') ?>
        </div>
        <div>
            <?= $pager->only(['tab', 'keyword', 'kelas_id', 'status'])->links('siswa', 'bootstrap_pagination') ?>
        </div>
    </div>
<?php endif; ?>