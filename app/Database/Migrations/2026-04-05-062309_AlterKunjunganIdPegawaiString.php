<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterKunjunganIdPegawaiString extends Migration
{
    public function up()
    {
        // Ubah tipe data id_pegawai_dituju agar bisa menerima teks seperti 'Kepala Madrasah'
        $this->forge->modifyColumn('kunjungan', [
            'id_pegawai_dituju' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->modifyColumn('kunjungan', [
            'id_pegawai_dituju' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ]
        ]);
    }
}
