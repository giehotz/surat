<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratKeluarModel extends Model
{
    protected $table            = 'surat_keluar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_kirim',
        'tujuan',
        'perihal',
        'tipe_penyimpanan',
        'lampiran',
        'file_path',
        'file_name',
        'file_size',
        'file_konsep_path',
        'file_link',
        'keterangan',
        'status',
        'approved_by',
        'approved_at',
        'created_by',
        'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    /**
     * Mendapatkan aturan validasi berdasarkan pengaturan wajib field
     */
    public function getValidationRulesFromPengaturan()
    {
        $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();
        $requiredFields = $wajibFieldModel->getRequiredFields('surat_keluar');
        
        $validationRules = [
            'tanggal_surat' => 'required|valid_date',
            'tujuan' => 'required|string|max_length[255]',
            'perihal' => 'required|string|max_length[500]',
            'tipe_penyimpanan' => 'required|in_list[lokal,cloud]'
        ];
        
        // Tambahkan field yang diatur sebagai wajib dari pengaturan
        foreach ($requiredFields as $field) {
            switch ($field) {
                case 'nomor_surat':
                    $validationRules['nomor_surat'] = 'required|string|max_length[100]';
                    break;
                case 'tanggal_kirim':
                    $validationRules['tanggal_kirim'] = 'required|valid_date';
                    break;
                case 'tujuan':
                    $validationRules['tujuan'] = 'required|string|max_length[255]';
                    break;
                case 'perihal':
                    $validationRules['perihal'] = 'required|string|max_length[500]';
                    break;
                case 'file_konsep':
                    // Field ini akan divalidasi di controller karena tergantung tipe penyimpanan
                    break;
            }
        }
        
        return $validationRules;
    }
}