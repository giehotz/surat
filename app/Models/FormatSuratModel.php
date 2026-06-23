<?php

namespace App\Models;

use CodeIgniter\Model;

class FormatSuratModel extends Model
{
    protected $table            = 'format_surat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama', 'template'];
    protected $useTimestamps    = false;
}
