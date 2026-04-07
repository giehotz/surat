<?php

namespace App\Controllers;

use App\Models\KunjunganModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminBukuTamu extends BaseController
{
    protected $kunjunganModel;

    public function __construct()
    {
        $this->kunjunganModel = new KunjunganModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $data = [
            'title'     => 'Rekap Buku Tamu',
            'kunjungan' => $this->kunjunganModel->getKunjunganWithDetails($status),
            'status'    => $status
        ];

        return view('buku_tamu/admin/index', $data);
    }

    public function show($id)
    {
        // Simple mock response for ajax modal
        $kunjungan = $this->kunjunganModel->find($id);
        if (!$kunjungan) return $this->response->setJSON(['status' => false]);
        
        $tamu = (new \App\Models\TamuModel())->find($kunjungan['id_tamu']);
        
        return $this->response->setJSON([
            'status' => true,
            'kunjungan' => $kunjungan,
            'tamu' => $tamu
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status_kunjungan');
        // Validasi
        if (in_array($status, ['menunggu', 'diterima', 'selesai', 'batal'])) {
            $this->kunjunganModel->update($id, ['status_kunjungan' => $status]);
            return redirect()->back()->with('success', 'Status kunjungan diperbarui.');
        }
        return redirect()->back()->with('error', 'Status tidak valid.');
    }

    public function addTindakLanjut($id)
    {
        $tindak_lanjut = $this->request->getPost('tindak_lanjut');
        $this->kunjunganModel->update($id, ['tindak_lanjut' => $tindak_lanjut]);
        return redirect()->back()->with('success', 'Tindak lanjut berhasil disimpan.');
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

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times-Roman');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Menggunakan orientasi landscape agar tabel lebar cukup
        $dompdf->render();

        $dompdf->stream("Rekap_Buku_Tamu_{$tahun}.pdf", ["Attachment" => false]);
        exit;
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
            return redirect()->back()->with('error', 'Data kunjungan tidak ditemukan.');
        }

        // List files to delete
        $filesToDelete = [
            $kunjungan['foto_wajah'],
            $kunjungan['tanda_tangan'],
            $kunjungan['dokumen_pendukung']
        ];

        foreach ($filesToDelete as $file) {
            if ($file && !str_starts_with($file, 'data:') && file_exists(FCPATH . $file)) {
                // Ensure it's not a Base64 string and file exists
                unlink(FCPATH . $file);
            }
        }

        // Delete from DB
        $this->kunjunganModel->delete($id);

        return redirect()->back()->with('success', 'Data kunjungan berhasil dihapus permanent.');
    }
}
