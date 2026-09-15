<?php

namespace App\Controllers;

use App\Models\SuratKeluarModel;
use App\Models\FormatSuratModel;
use App\Models\PengaturanModel;
use App\Models\WajibFieldPengaturanModel;
use App\Models\LogAktivitasModel;
use App\Services\SuratService;
use App\Services\ImportService;
use App\Services\ExportService;
use CodeIgniter\HTTP\ResponseInterface;

class SuratKeluar extends BaseController
{
    protected SuratKeluarModel $suratKeluarModel;
    protected SuratService $suratService;
    protected ImportService $importService;
    protected ExportService $exportService;

    public function __construct()
    {
        $this->suratKeluarModel = new SuratKeluarModel();
        $this->suratService     = new SuratService();
        $this->importService    = new ImportService();
        $this->exportService    = new ExportService();
    }

    public function index()
    {
        $data['search'] = $this->request->getGet('search');
        return view('surat_keluar/index', $data);
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
            0 => 'surat_keluar.id',
            1 => 'surat_keluar.nomor_agenda',
            2 => 'surat_keluar.tujuan',
            3 => 'surat_keluar.tanggal_surat',
            4 => 'surat_keluar.perihal',
            5 => 'surat_keluar.status',
            6 => 'creator.nama_lengkap',
            7 => 'updater.nama_lengkap',
            8 => 'surat_keluar.id'
        ];
        $orderColumn = $columns[$columnIndex] ?? 'surat_keluar.created_at';

        $builder = $this->suratKeluarModel->select('surat_keluar.*, creator.nama_lengkap as pembuat, updater.nama_lengkap as pengupdate')
            ->join('users as creator', 'creator.id = surat_keluar.created_by', 'left')
            ->join('users as updater', 'updater.id = surat_keluar.updated_by', 'left');

        $totalRecords = $builder->countAllResults(false);

        if (!empty($search)) {
            $builder->groupStart()
                ->like('surat_keluar.nomor_surat', $search)
                ->orLike('surat_keluar.nomor_agenda', $search)
                ->orLike('surat_keluar.tujuan', $search)
                ->orLike('surat_keluar.perihal', $search)
                ->groupEnd();
        }

        $filterStartDate = $request->getPost('start_date');
        $filterEndDate   = $request->getPost('end_date');
        $filterStatus    = $request->getPost('status');

        if (!empty($filterStartDate)) {
            $builder->where('surat_keluar.tanggal_surat >=', $filterStartDate);
        }
        if (!empty($filterEndDate)) {
            $builder->where('surat_keluar.tanggal_surat <=', $filterEndDate);
        }
        if (!empty($filterStatus)) {
            $builder->where('surat_keluar.status', $filterStatus);
        }

