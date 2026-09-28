<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\DataGuruModel;
use App\Models\SiswaModel;

class DataMadrasah extends BaseController
{
    protected $kelasModel;
    protected $dataGuruModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->kelasModel    = new KelasModel();
        $this->dataGuruModel = new DataGuruModel();
        $this->siswaModel    = new SiswaModel();
    }

    public function index()
    {
        helper(['tanggal', 'duk']);

        // Tangkap parameter tab aktif (prioritas: GET query param -> flashdata -> default 'kelas')
        $requestedTab = $this->request->getGet('tab') ?? session()->getFlashdata('active_tab') ?? 'kelas';
        $validTabs    = ['kelas', 'data_guru', 'siswa', 'duk'];
        $activeTab    = in_array($requestedTab, $validTabs) ? $requestedTab : 'kelas';

        // 1. Data Kelas (dengan kalkulasi jumlah siswa per rombel)
        $kelas = $this->kelasModel->getKelasWithSiswaCount();

        // 2. Data Guru & Tenaga Kependidikan
        $dataGuru = $this->dataGuruModel
            ->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_lahir, tanggal_lahir, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, kenaikan_pangkat, email, no_handphone')
            ->orderBy('nama_pegawai', 'ASC')
            ->findAll();

        // 3. Filter dan Data Siswa
        $keyword        = trim((string)$this->request->getGet('keyword'));
        $selectedKelas  = $this->request->getGet('kelas_id') ?? '';
        $selectedStatus = $this->request->getGet('status') ?? '';

        $siswa = $this->siswaModel->getFilteredSiswa($keyword, $selectedKelas, $selectedStatus, 20);
        $pager = $this->siswaModel->pager;

        // Daftar kelas untuk dropdown filter siswa
        $kelasList = $this->kelasModel
            ->orderBy('tingkat', 'ASC')
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();

        // 4. Perhitungan DUK (Daftar Urut Kepangkatan) Realtime
        $dukScope = $this->request->getGet('duk_scope') ?? 'pns';
        if (!in_array($dukScope, ['pns', 'asn', 'all'])) {
            $dukScope = 'pns';
        }
        $duk = urutkan_duk($dataGuru, $dukScope);

        // 5. Institutional Telemetry / Cockpit Stats
        $totalKelas   = count($kelas);
        $totalGuru    = count($dataGuru);
        $totalPns     = 0;
        $totalPppk    = 0;
        $totalHonorer = 0;

        foreach ($dataGuru as $g) {
            $status = strtolower($g['status_kepegawaian'] ?? '');
            if ($status === 'pns') {
                $totalPns++;
            } elseif ($status === 'pppk') {
                $totalPppk++;
            } else {
                $totalHonorer++;
            }
        }

        $totalSiswaAktif = $this->siswaModel->where('status', 'aktif')->countAllResults();
        $totalSiswaSemua = $this->siswaModel->countAllResults();

        $data = [
            'title'               => 'Data Madrasah',
            'container_class'     => 'container-fluid px-3 px-lg-4',
            'active_tab'          => $activeTab,
            'kelas'               => $kelas,
            'data_guru'           => $dataGuru,
            'siswa'               => $siswa,
            'kelasList'           => $kelasList,
            'keyword'             => $keyword,
            'selected_kelas'      => $selectedKelas,
            'selected_status'     => $selectedStatus,
            'pager'               => $pager,
            'duk'                 => $duk,
            'selected_duk_scope'  => $dukScope,
            'stats'               => [
                'total_kelas'       => $totalKelas,
                'total_guru'        => $totalGuru,
                'total_pns'         => $totalPns,
                'total_pppk'        => $totalPppk,
                'total_honorer'     => $totalHonorer,
                'total_siswa_aktif' => $totalSiswaAktif,
                'total_siswa_semua' => $totalSiswaSemua,
            ],
            'hide_default_header' => true
        ];

        return view('data_madrasah/index', $data);
    }

    /**
     * Cetak Resmi DUK Standar Madrasah (Landscape Print Ready)
     */
    public function cetakDuk()
    {
        helper(['tanggal', 'duk']);

        $dukScope = $this->request->getGet('duk_scope') ?? 'pns';
        if (!in_array($dukScope, ['pns', 'asn', 'all'])) {
            $dukScope = 'pns';
        }

        $dataGuru = $this->dataGuruModel
            ->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_lahir, tanggal_lahir, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, kenaikan_pangkat, email, no_handphone')
            ->findAll();

        $duk = urutkan_duk($dataGuru, $dukScope);

        $scopeTitles = [
            'pns' => 'PEGAWAI NEGERI SIPIL (PNS)',
            'asn' => 'APARATUR SIPIL NEGARA (ASN - PNS & PPPK)',
            'all' => 'SELURUH PENDIDIK & TENAGA KEPENDIDIKAN (PNS, PPPK & HONORER)'
        ];

        $data = [
            'title'              => 'Daftar Urut Kepangkatan (DUK)',
            'duk'                => $duk,
            'selected_duk_scope' => $dukScope,
            'sub_title'          => $scopeTitles[$dukScope] ?? 'PEGAWAI NEGERI SIPIL (PNS)',
            'tahun'              => date('Y'),
            'tanggal_cetak'      => date('Y-m-d')
        ];

        return view('data_madrasah/duk/cetak', $data);
    }

    /**
     * Ekspor Data DUK ke Excel (.xlsx) dengan Formatting Rapi
     */
    public function exportDukExcel()
    {
        helper(['tanggal', 'duk']);

        $dukScope = $this->request->getGet('duk_scope') ?? 'pns';
        if (!in_array($dukScope, ['pns', 'asn', 'all'])) {
            $dukScope = 'pns';
        }

        $dataGuru = $this->dataGuruModel
            ->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_lahir, tanggal_lahir, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, kenaikan_pangkat, email, no_handphone')
            ->findAll();

        $duk = urutkan_duk($dataGuru, $dukScope);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('DUK ' . strtoupper($dukScope));

        // Judul Laporan
        $sekolahNama = strtoupper($this->appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS');
        $sheet->setCellValue('A1', 'DAFTAR URUT KEPANGKATAN (DUK) PEGAWAI');
        $sheet->setCellValue('A2', $sekolahNama);
        $sheet->setCellValue('A3', 'TAHUN ' . date('Y') . ' (CAKUPAN: ' . strtoupper($dukScope) . ')');

        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');
        $sheet->mergeCells('A3:K3');
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(14);
        $sheet->getStyle('A2:A3')->getFont()->setSize(11);
        $sheet->getStyle('A1:A3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header Kolom
        $headers = [
            'A5' => 'NO',
            'B5' => 'NAMA PEGAWAI',
            'C5' => 'NIP / NUPTK',
            'D5' => 'GOL.',
            'E5' => 'TMT GOL.',
            'F5' => 'JABATAN',
            'G5' => 'MASA KERJA',
            'H5' => 'PENDIDIKAN',
            'I5' => 'USIA',
            'J5' => 'TMT AWAL',
            'K5' => 'STATUS'
        ];

        foreach ($headers as $cell => $title) {
            $sheet->setCellValue($cell, $title);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '206BC4'],
            ],
        ];
        $sheet->getStyle('A5:K5')->applyFromArray($headerStyle);
        $sheet->getRowDimension(5)->setRowHeight(26);

        // Data Rows
        $row = 6;
        foreach ($duk as $item) {
            $sheet->setCellValue('A' . $row, $item['no_urut_duk']);
            $sheet->setCellValue('B' . $row, $item['nama_pegawai']);

            $nipVal = !empty($item['nip']) ? $item['nip'] : ($item['peg_id_nuptk'] ?? '-');
            $sheet->setCellValueExplicit('C' . $row, $nipVal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            $sheet->setCellValue('D' . $row, $item['duk_label_golongan']);
            
            $tmtPangkatStr = ($item['duk_tmt_pangkat'] !== '2099-12-31') ? $item['duk_tmt_pangkat'] : '-';
            $sheet->setCellValue('E' . $row, $tmtPangkatStr);

            $sheet->setCellValue('F' . $row, $item['jabatan_mengajar'] ?? '-');
            $sheet->setCellValue('G' . $row, $item['duk_masa_kerja_format']);
            
            $pendidikanStr = ($item['pendidikan_terakhir'] ?? '-') . (!empty($item['perguruan_tinggi']) ? ' (' . $item['perguruan_tinggi'] . ')' : '');
            $sheet->setCellValue('H' . $row, $pendidikanStr);

            $sheet->setCellValue('I' . $row, $item['duk_usia_format']);

            $tmtAwalStr = ($item['duk_tmt_pengangkatan'] !== '2099-12-31') ? $item['duk_tmt_pengangkatan'] : '-';
            $sheet->setCellValue('J' . $row, $tmtAwalStr);

            $sheet->setCellValue('K' . $row, strtoupper($item['status_kepegawaian'] ?? '-'));

            // Alignment
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C' . $row . ':E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I' . $row . ':K' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

            $row++;
        }

        // Border Table
        $lastRow = max(6, $row - 1);
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
        ];
        $sheet->getStyle('A5:K' . $lastRow)->applyFromArray($borderStyle);

        // Auto width
        foreach (range('A', 'K') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $cleanFilename = 'DUK_' . strtoupper($dukScope) . '_' . date('Ymd_His') . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $cleanFilename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
}
