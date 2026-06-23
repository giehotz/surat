<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Surat Resmi</h3>
                <div>
                    <button type="button" class="btn btn-outline-secondary d-sm-inline-block me-2" data-bs-toggle="modal" data-bs-target="#modalKopSurat">
                        <i class="ti ti-settings me-2"></i> Pengaturan Kop Surat
                    </button>
                    <a href="<?= base_url('surat-resmi/create') ?>" class="btn btn-primary d-sm-inline-block">
                        <i class="ti ti-plus me-2"></i> Buat Surat Baru
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>No Surat</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Perihal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach($surat_resmi as $s): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($s['nomor_surat']) ?></td>
                                <td><?= date('d M Y', strtotime($s['tanggal_surat'])) ?></td>
                                <td><?= esc($s['tujuan_nama']) ?></td>
                                <td class="text-truncate" style="max-width: 250px;" title="<?= esc($s['perihal']) ?>"><?= esc($s['perihal']) ?></td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <a href="<?= base_url('surat-resmi/printPdf/'.$s['id']) ?>" target="_blank" class="btn btn-sm btn-outline-success" title="Cetak/PDF"><i class="ti ti-printer"></i></a>
                                        <a href="<?= base_url('surat-resmi/edit/'.$s['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="ti ti-edit"></i></a>
                                        <form action="<?= base_url('surat-resmi/delete/'.$s['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus surat ini?')">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"><i class="ti ti-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengaturan Kop Surat -->
<div class="modal modal-blur fade" id="modalKopSurat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="<?= base_url('surat-resmi/save-kop') ?>" method="post">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title">Pengaturan Kop Surat Resmi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kementerian (Baris 1)</label>
                        <input type="text" class="form-control" name="sekolah_kementerian" value="<?= esc($appSettings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kantor Kementerian (Baris 2)</label>
                        <input type="text" class="form-control" name="sekolah_kantor_kementerian" value="<?= esc($appSettings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Madrasah / Instansi (Baris 3)</label>
                        <input type="text" class="form-control" name="sekolah_nama" value="<?= esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Instansi</label>
                        <textarea class="form-control" name="sekolah_alamat" rows="2"><?= esc($appSettings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Instansi</label>
                        <input type="text" class="form-control" name="sekolah_kontak" value="<?= esc($appSettings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>
