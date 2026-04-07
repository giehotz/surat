<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class BackupDB extends BaseController
{
    public function download()
    {
        // Pastikan hanya admin yang bisa mengakses
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        // Konfigurasi db
        $db = \Config\Database::connect();
        $database = $db->getDatabase();
        $hostname = $db->hostname;
        $username = $db->username;
        $password = $db->password;

        $filename = 'backup_' . $database . '_' . date('Y-m-d_H-i-s') . '.sql';
        $filepath = WRITEPATH . 'uploads/' . $filename;

        // Command mysqldump (Pastikan mysqldump tersedia di environment vars server / XAMPP)
        // Konstruksi parameter password
        $passString = !empty($password) ? "-p" . escapeshellarg($password) : "";
        $command = "mysqldump -h " . escapeshellarg($hostname) . " -u " . escapeshellarg($username) . " {$passString} " . escapeshellarg($database) . " > " . escapeshellarg($filepath);

        // Eksekusi
        $output = [];
        $return_var = null;
        exec($command, $output, $return_var);

        if ($return_var === 0 && file_exists($filepath)) {
            // Force download using CodeIgniter Response
            return $this->response->download($filepath, null)->setFileName($filename);
            // File temporary ini tidak langsung dihapus karena proses download CI asinkron,
            // Namun idealnya bisa dilakukan scheduler cron untuk menghapus backup lawas di WRITEPATH/uploads.
        } else {
            return redirect()->back()->with('error', 'Gagal membuat backup database. Pastikan mysqldump tersedia di server.');
        }
    }
}
