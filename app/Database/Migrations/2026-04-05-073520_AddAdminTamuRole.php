<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminTamuRole extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'operator', 'pimpinan', 'admin_tamu', 'piket'],
                'default'    => 'operator',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'operator', 'pimpinan'],
                'default'    => 'operator',
            ],
        ]);
    }
}
