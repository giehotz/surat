<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - <?= esc($surat['nomor_surat']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: #e9ecef; /* Abu-abu diluar kertas */
            margin: 0;
            padding: 20px;
        }
        .page {
            width: 21cm;
            min-height: 29.7cm;
            padding: 2.5cm; /* Margin kertas */
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            box-sizing: border-box;
            position: relative;
        }
        @media print {
            body {
                background: none;
                padding: 0;
            }
            .page {
                margin: 0;
                box-shadow: none;
            }
            .no-print {
                display: none;
            }
        }
        
        /* Kop Surat */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }
        .kop-surat::after { /* Garis tipis di bawah garis tebal */
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -5px;
            border-bottom: 1px solid #000;
        }
        .logo-kop {
            width: 90px;
            height: auto;
            margin-right: 20px;
        }
        .teks-kop {
            text-align: center;
            flex-grow: 1;
        }
        .teks-kop h1 {
            font-size: 16pt;
            margin: 0;
            text-transform: uppercase;
        }
        .teks-kop h2 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .teks-kop p {
            font-size: 11pt;
            margin: 2px 0 0 0;
        }
        
        /* Informasi Surat (Kiri & Kanan) */
        .info-surat {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-kiri table {
            border-collapse: collapse;
        }
        .info-kiri td {
            vertical-align: top;
            padding: 1px 0;
        }
        .info-kiri td:nth-child(2) {
            padding: 0 5px;
        }
        .info-kanan {
            text-align: right;
        }

        /* Tujuan Surat */
        .tujuan-surat {
            margin-bottom: 30px;
        }

        /* Isi Surat */
        .isi-surat {
            text-align: justify;
            margin-bottom: 30px;
        }
        
        /* Tanda Tangan */
        .ttd-box {
            width: 300px;
            float: right;
            text-align: center;
            margin-top: 20px;
        }
        .ttd-space {
            height: 80px; /* Ruang untuk stempel dan tanda tangan */
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Tembusan */
        .tembusan {
            clear: both;
            margin-top: 50px;
        }
        .tembusan-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        /* Tombol Print (Akan dihide saat cetak) */
        .btn-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #0054a6;
            color: white;
            border: none;
            padding: 15px 25px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .btn-print:hover {
            background: #004080;
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print no-print">Cetak Surat</button>

    <div class="page">
        <!-- KOP SURAT -->
        <div class="kop-surat">
            <?php 
                // Gunakan logo dari appSettings jika ada, atau logo default
                $logo = !empty($appSettings['sekolah_logo']) ? base_url('uploads/logo/' . $appSettings['sekolah_logo']) : 'https://via.placeholder.com/90';
            ?>
            <img src="<?= $logo ?>" class="logo-kop" alt="Logo Instansi">
            <div class="teks-kop">
                <h1 style="font-size: 14pt; margin-bottom: 2px; font-weight: normal;"><?= esc($appSettings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?></h1>
                <h1 style="font-size: 14pt; margin-bottom: 2px; font-weight: bold;"><?= esc($appSettings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?></h1>
                <h2 style="font-size: 16pt; margin-bottom: 5px;"><?= esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?></h2>
                <p style="font-size: 10pt; margin-bottom: 0;"><?= esc($appSettings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></p>
                <p style="font-size: 10pt;">Email : <?= esc($appSettings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?></p>
            </div>
        </div>

        <!-- INFO SURAT -->
        <div class="info-surat">
            <div class="info-kiri">
                <table>
                    <tr>
                        <td>Nomor</td>
                        <td>:</td>
                        <td><?= esc($surat['nomor_surat']) ?></td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td><?= esc($surat['lampiran'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <td>Hal</td>
                        <td>:</td>
                        <td><?= esc($surat['perihal']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="info-kanan">
                <?= date('d M Y', strtotime($surat['tanggal_surat'])) ?>
            </div>
        </div>

        <!-- TUJUAN -->
        <div class="tujuan-surat">
            <p style="margin: 0;">Kepada Yth.</p>
            <p style="margin: 0;"><b><?= esc($surat['tujuan_nama']) ?></b></p>
            <p style="margin: 0; white-space: pre-line;"><?= esc($surat['tujuan_alamat']) ?></p>
        </div>

        <!-- ISI SURAT -->
        <div class="isi-surat">
            <p style="margin-bottom: 15px;"><?= esc($surat['salam_pembuka']) ?></p>
            
            <!-- Output rich text. Karena ini dari wysiwyg, jangan di-escape HTML-nya -->
            <div class="isi-content">
                <?= $surat['isi_surat'] ?>
            </div>

            <p style="margin-top: 20px;"><?= esc($surat['salam_penutup']) ?></p>
        </div>

        <!-- TANDA TANGAN -->
        <div class="ttd-box">
            <div style="margin-bottom: 5px;"><?= esc($surat['pengirim_jabatan']) ?>,</div>
            <div class="ttd-space"></div>
            <div class="ttd-nama"><?= esc($surat['pengirim_nama']) ?></div>
            <?php if(!empty($surat['pengirim_nip'])): ?>
                <div>NIP. <?= esc($surat['pengirim_nip']) ?></div>
            <?php endif; ?>
        </div>

        <!-- TEMBUSAN -->
        <?php if(!empty($surat['tembusan'])): ?>
        <div class="tembusan">
            <div class="tembusan-title">Tembusan:</div>
            <div style="white-space: pre-line; font-size: 11pt;">
                <?= esc($surat['tembusan']) ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>
