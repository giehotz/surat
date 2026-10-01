<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixPengaturanAutoIncrement extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        
        // Periksa jika ada row dengan id = 0
        $zeroRow = $db->table('pengaturan')->where('id', 0)->get()->getRowArray();
        if ($zeroRow) {
            $maxRow = $db->query("SELECT MAX(id) as max_id FROM pengaturan")->getRowArray();
            $newId = ((int)($maxRow['max_id'] ?? 0)) + 1;
            $db->query("UPDATE pengaturan SET id = ? WHERE id = 0", [$newId]);
        }

        // Pastikan kolom id memiliki atribut AUTO_INCREMENT
        $db->query("ALTER TABLE pengaturan MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT");
    }

    public function down()
    {
        // No-op
    }
}
