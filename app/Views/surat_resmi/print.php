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
            font-size: 11pt;
        }
        
        /* Tanda Tangan */
        .ttd-box {
            width: 300px;
            float: right;
            text-align: center;
            margin-top: 20px;
            font-size: 11pt;
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
        <?= $this->include('layout/kop_surat') ?>

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
                <?= format_tanggal_indo($surat['tanggal_surat']) ?>
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
