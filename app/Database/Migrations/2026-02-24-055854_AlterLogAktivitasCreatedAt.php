<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterLogAktivitasCreatedAt extends Migration
{
    public function up()
    {
        // Alter table to set default current_timestamp
        $fields = [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ]
        ];

        $this->forge->modifyColumn('log_aktivitas', $fields);

        // Update existing rows that have NULL created_at
        $this->db->query("UPDATE log_aktivitas SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL");
    }

    public function down()
    {
        // Remove default
        $fields = [
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => null
            ]
        ];
        $this->forge->modifyColumn('log_aktivitas', $fields);
    }
}
