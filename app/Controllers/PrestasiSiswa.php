<?php

namespace App\Controllers;

use App\Models\CatatanPrestasiSiswaModel;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PrestasiSiswa extends BaseController
{
    protected $prestasiModel;

    public function __construct()
    {
        $this->prestasiModel = new CatatanPrestasiSiswaModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Buku Catatan Prestasi Siswa',
            'prestasi' => $this->prestasiModel->orderBy('tanggal', 'DESC')->findAll()
        ];
        return view('prestasi_siswa/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Catatan Prestasi Siswa',
            'validation' => session()->get('validation') ?? \Config\Services::validation()
        ];
        return view('prestasi_siswa/create', $data);
    }

    public function store()
    {
        $validationRules = [
            'tanggal' => 'required',
            'nama_siswa' => 'required',
            'nisn' => 'required',
            'jenis_prestasi' => 'required|in_list[Akademik,Non Akademik]',
            'tingkat' => 'required|in_list[Kecamatan,Kabupaten,Provinsi,Nasional,Internasional]',
            'nama_lomba' => 'required',
            'peringkat' => 'required',
        ];

        $tipePenyimpanan = $this->request->getPost('tipe_penyimpanan');
        if ($tipePenyimpanan == 'lokal') {
            $fileSertifikat = $this->request->getFile('file_sertifikat');
            if ($fileSertifikat && $fileSertifikat->isValid()) {
                $validationRules['file_sertifikat'] = 'max_size[file_sertifikat,5120]|ext_in[file_sertifikat,pdf,jpg,jpeg,png]';
            }
        } else if ($tipePenyimpanan == 'cloud') {
            $validationRules['file_link'] = 'required|valid_url';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->to('/prestasi-siswa/create')->withInput()->with('validation', $this->validator);
        }

        $namaFileSertifikat = null;
        if ($tipePenyimpanan == 'lokal') {
            $fileSertifikat = $this->request->getFile('file_sertifikat');
            if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
                $namaFileSertifikat = $fileSertifikat->getRandomName();
                $fileSertifikat->move('uploads/sertifikat', $namaFileSertifikat);
            }
        }

        $this->prestasiModel->save([
            'tanggal'         => $this->request->getPost('tanggal'),
            'nama_siswa'      => $this->request->getPost('nama_siswa'),
            'nisn'            => $this->request->getPost('nisn'),
            'jenis_prestasi'  => $this->request->getPost('jenis_prestasi'),
            'tingkat'         => $this->request->getPost('tingkat'),
            'nama_lomba'      => $this->request->getPost('nama_lomba'),
            'peringkat'       => $this->request->getPost('peringkat'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'tipe_penyimpanan' => $tipePenyimpanan,
            'file_sertifikat' => $namaFileSertifikat,
            'file_link'       => $tipePenyimpanan == 'cloud' ? $this->request->getPost('file_link') : null
        ]);

        return redirect()->to('/prestasi-siswa')->with('success', 'Data prestasi siswa berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $prestasi = $this->prestasiModel->find($id);
        if (!$prestasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data prestasi tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Catatan Prestasi Siswa',
            'prestasi' => $prestasi,
            'validation' => session()->get('validation') ?? \Config\Services::validation()
        ];

        return view('prestasi_siswa/edit', $data);
    }

    public function update($id)
    {
        $validationRules = [
            'tanggal' => 'required',
            'nama_siswa' => 'required',
            'nisn' => 'required',
            'jenis_prestasi' => 'required|in_list[Akademik,Non Akademik]',
            'tingkat' => 'required|in_list[Kecamatan,Kabupaten,Provinsi,Nasional,Internasional]',
            'nama_lomba' => 'required',
            'peringkat' => 'required',
        ];

        $tipePenyimpanan = $this->request->getPost('tipe_penyimpanan');
        if ($tipePenyimpanan == 'lokal') {
            $fileSertifikat = $this->request->getFile('file_sertifikat');
            if ($fileSertifikat && $fileSertifikat->isValid()) {
                $validationRules['file_sertifikat'] = 'max_size[file_sertifikat,5120]|ext_in[file_sertifikat,pdf,jpg,jpeg,png]';
            }
        } else if ($tipePenyimpanan == 'cloud') {
            $validationRules['file_link'] = 'required|valid_url';
        }

        if (!$this->validate($validationRules)) {
            return redirect()->to('/prestasi-siswa/edit/' . $id)->withInput()->with('validation', $this->validator);
        }

        $prestasiLama = $this->prestasiModel->find($id);
        if (!$prestasiLama) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $namaFileSertifikat = $prestasiLama['file_sertifikat'];

        if ($tipePenyimpanan == 'lokal') {
            $fileSertifikat = $this->request->getFile('file_sertifikat');
            if ($fileSertifikat && $fileSertifikat->isValid() && !$fileSertifikat->hasMoved()) {
                $namaFileSertifikat = $fileSertifikat->getRandomName();
                $fileSertifikat->move('uploads/sertifikat', $namaFileSertifikat);

                // Hapus file lama jika ada
                if ($prestasiLama['file_sertifikat'] && file_exists('uploads/sertifikat/' . $prestasiLama['file_sertifikat'])) {
                    unlink('uploads/sertifikat/' . $prestasiLama['file_sertifikat']);
                }
            }
        } else {
            // Jika pindah ke cloud, opsional: bisa hapus file lama di db/storage
            $namaFileSertifikat = null;
        }

        $this->prestasiModel->update($id, [
            'tanggal'         => $this->request->getPost('tanggal'),
            'nama_siswa'      => $this->request->getPost('nama_siswa'),
            'nisn'            => $this->request->getPost('nisn'),
            'jenis_prestasi'  => $this->request->getPost('jenis_prestasi'),
            'tingkat'         => $this->request->getPost('tingkat'),
            'nama_lomba'      => $this->request->getPost('nama_lomba'),
            'peringkat'       => $this->request->getPost('peringkat'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'tipe_penyimpanan' => $tipePenyimpanan,
            'file_sertifikat' => $namaFileSertifikat,
            'file_link'       => $tipePenyimpanan == 'cloud' ? $this->request->getPost('file_link') : null
        ]);

        return redirect()->to('/prestasi-siswa')->with('success', 'Data prestasi siswa berhasil diubah.');
    }

    public function delete($id)
    {
        $prestasi = $this->prestasiModel->find($id);
        if ($prestasi) {
            // Hapus file PDF fisik jika ada
            if ($prestasi['file_sertifikat'] && file_exists('uploads/sertifikat/' . $prestasi['file_sertifikat'])) {
                unlink('uploads/sertifikat/' . $prestasi['file_sertifikat']);
            }
            $this->prestasiModel->delete($id);
        }

        return redirect()->to('/prestasi-siswa')->with('success', 'Data prestasi siswa berhasil dihapus.');
    }

    public function import()
    {
        $data = [
            'title' => 'Import Data Prestasi Siswa'
        ];
        return view('prestasi_siswa/import', $data);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Tanggal (YYYY-MM-DD)');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'NISN');
        $sheet->setCellValue('D1', 'Jenis Prestasi');
        $sheet->setCellValue('E1', 'Tingkat');
        $sheet->setCellValue('F1', 'Nama Lomba');
        $sheet->setCellValue('G1', 'Peringkat');
        $sheet->setCellValue('H1', 'Keterangan (Opsional)');

        // Format Header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EFEFEF']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Auto size columns
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Add note/validation hint
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->setActiveSheetIndex(1);
        $sheet2->setTitle('Panduan Pengisian');
        $sheet2->setCellValue('A1', 'Kolom');
        $sheet2->setCellValue('B1', 'Keterangan / Aturan Validasi');
        $sheet2->setCellValue('A2', 'Tanggal');
        $sheet2->setCellValue('B2', 'Wajib isi. Format Tanggal Standar ex: 2024-12-30');
        $sheet2->setCellValue('A3', 'Nama Siswa');
        $sheet2->setCellValue('B3', 'Wajib isi.');
        $sheet2->setCellValue('A4', 'NISN');
        $sheet2->setCellValue('B4', 'Wajib isi.');
        $sheet2->setCellValue('A5', 'Jenis Prestasi');
        $sheet2->setCellValue('B5', 'Wajib diisi "Akademik" atau "Non Akademik".');
        $sheet2->setCellValue('A6', 'Tingkat');
        $sheet2->setCellValue('B6', 'Wajib diisi antara: Kecamatan, Kabupaten, Provinsi, Nasional, Internasional.');
        $sheet2->setCellValue('A7', 'Nama Lomba');
        $sheet2->setCellValue('B7', 'Wajib isi. Contoh: Olimpiade Sains Matematika');
        $sheet2->setCellValue('A8', 'Peringkat');
        $sheet2->setCellValue('B8', 'Wajib isi. Contoh: Juara 1');

        foreach (range('A', 'B') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }
        $spreadsheet->setActiveSheetIndex(0); // Kembali ke sheet 1

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Prestasi_Siswa.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }

    public function preview()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Silakan pilih file excel terlebih dahulu.');
        }

        $extension = $file->getExtension();
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Format file tidak didukung. Gunakan .xls atau .xlsx');
        }

        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $spreadsheet = $reader->load($file->getTempName());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $importData = [];
            // Skip baris pertama (header)
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                // Lewati baris yang benar-benar kosong
                if (empty(array_filter($row))) {
                    continue;
                }

                // Handle Date formats from Excel if needed (often comes as days since 1900 or formatted string)
                $tanggal = rtrim(trim($row[0] ?? ''));
                if (is_numeric($tanggal)) {
                    $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
                }

                $importData[] = [
                    'tanggal' => $tanggal,
                    'nama_siswa' => rtrim(trim($row[1] ?? '')),
                    'nisn' => rtrim(trim($row[2] ?? '')),
                    'jenis_prestasi' => rtrim(trim($row[3] ?? '')),
                    'tingkat' => rtrim(trim($row[4] ?? '')),
                    'nama_lomba' => rtrim(trim($row[5] ?? '')),
                    'peringkat' => rtrim(trim($row[6] ?? '')),
                    'keterangan' => rtrim(trim($row[7] ?? '')),
                ];
            }

            if (empty($importData)) {
                return redirect()->to('/prestasi-siswa/import')->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            $data = [
                'title' => 'Preview Import Prestasi Siswa',
                'importData' => $importData
            ];

            return view('prestasi_siswa/preview', $data);
        } catch (\Exception $e) {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Gagal membaca file Excel. Pastikan format sesuai template. Error: ' . $e->getMessage());
        }
    }

    public function storeImport()
    {
        $base64Data = $this->request->getPost('import_data');
        if (!$base64Data) {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Data import tidak ditemukan.');
        }

        $importData = json_decode(base64_decode($base64Data), true);
        if (!is_array($importData)) {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Format data tidak valid.');
        }

        $validRows = 0;

        $db = \Config\Database::connect();
        $db->transStart();

        foreach ($importData as $row) {
            // Validasi ulang di sisi server sebelum insert
            if (empty($row['tanggal']) || empty($row['nama_siswa']) || empty($row['nisn']) || empty($row['jenis_prestasi']) || empty($row['tingkat']) || empty($row['nama_lomba']) || empty($row['peringkat'])) {
                continue; // Skip the row
            }
            if (!in_array($row['jenis_prestasi'], ['Akademik', 'Non Akademik'])) {
                continue;
            }
            if (!in_array($row['tingkat'], ['Kecamatan', 'Kabupaten', 'Provinsi', 'Nasional', 'Internasional'])) {
                continue;
            }

            $this->prestasiModel->insert([
                'tanggal' => $row['tanggal'],
                'nama_siswa' => $row['nama_siswa'],
                'nisn' => $row['nisn'],
                'jenis_prestasi' => $row['jenis_prestasi'],
                'tingkat' => $row['tingkat'],
                'nama_lomba' => $row['nama_lomba'],
                'peringkat' => $row['peringkat'],
                'keterangan' => $row['keterangan'] ?? null,
                'tipe_penyimpanan' => 'lokal', // Defaulting to lokal since no file uploaded in mass import
                'file_sertifikat' => null,
                'file_link' => null
            ]);

            $validRows++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }

        if ($validRows > 0) {
            return redirect()->to('/prestasi-siswa')->with('success', "$validRows data prestasi siswa berhasil diimport.");
        } else {
            return redirect()->to('/prestasi-siswa/import')->with('error', 'Tidak ada data valid yang bisa diimport. Periksa kembali file Excel Anda.');
        }
    }

    public function exportExcel()
    {
        $records = $this->prestasiModel->orderBy('tanggal', 'DESC')->findAll();

        $headers = ['Tanggal', 'Nama Siswa', 'NISN', 'Jenis Prestasi', 'Tingkat', 'Nama Lomba', 'Peringkat', 'Keterangan'];
        $data = [];
        foreach ($records as $item) {
            $data[] = [
                $item['tanggal'],
                $item['nama_siswa'],
                $item['nisn'],
                $item['jenis_prestasi'],
                $item['tingkat'],
                $item['nama_lomba'] ?? '-',
                $item['peringkat'] ?? '-',
                $item['keterangan'] ?? '-'
            ];
        }

        $exportService = new \App\Services\ExportService();
        $exportService->exportExcel($headers, $data, 'Laporan_Prestasi_Siswa_' . date('Y-m-d'), 'REKAPITULASI PRESTASI SISWA');
    }

    public function exportPdf()
    {
        $data['prestasi'] = $this->prestasiModel->orderBy('tanggal', 'DESC')->findAll();

        $html = view('prestasi_siswa/print_pdf', $data);

        $exportService = new \App\Services\ExportService();
        $exportService->exportPdf($html, 'Laporan_Prestasi_Siswa_' . date('Ymd'));
    }
}
