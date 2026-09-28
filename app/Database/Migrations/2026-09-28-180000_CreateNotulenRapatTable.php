<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotulenRapatTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('notulen_rapat')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'judul_rapat' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'subjudul' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'tanggal_kegiatan' => [
                    'type' => 'DATE',
                ],
                'waktu' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                ],
                'tempat' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'nama_notulis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'nip_notulis' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'jumlah_lembar' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 1,
                ],
                'metode_notulen' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'default'    => 'editor',
                ],
                'isi_notulen' => [
                    'type' => 'MEDIUMTEXT',
                    'null' => true,
                ],
                'file_lampiran' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'user_id' => [
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
            $this->forge->addKey('tanggal_kegiatan');
            $this->forge->createTable('notulen_rapat', true);
        }
    }

    public function down()
    {
        $this->forge->dropTable('notulen_rapat', true);
    }
}
