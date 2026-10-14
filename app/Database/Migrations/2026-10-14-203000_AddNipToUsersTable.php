<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNipToUsersTable extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('nip', 'users')) {
            $this->forge->addColumn('users', [
                'nip' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '30',
                    'null'       => true,
                    'after'      => 'nama_lengkap',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('nip', 'users')) {
            $this->forge->dropColumn('users', 'nip');
        }
    }
}
