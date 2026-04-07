<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipePenyimpananToBerkasGuru extends Migration
{
    public function up()
    {
        $fields = [
            'tipe_penyimpanan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => 'lokal',
                'after'      => 'nama_dokumen'
            ],
            'file_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'file_path'
            ],
        ];
        $this->forge->addColumn('berkas_guru', $fields);

        $modify = [
            'file_path' => [
                'name'       => 'file_path',
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ];
        $this->forge->modifyColumn('berkas_guru', $modify);
    }

    public function down()
    {
        $this->forge->dropColumn('berkas_guru', ['tipe_penyimpanan', 'file_link']);
        $modify = [
            'file_path' => [
                'name'       => 'file_path',
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
        ];
        $this->forge->modifyColumn('berkas_guru', $modify);
    }
}
