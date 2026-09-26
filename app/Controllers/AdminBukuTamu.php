<?php

namespace App\Controllers;

use App\Models\KunjunganModel;
use App\Models\TamuModel;
use App\Models\LogAktivitasModel;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminBukuTamu extends BaseController
{
    protected $kunjunganModel;
    protected $tamuModel;

    public function __construct()
    {
        $this->kunjunganModel = new KunjunganModel();
        $this->tamuModel      = new TamuModel();
    }

    public function index()
    {
        $today     = date('Y-m-d');
        $thisMonth = date('m');
        $thisYear  = date('Y');

        $tamuHariIni    = $this->kunjunganModel->where('DATE(tanggal_waktu)', $today)->countAllResults();
        $sedangMenunggu = $this->kunjunganModel->where('status_kunjungan', 'menunggu')->countAllResults();
        $sedangDilayani = $this->kunjunganModel->where('status_kunjungan', 'diterima')->countAllResults();
        $totalBulanIni  = $this->kunjunganModel->where('YEAR(tanggal_waktu)', $thisYear)->where('MONTH(tanggal_waktu)', $thisMonth)->countAllResults();

        $stats = [
            'tamu_hari_ini'   => $tamuHariIni,
            'sedang_menunggu' => $sedangMenunggu,
            'sedang_dilayani' => $sedangDilayani,
            'total_bulan_ini' => $totalBulanIni,
        ];

        $status = $this->request->getGet('status');

        $data = [
            'title'  => 'Rekap Buku Tamu Digital',
            'stats'  => $stats,
            'status' => $status
        ];

        return view('buku_tamu/admin/index', $data);
    }

    public function ajaxList(): ResponseInterface
    {
        $request = \Config\Services::request();

        $start  = (int) ($request->getPost('start') ?? 0);
        $length = (int) ($request->getPost('length') ?? 10);
        $search = $request->getPost('search')['value'] ?? '';

        $orderInfo   = $request->getPost('order')[0] ?? null;
        $columnIndex = $orderInfo['column'] ?? 2;
        $orderDir    = $orderInfo['dir'] ?? 'desc';

        $columns = [
            0 => 'kunjungan.id_kunjungan',
            1 => 'kunjungan.id_kunjungan',
            2 => 'kunjungan.tanggal_waktu',
            3 => 'tamu.nama_lengkap',
            4 => 'tamu.jenis_tamu',
            5 => 'data_guru.nama_pegawai',
            6 => 'kunjungan.tujuan_kunjungan',
            7 => 'kunjungan.status_kunjungan',
            8 => 'kunjungan.id_kunjungan',
        ];
        $orderColumn = $columns[$columnIndex] ?? 'kunjungan.tanggal_waktu';

        $builder = $this->kunjunganModel->db->table('kunjungan');
        $builder->select('kunjungan.*, tamu.nama_lengkap, tamu.jenis_tamu, tamu.sub_jenis_tamu, tamu.alamat_instansi, tamu.jabatan, tamu.no_hp, data_guru.nama_pegawai AS nama_pegawai_dituju');
        $builder->join('tamu', 'tamu.id_tamu = kunjungan.id_tamu');
        $builder->join('data_guru', 'data_guru.id = kunjungan.id_pegawai_dituju', 'left');

        $totalRecords = $builder->countAllResults(false);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('tamu.nama_lengkap', $search)
                ->orLike('tamu.alamat_instansi', $search)
                ->orLike('tamu.no_hp', $search)
                ->orLike('tamu.nip', $search)
                ->orLike('kunjungan.tujuan_kunjungan', $search)
                ->orLike('data_guru.nama_pegawai', $search)
                ->groupEnd();
        }

        $startDate = $request->getPost('start_date');
        $endDate   = $request->getPost('end_date');
        $jenisTamu = $request->getPost('jenis_tamu');
        $status    = $request->getPost('status_kunjungan');

        if (!empty($startDate)) {
            $builder->where('kunjungan.tanggal_waktu >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('kunjungan.tanggal_waktu <=', $endDate . ' 23:59:59');
        }
        if (!empty($jenisTamu)) {
            $builder->where('tamu.jenis_tamu', $jenisTamu);
        }
        if (!empty($status)) {
            $builder->where('kunjungan.status_kunjungan', $status);
        }

        $filteredRecords = $builder->countAllResults(false);

        $builder->orderBy($orderColumn, $orderDir);
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $data = $builder->get()->getResultArray();
        $responseData = [];
        $no = $start + 1;

        foreach ($data as $k) {
            $row = [];

            // 0: Checkbox
            $row[] = '<input type="checkbox" class="form-check-input row-checkbox" data-id="' . $k['id_kunjungan'] . '">';

            // 1: No
            $row[] = $no++;

            // 2: Waktu
            $tglStr = function_exists('format_tanggal_indo') ? format_tanggal_indo(date('Y-m-d', strtotime($k['tanggal_waktu']))) : date('d M Y', strtotime($k['tanggal_waktu']));
            $waktuFormatted = '<div class="fw-semibold text-nowrap">' . $tglStr . '</div>'
                . '<div class="text-secondary small"><i class="ti ti-clock me-1"></i>' . date('H:i', strtotime($k['tanggal_waktu'])) . ' WIB</div>';
            $row[] = $waktuFormatted;

            // 3: Tamu
            $tamuHtml = '<div class="fw-bold text-dark text-truncate" style="max-width: 220px;" title="' . esc($k['nama_lengkap']) . '">' . esc($k['nama_lengkap']) . '</div>';
            if (!empty($k['alamat_instansi'])) {
                $tamuHtml .= '<div class="text-secondary small text-truncate" style="max-width: 220px;" title="' . esc($k['alamat_instansi']) . '"><i class="ti ti-map-pin me-1"></i>' . esc($k['alamat_instansi']) . '</div>';
            }
            if (!empty($k['no_hp'])) {
                $tamuHtml .= '<div class="text-muted small"><i class="ti ti-phone me-1"></i>' . esc($k['no_hp']) . '</div>';
            }
            $row[] = $tamuHtml;

            // 4: Jenis Tamu
            if ($k['jenis_tamu'] === 'khusus') {
                $sub = !empty($k['sub_jenis_tamu']) ? '<div class="small text-muted">' . esc($k['sub_jenis_tamu']) . '</div>' : '';
                $row[] = '<span class="badge bg-blue-lt text-nowrap"><i class="ti ti-building me-1"></i>Dinas / Khusus</span>' . $sub;
            } else {
                $sub = !empty($k['sub_jenis_tamu']) ? '<div class="small text-muted">' . esc($k['sub_jenis_tamu']) . '</div>' : '';
                $row[] = '<span class="badge bg-secondary-lt text-nowrap"><i class="ti ti-user me-1"></i>Umum</span>' . $sub;
            }

            // 5: Dituju
            $ditujuHtml = '';
            if (!empty($k['nama_pegawai_dituju'])) {
                $ditujuHtml .= '<div class="fw-medium text-primary text-truncate" style="max-width: 180px;" title="' . esc($k['nama_pegawai_dituju']) . '"><i class="ti ti-user-check me-1"></i>' . esc($k['nama_pegawai_dituju']) . '</div>';
            } elseif (!empty($k['id_pegawai_dituju'])) {
                $ditujuHtml .= '<div class="fw-medium text-primary">' . esc($k['id_pegawai_dituju']) . '</div>';
            }
            if (!empty($k['id_siswa_dituju'])) {
                $ditujuHtml .= '<div class="text-secondary small"><i class="ti ti-school me-1"></i>Wali: ' . esc($k['id_siswa_dituju']) . '</div>';
            }
            if (empty($ditujuHtml)) {
                $ditujuHtml = '<span class="text-muted fst-italic">Tidak Spesifik</span>';
            }
            $row[] = $ditujuHtml;

            // 6: Tujuan
            $hasDoc = !empty($k['dokumen_pendukung']) ? ' <span class="badge bg-info-lt ms-1" title="Ada Berkas Surat Tugas"><i class="ti ti-paperclip"></i> Dokumen</span>' : '';
            $row[] = '<div class="text-truncate" style="max-width: 180px;" title="' . esc($k['tujuan_kunjungan']) . '">' . esc($k['tujuan_kunjungan']) . '</div>' . $hasDoc;

            // 7: Status Badge
            $statusBadge = match ($k['status_kunjungan']) {
                'menunggu' => '<span class="badge bg-warning text-warning-fg"><i class="ti ti-clock me-1"></i>Menunggu</span>',
                'diterima' => '<span class="badge bg-primary text-primary-fg"><i class="ti ti-user-check me-1"></i>Dilayani</span>',
                'selesai'  => '<span class="badge bg-success text-success-fg"><i class="ti ti-check me-1"></i>Selesai</span>',
                'batal'    => '<span class="badge bg-danger text-danger-fg"><i class="ti ti-x me-1"></i>Batal</span>',
                default    => '<span class="badge bg-secondary text-secondary-fg">' . esc($k['status_kunjungan']) . '</span>',
            };
            $row[] = $statusBadge;

            // 8: Aksi
            $btn = '<div class="btn-list flex-nowrap justify-content-end">';
            if ($k['status_kunjungan'] === 'menunggu') {
                $btn .= '<button type="button" class="btn btn-icon btn-sm btn-outline-primary" onclick="quickUpdateStatus(' . $k['id_kunjungan'] . ', \'diterima\')" title="Terima / Layani"><i class="ti ti-player-play"></i></button>';
            } elseif ($k['status_kunjungan'] === 'diterima') {
                $btn .= '<button type="button" class="btn btn-icon btn-sm btn-outline-success" onclick="quickUpdateStatus(' . $k['id_kunjungan'] . ', \'selesai\')" title="Selesaikan Layanan"><i class="ti ti-check"></i></button>';
            }
            $btn .= '<button type="button" class="btn btn-icon btn-sm btn-outline-info" onclick="showDetail(' . $k['id_kunjungan'] . ')" title="Lihat Detail & Tindak Lanjut"><i class="ti ti-eye"></i></button>';
            $namaTamuSafe = esc($k['nama_lengkap'], 'js');
            $btn .= '<button type="button" class="btn btn-icon btn-sm btn-outline-danger" onclick="deleteKunjungan(' . $k['id_kunjungan'] . ', \'' . $namaTamuSafe . '\')" title="Hapus"><i class="ti ti-trash"></i></button>';
            $btn .= '</div>';
            $row[] = $btn;

            $responseData[] = $row;
        }

        return $this->response->setJSON([
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data"            => $responseData,
        ]);
    }

    public function show($id)
    {
        $builder = $this->kunjunganModel->db->table('kunjungan');
        $builder->select('kunjungan.*, tamu.nama_lengkap, tamu.jenis_tamu, tamu.sub_jenis_tamu, tamu.alamat_instansi, tamu.nip, tamu.jabatan, tamu.no_hp, tamu.consent_wa, data_guru.nama_pegawai AS nama_pegawai_dituju');
        $builder->join('tamu', 'tamu.id_tamu = kunjungan.id_tamu');
        $builder->join('data_guru', 'data_guru.id = kunjungan.id_pegawai_dituju', 'left');
        $builder->where('kunjungan.id_kunjungan', $id);

        $kunjungan = $builder->get()->getRowArray();
        if (!$kunjungan) {
            return $this->response->setStatusCode(404)->setJSON(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        // WhatsApp direct link generator
        $waLink = '';
        if (!empty($kunjungan['no_hp'])) {
            $cleanedPhone = preg_replace('/[^0-9]/', '', $kunjungan['no_hp']);
            if (str_starts_with($cleanedPhone, '0')) {
                $cleanedPhone = '62' . substr($cleanedPhone, 1);
            }
            $salam = 'Halo Bapak/Ibu ' . $kunjungan['nama_lengkap'] . ', terima kasih telah berkunjung ke MIN 2 Tanggamus.';
            $waLink = 'https://wa.me/' . $cleanedPhone . '?text=' . rawurlencode($salam);
        }

        // Dokumen pendukung URL
        $docUrl = '';
        if (!empty($kunjungan['dokumen_pendukung'])) {
            $docUrl = base_url(ltrim($kunjungan['dokumen_pendukung'], '/'));
        }

        return $this->response->setJSON([
            'status'    => true,
            'kunjungan' => $kunjungan,
            'wa_link'   => $waLink,
            'doc_url'   => $docUrl
        ]);
    }

    public function quickUpdateStatus(): ResponseInterface
    {
        $id     = $this->request->getPost('id');
        $status = $this->request->getPost('status');

        if (!in_array($status, ['menunggu', 'diterima', 'selesai', 'batal'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Status kunjungan tidak valid.'
            ]);
        }

        $kunjungan = $this->kunjunganModel->find($id);
        if (!$kunjungan) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Data kunjungan tidak ditemukan.'
            ]);
        }

        $this->kunjunganModel->update($id, ['status_kunjungan' => $status]);

        $statusLabels = [
            'menunggu' => 'Menunggu',
            'diterima' => 'Diterima / Sedang Dilayani',
            'selesai'  => 'Selesai',
            'batal'    => 'Dibatalkan',
        ];

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status kunjungan berhasil diubah menjadi "' . ($statusLabels[$status] ?? $status) . '".'
        ]);
    }

    public function updateKunjungan($id)
    {
        $status        = $this->request->getPost('status_kunjungan');
        $tindak_lanjut = $this->request->getPost('tindak_lanjut');

        $dataUpdate = [];
        if (in_array($status, ['menunggu', 'diterima', 'selesai', 'batal'])) {
            $dataUpdate['status_kunjungan'] = $status;
        }

        if ($tindak_lanjut !== null) {
            $dataUpdate['tindak_lanjut'] = $tindak_lanjut;
        }

        if (!empty($dataUpdate)) {
            $this->kunjunganModel->update($id, $dataUpdate);

            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Data kunjungan berhasil diperbarui.'
                ]);
            }
            return redirect()->back()->with('success', 'Data kunjungan berhasil diperbarui.');
        }

        if ($this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Tidak ada perubahan data.'
            ]);
        }
        return redirect()->back()->with('error', 'Tidak ada data yang diperbarui.');
    }

    public function bulkUpdateStatus(): ResponseInterface
    {
        $ids    = $this->request->getPost('ids');
        $status = $this->request->getPost('status');

        if (empty($ids) || !is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Tidak ada kunjungan yang dipilih.'
            ]);
        }

        if (!in_array($status, ['menunggu', 'diterima', 'selesai', 'batal'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Status tidak valid.'
            ]);
        }

        $updated = 0;
        foreach ($ids as $id) {
            $this->kunjunganModel->update((int)$id, ['status_kunjungan' => $status]);
            $updated++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Sebanyak {$updated} kunjungan berhasil diperbarui ke status '{$status}'."
        ]);
    }

    public function bulkDelete(): ResponseInterface
    {
        $ids = $this->request->getPost('ids');
        if (empty($ids) || !is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Tidak ada kunjungan yang dipilih untuk dihapus.'
            ]);
        }

        $deletedCount = 0;
        foreach ($ids as $id) {
            $kunjungan = $this->kunjunganModel->find((int)$id);
            if (!$kunjungan) continue;

            $filesToDelete = [
                $kunjungan['foto_wajah'],
                $kunjungan['tanda_tangan'],
                $kunjungan['dokumen_pendukung']
            ];

            foreach ($filesToDelete as $file) {
                if ($file && !str_starts_with($file, 'data:') && file_exists(FCPATH . ltrim($file, '/'))) {
                    unlink(FCPATH . ltrim($file, '/'));
                }
            }

            $this->kunjunganModel->delete((int)$id);
            $deletedCount++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Sebanyak {$deletedCount} data kunjungan berhasil dihapus secara permanen."
        ]);
    }

    public function exportExcel()
    {
        $tahun = $this->request->getPost('tahun');
        $bulanAwal = $this->request->getPost('bulan_awal');
        $bulanAkhir = $this->request->getPost('bulan_akhir');

        $kunjungan = $this->getKunjunganFilter($tahun, $bulanAwal, $bulanAkhir);

        $spreadsheet = new Spreadsheet();
        
        // --- SHEET 1: Tamu Umum ---
        $sheetUmum = $spreadsheet->getActiveSheet();
        $sheetUmum->setTitle('Tamu Umum');
        $this->fillSheetData($sheetUmum, array_filter($kunjungan, fn($k) => $k['jenis_tamu'] !== 'khusus'), 'LAPORAN REKAP TAMU UMUM');

        // --- SHEET 2: Tamu Dinas ---
        $sheetDinas = $spreadsheet->createSheet();
        $sheetDinas->setTitle('Tamu Dinas');
        $this->fillSheetData($sheetDinas, array_filter($kunjungan, fn($k) => $k['jenis_tamu'] === 'khusus'), 'LAPORAN REKAP TAMU DINAS / KHUSUS');

        $spreadsheet->setActiveSheetIndex(0);

        $filename = "Rekap_Buku_Tamu_{$tahun}_{$bulanAwal}_{$bulanAkhir}.xlsx";

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'. $filename .'"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $tahun = $this->request->getPost('tahun');
        $bulanAwal = $this->request->getPost('bulan_awal');
        $bulanAkhir = $this->request->getPost('bulan_akhir');

        $kunjungan = $this->getKunjunganFilter($tahun, $bulanAwal, $bulanAkhir);
        
        $bulans = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $periodeText = $bulans[$bulanAwal-1] . ($bulanAwal != $bulanAkhir ? " s/d " . $bulans[$bulanAkhir-1] : "") . " " . $tahun;

        $viewData = [
            'kunjungan' => $kunjungan,
            'periode_text' => $periodeText,
            'appSettings' => (new \App\Models\PengaturanModel())->getSettings()
        ];

        $html = view('buku_tamu/admin/export_pdf', $viewData);

        $exportService = new \App\Services\ExportService();
        $exportService->exportPdf($html, "Rekap_Buku_Tamu_{$tahun}", 'A4', 'landscape');
    }

    private function getKunjunganFilter($tahun, $bulanAwal, $bulanAkhir)
    {
        $start = "{$tahun}-" . str_pad($bulanAwal, 2, '0', STR_PAD_LEFT) . "-01 00:00:00";
        $end = "{$tahun}-" . str_pad($bulanAkhir, 2, '0', STR_PAD_LEFT) . "-" . date('t', strtotime("{$tahun}-{$bulanAkhir}-01")) . " 23:59:59";

        $builder = $this->kunjunganModel->db->table('kunjungan');
        $builder->select('kunjungan.*, tamu.nama_lengkap, tamu.jenis_tamu, tamu.alamat_instansi, tamu.jabatan, data_guru.nama_pegawai AS nama_pegawai_dituju');
        $builder->join('tamu', 'tamu.id_tamu = kunjungan.id_tamu');
        $builder->join('data_guru', 'data_guru.id = kunjungan.id_pegawai_dituju', 'left');
        $builder->where('kunjungan.tanggal_waktu >=', $start);
        $builder->where('kunjungan.tanggal_waktu <=', $end);
        $builder->orderBy('kunjungan.tanggal_waktu', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    private function fillSheetData($sheet, $data, $title)
    {
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $headers = ['No', 'Tanggal Waktu', 'Nama Tamu', 'Instansi', 'Tujuan', 'Petugas/Siswa Dituju', 'Status'];
        $column = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($column . '3', $h);
            $sheet->getStyle($column . '3')->getFont()->setBold(true);
            $column++;
        }

        $rowNum = 4;
        $idx = 1;
        foreach ($data as $d) {
            $sheet->setCellValue('A' . $rowNum, $idx++);
            $sheet->setCellValue('B' . $rowNum, $d['tanggal_waktu']);
            $sheet->setCellValue('C' . $rowNum, $d['nama_lengkap']);
            $sheet->setCellValue('D' . $rowNum, $d['alamat_instansi']);
            $sheet->setCellValue('E' . $rowNum, $d['tujuan_kunjungan']);
            $dituju = $d['nama_pegawai_dituju'] ?: ($d['id_pegawai_dituju'] ?: '');
            if (!empty($d['id_siswa_dituju'])) {
                $dituju .= ($dituju ? ' (Wali dari: ' : 'Wali dari: ') . $d['id_siswa_dituju'] . ($dituju ? ')' : '');
            }
            $sheet->setCellValue('F' . $rowNum, $dituju ?: 'Tidak Spesifik');
            $sheet->setCellValue('G' . $rowNum, $d['status_kunjungan']);
            $rowNum++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    public function delete($id)
    {
        $kunjungan = $this->kunjunganModel->find($id);
        if (!$kunjungan) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'message' => 'Data kunjungan tidak ditemukan.'
                ]);
            }
            return redirect()->back()->with('error', 'Data kunjungan tidak ditemukan.');
        }

        // List files to delete
        $filesToDelete = [
            $kunjungan['foto_wajah'],
            $kunjungan['tanda_tangan'],
            $kunjungan['dokumen_pendukung']
        ];

        foreach ($filesToDelete as $file) {
            if ($file && !str_starts_with($file, 'data:') && file_exists(FCPATH . ltrim($file, '/'))) {
                unlink(FCPATH . ltrim($file, '/'));
            }
        }

        // Delete from DB
        $this->kunjunganModel->delete($id);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data kunjungan berhasil dihapus.'
            ]);
        }

        return redirect()->back()->with('success', 'Data kunjungan berhasil dihapus permanent.');
    }
}
