<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2">
                <h3 class="card-title mb-0">Daftar Surat Resmi</h3>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modalKopSurat">
                        <i class="ti ti-settings me-1"></i> Kop Surat
                    </button>
                    <a href="<?= base_url('surat-resmi/create') ?>" class="btn btn-primary d-sm-inline-block">
                        <i class="ti ti-plus me-1"></i> Buat Surat Baru
                    </a>
                </div>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-muted">Cari</label>
                        <input type="text" id="search-input" class="form-control" placeholder="Cari nomor, perihal, atau tujuan...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Filter Tanggal</label>
                        <input type="date" id="filter-date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btn-reset" class="btn btn-outline-secondary w-100">
                            <i class="ti ti-refresh me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable mb-0">
                        <thead>
                            <tr>
                                <th class="w-1">No</th>
                                <th>No Surat</th>
                                <th>Tanggal</th>
                                <th>Tujuan</th>
                                <th>Perihal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            <?php if (!empty($surat_resmi)): ?>
                            <?php $no=1; foreach($surat_resmi as $s): ?>
                            <tr class="surat-row">
                                <td><?= $no++ ?></td>
                                <td class="fw-semibold"><?= esc($s['nomor_surat']) ?></td>
                                <td><?= date('d M Y', strtotime($s['tanggal_surat'])) ?></td>
                                <td><?= esc($s['tujuan_nama']) ?></td>
                                <td class="text-truncate" style="max-width: 250px;" title="<?= esc($s['perihal']) ?>"><?= esc($s['perihal']) ?></td>
                                <td>
                                    <div class="btn-list flex-nowrap justify-content-center">
                                        <a href="<?= base_url('surat-resmi/printPdf/'.$s['id']) ?>" target="_blank" class="btn btn-sm btn-outline-success" title="Cetak/PDF">
                                            <i class="ti ti-printer"></i>
                                        </a>
                                        <a href="<?= base_url('surat-resmi/edit/'.$s['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        <form action="<?= base_url('surat-resmi/delete/'.$s['id']) ?>" method="post" class="d-inline delete-form">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="ti ti-trash"></i>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<script>
    $(document).ready(function() {
        var table = $('.datatable').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                search: "Cari:",
                searchPlaceholder: "Cari...",
                emptyTable: '<div class="text-center text-muted py-5"><i class="ti ti-file-off" style="font-size: 2rem;"></i><p class="mt-2 mb-0">Belum ada surat resmi. Klik "Buat Surat Baru" untuk memulai.</p></div>'
            },
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [5] }
            ],
            dom: "<'row'<'col-sm-12'tr>>" +
                 "<'row px-3 py-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            initComplete: function() {
                $('#search-input').on('keyup', function() {
                    table.search(this.value).draw();
                });
                $('#filter-date').on('change', function() {
                    var date = this.value;
                    $.fn.dataTable.ext.search.push(function(settings, data) {
                        var rowDate = data[2];
                        if (!date) return true;
                        var parts = rowDate.split(' ');
                        var months = { 'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'Mei': '05', 'Jun': '06', 'Jul': '07', 'Agu': '08', 'Sep': '09', 'Okt': '10', 'Nov': '11', 'Des': '12' };
                        var d = parts[0].padStart(2, '0');
                        var m = months[parts[1]] || '01';
                        var y = parts[2];
                        var rowIso = y + '-' + m + '-' + d;
                        return rowIso === date;
                    });
                    table.draw();
                    $.fn.dataTable.ext.search.pop();
                });
                $('#btn-reset').on('click', function() {
                    $('#search-input').val('');
                    $('#filter-date').val('');
                    table.search('').columns().search('').draw();
                });
            }
        });

        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: 'Hapus surat ini?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
