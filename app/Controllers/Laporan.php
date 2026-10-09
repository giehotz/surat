<?php

namespace App\Controllers;

use App\Models\SuratMasukModel;
use App\Models\SuratKeluarModel;
use App\Models\PengaturanModel;
use App\Models\TahunAnggaranModel;
use App\Models\KopSuratModel;
use App\Services\ExportService;

class Laporan extends BaseController
{
    protected SuratMasukModel $suratMasukModel;
    protected SuratKeluarModel $suratKeluarModel;
    protected PengaturanModel $pengaturanModel;
    protected TahunAnggaranModel $tahunAnggaranModel;
    protected ExportService $exportService;

    public function __construct()
    {
        $this->suratMasukModel    = new SuratMasukModel();
        $this->suratKeluarModel   = new SuratKeluarModel();
        $this->pengaturanModel    = new PengaturanModel();
        $this->tahunAnggaranModel = new TahunAnggaranModel();
        $this->exportService      = new ExportService();
    }

    /**
     * Tampilan utama menu Laporan Surat Masuk & Keluar
     */
    public function index()
    {
        $settings = $this->pengaturanModel->getSettings();
        $defaultTahun = (int) ($settings['tahun_anggaran'] ?? date('Y'));

        $startMonth = (int) ($this->request->getGet('start_month') ?? 1);
        $endMonth   = (int) ($this->request->getGet('end_month') ?? date('n'));
        $tahun      = (int) ($this->request->getGet('tahun') ?? $defaultTahun);

        // Validasi batasan bulan 1 s/d 12
        $startMonth = max(1, min(12, $startMonth));
        $endMonth   = max(1, min(12, $endMonth));
        if ($startMonth > $endMonth) {
            $tmp = $startMonth;
            $startMonth = $endMonth;
            $endMonth = $tmp;
        }

        $bulanList = $this->getBulanList();
        $tahunList = $this->tahunAnggaranModel->getList();

        // Pastikan tahun yang dipilih ada dalam dropdown
        $tahunTersedia = array_column($tahunList, 'tahun');
        if (!in_array((string)$tahun, $tahunTersedia)) {
            $tahunList[] = ['tahun' => (string)$tahun];
            usort($tahunList, fn($a, $b) => $b['tahun'] <=> $a['tahun']);
        }

        // Ambil ringkasan rekapitulasi bulanan
        $rekapBulanan = $this->getRekapBulanan($tahun, $startMonth, $endMonth);

        $totalMasuk  = array_sum(array_column($rekapBulanan, 'surat_masuk'));
        $totalKeluar = array_sum(array_column($rekapBulanan, 'surat_keluar'));
        $totalSemua  = $totalMasuk + $totalKeluar;

        // Ambil rincian surat masuk & keluar untuk preview tabel tab
        $db = \Config\Database::connect();
        $suratMasukList = $db->query("
            SELECT nomor_agenda, nomor_surat, tanggal_surat, tanggal_terima, pengirim, perihal
            FROM surat_masuk
            WHERE YEAR(COALESCE(tanggal_terima, tanggal_surat)) = ?
              AND MONTH(COALESCE(tanggal_terima, tanggal_surat)) BETWEEN ? AND ?
            ORDER BY COALESCE(tanggal_terima, tanggal_surat) ASC, nomor_agenda ASC
            LIMIT 50
        ", [$tahun, $startMonth, $endMonth])->getResultArray();

        $suratKeluarList = $db->query("
            SELECT nomor_agenda, nomor_surat, tanggal_surat, tanggal_kirim, tujuan, perihal
            FROM surat_keluar
            WHERE YEAR(tanggal_surat) = ?
              AND MONTH(tanggal_surat) BETWEEN ? AND ?
            ORDER BY tanggal_surat ASC, nomor_agenda ASC
            LIMIT 50
        ", [$tahun, $startMonth, $endMonth])->getResultArray();

        $tanggalCetak = $this->request->getGet('tanggal_cetak');
        if (empty($tanggalCetak) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalCetak)) {
            $tanggalCetak = date('Y-m-d');
        }

        $data = [
            'title'         => 'Laporan Surat Masuk & Keluar',
            'start_month'   => $startMonth,
            'end_month'     => $endMonth,
            'tahun'         => $tahun,
            'tanggal_cetak' => $tanggalCetak,
            'bulan_list'    => $bulanList,
            'tahun_list'    => $tahunList,
            'rekap'         => $rekapBulanan,
            'total_masuk'   => $totalMasuk,
            'total_keluar'  => $totalKeluar,
            'total_semua'   => $totalSemua,
            'surat_masuk'   => $suratMasukList,
            'surat_keluar'  => $suratKeluarList,
            'appSettings'   => $settings,
        ];

        return view('laporan/index', $data);
    }

    /**
     * Ekspor Dokumen Resmi Laporan Surat Masuk & Keluar ke format PDF
     */
    public function exportPdf()
    {
        $settings = $this->pengaturanModel->getSettings();
        $defaultTahun = (int) ($settings['tahun_anggaran'] ?? date('Y'));

        $startMonth = (int) ($this->request->getGet('start_month') ?? 1);
        $endMonth   = (int) ($this->request->getGet('end_month') ?? date('n'));
        $tahun      = (int) ($this->request->getGet('tahun') ?? $defaultTahun);

        $startMonth = max(1, min(12, $startMonth));
        $endMonth   = max(1, min(12, $endMonth));
        if ($startMonth > $endMonth) {
            $tmp = $startMonth;
            $startMonth = $endMonth;
            $endMonth = $tmp;
        }

        $bulanList    = $this->getBulanList();
        $rekapBulanan = $this->getRekapBulanan($tahun, $startMonth, $endMonth);

        $totalMasuk  = array_sum(array_column($rekapBulanan, 'surat_masuk'));
        $totalKeluar = array_sum(array_column($rekapBulanan, 'surat_keluar'));
        $totalSemua  = $totalMasuk + $totalKeluar;

        // Ambil data detail surat masuk lengkap untuk lampiran PDF
        $db = \Config\Database::connect();
        $suratMasukList = $db->query("
            SELECT sm.id, sm.nomor_agenda, sm.nomor_surat, sm.tanggal_surat, sm.tanggal_terima, sm.pengirim, sm.perihal, sm.keterangan, sm.status,
                   (SELECT d.status FROM disposisi d WHERE d.surat_masuk_id = sm.id ORDER BY d.id DESC LIMIT 1) as disposisi_status
            FROM surat_masuk sm
            WHERE YEAR(COALESCE(sm.tanggal_terima, sm.tanggal_surat)) = ?
              AND MONTH(COALESCE(sm.tanggal_terima, sm.tanggal_surat)) BETWEEN ? AND ?
            ORDER BY COALESCE(sm.tanggal_terima, sm.tanggal_surat) ASC, sm.nomor_agenda ASC
        ", [$tahun, $startMonth, $endMonth])->getResultArray();

        // Ambil data detail surat keluar lengkap untuk lampiran PDF
        $suratKeluarList = $db->query("
            SELECT sk.id, sk.nomor_agenda, sk.nomor_surat, sk.tanggal_surat, sk.tanggal_kirim, sk.tujuan, sk.perihal, sk.keterangan, sk.status,
                   u.nama_lengkap as penandatangan
            FROM surat_keluar sk
            LEFT JOIN users u ON u.id = sk.approved_by
            WHERE YEAR(sk.tanggal_surat) = ?
              AND MONTH(sk.tanggal_surat) BETWEEN ? AND ?
            ORDER BY sk.tanggal_surat ASC, sk.nomor_agenda ASC
        ", [$tahun, $startMonth, $endMonth])->getResultArray();

        if ($startMonth === $endMonth) {
            $filterText = "Bulan " . $bulanList[$startMonth] . " " . $tahun;
            $fileLabel  = $bulanList[$startMonth] . "_" . $tahun;
        } else {
            $filterText = "Bulan " . $bulanList[$startMonth] . " s.d. " . $bulanList[$endMonth] . " " . $tahun;
            $fileLabel  = $bulanList[$startMonth] . "_" . $bulanList[$endMonth] . "_" . $tahun;
        }

        // Kop surat aktif dari KopSuratModel jika ada
        $kopSuratModel = new KopSuratModel();
        $kopSurat = $kopSuratModel->getActiveKop();

        // Siapkan Logo dalam Base64
        $logoDataUri = '';
        $logoNama = !empty($kopSurat['logo_kop']) ? $kopSurat['logo_kop'] : ($settings['sekolah_logo'] ?? '');
        if (!empty($logoNama)) {
            $logoPath = FCPATH . 'uploads/logo/' . $logoNama;
            if (file_exists($logoPath)) {
                $logoData = base64_encode(file_get_contents($logoPath));
                $logoMime = mime_content_type($logoPath);
                $logoDataUri = 'data:' . $logoMime . ';base64,' . $logoData;
            }
        }

        $tanggalCetak = $this->request->getGet('tanggal_cetak');
        if (empty($tanggalCetak) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalCetak)) {
            $tanggalCetak = date('Y-m-d');
        }

        $pelaporNama    = session('nama_lengkap') ?? 'Staf Tata Usaha / Admin Persuratan';
        $pelaporJabatan = session('jabatan') ?? 'Staf Tata Usaha';

        $data = [
            'appSettings'     => $settings,
            'kopSurat'        => $kopSurat,
            'logoDataUri'     => $logoDataUri,
            'start_month'     => $startMonth,
            'end_month'       => $endMonth,
            'tahun'           => $tahun,
            'tanggal_cetak'   => $tanggalCetak,
            'filter_text'     => $filterText,
            'rekap'           => $rekapBulanan,
            'total_masuk'     => $totalMasuk,
            'total_keluar'    => $totalKeluar,
            'total_semua'     => $totalSemua,
            'surat_masuk'     => $suratMasukList,
            'surat_keluar'    => $suratKeluarList,
            'pelapor_nama'    => $pelaporNama,
            'pelapor_jabatan' => $pelaporJabatan,
        ];

        $html = view('laporan/print_pdf', $data);
        $filename = 'Laporan_Surat_Masuk_Keluar_' . $fileLabel . '_' . date('Ymd_His');

        $this->exportService->exportPdf($html, $filename, 'A4', 'portrait');
    }

    /**
     * Kalkulasi rekapitulasi jumlah surat masuk & keluar per bulan
     */
    protected function getRekapBulanan(int $tahun, int $startMonth, int $endMonth): array
    {
        $db = \Config\Database::connect();
        $bulanList = $this->getBulanList();

        // Hitung surat masuk per bulan
        $queryMasuk = $db->query("
            SELECT MONTH(COALESCE(tanggal_terima, tanggal_surat)) as bulan, COUNT(*) as total
            FROM surat_masuk
            WHERE YEAR(COALESCE(tanggal_terima, tanggal_surat)) = ?
              AND MONTH(COALESCE(tanggal_terima, tanggal_surat)) BETWEEN ? AND ?
            GROUP BY MONTH(COALESCE(tanggal_terima, tanggal_surat))
        ", [$tahun, $startMonth, $endMonth])->getResultArray();

        $mapMasuk = [];
        foreach ($queryMasuk as $row) {
            $mapMasuk[(int)$row['bulan']] = (int)$row['total'];
        }

        // Hitung surat keluar per bulan
        $queryKeluar = $db->query("
            SELECT MONTH(tanggal_surat) as bulan, COUNT(*) as total
            FROM surat_keluar
            WHERE YEAR(tanggal_surat) = ?
              AND MONTH(tanggal_surat) BETWEEN ? AND ?
            GROUP BY MONTH(tanggal_surat)
        ", [$tahun, $startMonth, $endMonth])->getResultArray();

        $mapKeluar = [];
        foreach ($queryKeluar as $row) {
            $mapKeluar[(int)$row['bulan']] = (int)$row['total'];
        }

        $result = [];
        for ($m = $startMonth; $m <= $endMonth; $m++) {
            $masukCount  = $mapMasuk[$m] ?? 0;
            $keluarCount = $mapKeluar[$m] ?? 0;
            $totalBulan  = $masukCount + $keluarCount;

            $result[] = [
                'bulan_angka'  => $m,
                'nama_bulan'   => $bulanList[$m],
                'surat_masuk'  => $masukCount,
                'surat_keluar' => $keluarCount,
                'total'        => $totalBulan,
            ];
        }

        return $result;
    }

    /**
     * Daftar nama bulan Indonesia
     */
    public function getBulanList(): array
    {
        return [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
    }
}
