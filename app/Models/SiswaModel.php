<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nis',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'telepon',
        'kelas_id',
        'status',
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
        'nis'           => 'required|min_length[3]|max_length[20]',
        'nama'          => 'required|min_length[2]|max_length[100]',
        'jenis_kelamin' => 'required|in_list[L,P]',
        'kelas_id'      => 'required|is_not_unique[kelas.id]',
        'status'        => 'permit_empty|in_list[aktif,nonaktif,lulus]'
    ];

    /**
     * Query data siswa dengan join nama kelas
     */
    public function getFilteredSiswa($keyword = '', $kelas_id = '', $status = '', $perPage = 20)
    {
        $builder = $this->select('siswa.*, kelas.nama_kelas, kelas.tingkat')
            ->join('kelas', 'kelas.id = siswa.kelas_id', 'left')
            ->orderBy('siswa.nama', 'ASC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('siswa.nama', $keyword)
                ->orLike('siswa.nis', $keyword)
                ->groupEnd();
        }

        if (!empty($kelas_id)) {
            $builder->where('siswa.kelas_id', $kelas_id);
        }

        if (!empty($status)) {
            $builder->where('siswa.status', $status);
        }

        return $builder->paginate($perPage, 'siswa');
    }
}
