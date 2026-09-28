<?php

namespace App\Models;

use CodeIgniter\Model;

class NotulenRapatModel extends Model
{
    protected $table            = 'notulen_rapat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    
    protected $allowedFields    = [
        'judul_rapat',
        'subjudul',
        'tanggal_kegiatan',
        'waktu',
        'tempat',
        'nama_notulis',
        'nip_notulis',
        'jumlah_lembar',
        'metode_notulen',
        'isi_notulen',
        'file_lampiran',
        'user_id',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'judul_rapat'      => 'required|min_length[3]|max_length[255]',
        'tanggal_kegiatan' => 'required|valid_date',
    ];

    protected $validationMessages = [
        'judul_rapat' => [
            'required'   => 'Judul agenda / rapat wajib diisi.',
            'min_length' => 'Judul rapat minimal 3 karakter.'
        ],
        'tanggal_kegiatan' => [
            'required'   => 'Tanggal kegiatan rapat wajib diisi.',
            'valid_date' => 'Format tanggal tidak valid.'
        ]
    ];
}
