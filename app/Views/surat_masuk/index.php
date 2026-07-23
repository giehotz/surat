<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="">
            <!-- Header -->
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between g-3 mb-3 pb-3 border-bottom">
                <div>
                    <h3 class="mb-1">Daftar Surat Masuk</h3>
                    <p class="text-muted small mb-0">Manajemen dan pelacakan arsip surat masuk</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <div class="btn-list">
                        <!-- Group Export -->
                        <div class="dropdown">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="ti ti-download icon"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('surat-masuk/export-excel') ?>">
                                    <i class="ti ti-file-spreadsheet icon me-2 text-success"></i> Excel
                                </a>
                                <a class="dropdown-item" href="<?= base_url('surat-masuk/export-pdf') ?>" target="_blank">
                                    <i class="ti ti-file-type-pdf icon me-2 text-danger"></i> PDF
                                </a>
                            </div>
                        </div>

                        <?php if (session()->get('role') === 'admin'): ?>
                            <button type="button" id="btn-reassign-agenda" class="btn btn-outline-warning" title="Urutkan ulang nomor agenda berdasarkan tanggal surat">
                                <i class="ti ti-arrows-sort icon"></i> Urutkan Agenda
                            </button>
                        <?php endif; ?>

                        <?php if (session()->get('role') !== 'pimpinan'): ?>
                            <a href="<?= base_url('surat-masuk/import') ?>" class="btn btn-outline-primary">
                                <i class="ti ti-upload icon"></i> Import
                            </a>
                            <a href="<?= base_url('surat-masuk/create') ?>" class="btn btn-primary shadow-sm">
                                <i class="ti ti-plus icon"></i> Tambah Surat
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Filter Area -->
            <div class="border-bottom pb-4 mb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Dari Tanggal</label>
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
                            <option value="tercatat">Tercatat</option>
                            <option value="didisposisikan">Didisposisikan</option>
                            <option value="selesai">Selesai</option>
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
                <table id="table-surat-masuk" class="table table-sm table-vcenter table-striped table-hover mt-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-nowrap" style="width:45px">No.</th>
                            <th class="text-nowrap">No. Agenda / Surat</th>
                            <th>Pengirim</th>
                            <th class="text-nowrap">Tgl Terima</th>
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<style>
    #table-surat-masuk {
        border-collapse: collapse !important;
        margin-top: 0 !important;
    }

    #table-surat-masuk thead th {
        background: var(--tblr-bg-surface-secondary, #f6f8fb);
        text-transform: uppercase;
        font-size: 0.70rem;
        letter-spacing: 0.02em;
        color: var(--tblr-muted, #616876);
        padding: 8px 10px !important;
        border-top: none !important;
        border-bottom: 1px solid var(--tblr-border-color, #e6e8eb) !important;
    }

    [data-bs-theme="dark"] #table-surat-masuk thead th,
    html[data-bs-theme="dark"] #table-surat-masuk thead th {
        background: var(--tblr-bg-surface, #1e293b);
        color: var(--tblr-muted, #94a3b8);
        border-bottom-color: var(--tblr-border-color, #334155) !important;
    }

    #table-surat-masuk td.perihal-wrap {
        max-width: 250px;
        word-wrap: break-word;
        white-space: normal !important;
    }

    #table-surat-masuk td {
        font-size: 0.75rem;
        vertical-align: middle;
        padding: 8px 10px;
    }

    #table-surat-masuk td.text-nowrap, #table-surat-masuk th.text-nowrap {
        white-space: nowrap !important;
    }

    #table-surat-masuk td:last-child {
        padding-right: 1.5rem !important;
    }

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
        var table = $('#table-surat-masuk').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: "<?= base_url('surat-masuk/ajax-list') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                    d.status = $('#filter_status').val();
                }
            },
            columnDefs: [
                {
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
                }
            ],
            order: [[1, 'asc']],
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

        // Tombol Urutkan Agenda (admin only)
        $('#btn-reassign-agenda').click(function() {
            Swal.fire({
                title: 'Urutkan Ulang No. Agenda?',
                text: 'Nomor agenda akan diurutkan ulang berdasarkan tanggal surat (kronologis). Proses ini akan mengubah nomor agenda yang sudah ada.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e6a919',
                confirmButtonText: '<i class="ti ti-arrows-sort"></i> Ya, Urutkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    var btn = $('#btn-reassign-agenda');
                    btn.prop('disabled', true).html('<i class="ti ti-loader icon spin"></i> Memproses...');

                    $.ajax({
                        url: '<?= base_url("surat-masuk/reassign-agenda") ?>',
                        type: 'POST',
                        data: { <?= csrf_token() ?>: '<?= csrf_hash() ?>' },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    timer: 2500,
                                    showConfirmButton: false
                                });
                                table.draw();
                            } else {
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghubungi server.', 'error');
                        },
                        complete: function() {
                            btn.prop('disabled', false).html('<i class="ti ti-arrows-sort icon"></i> Urutkan Agenda');
                        }
                    });
                }
            });
        });
    });

    function previewDokumen(url) {
        var isExternal = url.match(/drive\.google\.com|docs\.google\.com|onedrive\.live\.com|dropbox\.com|sharepoint\.com/i);

        if (isExternal) {
            window.open(url, '_blank');
        } else {
            $('#preview-iframe').attr('src', url);
            $('#btn-open-new-tab').attr('href', url);
            $('#modal-preview').modal('show');
        }
    }
</script>
<?= $this->endSection() ?>
