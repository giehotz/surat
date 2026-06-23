<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<?php
$isEdit = isset($surat);
$actionUrl = $isEdit ? base_url('surat-resmi/update/'.$surat['id']) : base_url('surat-resmi/store');
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title"><?= esc($title) ?></h3>
            </div>
            <div class="card-body">
                <form action="<?= $actionUrl ?>" method="post" id="form-surat">
                    <?= csrf_field() ?>
                    
                    <?= $this->include('surat_resmi/FormSurat/Partial/_template_selector') ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Nomor Surat</label>
                            <input type="text" class="form-control" name="nomor_surat" list="list_nomor_surat" value="<?= old('nomor_surat', $surat['nomor_surat'] ?? ($next_nomor ?? '')) ?>" required autocomplete="off">
                            <datalist id="list_nomor_surat">
                                <?php if(isset($list_surat_keluar)): ?>
                                    <?php foreach($list_surat_keluar as $sk): ?>
                                        <option value="<?= esc($sk['nomor_surat']) ?>"><?= esc($sk['perihal']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </datalist>
                            <small class="text-muted">Ketik manual atau pilih dari riwayat Daftar Surat Keluar.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Tanggal Surat</label>
                            <input type="date" class="form-control" name="tanggal_surat" value="<?= old('tanggal_surat', $surat['tanggal_surat'] ?? date('Y-m-d')) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Perihal</label>
                            <input type="text" class="form-control" name="perihal" value="<?= old('perihal', $surat['perihal'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lampiran</label>
                            <input type="text" class="form-control" name="lampiran" value="<?= old('lampiran', $surat['lampiran'] ?? '-') ?>">
                            <small class="text-muted">Contoh: 1 (satu) berkas / -</small>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Informasi Tujuan</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Kepada Yth. (Nama/Jabatan)</label>
                            <input type="text" class="form-control" name="tujuan_nama" value="<?= old('tujuan_nama', $surat['tujuan_nama'] ?? '') ?>" placeholder="Contoh: Kepala Dinas Pendidikan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label required">Alamat Tujuan</label>
                            <input type="text" class="form-control" name="tujuan_alamat" value="<?= old('tujuan_alamat', $surat['tujuan_alamat'] ?? '') ?>" placeholder="Contoh: di Tempat" required>
                        </div>
                    </div>

                    <hr>
                    <h4 class="mb-3">Isi Surat</h4>
                    <div class="mb-3">
                        <label class="form-label required">Salam Pembuka</label>
                        <input type="text" class="form-control" name="salam_pembuka" value="<?= old('salam_pembuka', $surat['salam_pembuka'] ?? 'Dengan hormat,') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Isi Utama Surat</label>
                        <!-- Kita gunakan textarea yang nanti akan diubah menjadi Rich Text Editor -->
                        <textarea id="isi_surat_editor" class="form-control" name="isi_surat" rows="10"><?= old('isi_surat', $surat['isi_surat'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label required">Salam Penutup</label>
                        <input type="text" class="form-control" name="salam_penutup" value="<?= old('salam_penutup', $surat['salam_penutup'] ?? 'Hormat kami,') ?>" required>
                    </div>

                    <hr>
                    <h4 class="mb-3">Pengirim</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Jabatan Pengirim</label>
                            <input type="text" class="form-control" name="pengirim_jabatan" value="<?= old('pengirim_jabatan', $surat['pengirim_jabatan'] ?? '') ?>" placeholder="Contoh: Kepala Sekolah" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label required">Nama Pengirim</label>
                            <input type="text" class="form-control" name="pengirim_nama" value="<?= old('pengirim_nama', $surat['pengirim_nama'] ?? '') ?>" placeholder="Contoh: Budi Santoso, S.Pd" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">NIP / NIK (Opsional)</label>
                            <input type="text" class="form-control" name="pengirim_nip" value="<?= old('pengirim_nip', $surat['pengirim_nip'] ?? '') ?>" placeholder="NIP. ...">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Tembusan (Opsional)</label>
                        <textarea class="form-control" name="tembusan" rows="3" placeholder="1. Arsip\n2. ..."><?= old('tembusan', $surat['tembusan'] ?? '') ?></textarea>
                    </div>

                    <div class="form-footer text-end">
                        <a href="<?= base_url('surat-resmi') ?>" class="btn btn-secondary me-2">Batal</a>
                        <button type="button" class="btn btn-info me-2" id="btn-preview">
                            <i class="ti ti-eye me-2"></i> Preview Surat
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i> Simpan Surat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Preview -->
<div class="modal modal-blur fade" id="modalPreview" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview Surat Resmi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh;">
                <!-- Iframe untuk menampilkan print.php dari hasil POST form -->
                <iframe name="preview_iframe" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Menggunakan TinyMCE (gratis/open source fallback) atau ckeditor jika ada -->
<script src="https://cdn.tiny.cloud/1/4wj73q4szc3rdlnlsj744qe11xih3cmbai3g0b7eoheca3d1/tinymce/8/tinymce.min.js" referrerpolicy="origin"></script>
<?= $this->include('surat_resmi/FormSurat/Partial/_template_selector_js') ?>

<script>
    tinymce.init({
        selector: '#isi_surat_editor',
        menubar: false,
        plugins: 'lists link table code',
        toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table code',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });

    $(document).ready(function() {
        // Logika Preview
        $('#btn-preview').click(function() {
            // Pastikan data tinymce disave ke textarea sebelum submit
            if (tinymce.get('isi_surat_editor')) {
                tinymce.get('isi_surat_editor').save();
            }
            
            var originalAction = $('#form-surat').attr('action');
            var originalTarget = $('#form-surat').attr('target') || '';

            // Ubah target dan action ke rute preview
            $('#form-surat').attr('target', 'preview_iframe');
            $('#form-surat').attr('action', '<?= base_url('surat-resmi/previewPdf') ?>');
            
            // Tampilkan modal
            var modal = new bootstrap.Modal(document.getElementById('modalPreview'));
            modal.show();
            
            // Submit form ke iframe
            $('#form-surat').submit();
            
            // Kembalikan atribut form seperti semula
            setTimeout(function() {
                if (originalTarget) {
                    $('#form-surat').attr('target', originalTarget);
                } else {
                    $('#form-surat').removeAttr('target');
                }
                $('#form-surat').attr('action', originalAction);
            }, 500);
        });
    });
</script>
<?= $this->endSection() ?>
