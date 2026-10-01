<div class="tab-pane <?= ($active_tab ?? '') == 'kop-surat' ? 'active show' : '' ?>" id="tab-kop-surat">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="ti ti-file-description me-2 text-indigo"></i>Pengaturan Kop Surat (Tabel Mandiri)
        </h3>
        <span class="badge bg-indigo-lt">
            <i class="ti ti-database me-1"></i>Tersimpan di tabel kop_surat
        </span>
    </div>

    <div class="card-body">
        <form action="<?= base_url('pengaturan/update-kop-surat') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4 p-3 rounded-3">
                <i class="ti ti-info-circle fs-2 text-info me-3"></i>
                <div class="small">
                    <strong>Penyimpanan Terpisah:</strong> Pengaturan baris kop surat kini dikelola di tabel database mandiri <code>kop_surat</code>. Perubahan pada <strong>Nama Madrasah / Instansi (Baris 3)</strong> di sini tidak akan mengubah <strong>Nama Institusi</strong> di tab Identitas.
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-12">
                    <label class="form-label">Kementerian (Baris 1)</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-building-estate"></i></span>
                        <input type="text" class="form-control ps-1" name="kementerian" id="input-kementerian" value="<?= esc($kopSurat['kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?>" required>
                    </div>
                    <small class="form-hint mt-1">Baris pertama kop surat resmi</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Kantor Kementerian (Baris 2)</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-building"></i></span>
                        <input type="text" class="form-control ps-1" name="kantor_kementerian" id="input-kantor" value="<?= esc($kopSurat['kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?>">
                    </div>
                    <small class="form-hint mt-1">Baris kedua kop surat (instansi vertikal / dinas daerah)</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label required d-flex align-items-center justify-content-between">
                        <span>Nama Madrasah / Instansi (Baris 3)</span>
                        <span class="badge bg-teal-lt small"><i class="ti ti-check me-1"></i>Independen</span>
                    </label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-school"></i></span>
                        <input type="text" class="form-control ps-1 font-weight-bold" name="nama_madrasah_kop" id="input-nama" value="<?= esc($kopSurat['nama_madrasah_kop'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?>" required>
                    </div>
                    <small class="form-hint mt-1">Baris ketiga kop surat (nama cetak resmi instansi pada kop dokumen)</small>
                </div>

                <div class="col-md-12">
                    <label class="form-label required">Alamat Instansi pada Kop</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-map-pin"></i></span>
                        <textarea class="form-control ps-1" name="alamat_kop" id="input-alamat" rows="2" required><?= esc($kopSurat['alamat_kop'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Email / Kontak pada Kop</label>
                    <div class="input-group input-group-flat">
                        <span class="input-group-text"><i class="ti ti-address-book"></i></span>
                        <input type="text" class="form-control ps-1" name="kontak_kop" id="input-kontak" value="<?= esc($kopSurat['kontak_kop'] ?? 'minduatanggamus@gmail.com') ?>">
                    </div>
                    <small class="form-hint mt-1">Akan tampil di baris terakhir kop surat</small>
                </div>
            </div>

            <!-- LIVE PREVIEW KOP SURAT -->
            <div class="hr-text mt-5 mb-4 text-primary fw-bold">Live Preview Kop Surat</div>

            <?php 
                $logoKopName = !empty($kopSurat['logo_kop']) ? $kopSurat['logo_kop'] : ($settings['sekolah_logo'] ?? '');
                $logoKopUrl  = !empty($logoKopName) ? base_url('uploads/logo/' . $logoKopName) : '';
            ?>

            <div class="border rounded-3 p-4 bg-white shadow-sm mb-4" style="font-family: 'Times New Roman', Times, serif;">
                <div class="d-flex align-items-center justify-content-center" style="gap: 15px;">
                    <div>
                        <img src="<?= $logoKopUrl ?>" style="width: 75px; height: auto; display: <?= !empty($logoKopUrl) ? 'block' : 'none' ?>;" alt="Logo" id="preview-logo-img" onerror="this.style.display='none'">
                    </div>
                    <div class="text-center flex-grow-1">
                        <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.2;" id="preview-kementerian"><?= esc($kopSurat['kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?></div>
                        <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.2;" id="preview-kantor"><?= esc($kopSurat['kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?></div>
                        <div style="font-size: 12pt; font-weight: bold; text-transform: uppercase; line-height: 1.25;" id="preview-nama"><?= esc($kopSurat['nama_madrasah_kop'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?></div>
                        <div style="font-size: 9pt; line-height: 1.3;" id="preview-alamat"><?= esc($kopSurat['alamat_kop'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></div>
                        <div style="font-size: 9pt; line-height: 1.3;">Email : <span id="preview-kontak"><?= esc($kopSurat['kontak_kop'] ?? 'minduatanggamus@gmail.com') ?></span></div>
                    </div>
                </div>
                <hr style="border-top: 3px solid #000; margin: 10px 0 2px 0;">
                <hr style="border-top: 1px solid #000; margin: 0;">
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                    <i class="ti ti-device-floppy"></i>
                    Simpan Pengaturan Kop Surat
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Realtime Live Preview Update Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bindLive = (inputId, previewId, prefix = '', suffix = '') => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (input && preview) {
            input.addEventListener('input', function () {
                preview.textContent = prefix + (this.value || '-') + suffix;
            });
        }
    };

    bindLive('input-kementerian', 'preview-kementerian');
    bindLive('input-kantor', 'preview-kantor');
    bindLive('input-nama', 'preview-nama');
    bindLive('input-alamat', 'preview-alamat');
    bindLive('input-kontak', 'preview-kontak');
});
</script>