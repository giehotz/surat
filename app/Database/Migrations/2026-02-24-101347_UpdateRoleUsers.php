<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRoleUsers extends Migration
{
    public function up()
    {
        // Add new roles to enum first to avoid data truncation
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'staff', 'kepala', 'pegawai', 'operator', 'pimpinan'],
                'default'    => 'operator',
            ],
        ]);

        // Update existing data
        $this->db->table('users')->whereIn('role', ['staff', 'pegawai'])->update(['role' => 'operator']);
        $this->db->table('users')->where('role', 'kepala')->update(['role' => 'pimpinan']);

        // Restrict enum to only the 3 required roles
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'operator', 'pimpinan'],
                'default'    => 'operator',
            ],
        ]);
    }

    public function down()
    {
        // Revert enum change
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'staff', 'kepala', 'operator', 'pimpinan'],
                'default'    => 'staff',
            ],
        ]);
        
        $this->db->table('users')->where('role', 'operator')->update(['role' => 'staff']);
        $this->db->table('users')->where('role', 'pimpinan')->update(['role' => 'kepala']);

        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'staff', 'kepala'],
                'default'    => 'staff',
            ],
        ]);
    }
}
