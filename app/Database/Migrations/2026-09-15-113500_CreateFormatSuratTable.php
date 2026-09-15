<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFormatSuratTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('format_surat')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'template' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->createTable('format_surat', true);

            // Isi data awal format surat bawaan
            $this->db->table('format_surat')->insertBatch([
                [
                    'id'       => 1,
                    'nama'     => 'Biasa',
                    'template' => 'B-{nomor}/MI.08.02/PP.004/{bulan}/{tahun}',
                ],
                [
                    'id'       => 2,
                    'nama'     => 'Keputusan',
                    'template' => 'K-{nomor}/MI.08.02/PP.004/{bulan}/{tahun}',
                ],
                [
                    'id'       => 3,
                    'nama'     => 'Edaran',
                    'template' => 'E-{nomor}/MI.08.02/PP.004/{bulan}/{tahun}',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropTable('format_surat', true);
    }
}
