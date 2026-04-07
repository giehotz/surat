<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan pengguna sudah login terlebih dahulu
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        $userRole = session()->get('role');

        // Jika tidak ada argumen (role yang diizinkan), tolak akses secara default
        if (empty($arguments)) {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak: Tidak ada role yang dikonfigurasi.');
        }

        // Cek apakah role pengguna saat ini ada dalam daftar argumen (role yang diizinkan)
        if (!in_array($userRole, $arguments)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki hak akses yang cukup untuk membuka menu tersebut.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}
