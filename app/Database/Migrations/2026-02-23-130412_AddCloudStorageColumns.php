<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCloudStorageColumns extends Migration
{
    public function up()
    {
        $fields = [
            'tipe_penyimpanan' => [
                'type'       => 'ENUM',
                'constraint' => ['lokal', 'cloud'],
                'default'    => 'lokal',
                'after'      => 'lampiran',
            ],
            'file_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
                'after'      => 'file_size',
            ]
        ];

        $this->forge->addColumn('surat_masuk', $fields);

        // Surat Keluar has file_konsep_path right after file_size
        $fields_keluar = [
            'tipe_penyimpanan' => [
                'type'       => 'ENUM',
                'constraint' => ['lokal', 'cloud'],
                'default'    => 'lokal',
                'after'      => 'lampiran',
            ],
            'file_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
                'after'      => 'file_konsep_path',
            ]
        ];

        $this->forge->addColumn('surat_keluar', $fields_keluar);
    }

    public function down()
    {
        $this->forge->dropColumn('surat_masuk', ['tipe_penyimpanan', 'file_link']);
        $this->forge->dropColumn('surat_keluar', ['tipe_penyimpanan', 'file_link']);
    }
}
