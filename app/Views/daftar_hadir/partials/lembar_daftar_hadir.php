<!-- ==================== LEMBAR 1: DAFTAR HADIR (A4) ==================== -->
<div id="lembar-daftar-hadir" class="page-a4 bg-white text-dark">
    
    <!-- KOP SURAT RESMI -->
    <div id="section-kop" class="kop-container mb-3 pb-2">
        <table class="table-kop w-100">
            <tr>
                <td style="width: 85px; text-align: center; vertical-align: middle; padding-right: 15px;">
                    <?php if (!empty($appSettings['sekolah_logo'])): ?>
                        <img src="<?= base_url('uploads/logo/' . $appSettings['sekolah_logo']) ?>" style="width: 75px; height: auto;" alt="Logo Madrasah">
                    <?php else: ?>
                        <div style="width: 75px; height: 75px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; font-size: 10px;">LOGO</div>
                    <?php endif; ?>
                </td>
                <td style="text-align: center; vertical-align: middle;">
                    <h4 class="mb-0 text-uppercase fw-bold" style="font-size: 11pt; letter-spacing: 0.5px;"><?= esc($appSettings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?></h4>
                    <?php if (!empty($appSettings['sekolah_kantor_kementerian'])): ?>
                        <h4 class="mb-0 text-uppercase fw-bold" style="font-size: 11pt;"><?= esc($appSettings['sekolah_kantor_kementerian']) ?></h4>
                    <?php endif; ?>
                    <h3 class="mb-1 text-uppercase fw-bold" style="font-size: 13pt;"><?= esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?></h3>
                    <p class="mb-0" style="font-size: 9pt; line-height: 1.3;"><?= esc($appSettings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578') ?></p>
                    <p class="mb-0" style="font-size: 9pt; line-height: 1.3;">Email: <?= esc($appSettings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?></p>
                </td>
                <td style="width: 85px;"></td>
            </tr>
        </table>
        <div style="border-top: 3px solid #000; margin-top: 8px;"></div>
        <div style="border-top: 1px solid #000; margin-top: 2px;"></div>
    </div>

    <!-- JUDUL DAFTAR HADIR -->
    <div class="text-center mb-3">
        <h3 id="preview-judul" class="fw-bold text-uppercase mb-1" style="font-size: 13pt; text-decoration: underline;">
            DAFTAR HADIR RAPAT DEWAN GURU DAN STAF
        </h3>
        <div id="preview-subjudul" class="fw-bold" style="font-size: 11pt;">
            Tahun Pelajaran <?= esc($appSettings['tahun_anggaran'] ?? date('Y')) ?>
        </div>
    </div>

    <!-- METADATA KEGIATAN -->
    <table class="table-info mb-3" style="width: 100%; font-size: 11pt;">
        <tr>
            <td style="width: 130px; font-weight: bold; vertical-align: top;">Hari / Tanggal</td>
            <td style="width: 15px; vertical-align: top;">:</td>
            <td id="preview-tanggal" style="vertical-align: top;">
                <?= format_tanggal_indo($default_tanggal) ?>
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Waktu</td>
            <td style="vertical-align: top;">:</td>
            <td id="preview-waktu" style="vertical-align: top;">08.00 WIB s.d. Selesai</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Tempat</td>
            <td style="vertical-align: top;">:</td>
            <td id="preview-tempat" style="vertical-align: top;">Ruang Guru / Aula Madrasah</td>
        </tr>
    </table>

    <!-- TABEL DAFTAR HADIR GURU -->
    <table class="table-presensi w-100 mb-4">
        <thead>
            <tr>
                <th style="width: 35px; text-align: center;">NO</th>
                <th>NAMA</th>
                <th style="width: 180px; text-align: center;">NIP</th>
                <th style="width: 170px; text-align: center;">TANDA TANGAN</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($daftar_guru)): ?>
                <?php $no = 1; foreach ($daftar_guru as $guru): ?>
                    <tr>
                        <td style="text-align: center; vertical-align: middle;"><?= $no ?></td>
                        <td style="vertical-align: middle;">
                            <strong><?= esc($guru['nama_pegawai']) ?></strong>
                        </td>
                        <td style="text-align: center; vertical-align: middle; font-size: 10pt;">
                            <?php if (!empty($guru['nip'])): ?>
                                <?= esc($guru['nip']) ?>
                            <?php elseif (!empty($guru['peg_id_nuptk'])): ?>
                                <?= esc($guru['peg_id_nuptk']) ?>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td style="height: 34px; vertical-align: middle; padding: 2px 8px;">
                            <?php if ($no % 2 !== 0): ?>
                                <!-- Posisi Kiri (Ganjil) -->
                                <div style="text-align: left;">
                                    <span style="font-size: 9pt;"><?= $no ?>.</span>
                                </div>
                            <?php else: ?>
                                <!-- Posisi Kanan (Genap) -->
                                <div style="text-align: right; padding-right: 20px;">
                                    <span style="font-size: 9pt;"><?= $no ?>.</span>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $no++; endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">Belum ada data guru/pegawai yang terdaftar.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- TANDA TANGAN KEPALA MADRASAH PADA DAFTAR HADIR -->
    <div id="section-ttd-hadir" class="row justify-content-end mt-4" style="page-break-inside: avoid;">
        <div class="col-5" style="text-align: left; font-size: 11pt; padding-left: 20px;">
            <div>Purwodadi, <span id="preview-tanggal-ttd-hadir"><?= format_tanggal_indo($default_tanggal) ?></span></div>
            <div class="mb-5">Kepala MIN 2 Tanggamus,</div>
            <div style="height: 50px;"></div>
            <div class="fw-bold" style="text-decoration: underline;">
                <?= esc($appSettings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd') ?>
            </div>
            <div>NIP. <?= esc($appSettings['pejabat_kepsek_nip'] ?? '-') ?></div>
        </div>
    </div>

</div>
