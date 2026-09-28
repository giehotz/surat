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
        // Tangkap parameter tab aktif (prioritas: GET query param -> flashdata -> default 'kelas')
        $requestedTab = $this->request->getGet('tab') ?? session()->getFlashdata('active_tab') ?? 'kelas';
        $validTabs    = ['kelas', 'data_guru', 'siswa'];
        $activeTab    = in_array($requestedTab, $validTabs) ? $requestedTab : 'kelas';

        // 1. Data Kelas (dengan kalkulasi jumlah siswa per rombel)
        $kelas = $this->kelasModel->getKelasWithSiswaCount();

        // 2. Data Guru & Tenaga Kependidikan
        $dataGuru = $this->dataGuruModel
            ->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_lahir, tanggal_lahir, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, email, no_handphone')
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

        // 4. Institutional Telemetry / Cockpit Stats
        $totalKelas   = count($kelas);
        $totalGuru    = count($dataGuru);
        $totalPns     = 0;
        $totalHonorer = 0;

        foreach ($dataGuru as $g) {
            $status = strtolower($g['status_kepegawaian'] ?? '');
            if ($status === 'pns') {
                $totalPns++;
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
            'stats'               => [
                'total_kelas'       => $totalKelas,
                'total_guru'        => $totalGuru,
                'total_pns'         => $totalPns,
                'total_honorer'     => $totalHonorer,
                'total_siswa_aktif' => $totalSiswaAktif,
                'total_siswa_semua' => $totalSiswaSemua,
            ],
            'hide_default_header' => true
        ];

        return view('data_madrasah/index', $data);
    }
}
