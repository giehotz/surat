<?php

namespace App\Controllers;

use App\Models\SuratMasukModel;
use App\Models\LogAktivitasModel;
use App\Models\PengaturanModel;
use App\Services\SuratService;
use App\Services\ImportService;
use App\Services\ExportService;
use CodeIgniter\HTTP\ResponseInterface;

class SuratMasuk extends BaseController
{
    protected SuratMasukModel $suratMasukModel;
    protected SuratService $suratService;
    protected ImportService $importService;
    protected ExportService $exportService;

    public function __construct()
    {
        $this->suratMasukModel = new SuratMasukModel();
        $this->suratService    = new SuratService();
        $this->importService   = new ImportService();
        $this->exportService   = new ExportService();
    }

    public function index()
    {
        $data['search'] = $this->request->getGet('search');
        return view('surat_masuk/index', $data);
    }

    public function ajaxList(): ResponseInterface
    {
        $request = \Config\Services::request();

        $start       = (int) ($request->getPost('start') ?? 0);
        $length      = (int) ($request->getPost('length') ?? 10);
        $search      = $request->getPost('search')['value'] ?? '';
        $orderInfo   = $request->getPost('order')[0] ?? null;
        $columnIndex = $orderInfo['column'] ?? 0;
        $orderDir    = $orderInfo['dir'] ?? 'desc';

        $columns = [
            0 => 'surat_masuk.id',
            1 => 'surat_masuk.nomor_agenda',
            2 => 'surat_masuk.pengirim',
            3 => 'surat_masuk.tanggal_terima',
            4 => 'surat_masuk.perihal',
            5 => 'surat_masuk.status',
            6 => 'creator.nama_lengkap',
            7 => 'updater.nama_lengkap',
            8 => 'surat_masuk.id'
        ];
        $orderColumn = $columns[$columnIndex] ?? 'surat_masuk.created_at';

        $builder = $this->suratMasukModel->select('surat_masuk.*, creator.nama_lengkap as pembuat, updater.nama_lengkap as pengupdate')
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
        $filterEndDate   = $request->getPost('end_date');
        $filterStatus    = $request->getPost('status');

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
        $pengaturanModel = new PengaturanModel();
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

        foreach ($data as $sm) {
            $row = [];
            $row[] = $no++;

            $fileUrl = '';
            if ($sm['tipe_penyimpanan'] === 'cloud' && !empty($sm['file_link'])) {
                $fileUrl = $sm['file_link'];
            } elseif ($sm['tipe_penyimpanan'] === 'lokal' && !empty($sm['file_path'])) {
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
            $row[] = '<div class="text-truncate" style="max-width: 250px;" title="' . esc($sm['perihal']) . '">' . esc($sm['perihal']) . '</div>';

            $statusBadge = match ($sm['status']) {
                'tercatat'      => '<span class="badge bg-blue text-blue-fg">Tercatat</span>',
                'didisposisikan' => '<span class="badge bg-warning text-warning-fg">Didisposisikan</span>',
                'selesai'       => '<span class="badge bg-success text-success-fg">Selesai</span>',
                default         => '<span class="badge bg-secondary text-secondary-fg">' . esc($sm['status']) . '</span>',
            };
            $row[] = $statusBadge;

            $row[] = esc($sm['pembuat'] ?? '-');
            $row[] = esc($sm['pengupdate'] ?? '-');

            $btn = '<div class="btn-list flex-nowrap justify-content-center">';
            $btn .= '<a href="' . base_url('surat-masuk/show/' . $sm['id']) . '" class="btn btn-icon btn-sm btn-outline-info" title="Detail"><i class="ti ti-eye"></i></a>';
            if ($role !== 'pimpinan') {
                $btn .= '<a href="' . base_url('disposisi/create/' . $sm['id']) . '" class="btn btn-icon btn-sm btn-outline-warning" title="Disposisi"><i class="ti ti-share"></i></a>';
                $btn .= '<a href="' . base_url('surat-masuk/edit/' . $sm['id']) . '" class="btn btn-icon btn-sm btn-outline-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                $btn .= '<form action="' . base_url('surat-masuk/delete/' . $sm['id']) . '" method="post" style="display:inline;">' . csrf_field() . '<button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"><i class="ti ti-trash"></i></button></form>';
            }
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

    public function create()
    {
        return view('surat_masuk/create');
    }

    public function store()
    {
        $nomorSurat = $this->request->getPost('nomor_surat');
        $perihal    = $this->request->getPost('perihal');

        // Cek duplikasi nomor surat atau perihal
        $existingSurat = $this->suratMasukModel->groupStart()
            ->where('nomor_surat', $nomorSurat)
            ->orWhere('perihal', $perihal)
            ->groupEnd()
            ->first();

        if ($existingSurat) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor atau isi surat sudah ada!');
        }

        $tanggalSurat = $this->request->getPost('tanggal_surat');
        $tahunSurat   = date('Y', strtotime($tanggalSurat));
        $nomorAgenda  = 'IN-' . $tahunSurat . '-TEMP-' . time();

        $tipePenyimpanan = $this->request->getPost('tipe_penyimpanan');
        $fileInfo = null;
        $fileLink = null;

        if ($tipePenyimpanan === 'lokal') {
            $file = $this->request->getFile('file_surat');
            $fileInfo = $this->suratService->handleFileUpload($file, 'surat_masuk');
        } elseif ($tipePenyimpanan === 'cloud') {
            $fileLink = $this->request->getPost('file_link');
        }

        $data = [
            'nomor_agenda'     => $nomorAgenda,
            'nomor_surat'      => $nomorSurat,
            'tanggal_surat'    => $tanggalSurat,
            'tanggal_terima'   => $this->request->getPost('tanggal_terima'),
            'pengirim'         => $this->request->getPost('pengirim'),
            'perihal'          => $perihal,
            'tipe_penyimpanan' => $tipePenyimpanan,
            'lampiran'         => $this->request->getPost('lampiran') ?? 0,
            'keterangan'       => $this->request->getPost('keterangan'),
            'file_name'        => $fileInfo['file_name'] ?? null,
            'file_path'        => $fileInfo['file_path'] ?? null,
            'file_size'        => $fileInfo['file_size'] ?? null,
            'file_link'        => $fileLink,
            'status'           => 'tercatat',
            'created_by'       => session()->get('user_id'),
        ];

        $this->suratMasukModel->insert($data);
        $insertId = $this->suratMasukModel->getInsertID();

        // Reassign nomor_agenda berdasarkan urutan kronologis tanggal_surat
        $this->suratMasukModel->reassignNomorAgenda($tahunSurat);

        // Catat di log_aktivitas
        $logModel = new LogAktivitasModel();
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
        $data['surat'] = $this->suratMasukModel->find($id);

        if (empty($data['surat'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Masuk tidak ditemukan.');
        }

        return view('surat_masuk/detail', $data);
    }

    public function edit($id = null)
    {
        $data['surat'] = $this->suratMasukModel->find($id);
        $data['id']    = $id;

        if (empty($data['surat'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Masuk tidak ditemukan.');
        }

        return view('surat_masuk/edit', $data);
    }

    public function update($id = null)
    {
        $surat = $this->suratMasukModel->find($id);

        if (empty($surat)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Masuk tidak ditemukan.');
        }

        $nomorSurat = $this->request->getPost('nomor_surat');
        $perihal    = $this->request->getPost('perihal');

        // Cek duplikasi (kecuali record saat ini)
        $existingSurat = $this->suratMasukModel->groupStart()
            ->where('nomor_surat', $nomorSurat)
            ->orWhere('perihal', $perihal)
            ->groupEnd()
            ->where('id !=', $id)
            ->first();

        if ($existingSurat) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor atau isi surat sudah ada!');
        }

        $tipePenyimpanan = $this->request->getPost('tipe_penyimpanan');
        $fileName = $surat['file_name'];
        $filePath = $surat['file_path'];
        $fileSize = $surat['file_size'];
        $fileLink = $surat['file_link'];

        if ($tipePenyimpanan === 'lokal') {
            $file = $this->request->getFile('file_surat');
            $uploaded = $this->suratService->handleFileUpload($file, 'surat_masuk', $filePath);
            if ($uploaded) {
                $fileName = $uploaded['file_name'];
                $filePath = $uploaded['file_path'];
                $fileSize = $uploaded['file_size'];
                $fileLink = null;
            }
        } elseif ($tipePenyimpanan === 'cloud') {
            $fileLink = $this->request->getPost('file_link');
        }

        $data = [
            'nomor_surat'      => $nomorSurat,
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_terima'   => $this->request->getPost('tanggal_terima'),
            'pengirim'         => $this->request->getPost('pengirim'),
            'perihal'          => $perihal,
            'tipe_penyimpanan' => $tipePenyimpanan,
            'lampiran'         => $this->request->getPost('lampiran') ?? 0,
            'keterangan'       => $this->request->getPost('keterangan'),
            'file_name'        => $fileName,
            'file_path'        => $filePath,
            'file_size'        => $fileSize,
            'file_link'        => $fileLink,
            'updated_by'       => session()->get('user_id'),
        ];

        $this->suratMasukModel->update($id, $data);

        // Reassign nomor_agenda jika tanggal_surat berubah
        $tanggalSuratBaru = $this->request->getPost('tanggal_surat');
        $tanggalSuratLama = $surat['tanggal_surat'];
        $tahunBaru = date('Y', strtotime($tanggalSuratBaru));
        $tahunLama = date('Y', strtotime($tanggalSuratLama));

        if ($tanggalSuratBaru !== $tanggalSuratLama) {
            $this->suratMasukModel->reassignNomorAgenda($tahunBaru);
            if ($tahunBaru !== $tahunLama) {
                $this->suratMasukModel->reassignNomorAgenda($tahunLama);
            }
        }

        $logModel = new LogAktivitasModel();
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
        $surat = $this->suratMasukModel->find($id);

        if ($surat) {
            $tahunSurat = date('Y', strtotime($surat['tanggal_surat']));
            $this->suratMasukModel->delete($id);
            $this->suratMasukModel->reassignNomorAgenda($tahunSurat);

            $logModel = new LogAktivitasModel();
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

    public function reassignAgenda(): ResponseInterface
    {
        if (session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda tidak memiliki akses untuk fitur ini.']);
        }

        try {
            $this->suratMasukModel->reassignNomorAgenda();
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Nomor agenda berhasil diperbarui berdasarkan urutan kronologis tanggal surat.'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memperbarui nomor agenda: ' . $e->getMessage()
            ]);
        }
    }

    public function exportExcel(): void
    {
        $pengaturanModel = new PengaturanModel();
        $settings = $pengaturanModel->getSettings();
        $tahunAnggaran = $settings['tahun_anggaran'] ?? '';

        $builder = $this->suratMasukModel->select('tanggal_terima, nomor_surat, tanggal_surat, pengirim, perihal');
        if (!empty($tahunAnggaran)) {
            $builder->where('YEAR(tanggal_terima)', $tahunAnggaran);
        }

        $records = $builder->orderBy('tanggal_terima', 'ASC')->get()->getResultArray();
        $headers = ['Tgl Diterima', 'No Surat', 'Tgl Surat', 'Pengirim', 'Perihal'];
        $data = [];

        foreach ($records as $surat) {
            $data[] = [
                $surat['tanggal_terima'],
                $surat['nomor_surat'] ?? '-',
                $surat['tanggal_surat'],
                $surat['pengirim'],
                $surat['perihal']
            ];
        }

        $this->exportService->exportExcel($headers, $data, 'Laporan_Surat_Masuk_' . date('Y-m-d'), 'REKAPITULASI SURAT MASUK');
    }

    public function exportPdf(): void
    {
        $pengaturanModel = new PengaturanModel();
        $settings = $pengaturanModel->getSettings();
        $tahunAnggaran = $settings['tahun_anggaran'] ?? '';

        $builder = $this->suratMasukModel->builder();
        if (!empty($tahunAnggaran)) {
            $builder->where('YEAR(tanggal_terima)', $tahunAnggaran);
        }

        $data['appSettings'] = $settings;
        $data['surat_masuk'] = $builder->orderBy('tanggal_terima', 'ASC')->get()->getResultArray();
        $html = view('surat_masuk/print_pdf', $data);
        $this->exportService->exportPdf($html, 'Laporan_Surat_Masuk_' . date('Ymd'));
    }

    public function import()
    {
        return view('surat_masuk/import', ['title' => 'Import Data Surat Masuk']);
    }

    public function downloadTemplate(): void
    {
        $this->importService->downloadTemplateSuratMasuk();
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
            $importData = $this->importService->parseExcelSuratMasuk($file);

            if (empty($importData)) {
                return redirect()->to('/surat-masuk/import')->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            return view('surat_masuk/preview', [
                'title'      => 'Preview Import Surat Masuk',
                'importData' => $importData
            ]);
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

        $userId    = (int) session()->get('user_id');
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();

        $result = $this->importService->importSuratMasuk($importData, $userId, $ipAddress, $userAgent);

        if ($result['success']) {
            return redirect()->to('/surat-masuk')->with('success', "{$result['count']} surat masuk berhasil diimport.");
        }

        return redirect()->to('/surat-masuk/import')->with('error', 'Tidak ada data valid yang bisa diimport atau terjadi kesalahan sistem.');
    }
}
