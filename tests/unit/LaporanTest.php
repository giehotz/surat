<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Controllers\Laporan;

/**
 * @internal
 */
final class LaporanTest extends CIUnitTestCase
{
    public function testLaporanControllerInstantiation(): void
    {
        $controller = new Laporan();
        $this->assertInstanceOf(Laporan::class, $controller);
    }

    public function testGetBulanList(): void
    {
        $controller = new Laporan();
        $bulanList = $controller->getBulanList();

        $this->assertIsArray($bulanList);
        $this->assertCount(12, $bulanList);
        $this->assertSame('Januari', $bulanList[1]);
        $this->assertSame('Desember', $bulanList[12]);
    }

    public function testLaporanPdfViewRenders(): void
    {
        helper('tanggal');

        $settings = [
            'sekolah_nama'                => 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS',
            'sekolah_kementerian'         => 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            'sekolah_kantor_kementerian'  => 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS',
            'sekolah_alamat'              => 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus',
            'sekolah_kontak'              => 'minduatanggamus@gmail.com',
            'pejabat_kepsek_nama'         => 'DRA. H. SUKIRNO, M.PD.I',
            'pejabat_kepsek_nip'          => '196807121994031002',
            'tahun_anggaran'              => '2026',
        ];

        $kopSurat = [
            'kementerian'        => 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            'kantor_kementerian' => 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS',
            'nama_madrasah_kop'  => 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS',
            'alamat_kop'         => 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus',
            'kontak_kop'         => 'minduatanggamus@gmail.com',
            'logo_kop'           => '',
        ];

        $controller = new Laporan();
        $bulanList = $controller->getBulanList();

        $rekap = [];
        for ($m = 1; $m <= 3; $m++) {
            $rekap[] = [
                'bulan_angka'  => $m,
                'nama_bulan'   => $bulanList[$m],
                'surat_masuk'  => 5,
                'surat_keluar' => 3,
                'total'        => 8,
            ];
        }

        $data = [
            'appSettings'     => $settings,
            'kopSurat'        => $kopSurat,
            'logoDataUri'     => '',
            'start_month'     => 1,
            'end_month'       => 3,
            'tahun'           => 2026,
            'filter_text'     => 'Periode: Bulan Januari s.d. Maret 2026',
            'rekap'           => $rekap,
            'total_masuk'     => 15,
            'total_keluar'    => 9,
            'total_semua'     => 24,
            'surat_masuk'     => [
                [
                    'nomor_agenda'   => 'IN-2026-001',
                    'nomor_surat'    => '123/TEST/2026',
                    'tanggal_surat'  => '2026-01-10',
                    'tanggal_terima' => '2026-01-12',
                    'pengirim'       => 'Pengirim Uji Coba',
                    'perihal'        => 'Perihal Uji Coba Laporan',
                ]
            ],
            'surat_keluar'    => [
                [
                    'nomor_agenda'   => 'OUT-2026-001',
                    'nomor_surat'    => 'B-001/TEST/2026',
                    'tanggal_surat'  => '2026-01-15',
                    'tanggal_kirim'  => '2026-01-15',
                    'tujuan'         => 'Tujuan Uji Coba',
                    'perihal'        => 'Perihal Keluar Uji Coba',
                ]
            ],
            'pelapor_nama'    => 'Staf Tata Usaha',
            'pelapor_jabatan' => 'Admin Persuratan',
            'tanggal_cetak'   => '2026-03-31',
        ];

        $html = view('laporan/print_pdf', $data);

        $this->assertNotEmpty($html);
        $this->assertStringContainsString('LAPORAN REKAPITULASI SURAT MASUK DAN SURAT KELUAR', $html);
        $this->assertStringContainsString('I. PENDAHULUAN', $html);
        $this->assertStringContainsString('II. PELAKSANAAN ADMINISTRASI PERSURATAN', $html);
        $this->assertStringContainsString('III. HASIL YANG DICAPAI & EVALUASI', $html);
        $this->assertStringContainsString('IV. LAPORAN KEUANGAN', $html);
        $this->assertStringContainsString('V. PENUTUP', $html);
        $this->assertStringContainsString('Pelapor,', $html);
        $this->assertStringContainsString('Mengetahui,', $html);
        $this->assertStringContainsString('31 Maret 2026', $html);
        $this->assertStringContainsString('IN-2026-001', $html);
        $this->assertStringContainsString('OUT-2026-001', $html);

        // Uji bahwa Dompdf dapat merender HTML ini tanpa error
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $this->assertNotEmpty($pdfContent);
        // Validasi header format PDF resmi (%PDF-)
        $this->assertStringStartsWith('%PDF-', $pdfContent);
    }
}
