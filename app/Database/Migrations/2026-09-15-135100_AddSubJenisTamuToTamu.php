<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSubJenisTamuToTamu extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('sub_jenis_tamu', 'tamu')) {
            $this->forge->addColumn('tamu', [
                'sub_jenis_tamu' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                    'null'       => true,
                    'after'      => 'jenis_tamu',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('sub_jenis_tamu', 'tamu')) {
            $this->forge->dropColumn('tamu', 'sub_jenis_tamu');
        }
    }
}
