<?php
$currentKodeSatker = $settings['kode_satker'] ?? 'MI.08.02/PP.00.4';
?>
<div class="tab-pane <?= ($active_tab ?? '') == 'format-surat' ? 'active show' : '' ?>" id="tab-format-surat">
    <!-- Card Pengaturan Kode Satker Permanen -->
    <div class="card mb-3 border-primary-subtle shadow-sm">
        <div class="card-header bg-primary-lt d-flex justify-content-between align-items-center">
            <h4 class="card-title text-primary mb-0">
                <i class="ti ti-building me-2"></i>Kode Satker Permanen Instansi
            </h4>
            <span class="badge bg-primary text-primary-fg">Acuan Surat Keluar</span>
        </div>
        <div class="card-body">
            <form action="<?= base_url('pengaturan/update-kode-satker') ?>" method="post">
                <?= csrf_field() ?>
                <div class="row align-items-center g-3">
                    <div class="col-md-7">
                        <label class="form-label required mb-1">Kode Satker (Kementerian / Madrasah)</label>
                        <div class="input-group">
                            <span class="input-group-text font-monospace">/</span>
                            <input type="text" class="form-control font-monospace fw-bold" name="kode_satker" id="setting_kode_satker"
                                   value="<?= esc($currentKodeSatker) ?>" placeholder="MI.08.02/PP.00.4" required>
                            <span class="input-group-text font-monospace">/</span>
                        </div>
                        <small class="form-hint mt-1 text-muted">
                            Kode satker ini otomatis menjadi acuan bagian ke-3 template format surat keluar (contoh: <code>/MI.08.02/PP.00.4/</code>).
                        </small>
                    </div>
                    <div class="col-md-5 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i class="ti ti-device-floppy"></i> Simpan Kode Satker
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Format Surat Keluar -->
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="ti ti-file-description me-2 text-indigo"></i>Tabel Format Surat Keluar
            </h3>
            <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahFormat">
                <i class="ti ti-plus"></i> Tambah Format
            </button>
        </div>
        <div class="card-body p-0">
            <div class="p-3 bg-light-subtle border-bottom">
                <div class="d-flex align-items-start gap-2">
                    <i class="ti ti-info-circle text-info mt-1"></i>
                    <div class="small text-secondary">
                        Format nomor surat terbagi menjadi 4 komponen utama:
                        <strong>1. Klasifikasi</strong> (contoh: <code>B-</code>),
                        <strong>2. Nomor Urut</strong> (<code>{nomor}</code>, contoh: <code>102</code>),
                        <strong>3. Kode Satker</strong> (contoh: <code>/<?= esc($currentKodeSatker) ?>/</code>), dan
                        <strong>4. Bulan & Tahun</strong> (<code>{bulan}/{tahun}</code>, contoh: <code>06/2026</code>).
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>Nama Format</th>
                            <th>1. Klasifikasi</th>
                            <th>2. Nomor Urut</th>
                            <th>3. Kode Satker</th>
                            <th>4. Bulan & Tahun</th>
                            <th>Contoh Template Hasil</th>
                            <th class="text-end" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($format_surat_list)): ?>
                            <tr><td colspan="8" class="text-muted text-center py-4">Belum ada format surat terdaftar.</td></tr>
                        <?php else: ?>
                            <?php 
                            $no = 1; 
                            foreach ($format_surat_list as $f): 
                                preg_match('/^([^{]+)\{nomor\}\/(.+)\/\{bulan\}\/\{tahun\}$/', $f['template'], $m);
                                $klasifikasiVal = $m[1] ?? 'B-';
                                $satkerVal = $m[2] ?? $currentKodeSatker;
                                $contohHasil = str_replace(['{nomor}', '{bulan}', '{tahun}'], ['102', '06', date('Y')], $f['template']);
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td class="fw-bold text-dark"><?= esc($f['nama']) ?></td>
                                <td>
                                    <span class="badge bg-blue-lt font-monospace fs-6 px-2 py-1"><?= esc($klasifikasiVal) ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-purple-lt font-monospace fs-6 px-2 py-1">{nomor}</span>
                                </td>
                                <td>
                                    <span class="badge bg-teal-lt font-monospace fs-6 px-2 py-1">/<?= esc($satkerVal) ?>/</span>
                                </td>
                                <td>
                                    <span class="badge bg-green-lt font-monospace fs-6 px-2 py-1">{bulan}/{tahun}</span>
                                </td>
                                <td>
                                    <div class="font-monospace text-primary fw-semibold small"><?= esc($f['template']) ?></div>
                                    <small class="text-muted">Contoh: <?= esc($contohHasil) ?></small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-list flex-nowrap justify-content-end">
                                        <button type="button" class="btn btn-outline-primary btn-icon btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#modalEditFormat"
                                                data-id="<?= $f['id'] ?>"
                                                data-nama="<?= esc($f['nama'], 'attr') ?>"
                                                data-klasifikasi="<?= esc($klasifikasiVal, 'attr') ?>"
                                                data-satker="<?= esc($satkerVal, 'attr') ?>"
                                                data-template="<?= esc($f['template'], 'attr') ?>"
                                                title="Edit Format">
                                            <i class="ti ti-edit icon"></i>
                                        </button>
                                        <form action="<?= base_url('pengaturan/delete-format-surat/' . $f['id']) ?>" method="post" style="display:inline;">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-outline-danger btn-icon btn-sm"
                                                    onclick="return confirm('Hapus format surat \"<?= esc($f['nama'], 'js') ?>\"?')"
                                                    title="Hapus Format">
                                                <i class="ti ti-trash icon"></i>
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

