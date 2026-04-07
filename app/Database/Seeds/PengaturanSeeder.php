<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaturanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Identitas Institusi
            [
                'pengaturan_key'   => 'app_nama',
                'pengaturan_value' => 'SuratApp',
            ],
            [
                'pengaturan_key'   => 'sekolah_nama',
                'pengaturan_value' => 'SMA Negeri 1 Kota Kita',
            ],
            [
                'pengaturan_key'   => 'sekolah_npsn',
                'pengaturan_value' => '10203040',
            ],
            [
                'pengaturan_key'   => 'sekolah_alamat',
                'pengaturan_value' => 'Jl. Pendidikan No. 1, Kota Kita, Provinsi Kita 12345',
            ],
            [
                'pengaturan_key'   => 'sekolah_kontak',
                'pengaturan_value' => '021-1234567 | info@sman1.sch.id',
            ],
            [
                'pengaturan_key'   => 'sekolah_logo',
                'pengaturan_value' => '', // Kosong di awal, bisa diunggah via setting UI
            ],

            // Pimpinan
            [
                'pengaturan_key'   => 'pejabat_kepsek_nama',
                'pengaturan_value' => 'Drs. H. Ahmad Dahlan, M.Pd.',
            ],
            [
                'pengaturan_key'   => 'pejabat_kepsek_nip',
                'pengaturan_value' => '19700101 199512 1 001',
            ],
            [
                'pengaturan_key'   => 'pejabat_tu_nama',
                'pengaturan_value' => 'Budi Santoso, S.Kom.',
            ],
            [
                'pengaturan_key'   => 'pejabat_tu_nip',
                'pengaturan_value' => '19850202 201001 1 002',
            ],
        ];

        // Insert using ignore parameter to prevent replacing manual changes on re-seed
        $this->db->table('pengaturan')->ignore(true)->insertBatch($data);
    }
}
