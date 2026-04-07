<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndexesToSuratTables extends Migration
{
    public function up()
    {
        // Indexes for surat_masuk
        $this->db->query("CREATE INDEX surat_masuk_nomor_surat_idx ON surat_masuk(nomor_surat)");
        $this->db->query("CREATE INDEX surat_masuk_nomor_agenda_idx ON surat_masuk(nomor_agenda)");
        $this->db->query("CREATE INDEX surat_masuk_tanggal_surat_idx ON surat_masuk(tanggal_surat)");

        // Indexes for surat_keluar
        $this->db->query("CREATE INDEX surat_keluar_nomor_surat_idx ON surat_keluar(nomor_surat)");
        $this->db->query("CREATE INDEX surat_keluar_nomor_agenda_idx ON surat_keluar(nomor_agenda)");
        $this->db->query("CREATE INDEX surat_keluar_tanggal_surat_idx ON surat_keluar(tanggal_surat)");
        $this->db->query("CREATE INDEX surat_keluar_tujuan_idx ON surat_keluar(tujuan)");
    }

    public function down()
    {
        // Drop Indexes for surat_masuk
        $this->db->query("ALTER TABLE surat_masuk DROP INDEX surat_masuk_nomor_surat_idx");
        $this->db->query("ALTER TABLE surat_masuk DROP INDEX surat_masuk_nomor_agenda_idx");
        $this->db->query("ALTER TABLE surat_masuk DROP INDEX surat_masuk_tanggal_surat_idx");

        // Drop Indexes for surat_keluar
        $this->db->query("ALTER TABLE surat_keluar DROP INDEX surat_keluar_nomor_surat_idx");
        $this->db->query("ALTER TABLE surat_keluar DROP INDEX surat_keluar_nomor_agenda_idx");
        $this->db->query("ALTER TABLE surat_keluar DROP INDEX surat_keluar_tanggal_surat_idx");
        $this->db->query("ALTER TABLE surat_keluar DROP INDEX surat_keluar_tujuan_idx");
    }
}
