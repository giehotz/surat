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
}
