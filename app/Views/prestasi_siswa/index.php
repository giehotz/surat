<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Buku Catatan Prestasi Siswa</h3>
                <div>
                    <a href="<?= base_url('prestasi-siswa/export-excel') ?>" class="btn btn-outline-success d-none d-sm-inline-block me-2">
                        <i class="ti ti-file-spreadsheet icon"></i> Excel
                    </a>
                    <a href="<?= base_url('prestasi-siswa/export-excel') ?>" class="btn btn-outline-success d-sm-none btn-icon me-2" title="Export Excel">
                        <i class="ti ti-file-spreadsheet icon"></i>
                    </a>
                    <a href="<?= base_url('prestasi-siswa/export-pdf') ?>" target="_blank" class="btn btn-outline-danger d-none d-sm-inline-block me-2">
                        <i class="ti ti-file-type-pdf icon"></i> PDF
                    </a>
                    <a href="<?= base_url('prestasi-siswa/export-pdf') ?>" target="_blank" class="btn btn-outline-danger d-sm-none btn-icon me-2" title="Export PDF">
                        <i class="ti ti-file-type-pdf icon"></i>
                    </a>
                    <a href="<?= base_url('prestasi-siswa/import') ?>" class="btn btn-success d-none d-sm-inline-block me-2">
                        <i class="ti ti-upload icon"></i> Import Excel
                    </a>
                    <a href="<?= base_url('prestasi-siswa/import') ?>" class="btn btn-success d-sm-none btn-icon me-2" title="Import Excel">
                        <i class="ti ti-upload icon"></i>
                    </a>
                    <a href="<?= base_url('prestasi-siswa/create') ?>" class="btn btn-primary d-none d-sm-inline-block">
                        <i class="ti ti-plus icon"></i> Tambah Prestasi
                    </a>
                    <a href="<?= base_url('prestasi-siswa/create') ?>" class="btn btn-primary d-sm-none btn-icon">
                        <i class="ti ti-plus icon"></i>
                    </a>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible m-3" role="alert">
                    <div class="d-flex">
                        <div>
                            <i class="ti ti-check icon alert-icon"></i>
                        </div>
                        <div>
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    </div>
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable" id="table-prestasi">
                    <thead>
                        <tr>
                            <th class="w-1">No</th>
                            <th>Hari, tanggal</th>
                            <th>Nama Siswa</th>
                            <th>NISN</th>
                            <th>Jenis Prestasi</th>
                            <th>Tingkat</th>
                            <th>Nama Lomba</th>
                            <th>Peringkat</th>
                            <th>Keterangan</th>
                            <th>Sertifikat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($prestasi)) : ?>
                            <tr>
                                <td colspan="9" class="text-center">Belum ada data prestasi siswa.</td>
                            </tr>
                        <?php else : ?>
                            <?php $no = 1;
                            foreach ($prestasi as $item) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= format_tanggal_waktu_indo($item['tanggal']) ?></td>
                                    <td><?= esc($item['nama_siswa']) ?></td>
                                    <td><?= esc($item['nisn']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $item['jenis_prestasi'] == 'Akademik' ? 'blue' : 'green' ?> text-<?= $item['jenis_prestasi'] == 'Akademik' ? 'blue' : 'green' ?>-fg">
                                            <?= esc($item['jenis_prestasi']) ?>
                                        </span>
                                    </td>
                                    <td><?= esc($item['tingkat']) ?></td>
                                    <td><?= esc($item['nama_lomba'] ?? '-') ?></td>
                                    <td><?= esc($item['peringkat'] ?? '-') ?></td>
                                    <td class="text-wrap" style="max-width: 200px;"><?= esc($item['keterangan'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($item['tipe_penyimpanan'] == 'lokal' && !empty($item['file_sertifikat'])) : ?>
                                            <a href="<?= base_url('uploads/sertifikat/' . $item['file_sertifikat']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-file-pdf icon"></i> Lihat
                                            </a>
                                        <?php elseif ($item['tipe_penyimpanan'] == 'cloud' && !empty($item['file_link'])) : ?>
                                            <a href="<?= esc($item['file_link']) ?>" target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-link icon"></i> Akses Link
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="<?= base_url('prestasi-siswa/edit/' . $item['id']) ?>" class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="ti ti-edit icon m-0"></i>
                                            </a>
                                            <form action="<?= base_url('prestasi-siswa/delete/' . $item['id']) ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data prestasi ini?');">
                                                    <i class="ti ti-trash icon m-0"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof simpleDatatables !== 'undefined') {
            const tablePrestasi = new simpleDatatables.DataTable("#table-prestasi", {
                labels: {
                    placeholder: "Pencarian...",
                    perPage: "Data per halaman",
                    noRows: "Tidak ada data yang ditemukan",
                    info: "Menampilkan {start} - {end} dari {rows} data",
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>