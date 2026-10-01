<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - <?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?></title>
    <style>
        @page {
            size: 215mm 330mm; /* Standar Ukuran Kertas F4 / Folio */
            margin: 6mm 10mm 5mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #e5e7eb;
            color: #000;
            margin: 0;
            padding: 20px 10px;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .toolbar-cetak {
            max-width: 215mm;
            margin: 0 auto 20px auto;
            background: #ffffff;
            padding: 10px 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }

        .toolbar-info {
            font-size: 13px;
            color: #4b5563;
        }

        .btn-cetak {
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-cetak:hover {
            background-color: #1d4ed8;
        }

        .btn-kembali {
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #d1d5db;
            padding: 8px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-kembali:hover {
            background-color: #e5e7eb;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="toolbar-cetak no-print">
        <div class="toolbar-info">
            <strong>Daftar Hadir Harian Pegawai</strong> &bull; Ukuran F4 / Folio (215 &times; 330 mm) &bull; <?= count($daftar_guru) ?> Lembar
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="<?= base_url('daftar-hadir/harian?' . http_build_query($_GET)) ?>" class="btn-kembali">Kembali ke Filter</a>
            <button onclick="window.print()" class="btn-cetak">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Cetak Dokumen
            </button>
        </div>
    </div>

    <!-- Lembar-lembar F4 Tiap Guru -->
    <?= $this->include('daftar_hadir/partials/lembar_hadir_harian') ?>

    <?php if (!empty($autoprint)): ?>
    <script>
        // Otomatis picu dialog print browser jika tombol Cetak Langsung ditekan
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 300);
        });
    </script>
    <?php endif; ?>

</body>
</html>
