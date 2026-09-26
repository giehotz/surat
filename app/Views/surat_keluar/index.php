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
                                <i class="ti ti-download me-1.5"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= base_url('surat-keluar/export-excel') ?>">
                                    <i class="ti ti-file-spreadsheet me-2 text-success"></i> Excel
                                </a>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modal-export-pdf">
                                    <i class="ti ti-file-type-pdf me-2 text-danger"></i> PDF
                                </a>
                            </div>
                        </div>

                        <?php if (session()->get('role') !== 'pimpinan'): ?>
                            <a href="<?= base_url('surat-keluar/import') ?>" class="btn btn-outline-primary">
                                <i class="ti ti-upload me-1.5"></i> Import
                            </a>
                            <form action="<?= base_url('surat-keluar/renumber') ?>" method="post" style="display:inline;" 
                                  onsubmit="return confirm('Urutkan ulang nomor surat? Data yang ada akan diurutkan ulang secara berurutan.');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="ti ti-sort-ascending me-1.5"></i> Urutkan Ulang
                                </button>
                            </form>
                            <a href="<?= base_url('surat-resmi') ?>" class="btn btn-outline-info shadow-sm">
                                <i class="ti ti-file-certificate me-1.5"></i> Buat Surat Resmi
                            </a>
                            <a href="<?= base_url('surat-keluar/create') ?>" class="btn btn-primary shadow-sm">
                                <i class="ti ti-plus me-1.5"></i> Catat Surat Keluar
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
                            <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                            <input type="date" id="filter_start_date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-muted">Sampai Tanggal</label>
                        <div class="input-icon">
                            <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
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
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-filter" class="btn btn-primary flex-fill">
                                <i class="ti ti-filter me-1.5"></i> Filter Data
                            </button>
                            <button type="button" id="btn-reset-filter" class="btn btn-outline-secondary" title="Reset Filter & Pagination">
                                <i class="ti ti-rotate-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Area -->
            <div class="p-0">
                <!-- Bulk Action Bar -->
                <?php if (session()->get('role') === 'pimpinan' || session()->get('role') === 'admin'): ?>
                <div id="bulk-actions" class="d-none px-3 py-2 mb-3 bg-primary-lt rounded-2 border border-primary-subtle shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-secondary fw-medium">Dipilih:</span>
                        <span id="bulk-count" class="badge bg-primary text-primary-fg fs-6 px-2.5 py-1">0</span>
                    </div>
                    <div class="btn-list">
                        <button type="button" id="btn-bulk-approve" class="btn btn-success btn-sm">
                            <i class="ti ti-check me-1.5"></i> Setujui
                        </button>
                        <button type="button" id="btn-bulk-reject" class="btn btn-danger btn-sm">
                            <i class="ti ti-x me-1.5"></i> Tolak
                        </button>
                        <button type="button" id="btn-bulk-delete" class="btn btn-outline-danger btn-sm">
                            <i class="ti ti-trash me-1.5"></i> Hapus
                        </button>
                        <button type="button" id="btn-bulk-clear" class="btn btn-ghost-secondary btn-sm">
                            <i class="ti ti-rotate-2 me-1.5"></i> Batal
                        </button>
                    </div>
                </div>
                <?php endif; ?>
                <table id="table-surat-keluar" class="table table-sm table-vcenter table-striped table-hover mt-0 w-100">
                    <thead>
                        <tr>
                            <th class="text-center" style="width:40px"><input type="checkbox" id="select-all" class="form-check-input m-0"></th>
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
        var role = '<?= session()->get("role") ?>';
        var isApprover = (role === 'pimpinan' || role === 'admin');

        // ✨ PAGINATION & FILTER STATE MANAGER - LocalStorage
        const paginationKey = 'surat_keluar_pagination_state';

        // Pulihkan nilai input filter dari localStorage SEBELUM inisialisasi DataTable
        // Agar request AJAX perdana langsung mengirim filter yang aktif
        function restoreFiltersFromStorage() {
            try {
                const raw = localStorage.getItem(paginationKey);
                if (!raw) return;
                const state = JSON.parse(raw);
                if (state) {
                    const startDate = state.filter_start_date || state.startDate || '';
                    const endDate   = state.filter_end_date   || state.endDate   || '';
                    const status    = state.filter_status     || state.status    || '';

                    if (startDate) $('#filter_start_date').val(startDate);
                    if (endDate)   $('#filter_end_date').val(endDate);
                    if (status)    $('#filter_status').val(status);
                }
            } catch (e) {
                console.warn('Gagal membaca state filter:', e);
            }
        }

        restoreFiltersFromStorage();

        var table = $('#table-surat-keluar').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            stateSave: true,
            stateDuration: 0, // 0 = simpan permanen di localStorage tanpa kedaluwarsa waktu
            stateSaveCallback: function(settings, data) {
                // Simpan filter kustom bersama state internal DataTables
                data.filter_start_date = $('#filter_start_date').val();
                data.filter_end_date   = $('#filter_end_date').val();
                data.filter_status     = $('#filter_status').val();

                // Hapus state columns agar tidak bentrok dengan visibilitas role (admin vs non-admin)
                delete data.columns;

                try {
                    localStorage.setItem(paginationKey, JSON.stringify(data));
                } catch (e) {
                    console.warn('Gagal menyimpan state tabel:', e);
                }
            },
            stateLoadCallback: function(settings) {
                try {
                    const raw = localStorage.getItem(paginationKey);
                    if (!raw) return null;
                    const data = JSON.parse(raw);

                    // Kompatibilitas dengan data state format lama
                    if (data.page !== undefined && data.start === undefined) {
                        data.start = data.page;
                    }
                    if (data.pageLength !== undefined && data.length === undefined) {
                        data.length = data.pageLength;
                    }
                    if (data.timestamp && !data.time) {
                        data.time = data.timestamp;
                    }

                    // Hapus state columns
                    delete data.columns;

                    return data;
                } catch (e) {
                    console.warn('Gagal memuat state tabel:', e);
                    return null;
                }
            },
            ajax: {
                url: "<?= base_url('surat-keluar/ajax-list') ?>",
                type: "POST",
                data: function(d) {
                    d.<?= csrf_token() ?> = "<?= csrf_hash() ?>";
                    d.start_date = $('#filter_start_date').val();
                    d.end_date   = $('#filter_end_date').val();
                    d.status     = $('#filter_status').val();
                }
            },
            columnDefs: [
                {
                    targets: 0,
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    width: '40px',
                    visible: isApprover
                },
                {
                    targets: [0, 1, 9],
                    orderable: false,
                    searchable: false
                },
                {
                    className: "text-nowrap",
                    targets: [0, 1, 2, 4, 6, 9]
                },
                {
                    width: "250px",
                    className: "perihal-wrap",
                    targets: 5
                }
            ],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                searchPlaceholder: "Cari nomor surat atau perihal..."
            },
            dom: "<'row px-3 pt-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            drawCallback: function() {
                updateBulkUI();
                $('#select-all').prop('checked', false);
            }
        });

        // Penyesuaian otomatis jika posisi start melebihi total data hasil filter (misal setelah hapus data)
        table.on('xhr.dt', function(e, settings, json) {
            if (json && json.recordsFiltered !== undefined && json.recordsFiltered > 0) {
                if (settings._iDisplayStart >= json.recordsFiltered) {
                    var lastPage = Math.floor((json.recordsFiltered - 1) / settings._iDisplayLength);
                    table.page(lastPage).draw(false);
                }
            }
        });

        // Select All
        $(document).on('click', '#select-all', function() {
            var checked = $(this).prop('checked');
            $('.row-checkbox:visible').prop('checked', checked);
            updateBulkUI();
        });

        // Individual checkbox
        $(document).on('change', '.row-checkbox', function() {
            updateBulkUI();
            var total = $('.row-checkbox:visible').length;
            var selected = $('.row-checkbox:visible:checked').length;
            $('#select-all').prop('checked', total > 0 && total === selected);
        });

        function updateBulkUI() {
            var count = $('.row-checkbox:visible:checked').length;
            if (isApprover && count > 0) {
                $('#bulk-actions').removeClass('d-none');
                $('#bulk-count').text(count);
            } else {
                $('#bulk-actions').addClass('d-none');
            }
        }

        // Clear selection
        $('#btn-bulk-clear').on('click', function() {
            $('.row-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkUI();
        });

        // Bulk Approve
        $('#btn-bulk-approve').on('click', function() {
            var ids = getSelectedIds();
            if (!ids.length) return;

            Swal.fire({
                title: 'Setujui ' + ids.length + ' surat?',
                text: 'Semua surat yang dipilih akan disetujui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    doBulkAction(ids, 'approve');
                }
            });
        });

        // Bulk Reject
        $('#btn-bulk-reject').on('click', function() {
            var ids = getSelectedIds();
            if (!ids.length) return;

            Swal.fire({
                title: 'Tolak ' + ids.length + ' surat?',
                text: 'Semua surat yang dipilih akan ditolak.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    doBulkAction(ids, 'reject');
                }
            });
        });

        // Bulk Delete dengan Konfirmasi Peringatan
        $('#btn-bulk-delete').on('click', function() {
            var ids = getSelectedIds();
            if (!ids.length) {
                Swal.fire({
                    title: 'Peringatan',
                    text: 'Silakan pilih setidaknya satu surat keluar yang ingin dihapus.',
                    icon: 'warning',
                    confirmButtonColor: '#206bc4',
                    confirmButtonText: 'Mengerti'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Hapus Massal',
                html: `
                    <div class="text-center">
                        <div class="alert alert-danger py-2 px-3 text-start small mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="ti ti-alert-triangle fs-2 text-danger me-2"></i>
                                <strong class="text-danger">PERINGATAN: TINDAKAN PERMANEN!</strong>
                            </div>
                            <div class="text-secondary">
                                Anda akan menghapus <strong class="text-dark">${ids.length} surat keluar</strong> yang dipilih. File berkas lampiran yang tersimpan di server lokal juga akan ikut terhapus.
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Data yang sudah dihapus <strong>tidak dapat dipulihkan kembali</strong>. Apakah Anda benar-benar yakin ingin melanjutkan?</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63939',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus ' + ids.length + ' Surat',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    doBulkDelete(ids);
                }
            });
        });

        function getSelectedIds() {
            var ids = [];
            $('.row-checkbox:visible:checked').each(function() {
                ids.push($(this).data('id'));
            });
            return ids;
        }

        function doBulkAction(ids, actionType) {
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = '<?= csrf_hash() ?>';

            Swal.fire({
                title: 'Memproses...',
                allowOutsideClick: false,
                didOpen: function() { Swal.showLoading(); }
            });

            $.ajax({
                url: "<?= base_url('surat-keluar/bulk-approve') ?>",
                type: "POST",
                data: {
                    ids: ids,
                    action_type: actionType,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        // Clear checkboxes and reload
                        $('.row-checkbox').prop('checked', false);
                        $('#select-all').prop('checked', false);
                        updateBulkUI();
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    var errorMsg = 'Terjadi kesalahan server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal', errorMsg, 'error');
                }
            });
        }

        function doBulkDelete(ids) {
            var csrfName = '<?= csrf_token() ?>';
            var csrfHash = '<?= csrf_hash() ?>';

            Swal.fire({
                title: 'Memproses penghapusan...',
                text: 'Mohon tunggu, sedang menghapus ' + ids.length + ' data.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: function() { Swal.showLoading(); }
            });

            $.ajax({
                url: "<?= base_url('surat-keluar/bulk-delete') ?>",
                type: "POST",
                data: {
                    ids: ids,
                    [csrfName]: csrfHash
                },
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        $('.row-checkbox').prop('checked', false);
                        $('#select-all').prop('checked', false);
                        updateBulkUI();
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal Menghapus', response.message || 'Terjadi kesalahan saat menghapus data.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    var errorMsg = 'Terjadi kesalahan server saat memproses penghapusan.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire('Gagal', errorMsg, 'error');
                }
            });
        }

        // Konfirmasi Hapus Satuan (Single Delete) menggunakan SweetAlert2
        $(document).on('click', '.btn-delete-single', function(e) {
            e.preventDefault();
            var btn = $(this);
            var form = btn.closest('form');
            var nomorSurat = btn.data('nomor') ? ('nomor "' + btn.data('nomor') + '"') : 'surat keluar ini';

            Swal.fire({
                title: 'Hapus Surat Keluar?',
                html: `
                    <div class="text-center">
                        <div class="alert alert-danger py-2 px-3 text-start small mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="ti ti-alert-triangle fs-2 text-danger me-2"></i>
                                <strong class="text-danger">PERINGATAN!</strong>
                            </div>
                            <div class="text-secondary">
                                Data ${nomorSurat} akan dihapus secara permanen beserta berkas lampiran yang tersimpan.
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Tindakan ini <strong>tidak dapat dibatalkan</strong>. Apakah Anda yakin ingin menghapus surat ini?</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63939',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: function() { Swal.showLoading(); }
                    });

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        dataType: 'json',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        success: function(response) {
                            Swal.close();
                            if (response.success) {
                                Toast.fire({
                                    icon: 'success',
                                    title: response.message || 'Surat berhasil dihapus'
                                });
                                table.ajax.reload(null, false);
                            } else {
                                Swal.fire('Gagal Menghapus', response.message || 'Gagal menghapus surat.', 'error');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 200 || xhr.status === 302) {
                                form.submit();
                                return;
                            }
                            Swal.close();
                            var errorMsg = 'Terjadi kesalahan server saat memproses penghapusan.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            Swal.fire('Gagal', errorMsg, 'error');
                        }
                    });
                }
            });
        });

        // Tombol Filter: terapkan filter dan kembali ke halaman pertama
        $('#btn-filter').on('click', function() {
            table.page(0).draw(false);
        });

        // Terapkan filter saat tekan Enter di input tanggal
        $('#filter_start_date, #filter_end_date').on('keypress', function(e) {
            if (e.which === 13) {
                table.page(0).draw(false);
            }
        });

        // Tombol Reset Filter & Pagination
        $('#btn-reset-filter').on('click', function() {
            $('#filter_start_date').val('');
            $('#filter_end_date').val('');
            $('#filter_status').val('');
            localStorage.removeItem(paginationKey);
            table.search('').page(0).draw(false);
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
