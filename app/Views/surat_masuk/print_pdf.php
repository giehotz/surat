<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Surat Masuk</title>
    <style>
        /* Pengaturan Kertas & Font Formal */
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.3;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-surat td {
            padding: 0;
            vertical-align: middle;
        }
        .garis-kop {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 2px;
            margin-top: 10px;
            margin-bottom: 20px;
        }

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
            font-size: 10pt;
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
            <!-- Kolom Logo (Kiri) -->
            <td style="width: 15%; text-align: center; vertical-align: middle;">
                <?php if ($logoDataUri): ?>
                    <img src="<?= $logoDataUri ?>" width="90" alt="Logo">
                <?php endif; ?>
            </td>
            <!-- Kolom Teks (Tengah) -->
            <td style="width: 70%; text-align: center; vertical-align: middle;">
                <?php if (!empty($appSettings['sekolah_kementerian'])): ?>
                    <!-- Baris 1: Nama Instansi Induk — Kapital, Bold, 14pt -->
                    <div style="font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px;">
                        <?= esc($appSettings['sekolah_kementerian']) ?>
                    </div>
                <?php endif; ?>
                <!-- Baris 2: Nama Satuan Kerja — Kapital, Bold, 16pt -->
                <div style="font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 2px 0;">
                    <?= esc($appSettings['sekolah_nama'] ?? 'NAMA INSTITUSI') ?>
                </div>
                <!-- Baris 3+: Alamat & Kontak — Title case, Normal, 9pt -->
                <div style="font-size: 9pt; font-weight: normal; line-height: 1.5;">
                    <?php if (!empty($appSettings['sekolah_npsn']) || !empty($appSettings['sekolah_nsm'])): ?>
                        NPSN: <?= esc($appSettings['sekolah_npsn'] ?? '-') ?> | NSM: <?= esc($appSettings['sekolah_nsm'] ?? '-') ?><br>
                    <?php endif; ?>
                    <?= esc($appSettings['sekolah_alamat'] ?? 'Alamat Lengkap Institusi') ?><br>
                    <?php if (!empty($appSettings['sekolah_kontak'])): ?>
                        Telepon/Fax: <?= esc($appSettings['sekolah_kontak']) ?>
                    <?php endif; ?>
                </div>
            </td>
            <!-- Kolom Spacer (Kanan) - Untuk menyeimbangkan posisi tengah -->
            <td style="width: 15%;"></td>
        </tr>
    </table>
    <!-- Garis Kop Surat Formal -->
    <div class="garis-kop"></div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA SURAT MASUK</h3>
        <?php if (isset($filter_text)): ?>
            <p><?= esc($filter_text) ?></p>
        <?php endif; ?>
    </div>

    <!-- TABEL DATA -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Tgl Diterima</th>
                <th style="width: 15%; white-space: nowrap;">No Surat</th>
                <th style="width: 12%;">Tgl Surat</th>
                <th style="width: 20%;">Pengirim</th>
                <th style="width: 26%;">Perihal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(!empty($surat_masuk)):
                foreach ($surat_masuk as $surat) : 
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= format_tanggal_indo($surat['tanggal_terima']) ?></td>
                    <td class="text-center text-nowrap"><?= esc($surat['nomor_surat'] ?? '-') ?></td>
                    <td class="text-center"><?= format_tanggal_indo($surat['tanggal_surat']) ?></td>
                    <td class="text-left"><?= esc($surat['pengirim']) ?></td>
                    <td class="text-left"><?= esc($surat['perihal']) ?></td>
                </tr>
            <?php 
                endforeach; 
            else:
            ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data surat masuk pada periode ini.</td>
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