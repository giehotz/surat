<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="">
            <!-- Header dengan Desain Lebih Bersih -->
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between g-3 mb-3 pb-3 border-bottom">
                <div>
                    <h3 class="mb-1">Daftar Surat Keluar</h3>
                    <p class="text-muted small mb-0">Manajemen dan pelacakan arsip surat keluar</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <div class="btn-list">
                        <!-- Group Export -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-download icon"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('surat-keluar/export-excel') ?>">
                                    <i class="ti ti-file-spreadsheet icon me-2 text-success"></i> Excel
                                </a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-export-pdf">
                                    <i class="ti ti-file-type-pdf icon me-2 text-danger"></i> PDF
                                </a>
                            </div>
                        </div>

                        <?php if (session()->get('role') !== 'pimpinan'): ?>
                            <a href="<?= base_url('surat-keluar/import') ?>" class="btn btn-outline-primary">
                                <i class="ti ti-upload icon"></i> Import
                            </a>
                            <form action="<?= base_url('surat-keluar/renumber') ?>" method="post" style="display:inline;" 
                                  onsubmit="return confirm('Urutkan ulang nomor surat? Data yang ada akan diurutkan ulang secara berurutan.');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="ti ti-sort-ascending icon"></i> Urutkan Ulang
                                </button>
                            </form>
                            <a href="<?= base_url('surat-keluar/create') ?>" class="btn btn-primary shadow-sm">
                                <i class="ti ti-plus icon"></i> Buat Surat
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Filter Area sebagai Toolbar -->
            <div class="border-bottom pb-4 mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted ">Dari Tanggal</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-calendar icon"></i></span>
                            <input type="date" id="filter_start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Sampai Tanggal</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-calendar icon"></i></span>
                            <input type="date" id="filter_end_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Status</label>
                            <select id="filter_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="draft">Draft</option>
                                <option value="disetujui">Disetujui</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="dikirim">Dikirim</option>
                            </select>
                    </div>
                    <div class="col-md-3">
                        <button type="button" id="btn-filter" class="btn btn-dark w-100">
                            <i class="ti ti-filter icon"></i> Filter Data
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Area -->
            <div class="p-0">
                <table id="table-surat-keluar" class="table table-sm table-vcenter table-striped table-hover mt-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-nowrap">No.</th>
                            <th class="text-nowrap">No. Agenda / Surat</th>
                            <th>Tujuan</th>
                            <th class="text-nowrap">Tgl Surat</th>
                            <th>Perihal</th>
                            <th class="text-nowrap">Status</th>
                            <th>Pembuat</th>
                            <th>Update</th>
                            <th class="text-center text-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- AJAX content -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview Dokumen -->
<div class="modal modal-blur fade" id="modal-preview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-dark-lt" style="height: 75vh;">
                <iframe id="preview-iframe" src="" width="100%" height="100%" frameborder="0"></iframe>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Tutup</button>
                <a id="btn-open-new-tab" href="#" target="_blank" class="btn btn-primary">
                    <i class="ti ti-external-link icon"></i> Buka Penuh
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Export PDF -->
<div class="modal modal-blur fade" id="modal-export-pdf" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-file-type-pdf text-danger me-2"></i>Export Laporan PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('surat-keluar/export-pdf') ?>" method="get" target="_blank">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label required small fw-bold">Bulan Awal</label>
                            <select name="start_month" class="form-select" required>
                                <?php
                                $bulan = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                                foreach ($bulan as $num => $nama): ?>
                                    <option value="<?= $num ?>"><?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label required small fw-bold">Bulan Akhir</label>
                            <select name="end_month" class="form-select" required>
                                <?php foreach ($bulan as $num => $nama): ?>
                                    <option value="<?= $num ?>"><?= $nama ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">Tahun Anggaran Aktif</label>
                            <input type="text" class="form-control bg-light" name="tahun_anggaran" value="<?= esc($appSettings['tahun_anggaran'] ?? date('Y')) ?>" readonly>
                            <small class="text-secondary mt-1 d-block"><i class="ti ti-info-circle"></i> Tahun mengikuti pengaturan sistem aktif.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="ti ti-file-download icon me-1"></i> Generate Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<style>
    /* Desain ulang tabel agar pas di desktop */
    #table-surat-keluar {
        border-collapse: collapse !important;
        margin-top: 0 !important;
    }

    #table-surat-keluar thead th {
        background: var(--tblr-bg-surface-secondary, #f6f8fb);
        text-transform: uppercase;
        font-size: 0.70rem;
        letter-spacing: 0.02em;
        color: var(--tblr-muted, #616876);
        padding: 8px 10px !important;
        border-top: none !important;
        border-bottom: 1px solid var(--tblr-border-color, #e6e8eb) !important;
    }

    [data-bs-theme="dark"] #table-surat-keluar thead th,
    html[data-bs-theme="dark"] #table-surat-keluar thead th {
        background: var(--tblr-bg-surface, #1e293b);
        color: var(--tblr-muted, #94a3b8);
        border-bottom-color: var(--tblr-border-color, #334155) !important;
    }


    /* Penanganan kolom Perihal yang panjang */
    #table-surat-keluar td.perihal-wrap {
        max-width: 250px;
        word-wrap: break-word;
        white-space: normal !important;
    }

    #table-surat-keluar td {
        font-size: 0.75rem; /* Ukuran font dikecilkan dari 0.875rem */
        vertical-align: middle;
        padding: 8px 10px; /* Kurangi padding bawaan */
    }

    /* Memaksa kolom status & aksi tetap dalam satu baris */
    #table-surat-keluar td.text-nowrap, #table-surat-keluar th.text-nowrap {
        white-space: nowrap !important;
    }

    /* Padding ekstra untuk dropdown atau tombol di paling kanan agar tidak terpotong (overflow) */
    #table-surat-keluar td:last-child {
        padding-right: 1.5rem !important;
    }

    /* Style tambahan untuk card hover (dihapus agar tidak konflik dengan dark mode) */

    /* Perbaikan Scrollbar Table */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 10px;
    }
</style>

<script>
    $(document).ready(function() {
        var table = $('#table-surat-keluar').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false, // Matikan autowidth agar CSS kita yang bekerja
            ajax: {
                url: "<?= base_url('surat-keluar/ajax-list') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                    d.status = $('#filter_status').val();
                }
            },
            columnDefs: [{
                    targets: [0, 8],
                    orderable: false,
                    searchable: false
                },
                {
                    className: "text-nowrap",
                    targets: [0, 1, 3, 5, 8]
                },
                {
                    width: "250px",
                    className: "perihal-wrap",
                    targets: 4
                } // Kunci lebar kolom Perihal
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                searchPlaceholder: "Cari nomor surat atau perihal..."
            },
            dom: "<'row px-3 pt-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });

        $('#btn-filter').click(function() {
            table.draw();
        });
    });

    function previewDokumen(url) {
        // Deteksi link cloud/eksternal yang tidak bisa di-embed dalam iframe
        // (Google Drive, Docs, OneDrive, dll menolak koneksi via X-Frame-Options)
        var isExternal = url.match(/drive\.google\.com|docs\.google\.com|onedrive\.live\.com|dropbox\.com|sharepoint\.com/i);
        
        if (isExternal) {
            // Buka langsung di tab baru
            window.open(url, '_blank');
        } else {
            // File lokal/server — tampilkan di iframe modal
            $('#preview-iframe').attr('src', url);
            $('#btn-open-new-tab').attr('href', url);
            $('#modal-preview').modal('show');
        }
    }
</script>
<?= $this->endSection() ?>