<!-- Modal Tambah Format -->
<div class="modal fade" id="modalTambahFormat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('pengaturan/store-format-surat') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-plus me-2 text-indigo"></i>Tambah Format Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Format</label>
                    <input type="text" class="form-control" name="nama" placeholder="Contoh: Biasa, Keputusan, Undangan, Edaran" required>
                </div>

                <div class="card bg-light-subtle border p-3 mb-3">
                    <h5 class="card-title text-muted fs-6 mb-2">Komponen Format Surat</h5>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label required small">1. Klasifikasi Surat</label>
                            <input type="text" class="form-control font-monospace" id="tambah_klasifikasi" value="B-" placeholder="B-" required>
                            <small class="text-muted">Awalan kode (B-, K-, E-)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">2. Nomor Urut</label>
                            <input type="text" class="form-control font-monospace bg-light" value="{nomor}" readonly tabindex="-1">
                            <small class="text-muted">Auto-increment</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">3. Kode Satker</label>
                            <input type="text" class="form-control font-monospace" id="tambah_satker" value="<?= esc($currentKodeSatker) ?>" placeholder="MI.08.02/PP.00.4">
                            <small class="text-muted">Diapit tanda /</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">4. Bulan / Tahun</label>
                            <input type="text" class="form-control font-monospace bg-light" value="{bulan}/{tahun}" readonly tabindex="-1">
                            <small class="text-muted">Bulan & tahun aktif</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Template Lengkap</label>
                    <input type="text" class="form-control font-monospace fw-bold text-primary" name="template" id="tambah_template"
                           value="B-{nomor}/<?= esc($currentKodeSatker) ?>/{bulan}/{tahun}" required>
                    <small class="form-hint mt-1 text-muted">
                        Template otomatis dirangkai dari komponen di atas.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Format</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Format -->
<div class="modal fade" id="modalEditFormat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="" method="post" class="modal-content" id="formEditFormat">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-edit me-2 text-indigo"></i>Edit Format Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Format</label>
                    <input type="text" class="form-control" name="nama" id="editFormatNama" placeholder="Contoh: Biasa, Keputusan, Edaran" required>
                </div>

                <div class="card bg-light-subtle border p-3 mb-3">
                    <h5 class="card-title text-muted fs-6 mb-2">Komponen Format Surat</h5>
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label class="form-label required small">1. Klasifikasi Surat</label>
                            <input type="text" class="form-control font-monospace" id="edit_klasifikasi" placeholder="B-" required>
                            <small class="text-muted">Awalan kode (B-, K-, E-)</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">2. Nomor Urut</label>
                            <input type="text" class="form-control font-monospace bg-light" value="{nomor}" readonly tabindex="-1">
                            <small class="text-muted">Auto-increment</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">3. Kode Satker</label>
                            <input type="text" class="form-control font-monospace" id="edit_satker" placeholder="MI.08.02/PP.00.4">
                            <small class="text-muted">Diapit tanda /</small>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">4. Bulan / Tahun</label>
                            <input type="text" class="form-control font-monospace bg-light" value="{bulan}/{tahun}" readonly tabindex="-1">
                            <small class="text-muted">Bulan & tahun aktif</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label required">Template Lengkap</label>
                    <input type="text" class="form-control font-monospace fw-bold text-primary" name="template" id="editFormatTemplate"
                           placeholder="B-{nomor}/MI.08.02/PP.00.4/{bulan}/{tahun}" required>
                    <small class="form-hint mt-1 text-muted">
                        Template otomatis dirangkai dari komponen di atas.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function assembleTemplate(klasifikasiEl, satkerEl, targetEl) {
        var klas = (klasifikasiEl.value || '').trim();
        if (klas && !klas.endsWith('-')) {
            klas += '-';
        }
        var satker = (satkerEl.value || '').trim().replace(/^\/+|\/+$/g, '');
        targetEl.value = klas + '{nomor}/' + satker + '/{bulan}/{tahun}';
    }

    // Modal Tambah Sync
    var tambahKlas = document.getElementById('tambah_klasifikasi');
    var tambahSatker = document.getElementById('tambah_satker');
    var tambahTpl = document.getElementById('tambah_template');
    if (tambahKlas && tambahSatker && tambahTpl) {
        tambahKlas.addEventListener('input', function() { assembleTemplate(tambahKlas, tambahSatker, tambahTpl); });
        tambahSatker.addEventListener('input', function() { assembleTemplate(tambahKlas, tambahSatker, tambahTpl); });
    }

    // Modal Edit Sync
    var editKlas = document.getElementById('edit_klasifikasi');
    var editSatker = document.getElementById('edit_satker');
    var editTpl = document.getElementById('editFormatTemplate');
    if (editKlas && editSatker && editTpl) {
        editKlas.addEventListener('input', function() { assembleTemplate(editKlas, editSatker, editTpl); });
        editSatker.addEventListener('input', function() { assembleTemplate(editKlas, editSatker, editTpl); });
    }

    var modalEdit = document.getElementById('modalEditFormat');
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var template = button.getAttribute('data-template');
            var klasifikasi = button.getAttribute('data-klasifikasi') || 'B-';
            var satker = button.getAttribute('data-satker') || '<?= esc($currentKodeSatker, 'js') ?>';

            document.getElementById('editFormatNama').value = nama;
            document.getElementById('edit_klasifikasi').value = klasifikasi;
            document.getElementById('edit_satker').value = satker;
            document.getElementById('editFormatTemplate').value = template;
            document.getElementById('formEditFormat').action = '<?= base_url('pengaturan/update-format-surat') ?>/' + id;
        });
    }
});
</script>
