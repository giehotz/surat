<!-- Partial View for Kelas -->
<div class="row g-2 align-items-center mb-4">
    <div class="col">
        <h2 class="page-title">
            <?= isset($title) ? esc($title) : 'Data Kelas' ?>
        </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="<?= base_url('kelas/create') ?>" class="btn btn-primary d-none d-sm-inline-block">
                <i class="ti ti-plus icon"></i> Tambah Kelas
            </a>
            <a href="<?= base_url('kelas/create') ?>" class="btn btn-primary d-sm-none btn-icon" aria-label="Tambah Kelas">
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

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-alert-triangle icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap table-striped table-hover datatable">
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
                <?php if (isset($kelas) && count($kelas) > 0) : ?>
                    <?php $i = 1;
                    foreach ($kelas as $row) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td class="font-weight-medium fw-bold"><?= esc($row['nama_kelas']) ?></td>
                            <td><?= esc($row['tingkat']) ?></td>
                            <td><?= esc($row['jurusan'] ?: '-') ?></td>
                            <td><?= esc($row['deskripsi'] ?: '-') ?></td>
                            <td>
                                <a href="<?= base_url('kelas/siswa/' . $row['id']) ?>" class="badge bg-blue text-decoration-none p-2">
                                    <?= $row['jumlah_siswa'] ?> Siswa
                                </a>
                            </td>
                            <td>
                                <div class="btn-list flex-nowrap">
                                    <a href="<?= base_url('kelas/siswa/' . $row['id']) ?>" class="btn btn-icon btn-sm btn-outline-info tooltip-info" title="Lihat Siswa">
                                        <i class="ti ti-users icon"></i>
                                    </a>
                                    <a href="<?= base_url('kelas/edit/' . $row['id']) ?>" class="btn btn-icon btn-sm btn-outline-primary tooltip-info" title="Edit">
                                        <i class="ti ti-edit icon"></i>
                                    </a>
                                    <form action="<?= base_url('kelas/delete/' . $row['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data kelas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().destroy();
        }
        $('.datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            },
            "pageLength": 10
        });
        $('.tooltip-info').tooltip();
    });
</script>