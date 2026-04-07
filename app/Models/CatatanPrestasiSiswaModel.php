<?php

namespace App\Models;

use CodeIgniter\Model;

class CatatanPrestasiSiswaModel extends Model
{
    protected $table            = 'catatan_prestasi_siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tanggal',
        'nama_siswa',
        'nisn',
        'jenis_prestasi',
        'tingkat',
        'peringkat',
        'nama_lomba',
        'keterangan',
        'tipe_penyimpanan',
        'file_sertifikat',
        'file_link',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
