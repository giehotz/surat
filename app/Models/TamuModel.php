<?php

namespace App\Models;

use CodeIgniter\Model;

class TamuModel extends Model
{
    protected $table            = 'tamu';
    protected $primaryKey       = 'id_tamu';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'jenis_tamu',
        'sub_jenis_tamu',
        'nama_lengkap',
        'alamat_instansi',
        'nip',
        'jabatan',
        'no_hp',
        'consent_wa',
    ];

    protected $validationRules = [
        'nama_lengkap'    => 'required|min_length[3]',
        'no_hp'           => 'required',
        'nip'             => 'permit_empty',
        'jabatan'         => 'permit_empty',
        'alamat_instansi' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation     = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Mencari atau Membuat tamu berdasarkan parameter tertentu (biasanya No HP / NIP)
     * untuk mencegah duplikasi entitas tamu.
     */
    public function findOrCreateTamu(array $data)
    {
        // Kondisi jika no. HP ada, cari berdasar no. HP
        if (!empty($data['no_hp'])) {
            $existing = $this->where('no_hp', $data['no_hp'])->first();
            if ($existing) {
                // Update info terbaru
                $this->update($existing['id_tamu'], $data);
                return $existing['id_tamu'];
            }
        } 
        // Jika instansi dan ada NIP, cari berdasar NIP
        elseif (!empty($data['nip'])) {
            $existing = $this->where('nip', $data['nip'])->first();
            if ($existing) {
                $this->update($existing['id_tamu'], $data);
                return $existing['id_tamu'];
            }
        }
        
        // Coba cari berdasar nama lengkap yang persis
        $existing = $this->where('nama_lengkap', $data['nama_lengkap'])->first();
        if ($existing && empty($data['no_hp']) && empty($data['nip'])) {
             // Jika punya nama sama tapi tanpa no hp atau nip yang unik, asumsikan orang yg sama
             $this->update($existing['id_tamu'], $data);
             return $existing['id_tamu'];
        }

        // Jika tidak ketemu satupun, buat tamu baru
        $this->insert($data);
        return $this->getInsertID();
    }
}
