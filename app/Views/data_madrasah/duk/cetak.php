<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - <?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?></title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm 1cm 1.5cm 1cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            background: #e9ecef;
            margin: 0;
            padding: 20px;
        }

        .sheet {
            width: 27.7cm;
            min-height: 19cm;
            padding: 1.5cm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.15);
            box-sizing: border-box;
            position: relative;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .sheet {
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }

        .judul-duk {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-duk h2 {
            font-size: 13pt;
            font-weight: bold;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .judul-duk h3 {
            font-size: 11pt;
            font-weight: bold;
            margin: 0 0 3px 0;
            text-transform: uppercase;
        }

        .judul-duk p {
            font-size: 10pt;
            margin: 0;
            color: #222;
        }

        /* Tabel Formal DUK */
        table.duk-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 9pt;
        }

        table.duk-table th, 
        table.duk-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }

        table.duk-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8.5pt;
        }

        table.duk-table td.text-center {
            text-align: center;
        }

        table.duk-table td.text-right {
            text-align: right;
        }

        .nip-text {
            font-size: 8pt;
            color: #333;
            margin-top: 2px;
        }

        /* Tanda Tangan */
        .ttd-container {
            width: 100%;
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .ttd-box {
            width: 280px;
            text-align: center;
            font-size: 10pt;
        }

        .ttd-space {
            height: 65px;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Floating action buttons */
        .floating-bar {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: flex;
            gap: 10px;
            z-index: 9999;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 14px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: #206bc4;
            color: white;
        }

        .btn-print:hover {
            background: #185294;
        }

        .btn-close-window {
            background: #6c757d;
            color: white;
        }

        .btn-close-window:hover {
            background: #565e64;
        }
    </style>
</head>
<body>

    <!-- Tombol Cetak / Aksi Cepat -->
    <div class="floating-bar no-print">
        <button onclick="window.print()" class="btn-action btn-print">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" /><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" /><path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" /></svg>
            Cetak DUK (Landscape)
        </button>
        <button onclick="window.close()" class="btn-action btn-close-window">
            Tutup
        </button>
    </div>

    <div class="sheet">
        <!-- KOP SURAT RESMI -->
        <?= $this->include('layout/kop_surat') ?>

        <!-- JUDUL DOKUMEN -->
        <div class="judul-duk">
            <h2>DAFTAR URUT KEPANGKATAN (DUK) PEGAWAI</h2>
            <h3><?= esc($sub_title ?? 'PEGAWAI NEGERI SIPIL') ?></h3>
            <p>PADA <?= strtoupper(esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS')) ?> TAHUN <?= esc($tahun ?? date('Y')) ?></p>
        </div>

        <!-- TABEL DATA DUK -->
        <table class="duk-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 3%;">NO</th>
                    <th rowspan="2" style="width: 20%;">NAMA PEGAWAI / NIP</th>
                    <th colspan="2" style="width: 14%;">PANGKAT / GOLONGAN</th>
                    <th rowspan="2" style="width: 14%;">JABATAN</th>
                    <th rowspan="2" style="width: 10%;">MASA KERJA</th>
                    <th rowspan="2" style="width: 15%;">PENDIDIKAN TERAKHIR</th>
                    <th rowspan="2" style="width: 7%;">USIA</th>
                    <th rowspan="2" style="width: 9%;">TMT AWAL</th>
                    <th rowspan="2" style="width: 8%;">STATUS</th>
                </tr>
                <tr>
                    <th style="width: 7%;">GOL.</th>
                    <th style="width: 7%;">TMT</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($duk)) : ?>
                    <?php foreach ($duk as $item) : ?>
                        <tr>
                            <td class="text-center font-bold">
                                <?= (int)$item['no_urut_duk'] ?>
                            </td>
                            <td>
                                <strong><?= esc($item['nama_pegawai']) ?></strong>
                                <div class="nip-text">
                                    <?php if (!empty($item['nip'])) : ?>
                                        NIP. <?= esc($item['nip']) ?>
                                    <?php elseif (!empty($item['peg_id_nuptk'])) : ?>
                                        NUPTK. <?= esc($item['peg_id_nuptk']) ?>
                                    <?php else : ?>
                                        -
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <strong><?= esc($item['duk_label_golongan'] ?? '-') ?></strong>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item['duk_tmt_pangkat']) && $item['duk_tmt_pangkat'] !== '2099-12-31') : ?>
                                    <?= format_tanggal_indo($item['duk_tmt_pangkat']) ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= esc($item['jabatan_mengajar'] ?? '-') ?>
                            </td>
                            <td class="text-center">
                                <?= esc($item['duk_masa_kerja_format'] ?? '-') ?>
                            </td>
                            <td>
                                <strong><?= esc($item['pendidikan_terakhir'] ?? '-') ?></strong>
                                <?php if (!empty($item['perguruan_tinggi'])) : ?>
                                    <br><small><?= esc($item['perguruan_tinggi']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?= esc($item['duk_usia_format'] ?? '-') ?>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($item['duk_tmt_pengangkatan']) && $item['duk_tmt_pengangkatan'] !== '2099-12-31') : ?>
                                    <?= format_tanggal_indo($item['duk_tmt_pengangkatan']) ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?= strtoupper(esc($item['status_kepegawaian'] ?? '-')) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center" style="padding: 20px;">
                            Tidak ada data pegawai untuk cakupan yang dipilih.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- TANDA TANGAN KEPALA MADRASAH -->
        <div class="ttd-container">
            <div class="ttd-box">
                <div>Gisting, <?= format_tanggal_indo($tanggal_cetak ?? date('Y-m-d')) ?></div>
                <div>Kepala Madrasah,</div>
                <div class="ttd-space"></div>
                <div class="ttd-nama"><?= esc($appSettings['pejabat_kepsek_nama'] ?? 'Sipulloh, M.Pd.') ?></div>
                <div>NIP. <?= esc($appSettings['pejabat_kepsek_nip'] ?? '197005272007011019') ?></div>
            </div>
        </div>
    </div>

</body>
</html>
