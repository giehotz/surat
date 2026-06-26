<div class="tab-pane <?= ($active_tab ?? '') == 'kop-surat' ? 'active show' : '' ?>" id="tab-kop-surat">
    <div class="card-header">
        <h3 class="card-title">
            <i class="ti ti-file-description me-2 text-indigo"></i>Pengaturan Kop Surat
        </h3>
    </div>

    <div class="card-body">
        <form action="<?= base_url('pengaturan/update-kop-surat') ?>" method="post">
            <?= csrf_field() ?>

            <p class="text-muted mb-4">
                <i class="ti ti-info-circle me-1"></i>
                Pengaturan ini akan digunakan untuk kop surat resmi pada cetakan PDF (surat keluar, buku tamu, dll).
            </p>

            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label">Kementerian (Baris 1)</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-building-estate"></i></span>
                        <input type="text" class="form-control ps-1" name="sekolah_kementerian" value="<?= esc($settings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?>" required>
                    </div>
                    <small class="form-hint mt-1">Baris pertama kop surat, contoh: KEMENTERIAN AGAMA REPUBLIK INDONESIA</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Kantor Kementerian (Baris 2)</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-building"></i></span>
                        <input type="text" class="form-control ps-1" name="sekolah_kantor_kementerian" value="<?= esc($settings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?>">
                    </div>
                    <small class="form-hint mt-1">Baris kedua kop surat, contoh: KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label required">Nama Madrasah / Instansi (Baris 3)</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-school"></i></span>
                        <input type="text" class="form-control ps-1" name="sekolah_nama" value="<?= esc($settings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?>" required>
                    </div>
                    <small class="form-hint mt-1">Baris ketiga kop surat, nama resmi madrasah / instansi</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label required">Alamat Instansi</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-map-pin"></i></span>
                        <textarea class="form-control ps-1" name="sekolah_alamat" rows="2" required><?= esc($settings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Email / Kontak</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-address-book"></i></span>
                        <input type="text" class="form-control ps-1" name="sekolah_kontak" value="<?= esc($settings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?>">
                    </div>
                    <small class="form-hint mt-1">Akan tampil di baris terakhir kop surat</small>
                </div>
            </div>

            <div class="hr-text mt-4 mb-4">Preview Kop Surat</div>

            <div class="border rounded p-3 bg-light mb-4" style="font-family: 'Times New Roman', Times, serif;">
                <div class="d-flex align-items-center justify-content-center" style="gap: 15px;">
                    <div>
                        <img src="<?= !empty($settings['sekolah_logo']) ? base_url('uploads/logo/' . $settings['sekolah_logo']) : '' ?>" style="width: 70px; height: auto; display: block;" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    <div class="text-center">
                        <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase;" id="preview-kementerian"><?= esc($settings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?></div>
                        <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase;" id="preview-kantor"><?= esc($settings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?></div>
                        <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase;" id="preview-nama"><?= esc($settings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?></div>
                        <div style="font-size: 9pt;" id="preview-alamat"><?= esc($settings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></div>
                        <div style="font-size: 9pt;">Email : <span id="preview-kontak"><?= esc($settings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?></span></div>
                    </div>
                </div>
                <hr style="border-top: 3px solid #000; margin: 10px 0 2px 0;">
                <hr style="border-top: 1px solid #000; margin: 0;">
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="ti ti-device-floppy"></i>
                    Simpan Pengaturan Kop Surat
                </button>
            </div>
        </form>
    </div>
</div>