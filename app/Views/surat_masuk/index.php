<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Surat Masuk</h3>
                <div class="card-actions">
                    <div class="btn-list">
                        <a href="<?= base_url('surat-masuk/export-excel') ?>" class="btn btn-outline-success">
                            <i class="ti ti-file-spreadsheet icon"></i> Excel
                        </a>
                        <a href="<?= base_url('surat-masuk/export-pdf') ?>" target="_blank" class="btn btn-outline-danger">
                            <i class="ti ti-file-type-pdf icon"></i> PDF
                        </a>
                        <?php if (session()->get('role') !== 'pimpinan'): ?>
                            <a href="<?= base_url('surat-masuk/import') ?>" class="btn btn-success">
                                <i class="ti ti-upload icon"></i> Import Excel
                            </a>
                            <a href="<?= base_url('surat-masuk/create') ?>" class="btn btn-primary">
                                <i class="ti ti-plus icon"></i> Tambah Surat Masuk
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card-body border-bottom py-3">
                <div class="row gx-2 gy-2 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label mb-1">Dari Tanggal</label>
                        <input type="date" id="filter_start_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">Sampai Tanggal</label>
                        <input type="date" id="filter_end_date" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1">Status</label>
                        <select id="filter_status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="tercatat">Tercatat</option>
                            <option value="didisposisikan">Didisposisikan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 d-none d-md-block">&nbsp;</label>
                        <button type="button" id="btn-filter" class="btn btn-primary w-100">
                            <i class="ti ti-filter icon"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-surat-masuk" class="table card-table table-vcenter table-striped" style="width: 100%;">
                        <thead>
                            <tr>
                                <th class="w-1">No.</th>
                                <th>No. Agenda / No. Surat</th>
                                <th>Pengirim</th>
                                <th>Tanggal Terima</th>
                                <th>Perihal</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Diupdate Oleh</th>
                                <th class="text-center w-5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data ditarik oleh DataTables via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Nanti tambahkan pagination disini .card-footer -->
        </div>
    </div>
</div>
<!-- Modal Preview Dokumen -->
<div class="modal modal-blur fade" id="modal-preview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <iframe id="preview-iframe" src="" width="100%" height="100%" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <a id="btn-open-new-tab" href="#" target="_blank" class="btn btn-primary">
                    <i class="ti ti-external-link icon"></i> Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-link link-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#table-surat-masuk').DataTable({
            processing: true,
            serverSide: true,
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
            columnDefs: [{
                targets: [0, 8],
                orderable: false,
                searchable: false
            }],
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            }
        });

        $('#btn-filter').click(function() {
            table.draw();
        });
    });

    function previewDokumen(url) {
        // Deteksi link cloud/eksternal yang tidak bisa di-embed dalam iframe
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