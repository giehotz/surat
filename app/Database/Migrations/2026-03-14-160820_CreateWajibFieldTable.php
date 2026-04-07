<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWajibFieldTable extends Migration
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
            'form_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'field_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'is_required' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
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
        $this->forge->addKey(['form_type', 'field_name'], false);
        $this->forge->createTable('wajib_field_pengaturan');
        
        // Insert default values for surat_keluar form
        $defaults = [
            [
                'form_type' => 'surat_keluar',
                'field_name' => 'nomor_surat',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'form_type' => 'surat_keluar',
                'field_name' => 'tanggal_surat',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'form_type' => 'surat_keluar',
                'field_name' => 'tanggal_kirim',
                'is_required' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'form_type' => 'surat_keluar',
                'field_name' => 'tujuan',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'form_type' => 'surat_keluar',
                'field_name' => 'perihal',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'form_type' => 'surat_keluar',
                'field_name' => 'file_konsep',
                'is_required' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];
        
        $this->db->table('wajib_field_pengaturan')->insertBatch($defaults);
    }

    public function down()
    {
        $this->forge->dropTable('wajib_field_pengaturan');
    }
}