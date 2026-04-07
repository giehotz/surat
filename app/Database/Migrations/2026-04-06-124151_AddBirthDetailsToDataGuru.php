<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBirthDetailsToDataGuru extends Migration
{
    public function up()
    {
        $fields = [
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'peg_id_nuptk'
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'tempat_lahir'
            ],
        ];
        $this->forge->addColumn('data_guru', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('data_guru', ['tempat_lahir', 'tanggal_lahir']);
    }
}
