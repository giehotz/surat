<div class="tab-pane <?= $active_tab == 'pimpinan' ? 'active show' : '' ?>" id="tab-pimpinan">
    <div class="card-header">
        <h3 class="card-title">Data Pimpinan Surat</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-title">Catatan</h4>
            <div class="text-secondary">Data pejabat di bawah ini akan digunakan sebagai kolom otomatis yang tertera pada kaki halaman laporan cetak (PDF) dokumen persuratan.</div>
        </div>
        <form action="<?= base_url('pengaturan/update-pimpinan') ?>" method="post">
            <?= csrf_field() ?>

            <fieldset class="form-fieldset">
                <legend class="w-auto px-2 mb-3">Kepala Sekolah / Pimpinan Utama</legend>
                <div class="mb-3">
                    <label class="form-label required">Nama Lengkap & Gelar</label>
                    <input type="text" class="form-control" name="pejabat_kepsek_nama" value="<?= esc($settings['pejabat_kepsek_nama'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" class="form-control" name="pejabat_kepsek_nip" value="<?= esc($settings['pejabat_kepsek_nip'] ?? '') ?>">
                </div>
            </fieldset>

            <fieldset class="form-fieldset">
                <legend class="w-auto px-2 mb-3">Kepala Tata Usaha / Pejabat Surat</legend>
                <div class="mb-3">
                    <label class="form-label required">Nama Lengkap & Gelar</label>
                    <input type="text" class="form-control" name="pejabat_tu_nama" value="<?= esc($settings['pejabat_tu_nama'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor Induk Pegawai (NIP)</label>
                    <input type="text" class="form-control" name="pejabat_tu_nip" value="<?= esc($settings['pejabat_tu_nip'] ?? '') ?>">
                </div>
            </fieldset>

            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary">Simpan Data Pimpinan</button>
            </div>
        </form>
    </div>
</div>
