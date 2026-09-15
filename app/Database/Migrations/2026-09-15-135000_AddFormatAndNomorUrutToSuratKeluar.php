<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFormatAndNomorUrutToSuratKeluar extends Migration
{
    public function up()
    {
        $fields = [];

        if (!$this->db->fieldExists('nomor_urut', 'surat_keluar')) {
            $fields['nomor_urut'] = [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'nomor_surat',
            ];
        }

        if (!$this->db->fieldExists('bulan', 'surat_keluar')) {
            $fields['bulan'] = [
                'type'       => 'VARCHAR',
                'constraint' => '2',
                'null'       => true,
                'after'      => 'nomor_urut',
            ];
        }

        if (!$this->db->fieldExists('format_surat_id', 'surat_keluar')) {
            $fields['format_surat_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'bulan',
            ];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('surat_keluar', $fields);
        }
    }

    public function down()
    {
        $dropCols = [];
        if ($this->db->fieldExists('format_surat_id', 'surat_keluar')) {
            $dropCols[] = 'format_surat_id';
        }
        if ($this->db->fieldExists('bulan', 'surat_keluar')) {
            $dropCols[] = 'bulan';
        }
        if ($this->db->fieldExists('nomor_urut', 'surat_keluar')) {
            $dropCols[] = 'nomor_urut';
        }

        if (!empty($dropCols)) {
            $this->forge->dropColumn('surat_keluar', $dropCols);
        }
    }
}
