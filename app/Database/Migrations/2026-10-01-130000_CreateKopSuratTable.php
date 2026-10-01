<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKopSuratTable extends Migration
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
            'kementerian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            ],
            'kantor_kementerian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS',
            ],
            'nama_madrasah_kop' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'default'    => 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS',
            ],
            'alamat_kop' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'kontak_kop' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'logo_kop' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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
        $this->forge->createTable('kop_surat', true);

        // Seed data awal jika tabel masih kosong dari setting yang ada
        $db = \Config\Database::connect();
        $pengaturan = $db->table('pengaturan')->get()->getResultArray();
        $settings = [];
        foreach ($pengaturan as $row) {
            $settings[$row['pengaturan_key']] = $row['pengaturan_value'];
        }

        $db->table('kop_surat')->insert([
            'kementerian'        => $settings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA',
            'kantor_kementerian' => $settings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS',
            'nama_madrasah_kop'  => $settings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS',
            'alamat_kop'         => $settings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378',
            'kontak_kop'         => $settings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com',
            'logo_kop'           => $settings['sekolah_logo'] ?? null,
            'is_active'          => 1,
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('kop_surat', true);
    }
}
