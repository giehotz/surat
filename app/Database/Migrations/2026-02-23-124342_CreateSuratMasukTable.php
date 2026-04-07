<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuratMasukTable extends Migration
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
            'nomor_agenda' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nomor_surat' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tanggal_surat' => [
                'type' => 'DATE',
            ],
            'tanggal_terima' => [
                'type' => 'DATE',
            ],
            'pengirim' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'perihal' => [
                'type' => 'TEXT',
            ],
            'lampiran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'file_path' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'file_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'file_size' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['diterima', 'didistribusikan', 'selesai'],
                'default'    => 'diterima',
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('surat_masuk');
    }

    public function down()
    {
        $this->forge->dropTable('surat_masuk');
    }
}
