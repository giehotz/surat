<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameNisToNisnPrestasiSiswa extends Migration
{
    public function up()
    {
        $fields = [
            'nis' => [
                'name' => 'nisn',
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
        ];
        $this->forge->modifyColumn('catatan_prestasi_siswa', $fields);
    }

    public function down()
    {
        $fields = [
            'nisn' => [
                'name' => 'nis',
                'type' => 'VARCHAR',
                'constraint' => '20',
            ],
        ];
        $this->forge->modifyColumn('catatan_prestasi_siswa', $fields);
    }
}
