<?php
 
namespace App\Database\Migrations;
 
use CodeIgniter\Database\Migration;
 
class AlterKunjunganIdSiswaVarchar extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('kunjungan', [
            'id_siswa_dituju' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
        ]);
    }
 
    public function down()
    {
        $this->forge->modifyColumn('kunjungan', [
            'id_siswa_dituju' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);
    }
}