        // Filter Tahun Anggaran jika diset
        $pengaturanModel = new PengaturanModel();
        $settings = $pengaturanModel->getSettings();
        $tahunAnggaran = $settings['tahun_anggaran'] ?? '';
        if (!empty($tahunAnggaran)) {
            $builder->where('YEAR(surat_keluar.tanggal_surat)', $tahunAnggaran);
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
        $isApprover = ($role === 'pimpinan' || $role === 'admin');

        foreach ($data as $sk) {
            $row = [];

            $checkbox = '<input type="checkbox" class="form-check-input row-checkbox" data-id="' . $sk['id'] . '">';
            $row[] = $isApprover ? $checkbox : '';
            $row[] = $no++;

            $nomorSurat = esc($sk['nomor_surat']) ?: '<i class="text-muted">Draft</i>';

            $fileUrl = '';
            if ($sk['tipe_penyimpanan'] === 'cloud' && !empty($sk['file_link'])) {
                $fileUrl = $sk['file_link'];
            } elseif ($sk['tipe_penyimpanan'] === 'lokal' && !empty($sk['file_path'])) {
                $fileUrl = base_url($sk['file_path']);
            }

            if (!empty($fileUrl)) {
                $col1 = '<a href="javascript:void(0)" onclick="previewDokumen(\'' . esc($fileUrl) . '\')" class="text-nowrap text-decoration-none" title="Lihat Preview Dokumen"><div class="font-weight-bold text-primary">' . esc($sk['nomor_agenda']) . ' <i class="ti ti-external-link icon-sm"></i></div><div class="text-secondary">' . $nomorSurat . '</div></a>';
            } else {
                $col1 = '<div class="text-nowrap"><div class="font-weight-bold">' . esc($sk['nomor_agenda']) . '</div><div class="text-secondary">' . $nomorSurat . '</div></div>';
            }
            $row[] = $col1;

            $row[] = '<div class="text-truncate" style="max-width: 200px;" title="' . esc($sk['tujuan']) . '">' . esc($sk['tujuan']) . '</div>';
            $row[] = function_exists('format_tanggal_indo') ? format_tanggal_indo($sk['tanggal_surat']) : date('d M Y', strtotime($sk['tanggal_surat']));
            $row[] = '<div class="text-truncate" style="max-width: 200px;" title="' . esc($sk['perihal']) . '">' . esc($sk['perihal']) . '</div>';

            $statusBadge = match ($sk['status']) {
                'draft'     => '<span class="badge bg-secondary text-secondary-fg">Draft</span>',
                'disetujui' => '<span class="badge bg-success text-success-fg">Disetujui</span>',
                'ditolak'   => '<span class="badge bg-danger text-danger-fg">Ditolak</span>',
                default     => '<span class="badge bg-warning text-warning-fg">' . esc($sk['status']) . '</span>',
            };
            $row[] = $statusBadge;

            $row[] = esc($sk['pembuat'] ?? '-');
            $row[] = esc($sk['pengupdate'] ?? '-');

            $btn = '<div class="btn-list flex-nowrap justify-content-center">';
            $btn .= '<a href="' . base_url('surat-keluar/show/' . $sk['id']) . '" class="btn btn-icon btn-sm btn-outline-info" title="Detail"><i class="ti ti-eye"></i></a>';
            if ($role !== 'pimpinan') {
                $btn .= '<a href="' . base_url('surat-keluar/edit/' . $sk['id']) . '" class="btn btn-icon btn-sm btn-outline-primary" title="Edit"><i class="ti ti-edit"></i></a>';
                $btn .= '<form action="' . base_url('surat-keluar/delete/' . $sk['id']) . '" method="post" style="display:inline;">' . csrf_field() . '<button type="submit" class="btn btn-icon btn-sm btn-outline-danger" title="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"><i class="ti ti-trash"></i></button></form>';
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
        $wajibFieldModel = new WajibFieldPengaturanModel();
        $pengaturanModel = new PengaturanModel();
        $settings        = $pengaturanModel->getSettings();

        $allowedMethods = isset($settings['metode_lampiran']) && $settings['metode_lampiran'] !== ''
            ? explode(',', $settings['metode_lampiran'])
            : ['upload', 'link'];

        $defaultMethod = $allowedMethods[0] ?? 'upload';

        $lastSurat = $this->suratKeluarModel->where('nomor_surat !=', '')
            ->where('nomor_surat IS NOT NULL', null, false)
            ->orderBy('id', 'DESC')
            ->first();

        $data = [
            'required_fields'           => $wajibFieldModel->getRequiredFields('surat_keluar'),
            'allowedMethods'            => $allowedMethods,
            'defaultMethod'             => $defaultMethod,
            'latest_nomor_surat_keluar' => $lastSurat ? $lastSurat['nomor_surat'] : '-',
            'tahun_anggaran'            => $settings['tahun_anggaran'] ?? date('Y'),
            'bulan_list'                => $this->suratService->getBulanList(),
            'format_surat_list'         => (new FormatSuratModel())->findAll(),
        ];

        return view('surat_keluar/create', $data);
    }

    public function store()
    {
        $pengaturanModel = new PengaturanModel();
        $settings        = $pengaturanModel->getSettings();

        $nomorUrut     = $this->request->getPost('nomor_urut');
        $bulan         = $this->request->getPost('bulan');
        $formatSuratId = $this->request->getPost('format_surat_id');
        $perihal       = $this->request->getPost('perihal');

        $nomorSurat = '';
        if (!empty($nomorUrut) && !empty($bulan) && !empty($formatSuratId)) {
            $formatModel = new FormatSuratModel();
            $format = $formatModel->find($formatSuratId);
            $template = $format['template'] ?? '{nomor}/{bulan}/{tahun}';
            $tahun = $settings['tahun_anggaran'] ?? date('Y');
            $nomorSurat = $this->suratService->generateNomorSurat($template, $nomorUrut, $bulan, $tahun);
        }

        // Cek duplikasi: nomor_surat harus unik; jika draft, perihal harus unik
        $query = $this->suratKeluarModel->groupStart();
        if (!empty($nomorSurat)) {
            $query->where('nomor_surat', $nomorSurat);
        } else {
            $query->where('perihal', $perihal);
        }
        $existingSurat = $query->groupEnd()->first();

        if ($existingSurat) {
            $msg = !empty($nomorSurat)
                ? 'Nomor surat "' . esc($nomorSurat) . '" sudah digunakan.'
                : 'Draft dengan perihal "' . esc($perihal) . '" sudah ada.';
            return redirect()->back()->withInput()->with('error', $msg);
        }

        $validationRules = $this->suratKeluarModel->getValidationRulesFromPengaturan();
        $tipePenyimpanan = $this->request->getPost('tipe_penyimpanan');

        if ($tipePenyimpanan === 'lokal') {
            $wajibFieldModel = new WajibFieldPengaturanModel();
            if (in_array('file_konsep', $wajibFieldModel->getRequiredFields('surat_keluar'))) {
                $validationRules['file_konsep'] = 'uploaded[file_konsep]|max_size[file_konsep,5120]|ext_in[file_konsep,pdf,doc,docx]';
            }
        } elseif ($tipePenyimpanan === 'cloud') {
            if (!empty($this->request->getPost('file_link'))) {
                $validationRules['file_link'] = 'required|valid_url';
            }
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali inputan Anda.')->with('validation', $this->validator);
        }

        $tahunAgenda  = date('Y');
        $nomorAgenda  = $this->suratService->generateNextNomorAgendaKeluar($tahunAgenda);

        $fileInfo = null;
        $fileLink = null;

        if ($tipePenyimpanan === 'lokal') {
            $file = $this->request->getFile('file_konsep');
            $fileInfo = $this->suratService->handleFileUpload($file, 'surat_keluar');
        } elseif ($tipePenyimpanan === 'cloud') {
            $fileLink = $this->request->getPost('file_link');
        }

        $data = [
            'nomor_agenda'     => $nomorAgenda,
            'nomor_surat'      => $nomorSurat,
            'nomor_urut'       => $nomorUrut ?: null,
            'bulan'            => $bulan ?: null,
            'format_surat_id'  => $formatSuratId ?: null,
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_kirim'    => $this->request->getPost('tanggal_kirim'),
            'tujuan'           => $this->request->getPost('tujuan'),
            'perihal'          => $perihal,
            'tipe_penyimpanan' => $tipePenyimpanan,
            'lampiran'         => $this->request->getPost('lampiran') ?? 0,
            'keterangan'       => $this->request->getPost('keterangan'),
            'file_name'        => $fileInfo['file_name'] ?? null,
            'file_path'        => $fileInfo['file_path'] ?? null,
            'file_size'        => $fileInfo['file_size'] ?? null,
            'file_link'        => $fileLink,
            'status'           => 'draft',
            'created_by'       => session()->get('user_id'),
        ];

        $this->suratKeluarModel->insert($data);
        $insertId = $this->suratKeluarModel->getInsertID();

        $logModel = new LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $insertId,
            'aksi'       => 'create',
            'tipe_surat' => 'surat_keluar',
            'detail'     => 'Membuat draft surat keluar tujuan ' . $data['tujuan'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-keluar')->with('success', 'Surat Keluar berhasil ditambahkan sebagai Draft');
    }

    public function show($id = null)
    {
        $data = [
            'id'    => $id,
            'surat' => $this->suratKeluarModel->find($id)
        ];

        if (empty($data['surat'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Keluar tidak ditemukan.');
        }

        return view('surat_keluar/detail', $data);
    }

    public function edit($id = null)
    {
        $wajibFieldModel = new WajibFieldPengaturanModel();
        $pengaturanModel = new PengaturanModel();
        $settings        = $pengaturanModel->getSettings();

        $allowedMethods = isset($settings['metode_lampiran']) && $settings['metode_lampiran'] !== ''
            ? explode(',', $settings['metode_lampiran'])
            : ['upload', 'link'];

        $surat = $this->suratKeluarModel->find($id);
        if (empty($surat)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Keluar tidak ditemukan.');
        }

        $data = [
            'required_fields'   => $wajibFieldModel->getRequiredFields('surat_keluar'),
            'allowedMethods'    => $allowedMethods,
            'surat'             => $surat,
            'id'                => $id,
            'tahun_anggaran'    => $settings['tahun_anggaran'] ?? date('Y'),
            'bulan_list'        => $this->suratService->getBulanList(),
            'format_surat_list' => (new FormatSuratModel())->findAll(),
        ];

        return view('surat_keluar/edit', $data);
    }

    public function update($id = null)
    {
        $surat = $this->suratKeluarModel->find($id);
        if (empty($surat)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Keluar tidak ditemukan.');
        }

        $pengaturanModel = new PengaturanModel();
        $settings        = $pengaturanModel->getSettings();

        $nomorUrut     = $this->request->getPost('nomor_urut');
        $bulan         = $this->request->getPost('bulan');
        $formatSuratId = $this->request->getPost('format_surat_id');
        $perihal       = $this->request->getPost('perihal');

        $nomorSurat = '';
        if (!empty($nomorUrut) && !empty($bulan) && !empty($formatSuratId)) {
            $formatModel = new FormatSuratModel();
            $format = $formatModel->find($formatSuratId);
            $template = $format['template'] ?? '{nomor}/{bulan}/{tahun}';
            $tahun = $settings['tahun_anggaran'] ?? date('Y');
            $nomorSurat = $this->suratService->generateNomorSurat($template, $nomorUrut, $bulan, $tahun);
        }

        // Cek duplikasi
        $query = $this->suratKeluarModel->groupStart();
        if (!empty($nomorSurat)) {
            $query->where('nomor_surat', $nomorSurat);
        } else {
            $query->where('perihal', $perihal);
        }
        $existingSurat = $query->groupEnd()->where('id !=', $id)->first();

        if ($existingSurat) {
            $msg = !empty($nomorSurat)
                ? 'Nomor surat "' . esc($nomorSurat) . '" sudah digunakan.'
                : 'Draft dengan perihal "' . esc($perihal) . '" sudah ada.';
            return redirect()->back()->withInput()->with('error', $msg);
        }

        $validationRules = $this->suratKeluarModel->getValidationRulesFromPengaturan();
        unset($validationRules['nomor_agenda']);

        $tipePenyimpanan = $this->request->getPost('tipe_penyimpanan');

        if ($tipePenyimpanan === 'lokal') {
            $wajibFieldModel = new WajibFieldPengaturanModel();
            if (in_array('file_konsep', $wajibFieldModel->getRequiredFields('surat_keluar')) &&
                $this->request->getFile('file_konsep') &&
                $this->request->getFile('file_konsep')->getName()) {
                $validationRules['file_konsep'] = 'uploaded[file_konsep]|max_size[file_konsep,5120]|ext_in[file_konsep,pdf,doc,docx]';
            }
        } elseif ($tipePenyimpanan === 'cloud') {
            if (!empty($this->request->getPost('file_link'))) {
                $validationRules['file_link'] = 'required|valid_url';
            }
        }

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali inputan Anda.')->with('validation', $this->validator);
        }

        $fileName = $surat['file_name'];
        $filePath = $surat['file_path'];
        $fileSize = $surat['file_size'];
        $fileLink = $surat['file_link'];

        if ($tipePenyimpanan === 'lokal') {
            $file = $this->request->getFile('file_konsep');
            $uploaded = $this->suratService->handleFileUpload($file, 'surat_keluar', $filePath);
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
            'nomor_urut'       => $nomorUrut ?: null,
            'bulan'            => $bulan ?: null,
            'format_surat_id'  => $formatSuratId ?: null,
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_kirim'    => $this->request->getPost('tanggal_kirim'),
            'tujuan'           => $this->request->getPost('tujuan'),
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

        $this->suratKeluarModel->update($id, $data);

        $logModel = new LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $id,
            'aksi'       => 'update',
            'tipe_surat' => 'surat_keluar',
            'detail'     => 'Memperbarui surat keluar tujuan ' . $data['tujuan'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-keluar')->with('success', 'Surat Keluar berhasil diperbarui');
    }

    public function delete($id = null)
    {
        $surat = $this->suratKeluarModel->find($id);

        if (empty($surat)) {
            return redirect()->back()->with('error', 'Data Surat Keluar tidak ditemukan.');
        }

        if ($surat['tipe_penyimpanan'] === 'lokal' && $surat['file_path']) {
            $this->suratService->deleteFile($surat['file_path']);
        }

        $this->suratKeluarModel->delete($id);

        $logModel = new LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $id,
            'aksi'       => 'delete',
            'tipe_surat' => 'surat_keluar',
            'detail'     => 'Menghapus surat keluar nomor ' . $surat['nomor_surat'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-keluar')->with('success', 'Surat Keluar berhasil dihapus');
    }

    public function approve($id = null)
    {
        $userRole = session('role');
        if ($userRole !== 'pimpinan' && $userRole !== 'admin') {
            return redirect()->to('/surat-keluar')->with('error', 'Akses ditolak. Hanya pimpinan atau administrator yang dapat menyetujui surat.');
        }

        $surat = $this->suratKeluarModel->find($id);
        if (empty($surat)) {
            return redirect()->back()->with('error', 'Data Surat Keluar tidak ditemukan.');
        }

        $actionType = $this->request->getPost('action_type');

        if ($actionType === 'cancel') {
            $this->suratKeluarModel->update($id, [
                'status'      => 'draft',
                'approved_by' => null,
                'approved_at' => null
            ]);
            $msg       = 'Persetujuan Surat Keluar berhasil dibatalkan (kembali ke Draft).';
            $logAksi   = 'cancel_approval';
            $logDetail = 'Membatalkan persetujuan surat keluar nomor ' . $surat['nomor_surat'];
        } elseif ($actionType === 'reject') {
            $this->suratKeluarModel->update($id, [
                'status'      => 'ditolak',
                'approved_by' => session()->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            $msg       = 'Surat Keluar berhasil ditolak.';
            $logAksi   = 'reject';
            $logDetail = 'Menolak surat keluar nomor ' . $surat['nomor_surat'];
        } else {
            $this->suratKeluarModel->update($id, [
                'status'      => 'disetujui',
                'approved_by' => session()->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            $msg       = 'Surat Keluar berhasil disetujui.';
            $logAksi   = 'approve';
            $logDetail = 'Menyetujui surat keluar nomor ' . $surat['nomor_surat'];
        }

        $logModel = new LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $id,
            'aksi'       => $logAksi,
            'tipe_surat' => 'surat_keluar',
            'detail'     => $logDetail,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-keluar')->with('success', $msg);
    }

    public function bulkApprove(): ResponseInterface
    {
        $userRole = session('role');
        if ($userRole !== 'pimpinan' && $userRole !== 'admin') {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Akses ditolak. Hanya pimpinan atau administrator.'
            ]);
        }

        $ids        = $this->request->getPost('ids');
        $actionType = $this->request->getPost('action_type');

        if (empty($ids) || !is_array($ids)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Tidak ada surat yang dipilih.'
            ]);
        }

        if (!in_array($actionType, ['approve', 'reject'])) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Action type tidak valid.'
            ]);
        }

        $logModel  = new LogAktivitasModel();
        $userId    = session()->get('user_id');
        $now       = date('Y-m-d H:i:s');
        $processed = 0;
        $errors    = [];

        foreach ($ids as $id) {
            $id = (int) $id;
            $surat = $this->suratKeluarModel->find($id);

            if (empty($surat)) {
                $errors[] = ['id' => $id, 'message' => 'Data tidak ditemukan.'];
                continue;
            }

            $newStatus = ($actionType === 'approve') ? 'disetujui' : 'ditolak';
            $logAksi   = ($actionType === 'approve') ? 'approve' : 'reject';
            $logDetail = ($actionType === 'approve' ? 'Menyetujui' : 'Menolak') . ' surat keluar nomor ' . $surat['nomor_surat'];

            $this->suratKeluarModel->update($id, [
                'status'      => $newStatus,
                'approved_by' => $userId,
                'approved_at' => $now,
            ]);

            $logModel->save([
                'user_id'    => $userId,
                'surat_id'   => $id,
                'aksi'       => $logAksi,
                'tipe_surat' => 'surat_keluar',
                'detail'     => $logDetail,
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString(),
            ]);

            $processed++;
        }

        $actionLabel = $actionType === 'approve' ? 'disetujui' : 'ditolak';

        return $this->response->setJSON([
            'success'   => true,
            'message'   => "$processed surat berhasil $actionLabel.",
            'processed' => $processed,
            'errors'    => $errors,
        ]);
    }

    public function reject($id = null)
    {
        $userRole = session('role');
        if ($userRole !== 'pimpinan' && $userRole !== 'admin') {
            return redirect()->to('/surat-keluar')->with('error', 'Akses ditolak. Hanya pimpinan atau administrator yang dapat menolak surat.');
        }

        $surat = $this->suratKeluarModel->find($id);
        if (empty($surat)) {
            return redirect()->back()->with('error', 'Data Surat Keluar tidak ditemukan.');
        }

        $this->suratKeluarModel->update($id, [
            'status'      => 'ditolak',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        $logModel = new LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $id,
            'aksi'       => 'reject',
            'tipe_surat' => 'surat_keluar',
            'detail'     => 'Menolak surat keluar nomor ' . $surat['nomor_surat'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/surat-keluar')->with('success', 'Surat Keluar berhasil ditolak');
    }

    public function exportPdf(): void
    {
        $pengaturanModel     = new PengaturanModel();
        $data['appSettings'] = $pengaturanModel->getSettings();
        $tahunAnggaran       = $data['appSettings']['tahun_anggaran'] ?? date('Y');

        $startMonth = $this->request->getGet('start_month');
        $endMonth   = $this->request->getGet('end_month');

        $builder = $this->suratKeluarModel->builder();
        $builder->where('YEAR(tanggal_surat)', $tahunAnggaran);

        if ($startMonth && $endMonth) {
            $builder->where('MONTH(tanggal_surat) >=', $startMonth);
            $builder->where('MONTH(tanggal_surat) <=', $endMonth);

            $bulanList = $this->suratService->getBulanList();
            $data['filter_text'] = "Periode: " . ($bulanList[$startMonth] ?? $startMonth) . "-" . ($bulanList[$endMonth] ?? $endMonth) . " Tahun " . $tahunAnggaran;
        } else {
            $data['filter_text'] = "Periode: Tahun " . $tahunAnggaran;
        }

        $data['surat_keluar'] = $builder->orderBy('tanggal_surat', 'ASC')->get()->getResultArray();
        $html = view('surat_keluar/print_pdf', $data);

        $this->exportService->exportPdf($html, 'Laporan_Surat_Keluar_' . date('Ymd'));
    }

    public function exportExcel(): void
    {
        $pengaturanModel = new PengaturanModel();
        $settings        = $pengaturanModel->getSettings();
        $tahunAnggaran   = $settings['tahun_anggaran'] ?? '';

        $builder = $this->suratKeluarModel->select('nomor_surat, tujuan, tanggal_surat, tanggal_kirim, perihal, lampiran, tipe_penyimpanan, file_link, keterangan');
        if (!empty($tahunAnggaran)) {
            $builder->where('YEAR(tanggal_surat)', $tahunAnggaran);
        }

        $records = $builder->orderBy('tanggal_surat', 'ASC')->get()->getResultArray();
        $headers = [
            'Nomor Surat (dari Pengirim)',
            'Tujuan',
            'Tanggal Surat (DD/MM/YYYY)',
            'Tanggal Kirim (DD/MM/YYYY)',
            'Perihal',
            'Jumlah Lampiran',
            'Tipe Penyimpanan (lokal/cloud)',
            'Link Cloud (Opsional)',
            'Keterangan (Opsional)'
        ];

        $data = [];
        foreach ($records as $surat) {
            $tglSurat = (!empty($surat['tanggal_surat']) && $surat['tanggal_surat'] !== '0000-00-00')
                ? date('d/m/Y', strtotime($surat['tanggal_surat']))
                : '';

            $tglKirim = (!empty($surat['tanggal_kirim']) && $surat['tanggal_kirim'] !== '0000-00-00')
                ? date('d/m/Y', strtotime($surat['tanggal_kirim']))
                : '';

            $data[] = [
                $surat['nomor_surat'] ?? '',
                $surat['tujuan'] ?? '',
                $tglSurat,
                $tglKirim,
                $surat['perihal'] ?? '',
                $surat['lampiran'] ?? '0',
                $surat['tipe_penyimpanan'] ?? 'lokal',
                $surat['file_link'] ?? '',
                $surat['keterangan'] ?? ''
            ];
        }

        $this->exportService->exportExcel($headers, $data, 'Laporan_Surat_Keluar_' . date('Y-m-d'), 'REKAPITULASI SURAT KELUAR');
    }

    public function import()
    {
        return view('surat_keluar/import', ['title' => 'Import Data Surat Keluar']);
    }

    public function downloadTemplate(): void
    {
        $this->importService->downloadTemplateSuratKeluar();
    }

    public function preview()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file || !$file->isValid()) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Silakan pilih file excel terlebih dahulu.');
        }

        $extension = $file->getExtension();
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Format file tidak didukung. Gunakan .xls atau .xlsx');
        }

        try {
            $importData = $this->importService->parseExcelSuratKeluar($file);

            if (empty($importData)) {
                return redirect()->to('/surat-keluar/import')->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            session()->set('import_data', $importData);

            return view('surat_keluar/preview', [
                'title'      => 'Preview Import Surat Keluar',
                'importData' => $importData
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Gagal membaca file Excel. Pastikan format sesuai template. Error: ' . $e->getMessage());
        }
    }

    public function storeImport()
    {
        $importData = session()->get('import_data');
        if (!$importData || !is_array($importData)) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Data import tidak ditemukan. Silakan unggah ulang file Excel.');
        }

        session()->remove('import_data');

        $userId    = (int) session()->get('user_id');
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString();

        $result = $this->importService->importSuratKeluar($importData, $userId, $ipAddress, $userAgent);

        if ($result['success']) {
            $msg = "{$result['count']} surat keluar berhasil diimport.";
            if (!empty($result['errors'])) {
                $msg .= "<br><br><strong>Peringatan:</strong> Ada baris yang dilewati karena tidak lengkap:<br>" . implode("<br>", array_slice($result['errors'], 0, 5));
                if (count($result['errors']) > 5) {
                    $msg .= "<br>...dan " . (count($result['errors']) - 5) . " lainnya.";
                }
            }
            return redirect()->to('/surat-keluar')->with('success', $msg);
        }

        $errorMsg = 'Tidak ada data valid yang bisa diimport. Periksa kembali file Excel Anda.';
        if (!empty($result['errors'])) {
            $errorMsg .= '<br><br><strong>Detail Error:</strong><br>' . implode("<br>", array_slice($result['errors'], 0, 10));
        }

        return redirect()->to('/surat-keluar/import')->with('error', $errorMsg);
    }

    public function renumber()
    {
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->to('/surat-keluar')->with('error', 'Metode tidak diizinkan.');
        }

        $updated = $this->suratService->renumberSuratKeluar();

        return redirect()->to('/surat-keluar')
            ->with('success', "Berhasil merapikan nomor urut $updated surat keluar.");
    }
}