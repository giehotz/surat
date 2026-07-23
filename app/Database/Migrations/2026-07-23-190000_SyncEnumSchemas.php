<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SyncEnumSchemas extends Migration
{
    public function up()
    {
        // 1. Sync users.role
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'operator', 'pimpinan', 'admin_tamu', 'piket', 'staff', 'kepala'],
                'default'    => 'operator',
            ],
        ]);

        // 2. Sync surat_masuk.status
        $this->forge->modifyColumn('surat_masuk', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['tercatat', 'didisposisikan', 'selesai', 'diterima', 'didistribusikan'],
                'default'    => 'tercatat',
            ],
        ]);

        // 3. Sync surat_keluar.status
        $this->forge->modifyColumn('surat_keluar', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'menunggu', 'disetujui', 'ditolak', 'dikirim'],
                'default'    => 'draft',
            ],
        ]);

        // 4. Sync disposisi.status
        $this->forge->modifyColumn('disposisi', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'diproses', 'selesai', 'diteruskan'],
                'default'    => 'pending',
            ],
        ]);

        // 5. Sync log_aktivitas.tipe_surat
        $this->forge->modifyColumn('log_aktivitas', [
            'tipe_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['surat_masuk', 'surat_keluar', 'sistem', 'masuk', 'keluar'],
                'default'    => 'surat_masuk',
            ],
        ]);

        // 6. Sync file_attachments.tipe_surat
        $this->forge->modifyColumn('file_attachments', [
            'tipe_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['surat_masuk', 'surat_keluar', 'masuk', 'keluar'],
                'default'    => 'surat_masuk',
            ],
        ]);
    }

    public function down()
    {
        // Revert users.role
        $this->forge->modifyColumn('users', [
            'role' => [
                'type'       => 'ENUM',
                'constraint' => ['admin', 'operator', 'pimpinan', 'admin_tamu', 'piket'],
                'default'    => 'operator',
            ],
        ]);

        // Revert surat_masuk.status
        $this->forge->modifyColumn('surat_masuk', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['diterima', 'didistribusikan', 'selesai'],
                'default'    => 'diterima',
            ],
        ]);

        // Revert surat_keluar.status
        $this->forge->modifyColumn('surat_keluar', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'menunggu', 'disetujui', 'dikirim'],
                'default'    => 'draft',
            ],
        ]);

        // Revert disposisi.status
        $this->forge->modifyColumn('disposisi', [
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['diteruskan', 'diproses', 'selesai'],
                'default'    => 'diteruskan',
            ],
        ]);

        // Revert log_aktivitas.tipe_surat
        $this->forge->modifyColumn('log_aktivitas', [
            'tipe_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['masuk', 'keluar'],
            ],
        ]);

        // Revert file_attachments.tipe_surat
        $this->forge->modifyColumn('file_attachments', [
            'tipe_surat' => [
                'type'       => 'ENUM',
                'constraint' => ['masuk', 'keluar'],
            ],
        ]);
    }
}
