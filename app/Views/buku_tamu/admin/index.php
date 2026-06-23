<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row g-2 align-items-center mb-4">
    <div class="col">
        <h2 class="page-title">
            <i class="ti ti-address-book me-2 text-primary"></i> <?= esc($title) ?>
        </h2>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('admin-buku-tamu') ?>" method="get" class="row gx-3 gy-2 align-items-center">
            <div class="col-sm-3">
                <label class="visually-hidden" for="status">Status</label>
                <select class="form-select" id="status" name="status" onchange="this.form.submit()">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu" <?= ($status == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                    <option value="diterima" <?= ($status == 'diterima') ? 'selected' : '' ?>>Diterima</option>
                    <option value="selesai" <?= ($status == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                    <option value="batal" <?= ($status == 'batal') ? 'selected' : '' ?>>Batal</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?= base_url('admin-buku-tamu') ?>" class="btn btn-secondary">Reset</a>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#modal-export">
                    <i class="ti ti-download me-2"></i> Ekspor Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1">No</th>
                    <th>Waktu</th>
                    <th>Tamu</th>
                    <th>Jenis</th>
                    <th>Dituju</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; foreach($kunjungan as $k): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <span class="d-none"><?= $k['tanggal_waktu'] ?></span>
                        <?= date('d M Y H:i', strtotime($k['tanggal_waktu'])) ?>
                    </td>
                    <td>
                        <div class="font-weight-medium"><?= esc($k['nama_lengkap']) ?></div>
                        <div class="text-muted small"><?= esc($k['alamat_instansi']) ?></div>
                    </td>
                    <td>
                        <?php if($k['jenis_tamu'] == 'khusus'): ?>
                            <span class="badge bg-blue text-blue-fg">Dinas/Khusus</span>
                        <?php else: ?>
                            <span class="badge bg-secondary text-secondary-fg">Umum</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($k['nama_pegawai_dituju']): ?>
                            <div class="fw-medium"><?= esc($k['nama_pegawai_dituju']) ?></div>
                        <?php elseif(!empty($k['id_pegawai_dituju'])): ?>
                            <div class="fw-medium"><?= esc($k['id_pegawai_dituju']) ?></div>
                        <?php endif; ?>

                        <?php if(!empty($k['id_siswa_dituju'])): ?>
                            <div class="text-muted small"><i class="ti ti-school me-1"></i> Wali dari: <?= esc($k['id_siswa_dituju']) ?></div>
                        <?php endif; ?>

                        <?php if(empty($k['nama_pegawai_dituju']) && empty($k['id_pegawai_dituju']) && empty($k['id_siswa_dituju'])): ?>
                            <span class="text-muted">Tidak Spesifik</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-truncate" style="max-width: 150px;" title="<?= esc($k['tujuan_kunjungan']) ?>">
                        <?= esc($k['tujuan_kunjungan']) ?>
                    </td>
                    <td>
                        <?php
                            $stClass = 'secondary';
                            if($k['status_kunjungan'] == 'menunggu') $stClass = 'warning';
                            if($k['status_kunjungan'] == 'diterima') $stClass = 'primary';
                            if($k['status_kunjungan'] == 'selesai') $stClass = 'success';
                            if($k['status_kunjungan'] == 'batal') $stClass = 'danger';
                        ?>
                        <span class="badge bg-<?= $stClass ?>"><?= ucfirst($k['status_kunjungan']) ?></span>
                    </td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-icon btn-outline-info" onclick="showDetail(<?= $k['id_kunjungan'] ?>)" title="Lihat Detail">
                            <i class="ti ti-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-outline-danger ms-1" onclick="deleteKunjungan(<?= $k['id_kunjungan'] ?>)" title="Hapus Data">
                            <i class="ti ti-trash"></i>
                        </button>
                        <form id="delete-form-<?= $k['id_kunjungan'] ?>" action="<?= base_url('admin-buku-tamu/delete/' . $k['id_kunjungan']) ?>" method="POST" style="display: none;">
                            <?= csrf_field() ?>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('buku_tamu/admin/detail_modal') ?>

<!-- Modal Export -->
<div class="modal modal-blur fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ekspor Rekap Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-export" method="post" target="_blank">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select">
                                <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Bulan Awal</label>
                            <select name="bulan_awal" class="form-select">
                                <?php 
                                $bulans = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                foreach($bulans as $i => $b): 
                                ?>
                                    <option value="<?= $i+1 ?>" <?= (date('n') == $i+1) ? 'selected' : '' ?>><?= $b ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Bulan Akhir</label>
                            <select name="bulan_akhir" class="form-select">
                                <?php foreach($bulans as $i => $b): ?>
                                    <option value="<?= $i+1 ?>" <?= (date('n') == $i+1) ? 'selected' : '' ?>><?= $b ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="ti ti-info-circle me-1 text-info"></i> Laporan akan diunduh sesuai dengan rentang bulan yang dipilih.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" onclick="setExportAction('excel')" class="btn btn-success">
                        <i class="ti ti-file-spreadsheet me-2"></i> Excel
                    </button>
                    <button type="submit" onclick="setExportAction('pdf')" class="btn btn-danger">
                        <i class="ti ti-file-type-pdf me-2"></i> PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function setExportAction(type) {
        const form = document.getElementById('form-export');
        if (type === 'excel') {
            form.action = '<?= base_url("admin-buku-tamu/export-excel") ?>';
        } else {
            form.action = '<?= base_url("admin-buku-tamu/export-pdf") ?>';
        }
    }
</script>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables -->
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

    function showDetail(id) {
        $.get('<?= base_url("admin-buku-tamu/show") ?>/' + id, function(res) {
            if(res.status) {
                $('#modalDetailTamu .nama-tamu').text(res.tamu.nama_lengkap);
                $('#modalDetailTamu .asal-tamu').text(res.tamu.alamat_instansi);
                $('#modalDetailTamu .telepon-tamu').text(res.tamu.no_hp || '-');
                
                let imgHtml = '';
                const baseUrl = '<?= rtrim(base_url(), '/') ?>';
                
                if(res.kunjungan.foto_wajah) {
                    let path = res.kunjungan.foto_wajah.replace(/^\/+/, '');
                    let fotoUrl = res.kunjungan.foto_wajah.startsWith('data:') ? res.kunjungan.foto_wajah : baseUrl + '/' + path;
                    imgHtml += '<img src="'+fotoUrl+'" class="img-fluid rounded mb-2" alt="Foto Wajah">';
                }
                if(res.kunjungan.tanda_tangan) {
                    let path = res.kunjungan.tanda_tangan.replace(/^\/+/, '');
                    let ttdUrl = res.kunjungan.tanda_tangan.startsWith('data:') ? res.kunjungan.tanda_tangan : baseUrl + '/' + path;
                    imgHtml += '<img src="'+ttdUrl+'" class="img-fluid border rounded" style="background:#fff;" alt="Tanda Tangan">';
                }
                $('#modalDetailTamu .gambar-tamu').html(imgHtml);
                
                // Setup form update kunjungan
                $('#form-update-kunjungan').attr('action', '<?= base_url("admin-buku-tamu/update-kunjungan") ?>/' + id);
                $('#form-update-kunjungan select[name="status_kunjungan"]').val(res.kunjungan.status_kunjungan);
                $('#form-update-kunjungan textarea[name="tindak_lanjut"]').val(res.kunjungan.tindak_lanjut || '');
                
                var modal = new bootstrap.Modal(document.getElementById('modalDetailTamu'));
                modal.show();
            } else {
                alert('Data tidak ditemukan');
            }
        });
    }

    function deleteKunjungan(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data kunjungan dan file terkait akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
<?= $this->endSection() ?>
