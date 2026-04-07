<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAnggaranModel extends Model
{
    protected $table            = 'tahun_anggaran';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tahun', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all years alphabetically/numerically
     */
    public function getList()
    {
        return $this->orderBy('tahun', 'DESC')->findAll();
    }
}
