<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between g-3 mb-4 pb-2 border-bottom">
    <div>
        <h3 class="mb-1 d-flex align-items-center">
            <i class="ti ti-address-book me-2 text-primary fs-2"></i> <?= esc($title) ?>
        </h3>
        <p class="text-muted small mb-0">Manajemen pencatatan, verifikasi, dan pelacakan riwayat kunjungan tamu madrasah</p>
    </div>
    <div class="mt-3 mt-md-0 btn-list">
        <a href="<?= base_url('buku-tamu') ?>" target="_blank" class="btn btn-outline-primary shadow-sm">
            <i class="ti ti-external-link me-1.5"></i> Buka Portal Resepsionis
        </a>
        <button type="button" class="btn btn-outline-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modal-export">
            <i class="ti ti-download me-1.5"></i> Ekspor Laporan
        </button>
    </div>
</div>

<!-- 4 Kartu KPI Interaktif -->
<div class="row row-cards mb-4">
    <!-- Card 1: Tamu Hari Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm card-kpi border-start border-start-4 border-start-primary shadow-sm cursor-pointer" onclick="filterByKpi('today')" title="Klik untuk memfilter kunjungan hari ini">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-primary text-white avatar shadow-sm rounded-3">
                            <i class="ti ti-users fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-uppercase text-muted fw-bold small">Tamu Hari Ini</div>
                        <div class="h2 mb-0 fw-bolder text-dark" id="kpi-hari-ini"><?= esc($stats['tamu_hari_ini'] ?? 0) ?></div>
                        <div class="text-muted small">Kunjungan tercatat hari ini</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Sedang Menunggu -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm card-kpi border-start border-start-4 border-start-warning shadow-sm cursor-pointer" onclick="filterByKpi('menunggu')" title="Klik untuk memfilter status Menunggu">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-warning text-white avatar shadow-sm rounded-3">
                            <i class="ti ti-clock-pause fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-uppercase text-muted fw-bold small">Sedang Menunggu</div>
                        <div class="h2 mb-0 fw-bolder text-warning" id="kpi-menunggu"><?= esc($stats['sedang_menunggu'] ?? 0) ?></div>
                        <div class="text-muted small">Perlu konfirmasi / dilayani</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Sedang Dilayani -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm card-kpi border-start border-start-4 border-start-info shadow-sm cursor-pointer" onclick="filterByKpi('diterima')" title="Klik untuk memfilter status Dilayani">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-info text-white avatar shadow-sm rounded-3">
                            <i class="ti ti-user-check fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-uppercase text-muted fw-bold small">Sedang Dilayani</div>
                        <div class="h2 mb-0 fw-bolder text-info" id="kpi-dilayani"><?= esc($stats['sedang_dilayani'] ?? 0) ?></div>
                        <div class="text-muted small">Sedang dalam pertemuan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Total Bulan Ini -->
    <div class="col-sm-6 col-lg-3">
        <div class="card card-sm card-kpi border-start border-start-4 border-start-success shadow-sm cursor-pointer" onclick="filterByKpi('month')" title="Klik untuk memfilter kunjungan bulan ini">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="bg-success text-white avatar shadow-sm rounded-3">
                            <i class="ti ti-calendar-event fs-2"></i>
                        </span>
                    </div>
                    <div class="col">
                        <div class="text-uppercase text-muted fw-bold small">Total Bulan Ini</div>
                        <div class="h2 mb-0 fw-bolder text-success" id="kpi-bulan-ini"><?= esc($stats['total_bulan_ini'] ?? 0) ?></div>
                        <div class="text-muted small">Bulan <?= date('F Y') ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toolbar Filter Area -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body py-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Dari Tanggal</label>
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                    <input type="date" id="filter_start_date" class="form-control">
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Sampai Tanggal</label>
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="ti ti-calendar"></i></span>
                    <input type="date" id="filter_end_date" class="form-control">
                </div>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Jenis Tamu</label>
                <select id="filter_jenis_tamu" class="form-select">
                    <option value="">Semua Jenis</option>
                    <option value="khusus">Dinas / Khusus</option>
                    <option value="umum">Umum / Wali Murid</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-6">
                <label class="form-label small fw-bold text-uppercase text-muted">Status</label>
                <select id="filter_status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diterima">Dilayani</option>
                    <option value="selesai">Selesai</option>
                    <option value="batal">Batal</option>
                </select>
            </div>
            <div class="col-md-2 col-sm-12">
                <div class="d-flex gap-2">
                    <button type="button" id="btn-filter" class="btn btn-primary flex-fill">
                        <i class="ti ti-filter me-1.5"></i> Filter
                    </button>
                    <button type="button" id="btn-reset-filter" class="btn btn-outline-secondary" title="Reset Filter">
                        <i class="ti ti-rotate-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Bar -->
