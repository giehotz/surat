<?php
/**
 * Partial: Lembar Daftar Hadir Harian Pegawai (Format F4 / Folio)
 * Diadopsi dari template regulasi: PERATURAN DIRJEN PENDIS NO. 1 TAHUN 2013
 */
?>

<!-- Styling Sempurna Standar Kertas F4 / Folio (Perdirjen Pendis 1/2013) -->
<style>
    @page {
        size: 215mm 330mm; /* Standar Ukuran Kertas F4 / Folio */
        margin: 8mm 12mm 8mm 14mm; /* Top: 8mm, Right: 12mm, Bottom: 8mm, Left: 14mm (Aman untuk perforator jilid arsip) */
    }

    .f4-sheet {
        font-family: Arial, Helvetica, sans-serif !important;
        width: 215mm;
        height: 330mm;
        max-height: 330mm;
        padding: 8mm 12mm 8mm 14mm; /* Sesuai margin @page di tampilan layar */
        margin: 20px auto;
        background: #ffffff;
        color: #000000;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.18);
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        overflow: hidden;
        page-break-after: always;
        break-after: page;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .f4-sheet:last-child {
        page-break-after: avoid;
        break-after: avoid;
    }

    .f4-sheet h2, .f4-sheet h3, .f4-sheet p, .f4-sheet span, .f4-sheet td, .f4-sheet th {
        font-family: Arial, Helvetica, sans-serif !important;
        color: #000000;
    }

    /* Header Dokumen Sesuai Regulasi */
    .doc-header {
        text-align: center;
        margin-bottom: 2mm;
    }

    .doc-header .header-sub {
        font-size: 8.5pt;
        font-weight: 700;
        letter-spacing: -0.01em;
        text-transform: uppercase;
        line-height: 1.2;
        margin: 0 0 1px 0;
    }

    .doc-header .header-title {
        font-size: 11pt;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin: 1px 0 1px 0;
    }

    .doc-header .header-satker {
        font-size: 10.5pt;
        font-weight: 800;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        margin: 0;
    }

    /* Grid Identitas Pegawai & Satker */
    .grid-identitas {
        display: grid;
        grid-template-columns: 1fr 1fr;
        font-size: 10pt;
        font-weight: 600;
        border-bottom: 1.5px solid #000000;
        padding-bottom: 2px;
        margin-bottom: 2mm;
        margin-top: 1mm;
        line-height: 1.25;
    }

    .flex-row-id {
        display: flex;
    }

    .label-id {
        width: 72px;
        display: inline-block;
        font-weight: bold;
    }

    .value-id {
        flex: 1;
    }

    /* Tabel Presensi 31 Baris */
    table.table-absen {
        border-collapse: collapse;
        width: 100%;
        font-size: 10pt;
        line-height: 1.15;
        margin: 0;
    }

    table.table-absen th {
        border: 1px solid #111111;
        background-color: #f3f4f6 !important;
        padding: 2.5px 2px;
        text-align: center;
        vertical-align: middle;
        font-size: 9.5pt;
        font-weight: bold;
    }

    table.table-absen td {
        border: 1px solid #111111;
        padding: 1px 2px;
        text-align: center;
        vertical-align: middle;
        height: 20pt;
        box-sizing: border-box;
        font-size: 9.5pt;
    }

    .th-no { width: 30px; }
    .th-tgl { width: 68px; }
    .th-jam { width: 56px; }
    .th-paraf { width: 65px; }
    .th-ket { width: 120px; white-space: nowrap; }

    .ahad-row,
    .ahad-row td {
        background-color: #cccccc !important; /* Shading abu-abu lebih gelap dan tegas untuk hari Ahad & Libur Nasional */
        color: #8b0000 !important; /* Teks merah gelap pekat dengan kontras tinggi */
        font-weight: 700;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .jam-kosong {
        white-space: pre;
        letter-spacing: 1px;
    }

    /* Blok Tanda Tangan: Struktur Baris Identik & Sejajar (Sekitar 2x Enter di Bawah Tabel) */
    .ttd-wrap {
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin-top: 6.5mm; /* Sekitar 2x enter di bawah tabel agar tidak terlalu ke bawah */
        font-size: 10.5pt;
        line-height: 1.25;
    }

    .ttd-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .ttd-row-tanggal,
    .ttd-row-jabatan,
    .ttd-row-nama,
    .ttd-row-nip {
        min-height: 1.25em;
        margin: 0;
    }

    .ttd-space {
        height: 2em; /* Sekitar 2x enter untuk ruang tanda tangan */
    }

    .ttd-row-nama {
        font-weight: 700;
        text-decoration: underline;
    }

    .ttd-row-nip {
        font-size: 9pt;
    }

    /* Optimasi Cetak (Print Media) */
    @media print {
        @page {
            size: 215mm 330mm;
            margin: 8mm 12mm 8mm 14mm;
        }

        body {
            background: transparent !important;
            padding: 0 !important;
            margin: 0 !important;
            color: #000000 !important;
        }

        .f4-sheet {
            width: 100% !important;
            height: 100% !important;
            min-height: 0 !important;
            max-height: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border: none !important;
            page-break-after: always !important;
            break-after: page !important;
            overflow: visible !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: flex-start !important;
        }

        .f4-sheet:last-child {
            page-break-after: avoid !important;
            break-after: avoid !important;
        }
    }
</style>

<?php if (empty($daftar_guru)): ?>
    <div class="alert alert-warning text-center my-4 py-4 w-100 shadow-sm border-0 rounded-3">
        <i class="ti ti-alert-circle fs-1 d-block mb-2 text-warning"></i>
        <h4 class="fw-bold mb-1">Tidak Ada Data Guru / Pegawai</h4>
        <p class="text-muted mb-0">Tidak ditemukan data pegawai untuk kriteria filter yang dipilih. Silakan ubah filter status atau nama pegawai.</p>
    </div>
<?php else: ?>
    <?php foreach ($daftar_guru as $idx => $guru): ?>
        <div class="f4-sheet text-gray-900 bg-white" id="sheet-guru-<?= esc($guru['id']) ?>">
            <div>
                <!-- Header Dokumen Sesuai Regulasi Kemenag -->
                <div class="doc-header">
                    <h3 class="header-sub">
                        PERATURAN DIREKTUR JENDERAL PENDIDIKAN ISLAM NOMOR 1 TAHUN 2013
                    </h3>
                    <h3 class="header-sub">
                        TENTANG DISIPLIN KEHADIRAN GURU DI LINGKUNGAN MADRASAH
                    </h3>
                    <h2 class="header-title">
                        DAFTAR HADIR PEGAWAI
                    </h2>
                    <h2 class="header-satker">
                        <?= strtoupper(esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS')) ?>
                    </h2>
                </div>

                <!-- Baris Identitas Pegawai & Satker -->
                <div class="grid-identitas">
                    <div class="col-kiri">
                        <div class="flex-row-id">
                            <span class="label-id">NAMA</span>
                            <span class="value-id">: <?= esc($guru['nama_pegawai'] ?? '-') ?></span>
                        </div>
                        <div class="flex-row-id">
                            <span class="label-id">NIP</span>
                            <span class="value-id">: <?= !empty($guru['nip']) ? esc($guru['nip']) : '-' ?></span>
                        </div>
                    </div>
                    <div class="col-kanan">
                        <div class="flex-row-id">
                            <span class="label-id">SATKER</span>
                            <span class="value-id">: <?= strtoupper(esc($appSettings['sekolah_nama'] ?? 'MIN 2 TANGGAMUS')) ?></span>
                        </div>
                        <div class="flex-row-id">
                            <span class="label-id">BULAN</span>
                            <span class="value-id">: <?= strtoupper(esc($nama_bulan . ' ' . $tahun)) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tabel Kehadiran 31 Baris -->
                <table class="table-absen">
                    <thead>
                        <tr>
                            <th rowspan="2" class="th-no">NO</th>
                            <th rowspan="2" class="th-tgl">TANGGAL</th>
                            <th colspan="2">KEDATANGAN</th>
                            <th colspan="2">KEPULANGAN</th>
                            <th rowspan="2" class="th-ket">KETERANGAN</th>
                        </tr>
                        <tr>
                            <th class="th-jam">JAM</th>
                            <th class="th-paraf">PARAF</th>
                            <th class="th-jam">JAM</th>
                            <th class="th-paraf">PARAF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($days as $day): ?>
                            <?php if ($day['tipe'] === 'ahad' || $day['tipe'] === 'libur'): ?>
                                <tr class="ahad-row">
                                    <td><?= $day['no'] ?></td>
                                    <td><?= esc($day['tanggal']) ?></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td class="text-truncate" title="<?= esc($day['keterangan']) ?>"><?= esc($day['keterangan']) ?></td>
                                </tr>
                            <?php elseif ($day['tipe'] === 'empty'): ?>
                                <tr>
                                    <td><?= $day['no'] ?></td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <td><?= $day['no'] ?></td>
                                    <td><?= esc($day['tanggal']) ?></td>
                                    <td><span class="jam-kosong">&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</span></td>
                                    <td></td>
                                    <td><span class="jam-kosong">&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;</span></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Blok Tanda Tangan: Struktur Baris Identik & Sejajar -->
            <div class="ttd-wrap">
                <div class="ttd-col">
                    <p class="ttd-row-tanggal">&nbsp;</p>
                    <p class="ttd-row-jabatan">Pegawai yang bersangkutan,</p>
                    <div class="ttd-space"></div><br>
                    <p class="ttd-row-nama"><?= esc($guru['nama_pegawai'] ?? '') ?></p>
                    <p class="ttd-row-nip"><?= !empty($guru['nip']) ? 'NIP. ' . esc($guru['nip']) : '-' ?></p>
                </div>

                <div class="ttd-col">
                    <p class="ttd-row-tanggal"><?= esc($kota_titimangsa) ?>, <?= esc($tanggal_akhir_bulan) ?></p>
                    <p class="ttd-row-jabatan">Kepala Madrasah,</p>
                    <div class="ttd-space"></div><br>
                    <p class="ttd-row-nama"><?= esc($appSettings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I') ?></p>
                    <p class="ttd-row-nip"><?= !empty($appSettings['pejabat_kepsek_nip']) ? 'NIP. ' . esc($appSettings['pejabat_kepsek_nip']) : '-' ?></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
