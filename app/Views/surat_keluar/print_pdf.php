<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Surat Keluar</title>
    <style>
        /* Pengaturan Kertas & Font Formal */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.3;
        }

        /* Kop Surat - table layout kompatibel Dompdf */
        .kop-surat { width: 100%; border-collapse: collapse; margin-bottom: 0; }
        .kop-surat td { vertical-align: middle; padding: 0; }
        .kop-logo { width: 90px; text-align: center; padding-right: 15px; }
        .kop-logo img { width: 80px; height: auto; display: block; margin: 0 auto; }
        .kop-teks { text-align: center; }
        .kop-teks h1 { font-size: 12pt; margin: 0 0 2px 0; font-weight: bold; text-transform: uppercase; }
        .kop-teks h2 { font-size: 12pt; margin: 0 0 5px 0; font-weight: bold; text-transform: uppercase; }
        .kop-teks p { font-size: 9pt; margin: 0; line-height: 1.4; }
        .garis-kop-tebal { border: none; border-top: 3px solid #000; margin: 0; }
        .garis-kop-tipis { border: none; border-top: 1px solid #000; margin: 2px 0 20px 0; }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-laporan h3 {
            margin: 0;
            text-decoration: underline;
            font-size: 14pt;
        }
        .judul-laporan p {
            margin: 5px 0 0 0;
            font-weight: bold;
            font-size: 11pt;
        }

        /* Tabel Data */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 10pt; /* Diubah menjadi 10pt */
        }
        .table-data th,
        .table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        .table-data th {
            background-color: #e9ecef;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
        }
        .table-data td {
            vertical-align: top;
        }

        /* Utilitas Teks */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-uppercase { text-transform: uppercase; }
        .fw-bold { font-weight: bold; }
        .text-nowrap { white-space: nowrap; }
    </style>
</head>

<body>
    <?php
    // Persiapan Logo (Base64 untuk Kompatibilitas PDF Renderer)
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

    <!-- KOP SURAT -->
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

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA SURAT KELUAR</h3>
        <?php if (isset($filter_text)): ?>
            <p><?= esc($filter_text) ?></p>
        <?php endif; ?>
    </div>

    <!-- TABEL DATA -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tgl Keluar</th>
                <th style="width: 15%; white-space: nowrap;">No Surat</th>
                <th style="width: 12%;">Tgl Surat</th>
                <th style="width: 20%;">Tujuan</th>
                <th style="width: 26%;">Perihal</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(!empty($surat_keluar)):
                foreach ($surat_keluar as $surat) : 
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= !empty($surat['tanggal_kirim']) ? format_tanggal_indo($surat['tanggal_kirim']) : '-' ?></td>
                    <td class="text-center text-nowrap"><?= esc($surat['nomor_surat'] ?? '-') ?></td>
                    <td class="text-center"><?= format_tanggal_indo($surat['tanggal_surat']) ?></td>
                    <td class="text-left"><?= esc($surat['tujuan']) ?></td>
                    <td class="text-left"><?= esc($surat['perihal']) ?></td>
                    <td class="text-center"><?= esc(ucfirst($surat['status'])) ?></td>
                </tr>
            <?php 
                endforeach; 
            else:
            ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 15px;">Tidak ada data surat keluar pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- BAGIAN TANDA TANGAN -->
    <table style="width: 100%; border: none; margin-top: 40px; page-break-inside: avoid;">
        <tr>
            <td style="width: 60%; border: none;"></td>
            <td style="width: 40%; text-align: center; border: none; font-size: 11pt;">
                <p style="margin: 0; padding-bottom: 5px;">Mengesahkan,</p>
                <p style="margin: 0; font-weight: bold;">Kepala Sekolah</p>
                
                <!-- Ruang untuk Tanda Tangan & Stempel -->
                <br><br><br><br>
                
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    <?= esc($appSettings['pejabat_kepsek_nama'] ?? 'NAMA KEPALA SEKOLAH') ?>
                </p>
                <p style="margin: 0;">
                    NIP. <?= esc($appSettings['pejabat_kepsek_nip'] ?? '-') ?>
                </p>
            </td>
        </tr>
    </table>
</body>

</html>