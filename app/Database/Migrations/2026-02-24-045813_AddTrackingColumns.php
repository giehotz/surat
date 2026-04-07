<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTrackingColumns extends Migration
{
    public function up()
    {
        // 1. Surat Masuk: Add updated_by
        $this->forge->addColumn('surat_masuk', [
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'created_by'
            ],
        ]);

        $this->db->query('ALTER TABLE `surat_masuk` ADD FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');

        // 2. Surat Keluar: Add updated_by
        $this->forge->addColumn('surat_keluar', [
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'created_by'
            ],
        ]);

        $this->db->query('ALTER TABLE `surat_keluar` ADD FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');

        // 3. Disposisi: Add created_by and updated_by
        $this->forge->addColumn('disposisi', [
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'catatan' // Put before created_at if possible
            ],
            'updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'created_by'
            ],
        ]);

        $this->db->query('ALTER TABLE `disposisi` ADD FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE `disposisi` ADD FOREIGN KEY (`updated_by`) REFERENCES `users`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop foreign keys first
        $this->db->query('ALTER TABLE `surat_masuk` DROP FOREIGN KEY `surat_masuk_updated_by_foreign`');
        $this->forge->dropColumn('surat_masuk', 'updated_by');

        $this->db->query('ALTER TABLE `surat_keluar` DROP FOREIGN KEY `surat_keluar_updated_by_foreign`');
        $this->forge->dropColumn('surat_keluar', 'updated_by');

        $this->db->query('ALTER TABLE `disposisi` DROP FOREIGN KEY `disposisi_created_by_foreign`');
        $this->db->query('ALTER TABLE `disposisi` DROP FOREIGN KEY `disposisi_updated_by_foreign`');
        $this->forge->dropColumn('disposisi', 'created_by');
        $this->forge->dropColumn('disposisi', 'updated_by');
    }
}
