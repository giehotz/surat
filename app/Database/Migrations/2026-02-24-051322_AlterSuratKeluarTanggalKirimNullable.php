<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterSuratKeluarTanggalKirimNullable extends Migration
{
    public function up()
    {
        $fields = [
            'tanggal_kirim' => [
                'type' => 'DATE',
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('surat_keluar', $fields);
    }

    public function down()
    {
        $fields = [
            'tanggal_kirim' => [
                'type' => 'DATE',
                'null' => false,
            ],
        ];
        $this->forge->modifyColumn('surat_keluar', $fields);
    }
}
