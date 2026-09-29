<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTemplateDokumenTable extends Migration
{
    public function up()
    {
        // 1. Tabel template_dokumen
        if (!$this->db->tableExists('template_dokumen')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'kode' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'unique'     => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                ],
                'kategori' => [
                    'type'       => 'ENUM',
                    'constraint' => ['builtin', 'custom'],
                    'default'    => 'builtin',
                ],
                'file_path' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'deskripsi' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'is_has_repeater' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'is_active' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
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
            $this->forge->createTable('template_dokumen');

            // Seed 5 Template Utama
            $now = date('Y-m-d H:i:s');
            $defaultTemplates = [
                [
                    'kode'            => 'surat_tugas',
                    'nama'            => 'Surat Tugas',
                    'kategori'        => 'builtin',
                    'file_path'       => 'templates/surat_tugas.docx',
                    'deskripsi'       => 'Surat tugas kedinasan untuk 1 orang atau rombongan dewan guru/pegawai.',
                    'is_has_repeater' => 1,
                    'is_active'       => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'kode'            => 'spd',
                    'nama'            => 'Surat Perjalanan Dinas (SPD)',
                    'kategori'        => 'builtin',
                    'file_path'       => 'templates/spd.docx',
                    'deskripsi'       => 'Surat Perjalanan Dinas resmi untuk keperluan dinas luar atau perjalanan tugas.',
                    'is_has_repeater' => 1,
                    'is_active'       => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'kode'            => 'surat_kuasa',
                    'nama'            => 'Surat Kuasa',
                    'kategori'        => 'builtin',
                    'file_path'       => 'templates/surat_kuasa.docx',
                    'deskripsi'       => 'Surat pelimpahan wewenang dari pihak pertama kepada pihak kedua.',
                    'is_has_repeater' => 0,
                    'is_active'       => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'kode'            => 'sk',
                    'nama'            => 'Surat Keputusan (SK)',
                    'kategori'        => 'builtin',
                    'file_path'       => 'templates/sk.docx',
                    'deskripsi'       => 'Surat Keputusan Kepala Madrasah (Menimbang, Mengingat, Menetapkan).',
                    'is_has_repeater' => 0,
                    'is_active'       => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
                [
                    'kode'            => 'gangguan_absen',
                    'nama'            => 'Pemberitahuan Gangguan Absensi (PUSAKA)',
                    'kategori'        => 'builtin',
                    'file_path'       => 'templates/gangguan_absen.docx',
                    'deskripsi'       => 'Keterangan kendala absensi pada aplikasi PUSAKA Kementerian Agama.',
                    'is_has_repeater' => 0,
                    'is_active'       => 1,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ],
            ];

            $this->db->table('template_dokumen')->insertBatch($defaultTemplates);
        }

        // 2. Tabel template_dokumen_field
        if (!$this->db->tableExists('template_dokumen_field')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'template_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                ],
                'field_key' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'field_label' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                ],
                'field_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['text', 'textarea', 'date', 'guru_select', 'number'],
                    'default'    => 'text',
                ],
                'is_required' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 1,
                ],
                'urutan' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'default'    => 0,
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
            $this->forge->addForeignKey('template_id', 'template_dokumen', 'id', 'CASCADE', 'CASCADE');
            $this->forge->createTable('template_dokumen_field');
        }
    }

    public function down()
    {
        $this->forge->dropTable('template_dokumen_field', true);
        $this->forge->dropTable('template_dokumen', true);
    }
}
