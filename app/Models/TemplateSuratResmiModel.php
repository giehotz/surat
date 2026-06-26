<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateSuratResmiModel extends Model
{
    protected $table            = 'template_surat_resmi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'nama',
        'slug',
        'perihal',
        'lampiran',
        'tujuan_nama',
        'tujuan_alamat',
        'salam_pembuka',
        'isi_surat',
        'salam_penutup',
        'pengirim_jabatan',
        'pengirim_nama',
        'pengirim_nip',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nama'       => 'required|max_length[100]',
        'slug'       => 'required|max_length[100]|is_unique[template_surat_resmi.slug,id,{id}]',
        'perihal'    => 'required|max_length[255]',
        'isi_surat'  => 'required',
    ];

    protected $validationMessages = [
        'slug' => [
            'is_unique' => 'Slug template sudah digunakan.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
