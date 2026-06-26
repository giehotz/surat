<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekap Buku Tamu</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.2; }
        /* Kop Surat - menggunakan table agar kompatibel Dompdf */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .kop-surat td { vertical-align: middle; padding: 0; }
        .kop-logo { width: 90px; text-align: center; padding-right: 20px; }
        .kop-logo img { width: 90px; height: auto; display: block; margin: 0 auto; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { font-size: 12pt; margin: 0 0 2px 0; font-weight: bold; text-transform: uppercase; }
        .kop-teks h2 { font-size: 12pt; margin: 0 0 5px 0; font-weight: bold; text-transform: uppercase; }
        .kop-teks p { font-size: 9pt; margin: 0; line-height: 1.4; }
        .garis-kop-tebal { border-top: 3px solid #000; width: 100%; margin: 0; }
        .garis-kop-tipis { border-top: 1px solid #000; width: 100%; margin: 2px 0 20px 0; }
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
            <td style="width: 90px; text-align: center; vertical-align: middle; padding-right: 15px;">
                <img src="<?= $logoDataUri ?: 'data:image/png;base64,' ?>" style="width: 80px; height: auto; display: block; margin: 0 auto;" alt="Logo">
            </td>
            <td style="text-align: center; vertical-align: middle;">
                <h1 style="font-size: 12pt; margin: 0 0 2px 0; font-weight: bold; text-transform: uppercase;"><?= esc($appSettings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?></h1>
                <h1 style="font-size: 12pt; margin: 0 0 2px 0; font-weight: bold; text-transform: uppercase;"><?= esc($appSettings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?></h1>
                <h2 style="font-size: 12pt; margin: 0 0 5px 0; font-weight: bold; text-transform: uppercase;"><?= esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?></h2>
                <p style="font-size: 9pt; margin: 0; line-height: 1.4;"><?= esc($appSettings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></p>
                <p style="font-size: 9pt; margin: 0; line-height: 1.4;">Email : <?= esc($appSettings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?></p>
            </td>
            <td style="width: 90px;"></td>
        </tr>
    </table>
    <hr class="garis-kop-tebal" style="border: none; border-top: 3px solid #000; margin: 0;">
    <hr class="garis-kop-tipis" style="border: none; border-top: 1px solid #000; margin: 2px 0 20px 0;">

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
