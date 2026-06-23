<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratResmiModel extends Model
{
    protected $table            = 'surat_resmi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'nomor_surat',
        'tanggal_surat',
        'lampiran',
        'perihal',
        'tujuan_nama',
        'tujuan_alamat',
        'salam_pembuka',
        'isi_surat',
        'salam_penutup',
        'pengirim_jabatan',
        'pengirim_nama',
        'pengirim_nip',
        'tembusan'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nomor_surat'      => 'required',
        'tanggal_surat'    => 'required|valid_date',
        'perihal'          => 'required',
        'tujuan_nama'      => 'required',
        'tujuan_alamat'    => 'required',
        'isi_surat'        => 'required',
        'pengirim_nama'    => 'required',
        'pengirim_jabatan' => 'required',
    ];
    
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
