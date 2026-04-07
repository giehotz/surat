<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'username'     => 'admin',
            'email'        => 'admin@suratapp.com',
            'password'     => password_hash('admin123', PASSWORD_BCRYPT),
            'nama_lengkap' => 'Administrator Sistem',
            'jabatan'      => 'Kepala Tata Usaha',
            'role'         => 'admin',
            'created_at'   => date('Y-m-d H:i:s'),
            'updated_at'   => date('Y-m-d H:i:s'),
        ];

        // Insert into the users table
        $this->db->table('users')->insert($data);
    }
}
