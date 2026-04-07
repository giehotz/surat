<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Data Prestasi Siswa</title>
    <style>
        /* Pengaturan Kertas & Font Formal */
        body {
            font-family: 'Times New Roman', Times, serif; /* Standar dokumen formal */
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
            font-size: 10pt; /* Diatur ke 10pt agar muat banyak kolom */
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
            <td style="width: 15%; text-align: center;">
                <?php if ($logoDataUri): ?>
                    <img src="<?= $logoDataUri ?>" width="90" alt="Logo">
                <?php endif; ?>
            </td>
            <!-- Kolom Teks (Tengah) -->
            <td style="width: 70%; text-align: center;">
                <h2 style="margin: 0; font-size: 16pt;" class="text-uppercase fw-bold">
                    <?= esc($appSettings['sekolah_nama'] ?? 'NAMA INSTITUSI') ?>
                </h2>
                <div style="font-size: 11pt; margin-top: 5px;">
                    NPSN: <?= esc($appSettings['sekolah_npsn'] ?? '-') ?> | NSM: <?= esc($appSettings['sekolah_nsm'] ?? '-') ?> <br>
                    <?= esc($appSettings['sekolah_alamat'] ?? 'Alamat Lengkap Institusi') ?> <br>
                    Kontak: <?= esc($appSettings['sekolah_kontak'] ?? '-') ?>
                </div>
            </td>
            <!-- Kolom Spacer (Kanan) -->
            <td style="width: 15%;"></td>
        </tr>
    </table>
    <!-- Garis Kop Surat Formal -->
    <div class="garis-kop"></div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA PRESTASI SISWA</h3>
        <?php if (isset($filter_text)): ?>
            <p><?= esc($filter_text) ?></p>
        <?php endif; ?>
    </div>

    <!-- TABEL DATA -->
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 10%; white-space: nowrap;">Tanggal</th>
                <th style="width: 16%;">Nama Siswa</th>
                <th style="width: 10%; white-space: nowrap;">NISN</th>
                <th style="width: 14%;">Jenis Prestasi</th>
                <th style="width: 10%;">Tingkat</th>
                <th style="width: 15%;">Nama Lomba</th>
                <th style="width: 10%;">Peringkat</th>
                <th style="width: 10%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if(!empty($prestasi)):
                foreach ($prestasi as $item) : 
            ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center text-nowrap"><?= format_tanggal_indo($item['tanggal']) ?></td>
                    <td class="text-left"><?= esc($item['nama_siswa']) ?></td>
                    <td class="text-center text-nowrap"><?= esc($item['nisn']) ?></td>
                    <td class="text-left"><?= esc($item['jenis_prestasi']) ?></td>
                    <td class="text-center"><?= esc($item['tingkat']) ?></td>
                    <td class="text-left"><?= esc($item['nama_lomba'] ?? '-') ?></td>
                    <td class="text-center"><?= esc($item['peringkat'] ?? '-') ?></td>
                    <td class="text-left"><?= esc($item['keterangan'] ?? '-') ?></td>
                </tr>
            <?php 
                endforeach; 
            else:
            ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 15px;">Tidak ada data prestasi siswa pada periode ini.</td>
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