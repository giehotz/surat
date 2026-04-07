<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Buku Tamu</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.2; }
        .kop-surat { width: 100%; border-collapse: collapse; }
        .kop-surat td { padding: 0; vertical-align: middle; }
        .garis-kop { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 2px; margin-top: 10px; margin-bottom: 20px; }
        .judul-laporan { text-align: center; margin-bottom: 20px; }
        .judul-laporan h3 { margin: 0; text-decoration: underline; font-size: 14pt; text-transform: uppercase; }
        .judul-laporan p { margin: 5px 0 0 0; font-weight: bold; font-size: 10pt; }
        .table-data { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 9pt; }
        .table-data th, .table-data td { border: 1px solid #000; padding: 5px 7px; }
        .table-data th { background-color: #f2f2f2; text-align: center; font-weight: bold; }
        .text-center { text-align: center; }
        .badge { padding: 2px 5px; border-radius: 3px; font-size: 8pt; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <?php
    $logoDataUri = '';
    if (!empty($appSettings['sekolah_logo'])) {
        $logoPath = FCPATH . 'uploads/logo/' . $appSettings['sekolah_logo'];
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoMime = mime_content_type($logoPath);
            $logoDataUri = 'data:' . $logoMime . ';base64,' . $logoData;
        }
    }
    ?>
    <table class="kop-surat">
        <tr>
            <td style="width: 15%; text-align: center;">
                <?php if ($logoDataUri): ?>
                    <img src="<?= $logoDataUri ?>" width="80" alt="Logo">
                <?php endif; ?>
            </td>
            <td style="width: 70%; text-align: center;">
                <?php if (!empty($appSettings['sekolah_kementerian'])): ?>
                    <div style="font-size: 13pt; font-weight: bold; text-transform: uppercase;"><?= esc($appSettings['sekolah_kementerian']) ?></div>
                <?php endif; ?>
                <div style="font-size: 15pt; font-weight: bold; text-transform: uppercase; margin: 2px 0;"><?= esc($appSettings['sekolah_nama'] ?? 'NAMA INSTITUSI') ?></div>
                <div style="font-size: 9pt; font-weight: normal;">
                    <?php if (!empty($appSettings['sekolah_npsn']) || !empty($appSettings['sekolah_nsm'])): ?>
                        NPSN: <?= esc($appSettings['sekolah_npsn'] ?? '-') ?> | NSM: <?= esc($appSettings['sekolah_nsm'] ?? '-') ?><br>
                    <?php endif; ?>
                    <?= esc($appSettings['sekolah_alamat'] ?? 'Alamat Lengkap Institusi') ?><br>
                    <?php if (!empty($appSettings['sekolah_kontak'])): ?>
                        Telepon/Fax: <?= esc($appSettings['sekolah_kontak']) ?>
                    <?php endif; ?>
                </div>
            </td>
            <td style="width: 15%;"></td>
        </tr>
    </table>
    <div class="garis-kop"></div>

    <div class="judul-laporan">
        <h3>REKAPITULASI BUKU TAMU DIGITAL</h3>
        <p>Periode: <?= $periode_text ?></p>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 13%;">Waktu Kunjungan</th>
                <th style="width: 18%;">Nama Tamu</th>
                <th style="width: 15%;">Asal/Instansi</th>
                <th style="width: 12%;">Jenis</th>
                <th style="width: 20%;">Tujuan / Dituju</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($kunjungan)): $no=1; foreach($kunjungan as $k): ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td class="text-center"><?= date('d/m/Y H:i', strtotime($k['tanggal_waktu'])) ?></td>
                <td><?= esc($k['nama_lengkap']) ?></td>
                <td><?= esc($k['alamat_instansi']) ?></td>
                <td class="text-center"><?= ($k['jenis_tamu'] == 'khusus') ? 'Dinas' : 'Umum' ?></td>
                <td>
                    <strong>Ke:</strong> <?= esc($k['nama_pegawai_dituju'] ?: $k['id_pegawai_dituju'] ?: 'Umum') ?><br>
                    <small><?= esc($k['tujuan_kunjungan']) ?></small>
                </td>
                <td class="text-center"><?= ucfirst($k['status_kunjungan']) ?></td>
            </tr>
            <?php endforeach; else: ?>
            <tr>
                <td colspan="7" class="text-center">Tidak ada data kunjungan pada periode ini.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 30px; page-break-inside: avoid;">
        <tr>
            <td style="width: 65%;"></td>
            <td style="width: 35%; text-align: center;">
                <p style="margin: 0;">Dicetak pada: <?= date('d/m/Y H:i') ?></p>
                <p style="margin: 30px 0 0 0;">Mengesahkan,</p>
                <p style="margin: 0; font-weight: bold;">Kepala Sekolah</p>
                <br><br><br><br>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;"><?= esc($appSettings['pejabat_kepsek_nama'] ?? 'NAMA KEPALA SEKOLAH') ?></p>
                <p style="margin: 0;">NIP. <?= esc($appSettings['pejabat_kepsek_nip'] ?? '-') ?></p>
            </td>
        </tr>
    </table>
</body>
</html>
