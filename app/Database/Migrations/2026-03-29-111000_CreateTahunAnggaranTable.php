<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTahunAnggaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tahun' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('tahun');
        $this->forge->createTable('tahun_anggaran');

        // Optional: Pre-fill some data
        $data = [
            ['tahun' => '2025', 'created_at' => date('Y-m-d H:i:s')],
            ['tahun' => '2026', 'created_at' => date('Y-m-d H:i:s')],
        ];
        $this->db->table('tahun_anggaran')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('tahun_anggaran');
    }
}
