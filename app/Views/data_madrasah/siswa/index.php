<!-- Partial View for Data Siswa -->
<div class="row g-2 align-items-center mb-4">
    <div class="col">
        <h2 class="page-title">
            <?= isset($title) ? esc($title) : 'Data Siswa' ?>
        </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="<?= base_url('siswa/import') ?>" class="btn btn-success d-none d-sm-inline-block shadow-sm">
                <i class="ti ti-file-import icon me-2"></i> Import Excel
            </a>
            <a href="<?= base_url('siswa/create') ?>" class="btn btn-primary d-none d-sm-inline-block">
                <i class="ti ti-plus icon"></i> Tambah Data Baru
            </a>
            <a href="<?= base_url('siswa/create') ?>" class="btn btn-primary d-sm-none btn-icon" aria-label="Tambah Data">
                <i class="ti ti-plus icon"></i>
            </a>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-check icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('siswa') ?>" method="get" class="row gx-3 gy-2 align-items-center">
            <div class="col-sm-4">
                <label class="visually-hidden" for="keyword">Pencarian</label>
                <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Cari NIS / Nama..." value="<?= isset($keyword) ? esc($keyword) : '' ?>">
            </div>
            <div class="col-sm-3">
                <label class="visually-hidden" for="kelas_id">Kelas</label>
                <select class="form-select" id="kelas_id" name="kelas_id">
                    <option value="">-- Semua Kelas --</option>
                    <?php if (isset($kelasList)) foreach ($kelasList as $k) : ?>
                        <option value="<?= $k['id'] ?>" <?= (isset($selected_kelas) && $selected_kelas == $k['id']) ? 'selected' : '' ?>><?= esc($k['nama_kelas']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-sm-3">
                <label class="visually-hidden" for="status">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" <?= (isset($selected_status) && $selected_status == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= (isset($selected_status) && $selected_status == 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                    <option value="lulus" <?= (isset($selected_status) && $selected_status == 'lulus') ? 'selected' : '' ?>>Lulus</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?= base_url('siswa') ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th class="w-1">No.</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>L/P</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th class="w-1 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $page = isset($_GET['page_siswa']) ? $_GET['page_siswa'] : 1;
                $no = 1 + (20 * ($page - 1));
                if (isset($siswa) && count($siswa) > 0) : ?>
                    <?php foreach ($siswa as $row) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($row['nis']) ?></td>
                            <td class="font-weight-medium fw-bold"><?= esc($row['nama']) ?></td>
                            <td><?= esc($row['jenis_kelamin']) ?></td>
                            <td>
                                <?php if (!empty($row['nama_kelas'])): ?>
                                    <a href="<?= base_url('kelas/siswa/' . $row['kelas_id']) ?>" class="badge bg-blue-lt text-decoration-none">
                                        <?= esc($row['nama_kelas']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted"><em>Tidak ada kelas</em></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'aktif'): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php elseif ($row['status'] == 'nonaktif'): ?>
                                    <span class="badge bg-danger">Nonaktif</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Lulus</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= base_url('siswa/edit/' . $row['id']) ?>" class="btn btn-icon btn-sm btn-outline-primary tooltip-info" title="Edit">
                                        <i class="ti ti-edit icon"></i>
                                    </a>
                                    <form action="<?= base_url('siswa/delete/' . $row['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-icon btn-sm btn-outline-danger tooltip-info" title="Hapus">
                                            <i class="ti ti-trash icon"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Data siswa tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="card-footer d-flex align-items-center justify-content-center">
            <?= $pager->links('siswa', 'bootstrap_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('.tooltip-info').tooltip();
    });
</script>