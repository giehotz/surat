<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'     => 'admin',
                'password'     => password_hash('admin123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Administrator Sistem',
                'email'        => 'admin@suratapp.local',
                'role'         => 'admin',
                'jabatan'      => 'SysAdmin',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'pimpinan',
                'password'     => password_hash('pimpinan123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Bapak Kepala Sekolah',
                'email'        => 'kepsek@suratapp.local',
                'role'         => 'pimpinan',
                'jabatan'      => 'Kepala Instansi',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ],
            [
                'username'     => 'operator',
                'password'     => password_hash('operator123', PASSWORD_BCRYPT),
                'nama_lengkap' => 'Staf Tata Usaha',
                'email'        => 'tu@suratapp.local',
                'role'         => 'operator',
                'jabatan'      => 'Resepsionis',
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ]
        ];

        // Clean table sequence then insert to prevent unique key clashing
        // $this->db->table('users')->truncate();

        // Simple insert (ignore duplicates based on unique username)
        $this->db->table('users')->ignore(true)->insertBatch($data);
    }
}
