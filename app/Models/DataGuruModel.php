<?php

namespace App\Models;

use CodeIgniter\Model;

class DataGuruModel extends Model
{
    protected $table            = 'data_guru';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_pegawai',
        'nip',
        'peg_id_nuptk',
        'tempat_lahir',
        'tanggal_lahir',
        'tempat_tanggal_lahir',
        'jabatan_mengajar',
        'pangkat_golongan',
        'pendidikan_terakhir',
        'perguruan_tinggi',
        'mulai_tugas',
        'tmt_cpns_honorer',
        'masa_kerja_min',
        'masa_kerja_pns',
        'kenaikan_pangkat',
        'status_kepegawaian',
        'email',
        'no_handphone',
        'is_active',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_pegawai'        => 'required|min_length[3]|max_length[100]',
        'nip'                 => 'permit_empty|numeric|max_length[30]',
        'peg_id_nuptk'        => 'permit_empty|numeric|max_length[30]',
        'tempat_lahir'        => 'permit_empty|max_length[50]',
        'tanggal_lahir'       => 'permit_empty|valid_date',
        'jabatan_mengajar'    => 'permit_empty|max_length[100]',
        'pangkat_golongan'    => 'permit_empty|max_length[50]',
        'pendidikan_terakhir' => 'permit_empty|max_length[50]',
        'perguruan_tinggi'    => 'permit_empty|max_length[100]',
        'mulai_tugas'         => 'permit_empty|valid_date',
        'tmt_cpns_honorer'    => 'permit_empty|valid_date',
        'status_kepegawaian'  => 'permit_empty|max_length[50]',
        'email'               => 'permit_empty|valid_email|max_length[100]',
        'no_handphone'        => 'permit_empty|max_length[20]',
    ];

    protected $validationMessages = [
        'nama_pegawai' => [
            'required'   => 'Nama pegawai wajib diisi.',
            'min_length' => 'Nama pegawai minimal 3 karakter.',
            'max_length' => 'Nama pegawai maksimal 100 karakter.',
        ],
        'email' => [
            'valid_email' => 'Format alamat email tidak valid.',
        ],
        'tanggal_lahir' => [
            'valid_date' => 'Format tanggal lahir tidak valid.',
        ],
        'mulai_tugas' => [
            'valid_date' => 'Format tanggal mulai tugas tidak valid.',
        ],
        'tmt_cpns_honorer' => [
            'valid_date' => 'Format TMT CPNS/Honorer tidak valid.',
        ],
    ];
}

