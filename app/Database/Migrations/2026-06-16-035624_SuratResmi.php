<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuratResmi extends Migration
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
            'nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal_surat' => [
                'type' => 'DATE',
            ],
            'lampiran' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'perihal' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'tujuan_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'tujuan_alamat' => [
                'type' => 'TEXT',
            ],
            'salam_pembuka' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Dengan hormat,',
            ],
            'isi_surat' => [
                'type' => 'TEXT',
            ],
            'salam_penutup' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'default'    => 'Hormat kami,',
            ],
            'pengirim_jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'pengirim_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'pengirim_nip' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'tembusan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('surat_resmi');
    }

    public function down()
    {
        $this->forge->dropTable('surat_resmi');
    }
}
