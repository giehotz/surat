<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\SuratMasukModel;
use App\Models\SuratKeluarModel;
use App\Models\DisposisiModel;
use App\Models\UserModel;
use App\Models\LogAktivitasModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = session('role');

        // LOGIKA KHUSUS ADMIN BUKU TAMU
        if ($role === 'admin_tamu') {
            $kunjunganModel = new \App\Models\KunjunganModel();
            $tamuModel = new \App\Models\TamuModel();
            
            $db = \Config\Database::connect();
            $today = date('Y-m-d');
            $year = date('Y');

            // 1. Statistik Kartu
            $total_hari_ini = $kunjunganModel->where('DATE(tanggal_waktu)', $today)->countAllResults();
            $total_menunggu = $kunjunganModel->where('status_kunjungan', 'menunggu')->countAllResults();
            $total_bulan_ini = $kunjunganModel->where('MONTH(tanggal_waktu)', date('m'))->where('YEAR(tanggal_waktu)', $year)->countAllResults();
            
            // 2. Kunjungan Terbaru (5 terakhir)
            $latest_guests = $kunjunganModel->getKunjunganWithDetails();
            $latest_guests = array_slice($latest_guests, 0, 5);

            // 3. Data Grafik Mingguan (7 hari terakhir)
            $chart_query = $db->query("SELECT DATE(tanggal_waktu) as tgl, COUNT(*) as total FROM kunjungan WHERE tanggal_waktu >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) GROUP BY DATE(tanggal_waktu) ORDER BY DATE(tanggal_waktu) ASC")->getResultArray();
            
            $chart_labels = [];
            $chart_values = [];
            for ($i = 6; $i >= 0; $i--) {
                $d = date('Y-m-d', strtotime("-$i days"));
                $chart_labels[] = date('d M', strtotime($d));
                $found = false;
                foreach ($chart_query as $row) {
                    if ($row['tgl'] == $d) {
                        $chart_values[] = (int)$row['total'];
                        $found = true;
                        break;
                    }
                }
                if (!$found) $chart_values[] = 0;
            }

            $data = [
                'title' => 'Dashboard Buku Tamu',
                'stats' => [
                    'hari_ini' => $total_hari_ini,
                    'menunggu' => $total_menunggu,
                    'bulan_ini' => $total_bulan_ini,
                ],
                'latest_guests' => $latest_guests,
                'chart' => [
                    'labels' => $chart_labels,
                    'values' => $chart_values
                ]
            ];

            return view('dashboard/admin_tamu', $data);
        }

        $suratMasukModel   = new SuratMasukModel();
        $suratKeluarModel  = new SuratKeluarModel();
        $disposisiModel    = new DisposisiModel();
        $userModel         = new UserModel();
        $logAktivitasModel = new LogAktivitasModel();

        $db = \Config\Database::connect();
        $year = date('Y');

        // Data Surat Masuk per bulan
        $sm_query = $db->query("SELECT MONTH(tanggal_terima) as bulan, COUNT(*) as total FROM surat_masuk WHERE YEAR(tanggal_terima) = ? GROUP BY MONTH(tanggal_terima)", [$year])->getResultArray();
        $sm_data = array_fill(0, 12, 0);
        foreach ($sm_query as $row) {
            $sm_data[$row['bulan'] - 1] = (int)$row['total'];
        }

        // Data Surat Keluar per bulan
        $sk_query = $db->query("SELECT MONTH(tanggal_surat) as bulan, COUNT(*) as total FROM surat_keluar WHERE YEAR(tanggal_surat) = ? GROUP BY MONTH(tanggal_surat)", [$year])->getResultArray();
        $sk_data = array_fill(0, 12, 0);
        foreach ($sk_query as $row) {
            $sk_data[$row['bulan'] - 1] = (int)$row['total'];
        }

        $latest_surat_keluar = $suratKeluarModel->where('nomor_surat !=', '')
                                                ->where('nomor_surat IS NOT NULL', null, false)
                                                ->orderBy('id', 'DESC')
                                                ->first();
        $latest_nomor_surat_keluar = $latest_surat_keluar ? $latest_surat_keluar['nomor_surat'] : '-';

        // Get latest incoming mail from each sender
        $db = \Config\Database::connect();
        
        // Using a subquery with window function to get the latest record per sender
        $subQuery = $db->table('surat_masuk')
            ->select('*, ROW_NUMBER() OVER (PARTITION BY pengirim ORDER BY tanggal_terima DESC, id DESC) as rn')
            ->getCompiledSelect();
            
        $latest_surat_masuk_by_pengirim = $db->table("($subQuery) as ranked")
            ->where('rn', 1)
            ->orderBy('tanggal_terima', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'total_surat_masuk'  => $suratMasukModel->countAllResults(),
            'total_surat_keluar' => $suratKeluarModel->countAllResults(),
            'total_disposisi_pending' => $disposisiModel->where('status', 'diteruskan')->countAllResults(),
            'total_users'        => $userModel->countAllResults(),
            'latest_nomor_surat_keluar' => $latest_nomor_surat_keluar,
            'latest_surat_masuk_by_pengirim' => $latest_surat_masuk_by_pengirim,
            'chart_data'         => [
                'masuk'  => $sm_data,
                'keluar' => $sk_data
            ],
            'logs'               => $logAktivitasModel->select('log_aktivitas.*, users.nama_lengkap as username, users.foto_profile')
                ->join('users', 'users.id = log_aktivitas.user_id', 'left')
                ->orderBy('log_aktivitas.created_at', 'DESC')
                ->findAll(10) // 10 log terakhir
        ];

        return view('dashboard/index', $data);
    }

    public function deleteLog($id = null)
    {
        $logAktivitasModel = new LogAktivitasModel();

        // Optional: Ensure only admins can delete logs, or any user auth check
        if (session('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak.');
        }

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
