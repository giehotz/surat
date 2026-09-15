<?php

namespace App\Services;

use App\Models\SuratMasukModel;
use App\Models\SuratKeluarModel;
use App\Models\DisposisiModel;
use App\Models\UserModel;
use App\Models\LogAktivitasModel;
use App\Models\KunjunganModel;

class DashboardService
{
    /**
     * Mengambil data lengkap untuk dashboard admin tamu
     */
    public function getAdminTamuDashboardData(): array
    {
        $kunjunganModel = new KunjunganModel();
        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        $currentMonth = (int) date('m');
        $year = (int) date('Y');

        // 1. Statistik Kartu - Single Query Aggregation
        $statsRow = $db->query("
            SELECT 
                COUNT(CASE WHEN DATE(tanggal_waktu) = ? THEN 1 END) as total_hari_ini,
                COUNT(CASE WHEN status_kunjungan = 'menunggu' THEN 1 END) as total_menunggu,
                COUNT(CASE WHEN MONTH(tanggal_waktu) = ? AND YEAR(tanggal_waktu) = ? THEN 1 END) as total_bulan_ini
            FROM kunjungan
        ", [$today, $currentMonth, $year])->getRowArray();

        $total_hari_ini  = (int) ($statsRow['total_hari_ini'] ?? 0);
        $total_menunggu  = (int) ($statsRow['total_menunggu'] ?? 0);
        $total_bulan_ini = (int) ($statsRow['total_bulan_ini'] ?? 0);

        // 2. Kunjungan Terbaru (5 terakhir)
        $latest_guests = $kunjunganModel->getKunjunganWithDetails();
        $latest_guests = array_slice($latest_guests, 0, 5);

        // 3. Data Grafik Mingguan (7 hari terakhir)
        $chart_query = $db->query("
            SELECT DATE(tanggal_waktu) as tgl, COUNT(*) as total 
            FROM kunjungan 
            WHERE tanggal_waktu >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
            GROUP BY DATE(tanggal_waktu) 
            ORDER BY DATE(tanggal_waktu) ASC
        ")->getResultArray();

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
            if (!$found) {
                $chart_values[] = 0;
            }
        }

        return [
            'title' => 'Dashboard Buku Tamu',
            'stats' => [
                'hari_ini'  => $total_hari_ini,
                'menunggu'  => $total_menunggu,
                'bulan_ini' => $total_bulan_ini,
            ],
            'latest_guests' => $latest_guests,
            'chart' => [
                'labels' => $chart_labels,
                'values' => $chart_values
            ]
        ];
    }

    /**
     * Mengambil data lengkap untuk dashboard utama (admin/operator/pimpinan)
     */
    public function getMainDashboardData(): array
    {
        $suratKeluarModel  = new SuratKeluarModel();
        $logAktivitasModel = new LogAktivitasModel();

        $db = \Config\Database::connect();
        $year = (int) date('Y');

        // 1. Single composite query untuk seluruh counters
        $statsRow = $db->query("
            SELECT 
                (SELECT COUNT(*) FROM surat_masuk) as total_surat_masuk,
                (SELECT COUNT(*) FROM surat_keluar) as total_surat_keluar,
                (SELECT COUNT(*) FROM disposisi WHERE status = 'diteruskan') as total_disposisi_pending,
                (SELECT COUNT(*) FROM users) as total_users
        ")->getRowArray();

        // 2. Data Surat Masuk per bulan
        $sm_query = $db->query("SELECT MONTH(tanggal_terima) as bulan, COUNT(*) as total FROM surat_masuk WHERE YEAR(tanggal_terima) = ? GROUP BY MONTH(tanggal_terima)", [$year])->getResultArray();
        $sm_data = array_fill(0, 12, 0);
        foreach ($sm_query as $row) {
            $sm_data[$row['bulan'] - 1] = (int)$row['total'];
        }

        // 3. Data Surat Keluar per bulan
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

        // 4. Mengambil surat masuk terbaru per pengirim (window function)
        $subQuery = $db->table('surat_masuk')
            ->select('*, ROW_NUMBER() OVER (PARTITION BY pengirim ORDER BY tanggal_terima DESC, id DESC) as rn')
            ->getCompiledSelect();

        $latest_surat_masuk_by_pengirim = $db->table("($subQuery) as ranked")
            ->where('rn', 1)
            ->orderBy('tanggal_terima', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return [
            'total_surat_masuk'       => (int) ($statsRow['total_surat_masuk'] ?? 0),
            'total_surat_keluar'      => (int) ($statsRow['total_surat_keluar'] ?? 0),
            'total_disposisi_pending' => (int) ($statsRow['total_disposisi_pending'] ?? 0),
            'total_users'             => (int) ($statsRow['total_users'] ?? 0),
            'latest_nomor_surat_keluar'      => $latest_nomor_surat_keluar,
            'latest_surat_masuk_by_pengirim' => $latest_surat_masuk_by_pengirim,
            'chart_data'              => [
                'masuk'  => $sm_data,
                'keluar' => $sk_data
            ],
            'logs'                    => $logAktivitasModel->select('log_aktivitas.*, users.nama_lengkap as username, users.foto_profile')
                ->join('users', 'users.id = log_aktivitas.user_id', 'left')
                ->orderBy('log_aktivitas.created_at', 'DESC')
                ->findAll(10)
        ];
    }
}
