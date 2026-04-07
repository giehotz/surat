<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterSuratKeluarNomorSuratNullable extends Migration
{
    public function up()
    {
        $fields = [
            'nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ];
        $this->forge->modifyColumn('surat_keluar', $fields);
    }

    public function down()
    {
        // Reverting it might fail if there are actual nulls, so we just set it back to not null
        // But doing so could cause an error if there are already NULL values. Standard practice is to provide a default.
        $fields = [
            'nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
        ];
        $this->forge->modifyColumn('surat_keluar', $fields);
    }
}
