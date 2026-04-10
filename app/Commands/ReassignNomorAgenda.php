<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ReassignNomorAgenda extends BaseCommand
{
    protected $group       = 'Surat';
    protected $name        = 'surat:reassign-agenda';
    protected $description = 'Reassign semua nomor agenda surat masuk berdasarkan urutan kronologis tanggal surat.';

    public function run(array $params)
    {
        CLI::write('Memulai reassign nomor agenda surat masuk...', 'yellow');

        $model = new \App\Models\SuratMasukModel();
        $model->reassignNomorAgenda();

        CLI::write('Selesai! Semua nomor agenda telah diperbarui berdasarkan urutan tanggal surat.', 'green');
    }
}
