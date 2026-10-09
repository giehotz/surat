<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan Rekapitulasi Surat Masuk dan Surat Keluar') ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #000;
            line-height: 1.35;
            background-color: #fff;
        }

        /* Kop Surat */
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }

        .kop-surat td {
            vertical-align: middle;
            padding: 0;
        }

        .kop-logo {
            width: 80px;
            text-align: center;
            padding-right: 12px;
        }

        .kop-logo img {
            width: 70px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .kop-teks {
            text-align: center;
        }

        .kop-teks h1 {
            font-size: 11.5pt;
            margin: 0 0 2px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-teks h2 {
            font-size: 12pt;
            margin: 0 0 3px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-teks p {
            font-size: 8.5pt;
            margin: 0;
            line-height: 1.35;
        }

        .garis-kop-tebal {
            border: none;
            border-top: 3px solid #000;
            margin: 0;
        }

        .garis-kop-tipis {
            border: none;
            border-top: 1px solid #000;
            margin: 2px 0 12px 0;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin-bottom: 12px;
        }

        .judul-laporan h3 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 0.5px;
        }

        .judul-laporan p {
            margin: 3px 0 0 0;
            font-size: 10pt;
            font-weight: bold;
        }

        /* Tabel Memo / Identitas Laporan */
        .table-memo {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9.5pt;
            border: 1px solid #333;
            background-color: #fafafa;
        }

        .table-memo td {
            padding: 3.5px 7px;
            vertical-align: top;
            border: none;
        }

        /* Bab & Sub Bab */
        .bab-title {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 12px 0 4px 0;
            padding-bottom: 2px;
            border-bottom: 1px solid #444;
        }

        .subbab-title {
            font-size: 10pt;
            font-weight: bold;
            margin: 8px 0 4px 0;
        }

        .sub-subbab {
            margin: 4px 0 2px 0;
            font-weight: bold;
            font-size: 9.5pt;
        }

        p.text-justify {
            text-align: justify;
            text-justify: inter-word;
            margin: 4px 0 6px 0;
            line-height: 1.35;
            font-size: 9.5pt;
        }

        ol, ul {
            margin: 3px 0 6px 18px;
            padding: 0;
        }

        li {
            margin-bottom: 3px;
            line-height: 1.35;
            font-size: 9.5pt;
        }

        /* Tabel Data */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0 10px 0;
            font-size: 8pt;
        }

        .table-data th,
        .table-data td {
            border: 1px solid #000;
            padding: 3.5px 4px;
            word-wrap: break-word;
        }

        .table-data th {
            background-color: #f1f3f5;
            text-align: center;
            font-weight: bold;
            vertical-align: middle;
            text-transform: uppercase;
            font-size: 7.5pt;
        }

        .table-data td {
            vertical-align: top;
        }

        .table-data tfoot th,
        .table-data tfoot td {
            font-weight: bold;
            background-color: #f8f9fa;
            font-size: 8pt;
        }

        /* Dompdf multi-page table handling */
        table.table-data {
            page-break-inside: auto;
        }

        table.table-data tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table.table-data thead {
            display: table-header-group;
        }

        table.table-data tfoot {
            display: table-footer-group;
        }

        /* Badge status */
        .badge-status {
            display: inline-block;
            padding: 1px 3px;
            font-size: 7pt;
            text-transform: uppercase;
            border: 1px solid #555;
            border-radius: 2px;
            background-color: #f8f9fa;
        }

        /* Utilitas Teks */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-nowrap { white-space: nowrap; }
        .fw-bold { font-weight: bold; }

        /* Page break helper */
        .page-break {
            page-break-after: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Lembar Tanda Tangan */
        .ttd-table {
            width: 100%;
            border: none;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .ttd-table td {
            border: none;
            padding: 0;
            vertical-align: top;
            font-size: 9.5pt;
        }
    </style>
</head>

<body>
    <!-- ==================== HALAMAN 1: KOP, MEMO, PENDAHULUAN, & PELAKSANAAN SURAT MASUK ==================== -->

    <!-- KOP SURAT MADRASAH RESMI -->
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <?php if (!empty($logoDataUri)): ?>
                    <img src="<?= $logoDataUri ?>" alt="Logo">
                <?php else: ?>
                    <div style="width: 70px; height: 70px; border: 1px dashed #999; margin: 0 auto; text-align: center; line-height: 70px; font-size: 8pt;">LOGO</div>
                <?php endif; ?>
            </td>
            <td class="kop-teks">
                <h1><?= esc($kopSurat['kementerian'] ?? ($appSettings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA')) ?></h1>
                <?php 
                $kantorKemenag = $kopSurat['kantor_kementerian'] ?? ($appSettings['sekolah_kantor_kementerian'] ?? '');
                if (!empty($kantorKemenag)): 
                ?>
                    <h1><?= esc($kantorKemenag) ?></h1>
                <?php endif; ?>
                <h2><?= esc($kopSurat['nama_madrasah_kop'] ?? ($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS')) ?></h2>
                <p><?= esc($kopSurat['alamat_kop'] ?? ($appSettings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378')) ?></p>
                <p>Email: <?= esc($kopSurat['kontak_kop'] ?? ($appSettings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com')) ?></p>
            </td>
            <td style="width: 80px;"></td>
        </tr>
    </table>
    <hr class="garis-kop-tebal">
    <hr class="garis-kop-tipis">

    <!-- JUDUL DOKUMEN LAPORAN -->
    <div class="judul-laporan">
        <h3>LAPORAN REKAPITULASI SURAT MASUK DAN SURAT KELUAR</h3>
        <p>Periode: <?= esc($filter_text) ?></p>
    </div>

    <!-- KEPALA LAPORAN / MEMO KEDINASAN -->
    <table class="table-memo">
        <tr>
            <td style="width: 15%; font-weight: bold;">Kepada</td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;">Kepala <?= esc($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus') ?></td>
            <td style="width: 12%; font-weight: bold;">Tanggal</td>
            <td style="width: 2%;">:</td>
            <td style="width: 21%;"><?= format_tanggal_indo($tanggal_cetak ?? date('Y-m-d')) ?></td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Dari</td>
            <td>:</td>
            <td><?= esc($pelapor_jabatan ?? 'Staf Tata Usaha / Admin Persuratan') ?></td>
            <td style="font-weight: bold;">Lampiran</td>
            <td>:</td>
            <td>1 (satu) berkas</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Perihal</td>
            <td>:</td>
            <td colspan="4">Laporan Rekapitulasi Pencatatan Surat Masuk dan Surat Keluar</td>
        </tr>
    </table>

    <!-- I. PENDAHULUAN -->
    <div class="bab-title">I. PENDAHULUAN</div>
    <p class="text-justify">
        Laporan ini dibuat sebagai bentuk pertanggungjawaban kedinasan dan akuntabilitas administrasi persuratan pada 
        <strong><?= esc($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus') ?></strong> untuk periode <strong><?= esc($filter_text) ?></strong>. 
        Administrasi surat masuk dan surat keluar merupakan pilar penting dalam tata kelola perkantoran madrasah guna mendukung kelancaran 
        komunikasi resmi, koordinasi lintas instansi, kecepatan tindak lanjut instruksi disposisi Kepala Madrasah, serta pengarsipan dokumentasi kedinasan yang tertib dan akuntabel.
    </p>
    <p class="text-justify">
        Tujuan penyusunan laporan ini adalah menyajikan data rekapitulasi pencatatan surat masuk dan surat keluar secara transparan, 
        mengevaluasi kelancaran alur disposisi dan tata kelola persuratan, mengidentifikasi hambatan administratif yang dihadapi, 
        serta memberikan rekomendasi tindak lanjut bagi peningkatan mutu layanan tata usaha madrasah.
    </p>

    <!-- II. PELAKSANAAN ADMINISTRASI PERSURATAN -->
    <div class="bab-title">II. PELAKSANAAN ADMINISTRASI PERSURATAN</div>

    <!-- A. SURAT MASUK -->
    <div class="subbab-title">A. Surat Masuk</div>
    <table style="width: 100%; border: none; font-size: 9.5pt; margin-bottom: 6px;">
        <tr>
            <td style="width: 20%; vertical-align: top; font-weight: bold;">1. Waktu dan Tempat</td>
            <td style="width: 2%; vertical-align: top;">:</td>
            <td style="vertical-align: top;">Periode <?= esc($filter_text) ?> bertempat di Kantor Tata Usaha <?= esc($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus') ?>.</td>
        </tr>
        <tr>
            <td style="vertical-align: top; font-weight: bold;">2. Sasaran / Sumber</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">Seluruh surat masuk yang diterima madrasah dari Kementerian Agama, Dinas Pendidikan, instansi pemerintah/swasta, komite madrasah, dewan guru, maupun masyarakat luas.</td>
        </tr>
        <tr>
            <td style="vertical-align: top; font-weight: bold;">3. Proses Pelaksanaan</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">Surat diterima oleh petugas, dicatat ke dalam buku agenda digital, diverifikasi kelengkapan berkas fisik, diajukan kepada Kepala Madrasah untuk memperoleh arahan disposisi, kemudian diteruskan kepada pihak terkait untuk ditindaklanjuti serta diarsipkan.</td>
        </tr>
    </table>

    <div class="sub-subbab">4. Rekapitulasi & Rincian Agenda Surat Masuk (Total: <?= number_format($total_masuk, 0, ',', '.') ?> Surat)</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">No. Agenda</th>
                <th style="width: 11%;">Tgl Terima</th>
                <th style="width: 16%;">Nomor Surat</th>
                <th style="width: 11%;">Tgl Surat</th>
                <th style="width: 19%;">Asal Surat (Pengirim)</th>
                <th style="width: 18%;">Perihal</th>
                <th style="width: 9%;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($surat_masuk)): ?>
                <?php $noM = 1; foreach ($surat_masuk as $sm): ?>
                    <tr>
                        <td class="text-center"><?= $noM++ ?></td>
                        <td class="text-center"><strong><?= esc($sm['nomor_agenda'] ?? '-') ?></strong></td>
                        <td class="text-center"><?= format_tanggal_indo($sm['tanggal_terima']) ?></td>
                        <td class="text-center"><?= esc($sm['nomor_surat'] ?? '-') ?></td>
                        <td class="text-center"><?= format_tanggal_indo($sm['tanggal_surat']) ?></td>
                        <td><?= esc($sm['pengirim']) ?></td>
                        <td><?= esc($sm['perihal']) ?></td>
                        <td class="text-center">
                            <?php if (!empty($sm['disposisi_status'])): ?>
                                <span class="badge-status"><?= esc(ucfirst($sm['disposisi_status'])) ?></span>
                            <?php else: ?>
                                <span class="badge-status"><?= esc(ucfirst($sm['status'] ?? 'tercatat')) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 10px; color: #666;">
                        <em>Tidak ada catatan surat masuk pada rentang periode ini.</em>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">TOTAL SURAT MASUK TERCATAT:</th>
                <th class="text-center"><?= number_format($total_masuk, 0, ',', '.') ?> Surat</th>
            </tr>
        </tfoot>
    </table>

    <!-- ==================== HALAMAN 2: PELAKSANAAN SURAT KELUAR ==================== -->
    <div class="page-break"></div>

    <!-- B. SURAT KELUAR -->
    <div class="subbab-title">B. Surat Keluar</div>
    <table style="width: 100%; border: none; font-size: 9.5pt; margin-bottom: 6px;">
        <tr>
            <td style="width: 20%; vertical-align: top; font-weight: bold;">1. Waktu dan Tempat</td>
            <td style="width: 2%; vertical-align: top;">:</td>
            <td style="vertical-align: top;">Periode <?= esc($filter_text) ?> bertempat di Kantor Tata Usaha <?= esc($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus') ?>.</td>
        </tr>
        <tr>
            <td style="vertical-align: top; font-weight: bold;">2. Sasaran / Tujuan</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">Seluruh surat dinas yang dibuat dan dikirimkan oleh madrasah kepada instansi eksternal (Kemenag, Dinas, Sekolah/Madrasah lain) maupun internal (Dewan Guru, Tenaga Kependidikan, Wali Murid).</td>
        </tr>
        <tr>
            <td style="vertical-align: top; font-weight: bold;">3. Proses Pelaksanaan</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">Surat disusun berdasarkan kebutuhan dinas, diverifikasi dan disetujui (approval) oleh pimpinan, diberikan nomor surat dinas resmi berdasarkan kode klasifikasi, ditandatangani oleh Kepala Madrasah, dikirimkan kepada alamat tujuan, dan diarsipkan.</td>
        </tr>
    </table>

    <div class="sub-subbab">4. Rekapitulasi & Rincian Agenda Surat Keluar (Total: <?= number_format($total_keluar, 0, ',', '.') ?> Surat)</div>
    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">No. Agenda</th>
                <th style="width: 11%;">Tgl Kirim</th>
                <th style="width: 16%;">Nomor Surat</th>
                <th style="width: 11%;">Tgl Surat</th>
                <th style="width: 19%;">Tujuan Surat</th>
                <th style="width: 17%;">Perihal</th>
                <th style="width: 10%;">Pencatat/Penandatangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($surat_keluar)): ?>
                <?php $noK = 1; foreach ($surat_keluar as $sk): ?>
                    <tr>
                        <td class="text-center"><?= $noK++ ?></td>
                        <td class="text-center"><strong><?= esc($sk['nomor_agenda'] ?? '-') ?></strong></td>
                        <td class="text-center"><?= format_tanggal_indo($sk['tanggal_kirim']) ?></td>
                        <td class="text-center"><?= esc($sk['nomor_surat'] ?? '-') ?></td>
                        <td class="text-center"><?= format_tanggal_indo($sk['tanggal_surat']) ?></td>
                        <td><?= esc($sk['tujuan'] ?? '-') ?></td>
                        <td><?= esc($sk['perihal']) ?></td>
                        <td class="text-center">
                            <?= esc($sk['penandatangan'] ?? ($appSettings['pejabat_kepsek_nama'] ?? 'Kepala Madrasah')) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 10px; color: #666;">
                        <em>Tidak ada catatan surat keluar pada rentang periode ini.</em>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="7" class="text-right">TOTAL SURAT KELUAR TERCATAT:</th>
                <th class="text-center"><?= number_format($total_keluar, 0, ',', '.') ?> Surat</th>
            </tr>
        </tfoot>
    </table>

    <!-- ==================== HALAMAN 3: HASIL, EVALUASI, KEUANGAN, PENUTUP, & TANDA TANGAN ==================== -->
    <div class="page-break"></div>

    <!-- III. HASIL YANG DICAPAI & EVALUASI -->
    <div class="bab-title">III. HASIL YANG DICAPAI & EVALUASI</div>

    <div class="subbab-title">A. Hasil yang Dicapai</div>
    <ul>
        <li>Seluruh surat masuk dan surat keluar pada periode ini telah berhasil dicatat dalam buku kendali agenda dan sistem digital secara berurutan.</li>
        <li>Surat masuk telah diteruskan kepada Kepala Madrasah untuk memperoleh disposisi serta didistribusikan kepada pihak pelaksana tugas.</li>
        <li>Dokumen fisik dan pindaian digital telah tersimpan rapi dalam folder arsip persuratan madrasah.</li>
        <li>
            <strong>Rekapitulasi Volume Dokumen:</strong>
            Surat Masuk: <strong><?= number_format($total_masuk, 0, ',', '.') ?></strong> surat | 
            Surat Keluar: <strong><?= number_format($total_keluar, 0, ',', '.') ?></strong> surat | 
            Total Terproses: <strong><?= number_format($total_semua, 0, ',', '.') ?></strong> dokumen.
        </li>
    </ul>

    <!-- Tabel Rekapitulasi Bulanan Agregat -->
    <div class="sub-subbab">Rekapitulasi Kinerja Pencatatan per Bulan:</div>
    <table class="table-data" style="margin-bottom: 8px;">
        <thead>
            <tr>
                <th style="width: 6%;">No</th>
                <th style="width: 34%;">Bulan & Tahun</th>
                <th style="width: 20%;">Jumlah Surat Masuk</th>
                <th style="width: 20%;">Jumlah Surat Keluar</th>
                <th style="width: 20%;">Total Keseluruhan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $noR = 1;
            foreach ($rekap as $row): 
            ?>
                <tr>
                    <td class="text-center"><?= $noR++ ?></td>
                    <td><strong><?= esc($row['nama_bulan']) ?> <?= esc($tahun) ?></strong></td>
                    <td class="text-center"><?= number_format($row['surat_masuk'], 0, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row['surat_keluar'], 0, ',', '.') ?></td>
                    <td class="text-center fw-bold"><?= number_format($row['total'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-center">TOTAL KESELURUHAN PERIODE</th>
                <th class="text-center"><?= number_format($total_masuk, 0, ',', '.') ?></th>
                <th class="text-center"><?= number_format($total_keluar, 0, ',', '.') ?></th>
                <th class="text-center"><?= number_format($total_semua, 0, ',', '.') ?></th>
            </tr>
        </tfoot>
    </table>

    <div class="subbab-title">B. Kendala yang Dihadapi</div>
    <ul>
        <li>Masih terdapat surat masuk fisik dari instansi pengirim yang diterima mendadak mendekati waktu pelaksanaan kegiatan, sehingga memerlukan percepatan alur disposisi.</li>
        <li>Tingginya volume surat keluar pada waktu tertentu (seperti surat tugas dan undangan kegiatan) membutuhkan alur verifikasi konsep yang cepat dan teliti.</li>
        <li>Keterbatasan ruang penyimpanan fisik menuntut tata kelola e-arsip yang disiplin dan pencadangan data secara berkala.</li>
    </ul>

    <div class="subbab-title">C. Solusi dan Tindak Lanjut</div>
    <ul>
        <li>Menerapkan prinsip pencatatan seketika (<em>real-time registration</em>) sesaat setelah surat diterima di meja piket / staf tata usaha.</li>
        <li>Mengoptimalkan fitur pencatatan disposisi berbasis sistem agar proses tindak lanjut surat dapat dipantau status penyelesaiannya secara langsung.</li>
        <li>Melakukan penataan berkas fisik bulanan dan sinkronisasi arsip digital ke penyimpanan awan (<em>cloud backup</em>) secara terjadwal.</li>
    </ul>

    <!-- IV. LAPORAN KEUANGAN -->
    <div class="bab-title">IV. LAPORAN KEUANGAN</div>
    <p class="text-justify">
        Tidak ada pembebanan anggaran khusus yang digunakan secara terpisah dalam kegiatan rutin administrasi persuratan ini. 
        Kebutuhan operasional seperti kertas, pencetakan dokumen, dan perlengkapan kearsipan telah terakomodasi dalam belanja 
        operasional perkantoran madrasah / Bantuan Operasional Sekolah (BOS) sesuai dengan ketentuan yang berlaku.
    </p>

    <!-- V. PENUTUP -->
    <div class="bab-title">V. PENUTUP</div>
    <p class="text-justify">
        Demikian laporan rekapitulasi pencatatan surat masuk dan surat keluar <?= esc($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus') ?> 
        ini dibuat dengan sebenar-benarnya sebagai wujud transparansi, evaluasi berkala, dan pertanggungjawaban kedinasan. 
        Ucapan terima kasih disampaikan kepada Kepala Madrasah atas bimbingan dan arahan yang senantiasa diberikan demi terwujudnya 
        administrasi madrasah yang profesional, tertib, dan akuntabel.
    </p>

    <!-- LEMBAR PENGESAHAN DUA PIHAK: KIRI MENGETAHUI, KANAN PELAPOR -->
    <table class="ttd-table">
        <tr>
            <!-- Kolom Mengetahui (Kiri) -->
            <td style="width: 50%; text-align: center;">
                <p style="margin: 0 0 3px 0;">&nbsp;</p>
                <p style="margin: 0; font-weight: bold;">Mengetahui,</p>
                <p style="margin: 0; font-weight: bold;">Kepala <?= esc($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus') ?></p>
                
                <br><br><br><br>
                
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    <?= esc($appSettings['pejabat_kepsek_nama'] ?? 'DRA. H. SUKIRNO, M.PD.I') ?>
                </p>
                <p style="margin: 2px 0 0 0; font-size: 9pt;">
                    NIP. <?= esc($appSettings['pejabat_kepsek_nip'] ?? '-') ?>
                </p>
            </td>

            <!-- Kolom Pelapor (Kanan) -->
            <td style="width: 50%; text-align: center;">
                <?php 
                $kotaNama = !empty($appSettings['sekolah_kabupaten']) ? str_ireplace('kabupaten ', '', $appSettings['sekolah_kabupaten']) : 'Tanggamus';
                ?>
                <p style="margin: 0 0 3px 0;"><?= esc($kotaNama) ?>, <?= format_tanggal_indo($tanggal_cetak ?? date('Y-m-d')) ?></p>
                <p style="margin: 0; font-weight: bold;">Pelapor,</p>
                <p style="margin: 0; font-size: 9.5pt; color: #333;"><?= esc($pelapor_jabatan ?? 'Staf Tata Usaha / Admin Persuratan') ?></p>
                
                <br><br><br><br>
                
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">
                    <?= esc($pelapor_nama ?? 'Staf Tata Usaha') ?>
                </p>
                <p style="margin: 2px 0 0 0; font-size: 9pt;">
                    Petugas Administrasi Persuratan
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
