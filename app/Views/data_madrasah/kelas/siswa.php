<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
<div class="row g-2 align-items-center mb-4">
    <div class="col">
        <div class="page-pretitle">
            Daftar Siswa
        </div>
        <h2 class="page-title">
            Kelas <?= esc($kelas['nama_kelas']) ?>
        </h2>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <div class="btn-list">
            <a href="<?= base_url('kelas') ?>" class="btn btn-secondary">
                <i class="ti ti-arrow-left icon"></i> Kembali ke Kelas
            </a>
            <a href="<?= base_url('siswa/create') ?>" class="btn btn-primary d-none d-sm-inline-block">
                <i class="ti ti-plus icon"></i> Tambah Siswa Baru
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data Siswa Kelas <?= esc($kelas['nama_kelas']) ?></h3>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap table-striped table-hover datatable">
            <thead>
                <tr>
                    <th class="w-1">No.</th>
                    <th>NIS</th>
                    <th>Nama Lengkap</th>
                    <th>L/P</th>
                    <th>Status</th>
                    <th class="w-1 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($siswa) && count($siswa) > 0) : ?>
                    <?php $i = 1;
                    foreach ($siswa as $row) : ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($row['nis']) ?></td>
                            <td class="font-weight-medium fw-bold"><?= esc($row['nama']) ?></td>
                            <td><?= esc($row['jenis_kelamin']) ?></td>
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
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Belum ada siswa di kelas ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
<?= $this->endSection() ?>