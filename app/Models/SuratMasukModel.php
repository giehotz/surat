<?php

namespace App\Models;

use CodeIgniter\Model;

class SuratMasukModel extends Model
{
    protected $table            = 'surat_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'pengirim',
        'perihal',
        'tipe_penyimpanan',
        'lampiran',
        'file_path',
        'file_name',
        'file_size',
        'file_link',
        'keterangan',
        'status',
        'created_by',
        'updated_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Reassign nomor_agenda untuk semua surat masuk berdasarkan urutan tanggal_surat (kronologis).
     * Nomor agenda diurutkan per tahun surat: IN-YYYY-001, IN-YYYY-002, dst.
     * 
     * Menggunakan dua tahap untuk menghindari pelanggaran constraint UNIQUE:
     * 1. Set semua nomor_agenda ke nilai sementara
     * 2. Set ke nilai kronologis yang benar
     * 
     * Jika $year diberikan, hanya surat di tahun tersebut yang di-reassign.
     * Jika tidak, semua tahun yang ada di database akan di-reassign.
     */
    public function reassignNomorAgenda($year = null)
    {
        $db = \Config\Database::connect();

        // Tentukan tahun mana saja yang perlu di-reassign
        if ($year) {
            $years = [$year];
        } else {
            $result = $db->query("SELECT DISTINCT YEAR(tanggal_surat) as tahun FROM surat_masuk ORDER BY tahun")->getResultArray();
            $years = array_column($result, 'tahun');
        }

        foreach ($years as $yr) {
            // Ambil semua surat di tahun ini, urutkan berdasarkan tanggal_surat ASC, tanggal_terima ASC, id ASC
            $suratList = $db->query(
                "SELECT id, nomor_agenda FROM surat_masuk WHERE YEAR(tanggal_surat) = ? ORDER BY tanggal_surat ASC, tanggal_terima ASC, id ASC",
                [$yr]
            )->getResultArray();

            // Tahap 1: Set semua ke nilai sementara untuk menghindari constraint UNIQUE
            foreach ($suratList as $idx => $surat) {
                $tempAgenda = 'TEMP-REASSIGN-' . $surat['id'];
                $db->query("UPDATE surat_masuk SET nomor_agenda = ? WHERE id = ?", [$tempAgenda, $surat['id']]);
            }

            // Tahap 2: Set ke nilai kronologis yang benar
            $counter = 1;
            foreach ($suratList as $surat) {
                $newAgenda = 'IN-' . $yr . '-' . sprintf('%03d', $counter);
                $db->query("UPDATE surat_masuk SET nomor_agenda = ? WHERE id = ?", [$newAgenda, $surat['id']]);
                $counter++;
            }
        }
    }
}
