<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_kelas',
        'tingkat',
        'jurusan',
        'deskripsi',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_kelas' => 'required|min_length[1]|max_length[50]',
        'tingkat'    => 'required|max_length[10]',
        'jurusan'    => 'permit_empty|max_length[50]',
        'deskripsi'  => 'permit_empty'
    ];

    /**
     * Mengambil daftar kelas beserta jumlah siswa aktif
     */
    public function getKelasWithSiswaCount()
    {
        return $this->select('kelas.*, COUNT(siswa.id) as jumlah_siswa')
            ->join('siswa', 'siswa.kelas_id = kelas.id AND siswa.deleted_at IS NULL AND siswa.status = "aktif"', 'left')
            ->groupBy('kelas.id')
            ->orderBy('kelas.tingkat', 'ASC')
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->findAll();
    }
}
