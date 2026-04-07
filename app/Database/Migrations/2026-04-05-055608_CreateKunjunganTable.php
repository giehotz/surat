<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKunjunganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_kunjungan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_tamu' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'tanggal_waktu' => [
                'type' => 'DATETIME',
            ],
            'tujuan_kunjungan' => [
                'type' => 'TEXT',
            ],
            'id_pegawai_dituju' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_siswa_dituju' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'pesan_kesan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dokumen_pendukung' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'foto_wajah' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'tanda_tangan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status_kunjungan' => [
                'type'       => 'ENUM',
                'constraint' => ['menunggu', 'diterima', 'selesai', 'batal'],
                'default'    => 'menunggu',
            ],
            'tindak_lanjut' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'petugas_input' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_kunjungan', true);
        $this->forge->addForeignKey('id_tamu', 'tamu', 'id_tamu', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('kunjungan');
    }

    public function down()
    {
        $this->forge->dropTable('kunjungan');
    }
}
