<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DataMadrasah extends BaseController
{
    public function index()
    {
        // Define data defaults in case models are not ready yet
        // In a real scenario, you would fetch these from their respective models
        
        $data = [
            'title'           => 'Data Madrasah',
            'active_tab'      => session()->getFlashdata('active_tab') ?? 'kelas',
            'kelas'           => [],
            'data_guru'       => (new \App\Models\DataGuruModel())
                ->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, tanggal_lahir, email, no_handphone')
                ->orderBy('nama_pegawai', 'ASC')
                ->findAll(),
            'siswa'           => [],
            'kelasList'       => [],
            'keyword'         => '',
            'selected_kelas'  => '',
            'selected_status'     => '',
            'pager'               => \Config\Services::pager(),
            'hide_default_header' => true
        ];

        return view('data_madrasah/index', $data);
    }
}
