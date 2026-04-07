<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTipePenyimpananToPrestasi extends Migration
{
    public function up()
    {
        $fields = [
            'tipe_penyimpanan' => [
                'type'       => 'ENUM',
                'constraint' => ['lokal', 'cloud'],
                'default'    => 'lokal',
                'after'      => 'keterangan'
            ],
            'file_link' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'file_sertifikat'
            ],
        ];
        $this->forge->addColumn('catatan_prestasi_siswa', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('catatan_prestasi_siswa', ['tipe_penyimpanan', 'file_link']);
    }
}
