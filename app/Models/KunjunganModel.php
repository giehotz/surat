<?php

namespace App\Models;

use CodeIgniter\Model;

class KunjunganModel extends Model
{
    protected $table            = 'kunjungan';
    protected $primaryKey       = 'id_kunjungan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_tamu',
        'tanggal_waktu',
        'tujuan_kunjungan',
        'id_pegawai_dituju',
        'id_siswa_dituju',
        'pesan_kesan',
        'dokumen_pendukung',
        'foto_wajah',
        'tanda_tangan',
        'status_kunjungan',
        'tindak_lanjut',
        'petugas_input'
    ];

    // Dates
    protected $useTimestamps = false; // Tanggal waktu dikelola kustom via 'tanggal_waktu'

    /**
     * Dapatkan daftar kunjungan tergabung dengan data Tamu dan Pegawai(opsional)
     * untuk dirender di datatable admin.
     */
    public function getKunjunganWithDetails($status = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('kunjungan.*, tamu.nama_lengkap, tamu.jenis_tamu, tamu.alamat_instansi, tamu.jabatan, data_guru.nama_pegawai AS nama_pegawai_dituju');
        $builder->join('tamu', 'tamu.id_tamu = kunjungan.id_tamu');
        $builder->join('data_guru', 'data_guru.id = kunjungan.id_pegawai_dituju', 'left');

        if ($status) {
            $builder->where('kunjungan.status_kunjungan', $status);
        }

        $builder->orderBy('kunjungan.tanggal_waktu', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}
