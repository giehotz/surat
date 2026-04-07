<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SetKunjunganFilesToVarchar extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('kunjungan', [
            'foto_wajah' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tanda_tangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('kunjungan', [
            'foto_wajah' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
            'tanda_tangan' => [
                'type' => 'MEDIUMTEXT',
                'null' => true,
            ],
        ]);
    }
}
