<div class="tab-pane <?= ($active_tab ?? '') == 'format-surat' ? 'active show' : '' ?>" id="tab-format-surat">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="ti ti-file-description me-2 text-indigo"></i>Format Surat Keluar
        </h3>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalTambahFormat">
            <i class="ti ti-plus"></i> Tambah Format
        </button>
    </div>
    <div class="card-body">
        <div class="alert alert-info bg-info-lt" role="alert">
            <div class="d-flex">
                <div><i class="ti ti-info-circle icon alert-icon text-info me-3 mt-1"></i></div>
                <div>
                    <h4 class="alert-title mb-1">Template Format Surat</h4>
                    <div class="text-secondary">
                        Gunakan placeholder <code>{nomor}</code> untuk nomor urut, <code>{bulan}</code> untuk bulan, <code>{tahun}</code> untuk tahun anggaran aktif.
                        Contoh: <code>B-{nomor}/MI.08.02/PP.004/{bulan}/{tahun}</code> → <code>B-036/MI.08.02/PP.004/04/2026</code>.
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Format</th>
                        <th>Template</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($format_surat_list)): ?>
                        <tr><td colspan="4" class="text-muted text-center">Belum ada format surat.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($format_surat_list as $f): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td class="fw-semibold"><?= esc($f['nama']) ?></td>
                            <td><code><?= esc($f['template']) ?></code></td>
                            <td class="text-end">
                                <div class="btn-list flex-nowrap justify-content-end">
                                    <button type="button" class="btn btn-outline-primary btn-icon btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#modalEditFormat"
                                            data-id="<?= $f['id'] ?>"
                                            data-nama="<?= esc($f['nama'], 'attr') ?>"
                                            data-template="<?= esc($f['template'], 'attr') ?>">
                                        <i class="ti ti-edit icon"></i>
                                    </button>
                                    <form action="<?= base_url('pengaturan/delete-format-surat/' . $f['id']) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-outline-danger btn-icon btn-sm"
                                                onclick="return confirm('Hapus format surat \"<?= esc($f['nama'], 'js') ?>\"?')">
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

<!-- Modal Tambah Format -->
<div class="modal fade" id="modalTambahFormat" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="<?= base_url('pengaturan/store-format-surat') ?>" method="post" class="modal-content">
            <?= csrf_field() ?>
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-plus me-2 text-indigo"></i>Tambah Format Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Format</label>
                    <input type="text" class="form-control" name="nama" placeholder="Contoh: Biasa, Keputusan, Edaran" required>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Template</label>
                    <input type="text" class="form-control font-monospace" name="template"
                           placeholder="B-{nomor}/MI.08.02/PP.004/{bulan}/{tahun}" required>
                    <small class="form-hint mt-2">
                        Gunakan <code>{nomor}</code>, <code>{bulan}</code>, <code>{tahun}</code> sebagai placeholder.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
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
                <h5 class="modal-title"><i class="ti ti-edit me-2 text-indigo"></i>Edit Format Surat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label required">Nama Format</label>
                    <input type="text" class="form-control" name="nama" id="editFormatNama" placeholder="Contoh: Biasa, Keputusan, Edaran" required>
                </div>
                <div class="mb-3">
                    <label class="form-label required">Template</label>
                    <input type="text" class="form-control font-monospace" name="template" id="editFormatTemplate"
                           placeholder="B-{nomor}/MI.08.02/PP.004/{bulan}/{tahun}" required>
                    <small class="form-hint mt-2">
                        Gunakan <code>{nomor}</code>, <code>{bulan}</code>, <code>{tahun}</code> sebagai placeholder.
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
    var modalEdit = document.getElementById('modalEditFormat');
    if (modalEdit) {
        modalEdit.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nama = button.getAttribute('data-nama');
            var template = button.getAttribute('data-template');

            document.getElementById('editFormatNama').value = nama;
            document.getElementById('editFormatTemplate').value = template;
            document.getElementById('formEditFormat').action = '<?= base_url('pengaturan/update-format-surat') ?>/' + id;
        });
    }
});
</script>
