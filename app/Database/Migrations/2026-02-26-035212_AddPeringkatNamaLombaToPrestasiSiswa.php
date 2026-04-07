<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPeringkatNamaLombaToPrestasiSiswa extends Migration
{
    public function up()
    {
        $fields = [
            'peringkat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'nama_lomba' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
        ];
        $this->forge->addColumn('catatan_prestasi_siswa', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('catatan_prestasi_siswa', 'peringkat');
        $this->forge->dropColumn('catatan_prestasi_siswa', 'nama_lomba');
    }
}
