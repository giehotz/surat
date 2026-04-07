<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col">
        <h2 class="page-title">
            Manajemen Berkas: <?= esc($guru['nama_pegawai']) ?>
        </h2>
        <div class="text-secondary mt-1">
            NIP: <?= esc($guru['nip'] ?: '-') ?> | NUPTK: <?= esc($guru['peg_id_nuptk'] ?: '-') ?> | Status: <?= esc($guru['status_kepegawaian'] ?: '-') ?>
        </div>
    </div>
    <div class="col-auto ms-auto d-print-none">
        <a href="<?= base_url('data-guru') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left icon me-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-check icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('success') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <div class="d-flex">
            <div><i class="ti ti-alert-triangle icon alert-icon"></i></div>
            <div><?= session()->getFlashdata('error') ?></div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
    </div>
<?php endif; ?>

<div class="row row-cards">
    <!-- Daftar Berkas (Dipindah ke kiri) -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Dokumen Tersimpan</h3>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th class="w-1">No.</th>
                            <th>Nama Dokumen</th>
                            <th>Tanggal Diunggah</th>
                            <th class="w-1 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($berkas) && count($berkas) > 0) : ?>
                            <?php $i = 1;
                            foreach ($berkas as $b) : ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td>
                                        <?php if ($b['tipe_penyimpanan'] == 'cloud') : ?>
                                            <a href="<?= esc($b['file_link']) ?>" class="fw-bold text-reset" target="_blank" title="Buka Link di Tab Baru">
                                                <i class="ti ti-external-link icon text-blue"></i> <?= esc($b['nama_dokumen']) ?>
                                            </a>
                                            <div class="text-secondary small">
                                                <i class="ti ti-cloud icon"></i> Google Drive / Cloud Link
                                            </div>
                                        <?php else : ?>
                                            <a href="javascript:void(0)" class="fw-bold text-reset view-doc" data-file="<?= base_url('uploads/berkas_guru/' . $b['file_path']) ?>" data-name="<?= esc($b['nama_dokumen']) ?>" data-ext="<?= pathinfo($b['file_path'], PATHINFO_EXTENSION) ?>">
                                                <?= esc($b['nama_dokumen']) ?>
                                            </a>
                                            <div class="text-secondary small">
                                                <?= esc($b['file_path']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d M Y H:i', strtotime($b['created_at'])) ?></td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <?php if ($b['tipe_penyimpanan'] == 'cloud') : ?>
                                                <a href="<?= esc($b['file_link']) ?>" class="btn btn-sm btn-outline-info tooltip-info" target="_blank" title="Buka Link">
                                                    <i class="ti ti-external-link icon"></i>
                                                </a>
                                            <?php else : ?>
                                                <a href="javascript:void(0)" class="btn btn-sm btn-outline-info view-doc tooltip-info" title="Lihat Dokumen" data-file="<?= base_url('uploads/berkas_guru/' . $b['file_path']) ?>" data-name="<?= esc($b['nama_dokumen']) ?>" data-ext="<?= pathinfo($b['file_path'], PATHINFO_EXTENSION) ?>">
                                                    <i class="ti ti-eye icon"></i>
                                                </a>
                                            <?php endif; ?>
                                            <form action="<?= base_url('data-guru/berkas/delete/' . $b['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini? File yang dihapus tidak dapat dikembalikan.');">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger tooltip-info" title="Hapus Berkas">
                                                    <i class="ti ti-trash icon"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Belum ada dokumen yang diunggah untuk guru ini.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Form Upload Berkas (Dipindah ke kanan) -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Upload Dokumen Baru</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('data-guru/berkas/store/' . $guru['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label required">Nama Dokumen</label>
                        <input type="text" class="form-control" name="nama_dokumen" placeholder="Contoh: Ijazah S1" required>
                    </div>
                    <?php
                    $allowedMethods = isset($appSettings['metode_lampiran']) && $appSettings['metode_lampiran'] !== ''
                        ? explode(',', $appSettings['metode_lampiran'])
                        : ['upload', 'link'];
                    $defaultMethod = $allowedMethods[0] ?? 'upload';
                    ?>
                    <div class="mb-3 <?= count($allowedMethods) <= 1 ? 'd-none' : '' ?>">
                        <label class="form-label required">Metode Penyimpanan</label>
                        <select class="form-select" name="tipe_penyimpanan" id="tipe_penyimpanan">
                            <?php if (in_array('upload', $allowedMethods)): ?>
                                <option value="lokal" <?= $defaultMethod == 'upload' ? 'selected' : '' ?>>Upload File ke Server Lokal</option>
                            <?php endif; ?>
                            <?php if (in_array('link', $allowedMethods)): ?>
                                <option value="cloud" <?= $defaultMethod == 'link' ? 'selected' : '' ?>>Link Cloud (Google Drive, Docs, dll)</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div id="area_upload" style="display: <?= $defaultMethod == 'upload' ? 'block' : 'none' ?>;">
                        <div class="mb-3">
                            <label class="form-label required" id="label_file">Pilih File (PDF/JPG/PNG)</label>
                            <input type="file" class="form-control" name="file_dokumen" id="file_dokumen" accept=".pdf, .jpg, .jpeg, .png">
                            <small class="form-hint" id="hint_file">Maksimal ukuran file: <?= esc($maxSizeMB) ?>MB.</small>
                        </div>
                    </div>

                    <div id="area_cloud" style="display: <?= $defaultMethod == 'link' ? 'block' : 'none' ?>;">
                        <div class="mb-3">
                            <label class="form-label required">Link Dokumen Draft Cloud</label>
                            <input type="url" class="form-control" name="file_link" id="file_link" placeholder="https://docs.google.com/...">
                            <small class="form-hint text-blue"><i class="ti ti-info-circle icon"></i> Pastikan link memiliki akses yang sesuai.</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-upload icon me-2"></i> Upload Berkas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk View Dokumen -->
<div class="modal modal-blur fade" id="modal-view-doc" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="doc-title">Lihat Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0" style="background-color: #f8f9fa;">
                <div id="doc-container" style="height: 80vh; width: 100%; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <!-- Content will be loaded here via JS -->
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="btn-download-doc" class="btn btn-primary" download>
                    <i class="ti ti-download icon me-2"></i> Unduh File
                </a>
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().destroy();
        }
        $('.datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
            },
            "pageLength": 10,
            "ordering": false
        });
        // Initialize Tooltips (Bootstrap 5 vanilla way)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('.tooltip-info'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
 
        // Logic for toggling upload area vs cloud area
        function togglePenyimpananArea(value) {
            if (value === 'cloud') {
                $('#area_upload').hide();
                $('#file_dokumen').prop('required', false);
                $('#area_cloud').show();
                $('#file_link').prop('required', true);
            } else {
                $('#area_cloud').hide();
                $('#file_link').prop('required', false);
                $('#area_upload').show();
                $('#file_dokumen').prop('required', true);
            }
        }

        $('#tipe_penyimpanan').on('change', function() {
            togglePenyimpananArea($(this).val());
        });
 
        // Initialize display state on load
        togglePenyimpananArea($('#tipe_penyimpanan').val());

        // Handle View Document Modal
        $('.view-doc').on('click', function(e) {
            e.preventDefault();
            var fileUrl = $(this).data('file');
            var docName = $(this).data('name');
            var ext = $(this).data('ext').toLowerCase();

            $('#doc-title').text(docName);
            $('#btn-download-doc').attr('href', fileUrl);

            var container = $('#doc-container');
            container.empty();

            if (ext === 'pdf') {
                container.html('<iframe src="' + fileUrl + '#view=FitH" width="100%" height="100%" style="border: none;"></iframe>');
            } else if (ext === 'jpg' || ext === 'jpeg' || ext === 'png') {
                container.html('<img src="' + fileUrl + '" class="img-fluid" style="max-height: 100%; object-fit: contain;">');
            } else {
                container.html('<p class="text-muted text-center pt-5">Format file tidak mendukung preview di browser.<br><a href="' + fileUrl + '" class="btn btn-primary mt-3" download>Download untuk melihat</a></p>');
            }

            var modal = new bootstrap.Modal(document.getElementById('modal-view-doc'));
            modal.show();
        });
    });
</script>
<?= $this->endSection() ?>