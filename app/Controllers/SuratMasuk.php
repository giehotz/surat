<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SuratMasuk extends BaseController
{
    public function index()
    {
        $data['search'] = $this->request->getGet('search');
        return view('surat_masuk/index', $data);
    }

    public function ajaxList()
    {
        $request = \Config\Services::request();
        $suratMasukModel = new \App\Models\SuratMasukModel();

        $start = (int) ($request->getPost('start') ?? 0);
        $length = (int) ($request->getPost('length') ?? 10);
        $search = $request->getPost('search')['value'] ?? '';
        $orderInfo = $request->getPost('order')[0] ?? null;
        $columnIndex = $orderInfo['column'] ?? 0;
        $orderDir = $orderInfo['dir'] ?? 'desc';

        $columns = [
            0 => 'surat_masuk.id',
            1 => 'surat_masuk.nomor_agenda',
            2 => 'surat_masuk.pengirim',
            3 => 'surat_masuk.tanggal_terima',
            4 => 'surat_masuk.perihal',
            5 => 'surat_masuk.status',
            6 => 'creator.nama_lengkap',
            7 => 'updater.nama_lengkap',
            8 => 'surat_masuk.id' // Action column
        ];
        $orderColumn = $columns[$columnIndex] ?? 'surat_masuk.created_at';

        $builder = $suratMasukModel->select('surat_masuk.*, creator.nama_lengkap as pembuat, updater.nama_lengkap as pengupdate')
            ->join('users as creator', 'creator.id = surat_masuk.created_by', 'left')
            ->join('users as updater', 'updater.id = surat_masuk.updated_by', 'left');

        $totalRecords = $builder->countAllResults(false);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('surat_masuk.nomor_surat', $search)
                ->orLike('surat_masuk.nomor_agenda', $search)
                ->orLike('surat_masuk.pengirim', $search)
                ->orLike('surat_masuk.perihal', $search)
                ->groupEnd();
        }

        $filterStartDate = $request->getPost('start_date');
        $filterEndDate = $request->getPost('end_date');
        $filterStatus = $request->getPost('status');

        if (!empty($filterStartDate)) {
            $builder->where('surat_masuk.tanggal_terima >=', $filterStartDate);
        }
        if (!empty($filterEndDate)) {
            $builder->where('surat_masuk.tanggal_terima <=', $filterEndDate);
        }
        if (!empty($filterStatus)) {
            $builder->where('surat_masuk.status', $filterStatus);
        }

        // Filter Tahun Anggaran jika diset
        $pengaturanModel = new \App\Models\PengaturanModel();
        $settings = $pengaturanModel->getSettings();
        $tahunAnggaran = $settings['tahun_anggaran'] ?? '';
        if (!empty($tahunAnggaran)) {
            $builder->where('YEAR(surat_masuk.tanggal_terima)', $tahunAnggaran);
        }

        $filteredRecords = $builder->countAllResults(false);

        $builder->orderBy($orderColumn, $orderDir);
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $data = $builder->get()->getResultArray();

        $responseData = [];
        $no = $start + 1;
        $role = session()->get('role');

        // Use external helper if needed, we already loaded tanggal helper in BaseController
        foreach ($data as $sm) {
            $row = [];
            $row[] = $no++;

            $fileUrl = '';
            if ($sm['tipe_penyimpanan'] == 'cloud' && !empty($sm['file_link'])) {
                $fileUrl = $sm['file_link'];
            } elseif ($sm['tipe_penyimpanan'] == 'lokal' && !empty($sm['file_path'])) {
                $fileUrl = base_url($sm['file_path']);
            }

            if (!empty($fileUrl)) {
                $col1 = '<a href="javascript:void(0)" onclick="previewDokumen(\'' . esc($fileUrl) . '\')" class="text-nowrap text-decoration-none" title="Lihat Preview Dokumen"><div class="font-weight-bold text-primary">' . esc($sm['nomor_agenda']) . ' <i class="ti ti-external-link icon-sm"></i></div><div class="text-secondary">' . esc($sm['nomor_surat']) . '</div></a>';
            } else {
                $col1 = '<div class="text-nowrap"><div class="font-weight-bold">' . esc($sm['nomor_agenda']) . '</div><div class="text-secondary">' . esc($sm['nomor_surat']) . '</div></div>';
            }
            $row[] = $col1;

            $row[] = esc($sm['pengirim']);
            $row[] = function_exists('format_tanggal_indo') ? format_tanggal_indo($sm['tanggal_terima']) : date('d M Y', strtotime($sm['tanggal_terima']));

            $row[] = '<div class="text-truncate" style="max-width: 200px;" title="' . esc($sm['perihal']) . '">' . esc($sm['perihal']) . '</div>';

            $statusBadge = '';
            if ($sm['status'] == 'tercatat') {
                $statusBadge = '<span class="badge bg-blue text-blue-fg">Tercatat</span>';
            } elseif ($sm['status'] == 'didisposisikan') {
                $statusBadge = '<span class="badge bg-warning text-warning-fg">Didisposisikan</span>';
            } elseif ($sm['status'] == 'selesai') {
                $statusBadge = '<span class="badge bg-success text-success-fg">Selesai</span>';
            } else {
                $statusBadge = '<span class="badge bg-secondary text-secondary-fg">' . esc($sm['status']) . '</span>';
            }
            $row[] = $statusBadge;

            $row[] = esc($sm['pembuat'] ?? '-');
            $row[] = esc($sm['pengupdate'] ?? '-');

            $btn = '<div class="btn-list flex-nowrap justify-content-center">';
            $btn .= '<a href="' . base_url('surat-masuk/show/' . $sm['id']) . '" class="btn btn-outline-info btn-icon" title="Detail"><i class="ti ti-eye icon"></i></a>';
            if ($role !== 'pimpinan') {
                $btn .= '<a href="' . base_url('disposisi/create/' . $sm['id']) . '" class="btn btn-outline-warning btn-icon" title="Disposisi"><i class="ti ti-share icon"></i></a>';
                $btn .= '<a href="' . base_url('surat-masuk/edit/' . $sm['id']) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="ti ti-edit icon"></i></a>';

                // Gunakan form untuk method post delete
                $btn .= '<form action="' . base_url('surat-masuk/delete/' . $sm['id']) . '" method="post" style="display:inline;">' . csrf_field() . '<button type="submit" class="btn btn-outline-danger btn-icon" title="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"><i class="ti ti-trash icon"></i></button></form>';
            }
            $btn .= '</div>';
            $row[] = $btn;

            $responseData[] = $row;
        }

        $result = [
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $responseData,
        ];

        return $this->response->setJSON($result);
    }

    public function create()
    {
        return view('surat_masuk/create');
    }

    public function store()
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();

        $nomorSurat = $this->request->getPost('nomor_surat');
        $perihal = $this->request->getPost('perihal');

        // Cek duplikasi berdasarkan nomor_surat atau perihal
        $existingSurat = $suratMasukModel->groupStart()
                                            ->where('nomor_surat', $nomorSurat)
                                            ->orWhere('perihal', $perihal)
                                         ->groupEnd()
                                         ->first();

        if ($existingSurat) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor atau isi surat sudah ada!');
        }

        // Generate Nomor Agenda (IN-YYYY-001)
        $lastSurat = $suratMasukModel->orderBy('id', 'DESC')->first();
        $lastNumber = 0;
        if ($lastSurat && preg_match('/IN-\d{4}-(\d+)/', $lastSurat['nomor_agenda'], $matches)) {
            $lastNumber = (int)$matches[1];
        }
        $nomor_agenda = 'IN-' . date('Y') . '-' . sprintf('%03d', $lastNumber + 1);

        $tipe_penyimpanan = $this->request->getPost('tipe_penyimpanan');
        $fileName = null;
        $filePath = null;
        $fileSize = null;
        $fileLink = null;

        if ($tipe_penyimpanan == 'lokal') {
            $file = $this->request->getFile('file_surat');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/surat_masuk', $fileName);
                $filePath = 'uploads/surat_masuk/' . $fileName;
                $fileSize = $file->getSize('kb');
            }
        } elseif ($tipe_penyimpanan == 'cloud') {
            $fileLink = $this->request->getPost('file_link');
        }

        $data = [
            'nomor_agenda'     => $nomor_agenda,
            'nomor_surat'      => $this->request->getPost('nomor_surat'),
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_terima'   => $this->request->getPost('tanggal_terima'),
            'pengirim'         => $this->request->getPost('pengirim'),
            'perihal'          => $this->request->getPost('perihal'),
            'tipe_penyimpanan' => $tipe_penyimpanan,
            'lampiran'         => $this->request->getPost('lampiran') ?? 0,
            'keterangan'       => $this->request->getPost('keterangan'),
            'file_name'        => $fileName,
            'file_path'        => $filePath,
            'file_size'        => $fileSize,
            'file_link'        => $fileLink,
            'status'           => 'tercatat', // Default status awal
            'created_by'       => session()->get('user_id'),
        ];

        $suratMasukModel->insert($data);
        $insertId = $suratMasukModel->getInsertID();

        // Catat di log_aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $insertId,
            'aksi'       => 'create',
            'tipe_surat' => 'surat_masuk',
            'detail'     => 'Menambahkan surat masuk ' . $data['nomor_surat'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-masuk')->with('success', 'Surat Masuk berhasil ditambahkan');
    }

    public function show($id = null)
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();
        $data['surat'] = $suratMasukModel->find($id);

        if (empty($data['surat'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Masuk tidak ditemukan.');
        }

        return view('surat_masuk/detail', $data);
    }

    public function edit($id = null)
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();
        $data['surat'] = $suratMasukModel->find($id);
        $data['id'] = $id;

        if (empty($data['surat'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Masuk tidak ditemukan.');
        }

        return view('surat_masuk/edit', $data);
    }

    public function update($id = null)
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();
        $surat = $suratMasukModel->find($id);

        if (empty($surat)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Masuk tidak ditemukan.');
        }

        $nomorSurat = $this->request->getPost('nomor_surat');
        $perihal = $this->request->getPost('perihal');

        // Cek duplikasi berdasarkan nomor_surat atau perihal (kecuali surat ini sendiri)
        $existingSurat = $suratMasukModel->groupStart()
                                            ->where('nomor_surat', $nomorSurat)
                                            ->orWhere('perihal', $perihal)
                                         ->groupEnd()
                                         ->where('id !=', $id)
                                         ->first();

        if ($existingSurat) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor atau isi surat sudah ada!');
        }

        $tipe_penyimpanan = $this->request->getPost('tipe_penyimpanan');
        $fileName = $surat['file_name'];
        $filePath = $surat['file_path'];
        $fileSize = $surat['file_size'];
        $fileLink = $surat['file_link'];

        if ($tipe_penyimpanan == 'lokal') {
            $file = $this->request->getFile('file_surat');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/surat_masuk', $fileName);
                $filePath = 'uploads/surat_masuk/' . $fileName;
                $fileSize = $file->getSize('kb');
                $fileLink = null;
            }
        } elseif ($tipe_penyimpanan == 'cloud') {
            $fileLink = $this->request->getPost('file_link');
            // Opsional: Hapus file fisik lama jika berganti ke cloud, 
            // tapi dibiarkan saja juga tidak apa-apa untuk backup
        }

        $data = [
            'nomor_surat'      => $this->request->getPost('nomor_surat'),
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_terima'   => $this->request->getPost('tanggal_terima'),
            'pengirim'         => $this->request->getPost('pengirim'),
            'perihal'          => $this->request->getPost('perihal'),
            'tipe_penyimpanan' => $tipe_penyimpanan,
            'lampiran'         => $this->request->getPost('lampiran') ?? 0,
            'keterangan'       => $this->request->getPost('keterangan'),
            'file_name'        => $fileName,
            'file_path'        => $filePath,
            'file_size'        => $fileSize,
            'file_link'        => $fileLink,
            'updated_by'       => session()->get('user_id'),
        ];

        $suratMasukModel->update($id, $data);

        // Catat aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $id,
            'aksi'       => 'update',
            'tipe_surat' => 'surat_masuk',
            'detail'     => 'Memperbarui surat masuk ' . $data['nomor_surat'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-masuk')->with('success', 'Surat Masuk berhasil diperbarui');
    }

    public function delete($id = null)
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();
        $surat = $suratMasukModel->find($id);

        if ($surat) {
            $suratMasukModel->delete($id);

            // Jika ada file lokal, hapus fisik file (opsional, tergantung kebijakan)
            // if ($surat['file_path'] && file_exists(FCPATH . $surat['file_path'])) {
            //     unlink(FCPATH . $surat['file_path']);
            // }

            // Catat aktivitas
            $logModel = new \App\Models\LogAktivitasModel();
            $logModel->save([
                'user_id'    => session()->get('user_id'),
                'surat_id'   => $id,
                'aksi'       => 'delete',
                'tipe_surat' => 'surat_masuk',
                'detail'     => 'Menghapus surat masuk ' . $surat['nomor_surat'],
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString()
            ]);
        }

        return redirect()->to('/surat-masuk')->with('success', 'Surat Masuk berhasil dihapus');
    }

    public function exportExcel()
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();
        $records = $suratMasukModel->findAll();

        $headers = ['Tgl Diterima', 'No Surat', 'Tgl Surat', 'Sifat Surat', 'Pengirim', 'Ditujukan Kepada', 'Perihal'];
        $data = [];
        foreach ($records as $surat) {
            $data[] = [
                $surat['tanggal_terima'],
                $surat['nomor_surat'] ?? '-',
                $surat['tanggal_surat'],
                ucfirst($surat['sifat_surat']),
                $surat['pengirim'],
                $surat['ditujukan_kepada'],
                $surat['perihal']
            ];
        }

        $exportService = new \App\Services\ExportService();
        $exportService->exportExcel($headers, $data, 'Laporan_Surat_Masuk_' . date('Y-m-d'), 'REKAPITULASI SURAT MASUK');
    }

    public function exportPdf()
    {
        $suratMasukModel = new \App\Models\SuratMasukModel();
        $data['surat_masuk'] = $suratMasukModel->findAll();

        $html = view('surat_masuk/print_pdf', $data);

        $exportService = new \App\Services\ExportService();
        $exportService->exportPdf($html, 'Laporan_Surat_Masuk_' . date('Ymd'));
    }

    public function import()
    {
        $data = [
            'title' => 'Import Data Surat Masuk'
        ];
        return view('surat_masuk/import', $data);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nomor Surat (dari Pengirim)');
        $sheet->setCellValue('B1', 'Pengirim');
        $sheet->setCellValue('C1', 'Tanggal Surat (YYYY-MM-DD)');
        $sheet->setCellValue('D1', 'Tanggal Terima (YYYY-MM-DD)');
        $sheet->setCellValue('E1', 'Perihal');
        $sheet->setCellValue('F1', 'Jumlah Lampiran');
        $sheet->setCellValue('G1', 'Tipe Penyimpanan (lokal/cloud)');
        $sheet->setCellValue('H1', 'Link Cloud');
        $sheet->setCellValue('I1', 'Keterangan (Opsional)');

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
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Auto size columns
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Add note/validation hint
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->setActiveSheetIndex(1);
        $sheet2->setTitle('Panduan Pengisian');
        $sheet2->setCellValue('A1', 'Kolom');
        $sheet2->setCellValue('B1', 'Keterangan');
        $sheet2->setCellValue('A2', 'Nomor Surat');
        $sheet2->setCellValue('B2', 'Wajib isi.');
        $sheet2->setCellValue('A3', 'Pengirim');
        $sheet2->setCellValue('B3', 'Wajib isi.');
        $sheet2->setCellValue('A4', 'Tanggal Surat');
        $sheet2->setCellValue('B4', 'Wajib isi. Format ex: 2024-12-30');
        $sheet2->setCellValue('A5', 'Tanggal Terima');
        $sheet2->setCellValue('B5', 'Wajib isi. Format ex: 2024-12-31');
        $sheet2->setCellValue('A6', 'Perihal');
        $sheet2->setCellValue('B6', 'Wajib isi.');
        $sheet2->setCellValue('A7', 'Jumlah Lampiran');
        $sheet2->setCellValue('B7', 'Opsional. Angka (0, 1, 2, dll).');
        $sheet2->setCellValue('A8', 'Tipe Penyimpanan');
        $sheet2->setCellValue('B8', 'Wajib diisi "lokal" atau "cloud".');
        $sheet2->setCellValue('A9', 'Link Cloud');
        $sheet2->setCellValue('B9', 'Wajib diisi link (http/https) jika Tipe Penyimpanan adalah "cloud".');

        foreach (range('A', 'B') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Surat_Masuk.xlsx';

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
            return redirect()->to('/surat-masuk/import')->with('error', 'Silakan pilih file excel terlebih dahulu.');
        }

        $extension = $file->getExtension();
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return redirect()->to('/surat-masuk/import')->with('error', 'Format file tidak didukung. Gunakan .xls atau .xlsx');
        }

        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $spreadsheet = $reader->load($file->getTempName());
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $importData = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                if (empty(array_filter($row))) {
                    continue;
                }

                $tanggalSurat = rtrim(trim($row[2] ?? ''));
                if (is_numeric($tanggalSurat)) {
                    $tanggalSurat = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalSurat)->format('Y-m-d');
                }

                $tanggalTerima = rtrim(trim($row[3] ?? ''));
                if (is_numeric($tanggalTerima)) {
                    $tanggalTerima = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalTerima)->format('Y-m-d');
                }

                $importData[] = [
                    'nomor_surat' => rtrim(trim($row[0] ?? '')),
                    'pengirim' => rtrim(trim($row[1] ?? '')),
                    'tanggal_surat' => $tanggalSurat,
                    'tanggal_terima' => $tanggalTerima,
                    'perihal' => rtrim(trim($row[4] ?? '')),
                    'lampiran' => rtrim(trim($row[5] ?? '0')),
                    'tipe_penyimpanan' => rtrim(trim($row[6] ?? 'lokal')),
                    'file_link' => rtrim(trim($row[7] ?? '')),
                    'keterangan' => rtrim(trim($row[8] ?? '')),
                ];
            }

            if (empty($importData)) {
                return redirect()->to('/surat-masuk/import')->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            $data = [
                'title' => 'Preview Import Surat Masuk',
                'importData' => $importData
            ];

            return view('surat_masuk/preview', $data);
        } catch (\Exception $e) {
            return redirect()->to('/surat-masuk/import')->with('error', 'Gagal membaca file Excel. Pastikan format sesuai template. Error: ' . $e->getMessage());
        }
    }

    public function storeImport()
    {
        $base64Data = $this->request->getPost('import_data');
        if (!$base64Data) {
            return redirect()->to('/surat-masuk/import')->with('error', 'Data import tidak ditemukan.');
        }

        $importData = json_decode(base64_decode($base64Data), true);
        if (!is_array($importData)) {
            return redirect()->to('/surat-masuk/import')->with('error', 'Format data tidak valid.');
        }

        $suratMasukModel = new \App\Models\SuratMasukModel();
        $logModel = new \App\Models\LogAktivitasModel();
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $validRows = 0;

        $db->transStart();

        foreach ($importData as $row) {
            if (empty($row['nomor_surat']) || empty($row['pengirim']) || empty($row['tanggal_surat']) || empty($row['tanggal_terima']) || empty($row['perihal'])) {
                continue;
            }

            $metode = strtolower($row['tipe_penyimpanan']);
            if (!in_array($metode, ['lokal', 'cloud'])) {
                continue;
            }

            if ($metode == 'cloud' && empty($row['file_link'])) {
                continue;
            }

            // Generate Nomor Agenda (IN-YYYY-001) for each row
            $lastSurat = $suratMasukModel->orderBy('id', 'DESC')->first();
            $lastNumber = 0;
            if ($lastSurat && preg_match('/IN-\d{4}-(\d+)/', $lastSurat['nomor_agenda'], $matches)) {
                $lastNumber = (int)$matches[1];
            }
            $nomor_agenda = 'IN-' . date('Y') . '-' . sprintf('%03d', $lastNumber + 1);

            $dataInsert = [
                'nomor_agenda'     => $nomor_agenda,
                'nomor_surat'      => $row['nomor_surat'],
                'tanggal_surat'    => $row['tanggal_surat'],
                'tanggal_terima'   => $row['tanggal_terima'],
                'pengirim'         => $row['pengirim'],
                'perihal'          => $row['perihal'],
                'lampiran'         => $row['lampiran'] ?: 0,
                'tipe_penyimpanan' => $metode,
                'file_link'        => $metode == 'cloud' ? $row['file_link'] : null,
                'keterangan'       => $row['keterangan'] ?? null,
                'status'           => 'tercatat', // mass import masuk sebagai tercatat
                'created_by'       => $userId,
            ];

            $suratMasukModel->insert($dataInsert);
            $insertId = $suratMasukModel->getInsertID();

            // Log activity
            $logModel->save([
                'user_id'    => $userId,
                'surat_id'   => $insertId,
                'aksi'       => 'create',
                'tipe_surat' => 'surat_masuk',
                'detail'     => 'Import data surat masuk via excel dari ' . $dataInsert['pengirim'],
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString()
            ]);

            $validRows++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/surat-masuk/import')->with('error', 'Terjadi kesalahan sistem saat menyimpan data Surat Masuk.');
        }

        if ($validRows > 0) {
            return redirect()->to('/surat-masuk')->with('success', "$validRows surat masuk berhasil diimport.");
        } else {
            return redirect()->to('/surat-masuk/import')->with('error', 'Tidak ada data valid yang bisa diimport. Periksa kembali file Excel Anda.');
        }
    }
}
