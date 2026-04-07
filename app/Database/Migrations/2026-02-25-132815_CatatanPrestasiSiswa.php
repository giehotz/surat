<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CatatanPrestasiSiswa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'nama_siswa' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'nis' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'jenis_prestasi' => [
                'type'       => 'ENUM',
                'constraint' => ['Akademik', 'Non Akademik'],
            ],
            'tingkat' => [
                'type'       => 'ENUM',
                'constraint' => ['Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'],
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'file_sertifikat' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('catatan_prestasi_siswa');
    }

    public function down()
    {
        $this->forge->dropTable('catatan_prestasi_siswa');
    }
}
