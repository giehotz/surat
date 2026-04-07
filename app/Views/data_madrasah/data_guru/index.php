<!-- Header Halaman -->
<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title">
                Data Guru & Tenaga Kependidikan
            </h2>
            <div class="text-muted mt-1">Kelola informasi profil, jabatan, dan riwayat kepegawaian.</div>
        </div>
        <div class="col-auto ms-auto d-print-none">
            <div class="btn-list">
                <a href="<?= base_url('data-guru/import') ?>" class="btn btn-outline-success">
                    <i class="ti ti-file-import icon me-2"></i> Import Excel
                </a>
                <a href="<?= base_url('data-guru/create') ?>" class="btn btn-primary d-none d-sm-inline-block">
                    <i class="ti ti-plus icon me-2"></i> Tambah Data
                </a>
                <a href="<?= base_url('data-guru/create') ?>" class="btn btn-primary d-sm-none btn-icon" aria-label="Tambah Data" data-bs-toggle="tooltip" title="Tambah Data">
                    <i class="ti ti-plus icon"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Notifikasi -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-important alert-success alert-dismissible" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-check icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-important alert-danger alert-dismissible" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-alert-triangle icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<!-- Tabel Data -->
<div class="card shadow-sm">
    <div class="card-header border-bottom-0">
        <h3 class="card-title">Daftar Pegawai</h3>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th class="w-1">No</th>
                    <th>Profil Pegawai</th>
                    <th>Status & Jabatan</th>
                    <th>Pendidikan</th>
                    <th>Masa Kerja</th>
                    <th>Estimasi Pensiun</th>
                    <th>Kontak</th>
                    <th class="w-1 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!function_exists('hitungMasaKerjaGuru')) {
                    function hitungMasaKerjaGuru($tanggal)
                    {
                        if (empty($tanggal) || $tanggal === '0000-00-00') return '-';
                        try {
                            $start = new DateTime($tanggal);
                            $now = new DateTime();
                            if ($start > $now) return '0 th 0 bln';
                            $diff = $now->diff($start);
                            return $diff->y . ' Tahun ' . $diff->m . ' Bulan';
                        } catch (Exception $e) {
                            return '-';
                        }
                    }
                }

                if (!function_exists('hitungTanggalPensiun')) {
                    function hitungTanggalPensiun($tanggal)
                    {
                        if (empty($tanggal) || $tanggal === '0000-00-00') return '-';
                        try {
                            $birth = new DateTime($tanggal);
                            $pensiun = clone $birth;
                            $pensiun->modify('+60 years');

                            $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                            return $days[(int)$pensiun->format('w')] . ', ' . $pensiun->format('d') . ' ' . $months[(int)$pensiun->format('m')] . ' ' . $pensiun->format('Y');
                        } catch (Exception $e) {
                            return '-';
                        }
                    }
                }

                if (isset($data_guru) && count($data_guru) > 0) : ?>
                    <?php $i = 1;
                    foreach ($data_guru as $guru) :
                        // Mengambil inisial nama untuk avatar
                        $nama = (string) esc($guru['nama_pegawai']);
                        $inisial = strtoupper(substr($nama, 0, 1) . (strpos($nama, ' ') !== false ? substr(explode(' ', $nama)[1], 0, 1) : ''));
                        // Warna avatar random berdasarkan huruf pertama
                        $colors = ['bg-blue-lt', 'bg-azure-lt', 'bg-indigo-lt', 'bg-purple-lt', 'bg-pink-lt', 'bg-red-lt', 'bg-orange-lt', 'bg-yellow-lt', 'bg-lime-lt', 'bg-green-lt', 'bg-teal-lt', 'bg-cyan-lt'];
                        $bgColor = $colors[ord(strtoupper($nama[0])) % count($colors)];
                    ?>
                        <tr>
                            <td class="text-muted"><?= $i++ ?></td>

                            <!-- Kolom Gabungan: Profil -->
                            <td>
                                <div class="d-flex py-1 align-items-center">
                                    <span class="avatar me-3 <?= $bgColor ?>"><?= $inisial ?></span>
                                    <div class="flex-fill">
                                        <div class="font-weight-medium fw-bold">
                                            <a href="<?= base_url('data-guru/berkas/' . $guru['id']) ?>" class="text-reset text-decoration-none" data-bs-toggle="tooltip" title="Kelola Berkas">
                                                <?= $nama ?>
                                            </a>
                                        </div>
                                        <div class="text-muted mt-1" style="font-size: 0.85rem; line-height: 1.4;">
                                            <div>NIP: <?= esc($guru['nip'] ?: '-') ?></div>
                                            <div>NUPTK: <?= esc($guru['peg_id_nuptk'] ?: '-') ?></div>
                                            <div>TTL: <?= esc($guru['tempat_tanggal_lahir'] ?: '-') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Gabungan: Status & Jabatan -->
                            <td>
                                <div>
                                    <?php if (strtolower($guru['status_kepegawaian']) == 'pns'): ?>
                                        <span class="badge bg-green text-green-fg mb-1">PNS</span>
                                    <?php elseif (in_array(strtolower($guru['status_kepegawaian']), ['honorer', 'gtt'])): ?>
                                        <span class="badge bg-orange text-orange-fg mb-1">Honorer</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary text-secondary-fg mb-1"><?= esc($guru['status_kepegawaian'] ?: 'Status Kosong') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted" style="font-size: 0.85rem;">
                                    <?= esc($guru['jabatan_mengajar'] ?: 'Jabatan belum diset') ?>
                                    <?php if (!empty($guru['pangkat_golongan'])): ?>
                                        <br> Gol: <span class="fw-medium text-dark"><?= esc($guru['pangkat_golongan']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <!-- Kolom Gabungan: Pendidikan -->
                            <td>
                                <?php if (!empty($guru['pendidikan_terakhir'])): ?>
                                    <div class="fw-medium"><?= esc($guru['pendidikan_terakhir']) ?></div>
                                    <div class="text-muted text-truncate" style="max-width: 150px; font-size: 0.85rem;" data-bs-toggle="tooltip" title="<?= esc($guru['perguruan_tinggi']) ?>">
                                        <?= esc($guru['perguruan_tinggi'] ?: '-') ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Gabungan: Masa Kerja -->
                            <td>
                                <div style="font-size: 0.85rem;">
                                    <div class="mb-1">
                                        <i class="ti ti-building text-muted me-1"></i> MIN: <span class="fw-medium"><?= hitungMasaKerjaGuru($guru['mulai_tugas']) ?></span>
                                    </div>
                                    <div>
                                        <i class="ti ti-briefcase text-muted me-1"></i> PNS: <span class="fw-medium"><?= hitungMasaKerjaGuru($guru['tmt_cpns_honorer']) ?></span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kolom Gabungan: Pensiun -->
                            <td>
                                <div class="font-weight-medium text-blue"><?= hitungTanggalPensiun($guru['tanggal_lahir']) ?></div>
                                <div class="text-muted small">Usia 60 Tahun</div>
                            </td>

                            <!-- Kolom Gabungan: Kontak -->
                            <td>
                                <div class="text-muted" style="font-size: 0.85rem;">
                                    <div class="mb-1">
                                        <i class="ti ti-mail icon text-muted me-1"></i> <?= esc($guru['email'] ?: 'Belum ada email') ?>
                                    </div>
                                    <div>
                                        <i class="ti ti-phone icon text-muted me-1"></i> <?= esc($guru['no_handphone'] ?: 'Belum ada No. HP') ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="text-end">
                                <div class="btn-list flex-nowrap justify-content-center">
                                    <a href="<?= base_url('data-guru/berkas/' . $guru['id']) ?>" class="btn btn-icon btn-sm btn-ghost-info" data-bs-toggle="tooltip" title="Berkas Dokumen">
                                        <i class="ti ti-folder icon"></i>
                                    </a>
                                    <a href="<?= base_url('data-guru/edit/' . $guru['id']) ?>" class="btn btn-icon btn-sm btn-ghost-primary" data-bs-toggle="tooltip" title="Edit Pegawai">
                                        <i class="ti ti-edit icon"></i>
                                    </a>
                                    <form action="<?= base_url('data-guru/delete/' . $guru['id']) ?>" method="post" class="d-inline form-delete">
                                        <?= csrf_field() ?>
                                        <button type="button" class="btn btn-icon btn-sm btn-ghost-danger btn-delete" data-bs-toggle="tooltip" title="Hapus Data">
                                            <i class="ti ti-trash icon"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <!-- Empty State UI -->
                    <tr>
                        <td colspan="7">
                            <div class="empty py-5">
                                <div class="empty-icon">
                                    <i class="ti ti-users text-muted" style="font-size: 3rem;"></i>
                                </div>
                                <p class="empty-title">Tidak ada data pegawai</p>
                                <p class="empty-subtitle text-muted">
                                    Belum ada data guru atau tenaga kependidikan yang ditambahkan ke dalam sistem.
                                </p>
                                <div class="empty-action">
                                    <a href="<?= base_url('data-guru/create') ?>" class="btn btn-primary">
                                        <i class="ti ti-plus icon me-2"></i> Tambah Data Pertama
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- PENTING: Pastikan Anda memuat script SweetAlert2 di layout/header Anda -->
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi Tooltip Bootstrap (Tabler default)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Inisialisasi DataTable
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().destroy();
        }

        $('.datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json",
                "search": "",
                "searchPlaceholder": "Cari pegawai..."
            },
            "pageLength": 25,
            "columnDefs": [{
                    "orderable": false,
                    "targets": [7]
                } // Nonaktifkan sorting untuk kolom Aksi
            ],
            "dom": "<'row mb-3 align-items-center'<'col-sm-12 col-md-6 d-flex align-items-center justify-content-start'l><'col-sm-12 col-md-6 d-flex align-items-center justify-content-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3 align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>"
        });

        // Konfirmasi Hapus Menggunakan SweetAlert2
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');

            // Cek apakah ada SweetAlert
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data pegawai ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                // Fallback jika SweetAlert tidak terinstall
                if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    form.submit();
                }
            }
        });
    });
</script>