<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateDokumenModel extends Model
{
    protected $table            = 'template_dokumen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'kode',
        'nama',
        'kategori',
        'file_path',
        'deskripsi',
        'is_has_repeater',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'kode'      => 'required|max_length[50]|is_unique[template_dokumen.kode,id,{id}]',
        'nama'      => 'required|max_length[150]',
        'file_path' => 'required|max_length[255]',
    ];

    public function getActiveTemplates()
    {
        return $this->where('is_active', 1)->orderBy('nama', 'ASC')->findAll();
    }

    public function getByKode(string $kode)
    {
        return $this->where('kode', $kode)->where('is_active', 1)->first();
    }
}
