<?php

namespace App\Controllers;

use App\Models\LogAktivitasModel;
use App\Services\DashboardService;

class Dashboard extends BaseController
{
    protected DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        $role = session('role');

        if ($role === 'admin_tamu') {
            $data = $this->dashboardService->getAdminTamuDashboardData();
            return view('dashboard/admin_tamu', $data);
        }

        $data = $this->dashboardService->getMainDashboardData();
        return view('dashboard/index', $data);
    }

    public function deleteLog($id = null)
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $logAktivitasModel = new LogAktivitasModel();

        if ($logAktivitasModel->find($id)) {
            $logAktivitasModel->delete($id);
            return redirect()->to('/dashboard')->with('success', 'Aktivitas berhasil dihapus.');
        }

        return redirect()->to('/dashboard')->with('error', 'Aktivitas tidak ditemukan.');
    }

    public function deleteAllLogs()
    {
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

        $db = \Config\Database::connect();
        $db->table('log_aktivitas')->emptyTable();

        return redirect()->to('/dashboard')->with('success', 'Seluruh log aktivitas berhasil dibersihkan.');
    }
}