<div id="bulk-actions" class="d-none px-3 py-2 mb-3 bg-primary-lt rounded-2 border border-primary-subtle shadow-sm d-flex flex-wrap align-items-center justify-content-between gap-2">
    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary fw-medium">Dipilih:</span>
        <span id="bulk-count" class="badge bg-primary text-primary-fg fs-6 px-2.5 py-1">0</span>
    </div>
    <div class="btn-list">
        <button type="button" id="btn-bulk-selesai" class="btn btn-success btn-sm">
            <i class="ti ti-check me-1.5"></i> Tandai Selesai
        </button>
        <button type="button" id="btn-bulk-delete" class="btn btn-outline-danger btn-sm">
            <i class="ti ti-trash me-1.5"></i> Hapus Massal
        </button>
        <button type="button" id="btn-bulk-clear" class="btn btn-ghost-secondary btn-sm">
            <i class="ti ti-x me-1.5"></i> Batal
        </button>
    </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table id="table-buku-tamu" class="table table-sm table-vcenter table-striped table-hover mt-0 w-100">
            <thead>
                <tr>
                    <th class="text-center" style="width: 35px;"><input type="checkbox" id="select-all" class="form-check-input m-0"></th>
                    <th style="width: 40px;">No.</th>
                    <th class="text-nowrap">Waktu</th>
                    <th>Nama Tamu & Asal</th>
                    <th class="text-nowrap">Jenis</th>
                    <th>Dituju</th>
                    <th>Tujuan</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-end text-nowrap" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loaded via DataTables Server-Side AJAX -->
            </tbody>
        </table>
    </div>
</div>

<?= $this->include('buku_tamu/admin/detail_modal') ?>

<!-- Modal Export -->
<div class="modal modal-blur fade" id="modal-export" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header bg-light">
                <h5 class="modal-title"><i class="ti ti-file-export me-2 text-primary"></i> Ekspor Rekap Buku Tamu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-export" method="post" target="_blank">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label required small fw-bold">Tahun</label>
                            <select name="tahun" class="form-select" required>
                                <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                                    <option value="<?= $y ?>"><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label required small fw-bold">Bulan Awal</label>
                            <select name="bulan_awal" class="form-select" required>
                                <?php 
                                $bulans = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                foreach($bulans as $i => $b): 
                                ?>
                                    <option value="<?= $i+1 ?>" <?= (date('n') == $i+1) ? 'selected' : '' ?>><?= $b ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label required small fw-bold">Bulan Akhir</label>
                            <select name="bulan_akhir" class="form-select" required>
                                <?php foreach($bulans as $i => $b): ?>
                                    <option value="<?= $i+1 ?>" <?= (date('n') == $i+1) ? 'selected' : '' ?>><?= $b ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="ti ti-info-circle me-1 text-info"></i> Laporan akan digenerate sesuai dengan periode rentang bulan dan tahun yang Anda tentukan.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-link link-secondary me-auto" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" onclick="setExportAction('excel')" class="btn btn-success px-3">
                        <i class="ti ti-file-spreadsheet me-1.5"></i> Unduh Excel
                    </button>
                    <button type="submit" onclick="setExportAction('pdf')" class="btn btn-danger px-3">
                        <i class="ti ti-file-type-pdf me-1.5"></i> Unduh PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables Bootstrap 5 -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<style>
    .card-kpi {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-kpi:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.08) !important;
    }
    #table-buku-tamu thead th {
        background: var(--tblr-bg-surface-secondary, #f6f8fb);
        text-transform: uppercase;
        font-size: 0.70rem;
        letter-spacing: 0.02em;
        color: var(--tblr-muted, #616876);
        padding: 9px 10px !important;
    }
    #table-buku-tamu td {
        font-size: 0.80rem;
        vertical-align: middle;
        padding: 8px 10px;
    }
