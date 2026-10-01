<?php

namespace App\Models;

use CodeIgniter\Model;

class KopSuratModel extends Model
{
    protected $table            = 'kop_surat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kementerian',
        'kantor_kementerian',
        'nama_madrasah_kop',
        'alamat_kop',
        'kontak_kop',
        'logo_kop',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil data kop surat yang sedang aktif
     */
    public function getActiveKop(): ?array
    {
        $active = $this->where('is_active', 1)->orderBy('id', 'DESC')->first();
        if ($active) {
            return $active;
        }

        // Fallback: jika belum ada yang ditandai aktif, ambil baris pertama
        $first = $this->orderBy('id', 'ASC')->first();
        if ($first) {
            return $first;
        }

        return null;
    }

    /**
     * Simpan atau perbarui kop surat aktif
     */
    public function saveActiveKop(array $data): bool
    {
        $active = $this->where('is_active', 1)->first();
        if ($active) {
            return $this->update($active['id'], $data);
        }

        $data['is_active'] = 1;
        return (bool)$this->insert($data);
    }
}