</style>

<script>
    var table;
    const csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';

    $(document).ready(function() {
        // Inisialisasi Server-Side DataTables
        table = $('#table-buku-tamu').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            ajax: {
                url: "<?= base_url('admin-buku-tamu/ajax-list') ?>",
                type: "POST",
                data: function(d) {
                    d[csrfName] = csrfHash;
                    d.start_date = $('#filter_start_date').val();
                    d.end_date = $('#filter_end_date').val();
                    d.jenis_tamu = $('#filter_jenis_tamu').val();
                    d.status_kunjungan = $('#filter_status').val();
                }
            },
            columnDefs: [
                { targets: [0, 1, 8], orderable: false, searchable: false },
                { targets: [0, 1], className: 'text-center' },
                { targets: [2, 4, 7, 8], className: 'text-nowrap' }
            ],
            order: [[2, 'desc']],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json",
                searchPlaceholder: "Cari nama tamu, instansi, NIP, no HP..."
            },
            dom: "<'row px-3 pt-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row px-3 pb-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            drawCallback: function() {
                updateBulkUI();
                $('#select-all').prop('checked', false);
            }
        });

        // Filter button click
        $('#btn-filter').on('click', function() {
            table.page(0).draw(false);
        });

        // Reset Filter
        $('#btn-reset-filter').on('click', function() {
            $('#filter_start_date').val('');
            $('#filter_end_date').val('');
            $('#filter_jenis_tamu').val('');
            $('#filter_status').val('');
            table.search('').page(0).draw(false);
        });

        // Enter key in filter inputs
        $('#filter_start_date, #filter_end_date').on('keypress', function(e) {
            if (e.which === 13) table.page(0).draw(false);
        });

        // Checkbox Select All
        $(document).on('click', '#select-all', function() {
            var checked = $(this).prop('checked');
            $('.row-checkbox:visible').prop('checked', checked);
            updateBulkUI();
        });

        // Individual checkbox change
        $(document).on('change', '.row-checkbox', function() {
            updateBulkUI();
            var total = $('.row-checkbox:visible').length;
            var selected = $('.row-checkbox:visible:checked').length;
            $('#select-all').prop('checked', total > 0 && total === selected);
        });

        // Bulk Clear
        $('#btn-bulk-clear').on('click', function() {
            $('.row-checkbox').prop('checked', false);
            $('#select-all').prop('checked', false);
            updateBulkUI();
        });

        // Bulk Selesai
        $('#btn-bulk-selesai').on('click', function() {
            var ids = getSelectedIds();
            if (!ids.length) return;

            Swal.fire({
                title: 'Tandai Selesai ' + ids.length + ' Kunjungan?',
                text: 'Semua kunjungan terpilih akan diselesaikan statusnya.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2fb344',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tandai Selesai',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    doBulkUpdateStatus(ids, 'selesai');
                }
            });
        });

        // Bulk Delete dengan Konfirmasi Peringatan
        $('#btn-bulk-delete').on('click', function() {
            var ids = getSelectedIds();
            if (!ids.length) return;

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
                                Anda akan menghapus <strong class="text-dark">${ids.length} data kunjungan</strong> secara permanen. File foto wajah, tanda tangan, dan dokumen pendukung di server juga akan ikut terhapus.
                            </div>
                        </div>
                        <p class="text-muted small mb-0">Tindakan ini <strong>tidak dapat dibatalkan</strong>. Lanjutkan penghapusan?</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d63939',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus ' + ids.length + ' Data',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    doBulkDelete(ids);
                }
            });
        });

        // Form Submit Modal Update Kunjungan
        $('#form-update-kunjungan').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#btn-save-kunjungan');
            btn.prop('disabled', true).html('<i class="ti ti-loader icon spin me-1"></i> Menyimpan...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1.5"></i> Simpan Pembaruan');
                    if (res.success) {
                        $('#modalDetailTamu').modal('hide');
                        Toast.fire({
                            icon: 'success',
                            title: res.message || 'Data kunjungan berhasil diperbarui'
                        });
                        table.ajax.reload(null, false);
                    } else {
                        Swal.fire('Gagal', res.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="ti ti-device-floppy me-1.5"></i> Simpan Pembaruan');
                    var errorMsg = 'Terjadi kesalahan server saat memperbarui data.';
                    if (xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    Swal.fire('Gagal', errorMsg, 'error');
                }
            });
        });
    });

    // Filter instan via klik kartu KPI
    function filterByKpi(type) {
        var today = new Date().toISOString().slice(0, 10);
        if (type === 'today') {
            $('#filter_start_date').val(today);
            $('#filter_end_date').val(today);
            $('#filter_status').val('');
        } else if (type === 'menunggu') {
            $('#filter_status').val('menunggu');
        } else if (type === 'diterima') {
            $('#filter_status').val('diterima');
        } else if (type === 'month') {
            var date = new Date();
            var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).toISOString().slice(0, 10);
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().slice(0, 10);
            $('#filter_start_date').val(firstDay);
            $('#filter_end_date').val(lastDay);
            $('#filter_status').val('');
        }
        table.page(0).draw(false);
    }

    function updateBulkUI() {
        var count = $('.row-checkbox:visible:checked').length;
        if (count > 0) {
            $('#bulk-actions').removeClass('d-none');
            $('#bulk-count').text(count);
        } else {
            $('#bulk-actions').addClass('d-none');
        }
    }

    function getSelectedIds() {
        var ids = [];
        $('.row-checkbox:visible:checked').each(function() {
            ids.push($(this).data('id'));
        });
        return ids;
    }

    // Quick Update Status (Terima / Selesai langsung dari baris tabel)
    function quickUpdateStatus(id, newStatus) {
        var actionLabel = (newStatus === 'diterima') ? 'Mulai Layanan / Terima' : 'Selesaikan Kunjungan';
        Swal.fire({
            title: actionLabel + '?',
            text: 'Status kunjungan tamu ini akan diubah menjadi "' + newStatus + '".',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: (newStatus === 'diterima') ? '#206bc4' : '#2fb344',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Ubah Status',
            cancelButtonText: 'Batal'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url("admin-buku-tamu/quick-update-status") ?>',
                    type: 'POST',
                    data: {
                        id: id,
                        status: newStatus,
                        [csrfName]: csrfHash
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.success) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
            }
        });
    }

    // Modal Detail Kunjungan
    function showDetail(id) {
        $.get('<?= base_url("admin-buku-tamu/show") ?>/' + id, function(res) {
            if (res.status && res.kunjungan) {
                var k = res.kunjungan;
                var t = res.kunjungan; // data tamu tergabung dalam kunjungan

                $('#modalDetailTamu .nama-tamu').text(t.nama_lengkap || '-');
                $('#modalDetailTamu .asal-tamu').text(t.alamat_instansi || '-');
                $('#modalDetailTamu .telepon-tamu').text(t.no_hp || '-');
                $('#modalDetailTamu .waktu-tamu').text(k.tanggal_waktu || '-');

                // Info NIP & Jabatan
                var nipJabatan = [];
                if (t.nip) nipJabatan.push('NIP: ' + t.nip);
                if (t.jabatan) nipJabatan.push(t.jabatan);
                $('#modalDetailTamu .info-nip-jabatan').text(nipJabatan.join(' • '));

                // Badge Jenis Tamu
                if (t.jenis_tamu === 'khusus') {
                    $('#modalDetailTamu .badge-jenis-tamu').html('<span class="badge bg-blue-lt px-2 py-1"><i class="ti ti-building me-1"></i>Tamu Dinas / Khusus</span>');
                } else {
                    $('#modalDetailTamu .badge-jenis-tamu').html('<span class="badge bg-secondary-lt px-2 py-1"><i class="ti ti-user me-1"></i>Tamu Umum / Wali Murid</span>');
                }

                // WhatsApp Direct Link
                if (res.wa_link) {
                    $('#modalDetailTamu .btn-wa-direct').attr('href', res.wa_link).removeClass('d-none');
                } else {
                    $('#modalDetailTamu .btn-wa-direct').addClass('d-none');
                }

                // Foto & TTD
                var imgHtml = '';
                var baseUrl = '<?= rtrim(base_url(), "/") ?>';

                if (k.foto_wajah) {
                    var fotoUrl = k.foto_wajah.startsWith('data:') ? k.foto_wajah : baseUrl + '/' + k.foto_wajah.replace(/^\/+/, '');
                    imgHtml += '<div class="mb-2"><span class="small text-muted d-block mb-1">Foto Selfie Kehadiran:</span><img src="' + fotoUrl + '" class="img-fluid rounded border shadow-sm" style="max-height: 140px;" alt="Foto Wajah"></div>';
                }
                if (k.tanda_tangan) {
                    var ttdUrl = k.tanda_tangan.startsWith('data:') ? k.tanda_tangan : baseUrl + '/' + k.tanda_tangan.replace(/^\/+/, '');
                    imgHtml += '<div><span class="small text-muted d-block mb-1">Tanda Tangan Digital:</span><img src="' + ttdUrl + '" class="img-fluid border rounded p-1 shadow-sm" style="background:#fff; max-height: 80px;" alt="Tanda Tangan"></div>';
                }
                if (!imgHtml) {
                    imgHtml = '<span class="text-muted small fst-italic">Tidak ada foto/tanda tangan tersimpan</span>';
                }
                $('#modalDetailTamu .gambar-tamu').html(imgHtml);

                // Pegawai & Siswa yang dituju
                var pegawaiStr = k.nama_pegawai_dituju || (k.id_pegawai_dituju ? k.id_pegawai_dituju : 'Tidak Spesifik');
                $('#modalDetailTamu .pegawai-dituju span').text(pegawaiStr);

                if (k.id_siswa_dituju) {
                    $('#modalDetailTamu .siswa-dituju span').text('Wali Siswa: ' + k.id_siswa_dituju);
                } else {
                    $('#modalDetailTamu .siswa-dituju span').text('-');
                }

                // Tujuan Kunjungan
                $('#modalDetailTamu .tujuan-kunjungan').text(k.tujuan_kunjungan || '-');

                // Pesan Kesan
                if (k.pesan_kesan) {
                    $('#modalDetailTamu .area-pesan-kesan').removeClass('d-none');
                    $('#modalDetailTamu .pesan-kesan').text(k.pesan_kesan);
                } else {
                    $('#modalDetailTamu .area-pesan-kesan').addClass('d-none');
                }

                // Dokumen Surat Tugas
                if (res.doc_url) {
                    $('#modalDetailTamu .area-dokumen').html(
                        '<a href="' + res.doc_url + '" target="_blank" class="btn btn-sm btn-outline-info">' +
                        '<i class="ti ti-file-text me-1.5"></i> Buka / Unduh Berkas Surat Tugas' +
                        '</a>'
                    );
                } else {
                    $('#modalDetailTamu .area-dokumen').html('<span class="text-muted small fst-italic">Tidak ada dokumen surat tugas dilampirkan.</span>');
                }

                // Setup form update
                $('#form-update-kunjungan').attr('action', '<?= base_url("admin-buku-tamu/update-kunjungan") ?>/' + id);
                $('#form-update-kunjungan select[name="status_kunjungan"]').val(k.status_kunjungan);
                $('#form-update-kunjungan textarea[name="tindak_lanjut"]').val(k.tindak_lanjut || '');

                var modal = new bootstrap.Modal(document.getElementById('modalDetailTamu'));
                modal.show();
            } else {
                Swal.fire('Gagal', 'Data kunjungan tidak ditemukan.', 'error');
            }
        });
    }

    // Single Delete dengan SweetAlert2
    function deleteKunjungan(id, namaTamu) {
        Swal.fire({
            title: 'Hapus Kunjungan?',
            html: `
                <div class="text-center">
                    <div class="alert alert-danger py-2 px-3 text-start small mb-3">
                        <div class="d-flex align-items-center mb-1">
                            <i class="ti ti-alert-triangle fs-2 text-danger me-2"></i>
                            <strong class="text-danger">PERINGATAN!</strong>
                        </div>
                        <div class="text-secondary">
                            Data kunjungan atas nama <strong class="text-dark">"${namaTamu}"</strong> akan dihapus permanen beserta file foto dan tanda tangannya.
                        </div>
                    </div>
                    <p class="text-muted small mb-0">Apakah Anda yakin ingin menghapus data ini?</p>
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
                    didOpen: function() { Swal.showLoading(); }
                });

                $.ajax({
                    url: '<?= base_url("admin-buku-tamu/delete") ?>/' + id,
                    type: 'POST',
                    data: { [csrfName]: csrfHash },
                    dataType: 'json',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    success: function(res) {
                        Swal.close();
                        if (res.success) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message || 'Data kunjungan berhasil dihapus'
                            });
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal', res.message || 'Gagal menghapus.', 'error');
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Gagal', 'Terjadi kesalahan server saat menghapus data.', 'error');
                    }
                });
            }
        });
    }

    // Bulk Delete
    function doBulkDelete(ids) {
        Swal.fire({
            title: 'Menghapus...',
            text: 'Mohon tunggu, sedang memproses ' + ids.length + ' data.',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });

        $.ajax({
            url: '<?= base_url("admin-buku-tamu/bulk-delete") ?>',
            type: 'POST',
            data: {
                ids: ids,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(res) {
                Swal.close();
                if (res.success) {
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                    $('.row-checkbox').prop('checked', false);
                    $('#select-all').prop('checked', false);
                    updateBulkUI();
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.close();
                Swal.fire('Gagal', 'Terjadi kesalahan server saat memproses hapus massal.', 'error');
            }
        });
    }

    // Bulk Update Status
    function doBulkUpdateStatus(ids, status) {
        Swal.fire({
            title: 'Memperbarui status...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });

        $.ajax({
            url: '<?= base_url("admin-buku-tamu/bulk-update-status") ?>',
            type: 'POST',
            data: {
                ids: ids,
                status: status,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(res) {
                Swal.close();
                if (res.success) {
                    Toast.fire({
                        icon: 'success',
                        title: res.message
                    });
                    $('.row-checkbox').prop('checked', false);
                    $('#select-all').prop('checked', false);
                    updateBulkUI();
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            },
            error: function() {
                Swal.close();
                Swal.fire('Gagal', 'Terjadi kesalahan server.', 'error');
            }
        });
    }

    // Export Action Setting
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
