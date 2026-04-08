<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SuratKeluar extends BaseController
{
    public function index()
    {
        $data['search'] = $this->request->getGet('search');
        return view('surat_keluar/index', $data);
    }

    public function ajaxList()
    {
        $request = \Config\Services::request();
        $suratKeluarModel = new \App\Models\SuratKeluarModel();

        $start = (int) ($request->getPost('start') ?? 0);
        $length = (int) ($request->getPost('length') ?? 10);
        $search = $request->getPost('search')['value'] ?? '';
        $orderInfo = $request->getPost('order')[0] ?? null;
        $columnIndex = $orderInfo['column'] ?? 0;
        $orderDir = $orderInfo['dir'] ?? 'desc';

        $columns = [
            0 => 'surat_keluar.id',
            1 => 'surat_keluar.nomor_agenda',
            2 => 'surat_keluar.tujuan',
            3 => 'surat_keluar.tanggal_surat',
            4 => 'surat_keluar.perihal',
            5 => 'surat_keluar.status',
            6 => 'creator.nama_lengkap',
            7 => 'updater.nama_lengkap',
            8 => 'surat_keluar.id' // Action column
        ];
        $orderColumn = $columns[$columnIndex] ?? 'surat_keluar.created_at';

        $builder = $suratKeluarModel->select('surat_keluar.*, creator.nama_lengkap as pembuat, updater.nama_lengkap as pengupdate')
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
        $filterEndDate = $request->getPost('end_date');
        $filterStatus = $request->getPost('status');

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
        $pengaturanModel = new \App\Models\PengaturanModel();
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

        foreach ($data as $sk) {
            $row = [];
            $row[] = $no++;

            $nomorSurat = esc($sk['nomor_surat']) ?: '<i class="text-muted">Draft</i>';

            $fileUrl = '';
            if ($sk['tipe_penyimpanan'] == 'cloud' && !empty($sk['file_link'])) {
                $fileUrl = $sk['file_link'];
            } elseif ($sk['tipe_penyimpanan'] == 'lokal' && !empty($sk['file_path'])) {
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

            $statusBadge = '';
            if ($sk['status'] == 'draft') {
                $statusBadge = '<span class="badge bg-secondary text-secondary-fg">Draft</span>';
            } elseif ($sk['status'] == 'disetujui') {
                $statusBadge = '<span class="badge bg-success text-success-fg">Disetujui</span>';
            } elseif ($sk['status'] == 'ditolak') {
                $statusBadge = '<span class="badge bg-danger text-danger-fg">Ditolak</span>';
            } else {
                $statusBadge = '<span class="badge bg-warning text-warning-fg">' . esc($sk['status']) . '</span>';
            }
            $row[] = $statusBadge;

            $row[] = esc($sk['pembuat'] ?? '-');
            $row[] = esc($sk['pengupdate'] ?? '-');

            $btn = '<div class="btn-list flex-nowrap justify-content-center">';
            $btn .= '<a href="' . base_url('surat-keluar/show/' . $sk['id']) . '" class="btn btn-outline-info btn-icon" title="Detail"><i class="ti ti-eye icon"></i></a>';
            if ($role !== 'pimpinan') {
                $btn .= '<a href="' . base_url('surat-keluar/edit/' . $sk['id']) . '" class="btn btn-outline-primary btn-icon" title="Edit"><i class="ti ti-edit icon"></i></a>';
                $btn .= '<form action="' . base_url('surat-keluar/delete/' . $sk['id']) . '" method="post" style="display:inline;">' . csrf_field() . '<button type="submit" class="btn btn-outline-danger btn-icon" title="Hapus" onclick="return confirm(\'Apakah Anda yakin?\');"><i class="ti ti-trash icon"></i></button></form>';
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
        $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();
        $pengaturanModel = new \App\Models\PengaturanModel();
        $settings = $pengaturanModel->getSettings();

        // Ambil metode penyimpanan yang diizinkan admin dari pengaturan
        $allowedMethods = isset($settings['metode_lampiran']) && $settings['metode_lampiran'] !== ''
            ? explode(',', $settings['metode_lampiran'])
            : ['upload', 'link']; // Default: keduanya aktif

        $defaultMethod = $allowedMethods[0] ?? 'upload';

        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $lastSurat = $suratKeluarModel->where('nomor_surat !=', '')
                                      ->where('nomor_surat IS NOT NULL', null, false)
                                      ->orderBy('id', 'DESC')
                                      ->first();
        
        $data['required_fields'] = $wajibFieldModel->getRequiredFields('surat_keluar');
        $data['allowedMethods']  = $allowedMethods;
        $data['defaultMethod']   = $defaultMethod;
        $data['latest_nomor_surat_keluar'] = $lastSurat ? $lastSurat['nomor_surat'] : '-';
        
        return view('surat_keluar/create', $data);
    }

    public function store()
    {
        $suratKeluarModel = new \App\Models\SuratKeluarModel();

        $nomorSurat = $this->request->getPost('nomor_surat');
        $perihal = $this->request->getPost('perihal');

        // Cek duplikasi berdasarkan nomor_surat atau perihal
        // Jika nomor_surat kosong (draft), hanya cek perihal
        $query = $suratKeluarModel->groupStart();
        if (!empty($nomorSurat)) {
            $query->where('nomor_surat', $nomorSurat);
            $query->orWhere('perihal', $perihal);
        } else {
            $query->where('perihal', $perihal);
        }
        $existingSurat = $query->groupEnd()->first();

        if ($existingSurat) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor atau isi surat sudah ada!');
        }
        
        // Dapatkan aturan validasi berdasarkan pengaturan wajib field
        $validationRules = $suratKeluarModel->getValidationRulesFromPengaturan();
        
        // Validasi file upload secara terpisah karena tergantung tipe_penyimpanan
        $tipe_penyimpanan = $this->request->getPost('tipe_penyimpanan');
        
        if ($tipe_penyimpanan === 'lokal') {
            $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();
            if (in_array('file_konsep', $wajibFieldModel->getRequiredFields('surat_keluar'))) {
                $validationRules['file_konsep'] = 'uploaded[file_konsep]|max_size[file_konsep,5120]|ext_in[file_konsep,pdf,doc,docx]';
            }
        } elseif ($tipe_penyimpanan === 'cloud') {
            if (!empty($this->request->getPost('file_link'))) {
                $validationRules['file_link'] = 'required|valid_url';
            }
        }
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali inputan Anda.')->with('validation', $this->validator);
        }

        // Generate Nomor Agenda (OUT-YYYY-001)
        $lastSurat = $suratKeluarModel->orderBy('id', 'DESC')->first();
        $lastNumber = 0;
        if ($lastSurat && preg_match('/OUT-\d{4}-(\d+)/', $lastSurat['nomor_agenda'], $matches)) {
            $lastNumber = (int)$matches[1];
        }
        $nomor_agenda = 'OUT-' . date('Y') . '-' . sprintf('%03d', $lastNumber + 1);

        $fileName = null;
        $filePath = null;
        $fileSize = null;
        $fileLink = null;

        if ($tipe_penyimpanan == 'lokal') {
            $file = $this->request->getFile('file_konsep');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/surat_keluar', $fileName);
                $filePath = 'uploads/surat_keluar/' . $fileName;
                $fileSize = $file->getSize('kb');
            }
        } elseif ($tipe_penyimpanan == 'cloud') {
            $fileLink = $this->request->getPost('file_link');
        }

        $data = [
            'nomor_agenda'     => $nomor_agenda,
            'nomor_surat'      => $this->request->getPost('nomor_surat'), // Bisa kosong jika menunggu persetujuan
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_kirim'    => $this->request->getPost('tanggal_kirim'),
            'tujuan'           => $this->request->getPost('tujuan'),
            'perihal'          => $this->request->getPost('perihal'),
            'tipe_penyimpanan' => $tipe_penyimpanan,
            'lampiran'         => $this->request->getPost('lampiran') ?? 0,
            'keterangan'       => $this->request->getPost('keterangan'),
            'file_name'        => $fileName,
            'file_path'        => $filePath,
            'file_size'        => $fileSize,
            'file_link'        => $fileLink,
            'status'           => 'draft', // Draft sebelum disetujui pimpinan
            'created_by'       => session()->get('user_id'),
        ];

        $suratKeluarModel->insert($data);
        $insertId = $suratKeluarModel->getInsertID();

        // Catat di log_aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
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
        $model = new \App\Models\SuratKeluarModel();

        $data = [
            'id' => $id,
            'surat' => $model->find($id)
        ];

        return view('surat_keluar/detail', $data);
    }

    public function edit($id = null)
    {
        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();
        $pengaturanModel = new \App\Models\PengaturanModel();
        $settings = $pengaturanModel->getSettings();

        // Ambil metode penyimpanan yang diizinkan admin
        $allowedMethods = isset($settings['metode_lampiran']) && $settings['metode_lampiran'] !== ''
            ? explode(',', $settings['metode_lampiran'])
            : ['upload', 'link'];

        $data['required_fields'] = $wajibFieldModel->getRequiredFields('surat_keluar');
        $data['allowedMethods']  = $allowedMethods;
        $data['surat'] = $suratKeluarModel->find($id);
        $data['id'] = $id;

        if (empty($data['surat'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Keluar tidak ditemukan.');
        }

        return view('surat_keluar/edit', $data);
    }

    public function update($id = null)
    {
        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $surat = $suratKeluarModel->find($id);

        if (empty($surat)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data Surat Keluar tidak ditemukan.');
        }

        $nomorSurat = $this->request->getPost('nomor_surat');
        $perihal = $this->request->getPost('perihal');

        // Cek duplikasi berdasarkan nomor_surat atau perihal (kecuali surat ini sendiri)
        $query = $suratKeluarModel->groupStart();
        if (!empty($nomorSurat)) {
            $query->where('nomor_surat', $nomorSurat);
            $query->orWhere('perihal', $perihal);
        } else {
            $query->where('perihal', $perihal);
        }
        $existingSurat = $query->groupEnd()->where('id !=', $id)->first();

        if ($existingSurat) {
            return redirect()->back()->withInput()->with('error', 'Peringatan: Nomor atau isi surat sudah ada!');
        }

        // Dapatkan aturan validasi berdasarkan pengaturan wajib field
        $validationRules = $suratKeluarModel->getValidationRulesFromPengaturan();
        
        // Hapus validasi nomor_agenda karena ini hanya dibaca
        unset($validationRules['nomor_agenda']);
        
        // Validasi file upload secara terpisah karena tergantung tipe_penyimpanan
        $tipe_penyimpanan = $this->request->getPost('tipe_penyimpanan');
        
        if ($tipe_penyimpanan === 'lokal') {
            $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();
            if (in_array('file_konsep', $wajibFieldModel->getRequiredFields('surat_keluar')) && 
                $this->request->getFile('file_konsep') && 
                $this->request->getFile('file_konsep')->getName()) {
                
                $validationRules['file_konsep'] = 'uploaded[file_konsep]|max_size[file_konsep,5120]|ext_in[file_konsep,pdf,doc,docx]';
            }
        } elseif ($tipe_penyimpanan === 'cloud') {
            if (!empty($this->request->getPost('file_link'))) {
                $validationRules['file_link'] = 'required|valid_url';
            }
        }
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali inputan Anda.')->with('validation', $this->validator);
        }

        $tipe_penyimpanan = $this->request->getPost('tipe_penyimpanan');
        $fileName = $surat['file_name'];
        $filePath = $surat['file_path'];
        $fileSize = $surat['file_size'];
        $fileLink = $surat['file_link'];

        if ($tipe_penyimpanan == 'lokal') {
            $file = $this->request->getFile('file_konsep');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Hapus file lama jika ada
                if ($filePath && file_exists(FCPATH . $filePath)) {
                    unlink(FCPATH . $filePath);
                }
                
                $fileName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/surat_keluar', $fileName);
                $filePath = 'uploads/surat_keluar/' . $fileName;
                $fileSize = $file->getSize('kb');
                $fileLink = null;
            }
        } elseif ($tipe_penyimpanan == 'cloud') {
            $fileLink = $this->request->getPost('file_link');
        }

        $data = [
            'nomor_surat'      => $this->request->getPost('nomor_surat'),
            'tanggal_surat'    => $this->request->getPost('tanggal_surat'),
            'tanggal_kirim'    => $this->request->getPost('tanggal_kirim'),
            'tujuan'           => $this->request->getPost('tujuan'),
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

        $suratKeluarModel->update($id, $data);

        // Catat aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
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
        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $surat = $suratKeluarModel->find($id);

        if (empty($surat)) {
            return redirect()->back()->with('error', 'Data Surat Keluar tidak ditemukan.');
        }

        // Hapus file terkait jika ada
        if ($surat['tipe_penyimpanan'] == 'lokal' && $surat['file_path']) {
            if (file_exists(FCPATH . $surat['file_path'])) {
                unlink(FCPATH . $surat['file_path']);
            }
        }

        $suratKeluarModel->delete($id);

        // Catat aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
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

        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $surat = $suratKeluarModel->find($id);

        if (empty($surat)) {
            return redirect()->back()->with('error', 'Data Surat Keluar tidak ditemukan.');
        }

        $actionType = $this->request->getPost('action_type');

        if ($actionType === 'cancel') {
            $suratKeluarModel->update($id, [
                'status' => 'draft',
                'approved_by' => null,
                'approved_at' => null
            ]);
            $msg = 'Persetujuan Surat Keluar berhasil dibatalkan (kembali ke Draft).';
            $logAksi = 'cancel_approval';
            $logDetail = 'Membatalkan persetujuan surat keluar nomor ' . $surat['nomor_surat'];
        } elseif ($actionType === 'reject') {
            $suratKeluarModel->update($id, [
                'status' => 'ditolak',
                'approved_by' => session()->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            $msg = 'Surat Keluar berhasil ditolak.';
            $logAksi = 'reject';
            $logDetail = 'Menolak surat keluar nomor ' . $surat['nomor_surat'];
        } else {
            // Default to Approve
            $suratKeluarModel->update($id, [
                'status' => 'disetujui',
                'approved_by' => session()->get('user_id'),
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            $msg = 'Surat Keluar berhasil disetujui.';
            $logAksi = 'approve';
            $logDetail = 'Menyetujui surat keluar nomor ' . $surat['nomor_surat'];
        }

        // Catat aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
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

    public function reject($id = null)
    {
        $userRole = session('role');
        if ($userRole !== 'pimpinan' && $userRole !== 'admin') {
            return redirect()->to('/surat-keluar')->with('error', 'Akses ditolak. Hanya pimpinan atau administrator yang dapat menolak surat.');
        }

        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $surat = $suratKeluarModel->find($id);

        if (empty($surat)) {
            return redirect()->back()->with('error', 'Data Surat Keluar tidak ditemukan.');
        }

        // Update status menjadi ditolak
        $suratKeluarModel->update($id, [
            'status' => 'ditolak',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        // Catat aktivitas
        $logModel = new \App\Models\LogAktivitasModel();
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

    public function exportPdf()
    {
        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        
        // Ambil pengaturan aplikasi
        $pengaturanModel = new \App\Models\PengaturanModel();
        $data['appSettings'] = $pengaturanModel->getSettings();
        $tahunAnggaran = $data['appSettings']['tahun_anggaran'] ?? date('Y');

        $startMonth = $this->request->getGet('start_month');
        $endMonth = $this->request->getGet('end_month');

        $builder = $suratKeluarModel->builder();
        $builder->where('YEAR(tanggal_surat)', $tahunAnggaran);

        if ($startMonth && $endMonth) {
            $builder->where('MONTH(tanggal_surat) >=', $startMonth);
            $builder->where('MONTH(tanggal_surat) <=', $endMonth);
            
            $bulanStr = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
            $data['filter_text'] = "Periode: " . $bulanStr[$startMonth] . "-" . $bulanStr[$endMonth] . " Tahun " . $tahunAnggaran;
        } else {
            $data['filter_text'] = "Periode: Tahun " . $tahunAnggaran;
        }

        $data['surat_keluar'] = $builder->orderBy('tanggal_surat', 'ASC')->get()->getResultArray();

        $html = view('surat_keluar/print_pdf', $data);

        $exportService = new \App\Services\ExportService();
        $exportService->exportPdf($html, 'Laporan_Surat_Keluar_' . date('Ymd'));
    }

    public function exportExcel()
    {
        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $records = $suratKeluarModel->findAll();

        $headers = ['Tgl Keluar', 'No Surat', 'Tgl Surat', 'Tujuan', 'Perihal', 'Status'];
        $data = [];
        foreach ($records as $surat) {
            $data[] = [
                !empty($surat['tanggal_kirim']) ? $surat['tanggal_kirim'] : '-',
                $surat['nomor_surat'] ?? '-',
                $surat['tanggal_surat'],
                $surat['tujuan'],
                $surat['perihal'],
                ucfirst($surat['status'])
            ];
        }

        $exportService = new \App\Services\ExportService();
        $exportService->exportExcel($headers, $data, 'Laporan_Surat_Keluar_' . date('Y-m-d'), 'REKAPITULASI SURAT KELUAR');
    }

    public function import()
    {
        $data = [
            'title' => 'Import Data Surat Keluar'
        ];
        return view('surat_keluar/import', $data);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Nomor Surat (dari Pengirim)');
        $sheet->setCellValue('B1', 'Tujuan');
        $sheet->setCellValue('C1', 'Tanggal Surat (DD/MM/YYYY)');
        $sheet->setCellValue('D1', 'Tanggal Kirim (DD/MM/YYYY)');
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
        $sheet2->setCellValue('A3', 'Tujuan');
        $sheet2->setCellValue('B3', 'Wajib isi.');
        $sheet2->setCellValue('A4', 'Tanggal Surat');
        $sheet2->setCellValue('B4', 'Wajib isi. Format ex: 30/12/2024');
        $sheet2->setCellValue('A5', 'Tanggal Kirim');
        $sheet2->setCellValue('B5', 'Wajib isi. Format ex: 31/12/2024');
        $sheet2->setCellValue('A6', 'Perihal');
        $sheet2->setCellValue('B6', 'Wajib isi.');
        $sheet2->setCellValue('A7', 'Jumlah Lampiran');
        $sheet2->setCellValue('B7', 'Opsional. Angka (0, 1, 2, dll).');
        $sheet2->setCellValue('A8', 'Tipe Penyimpanan');
        $sheet2->setCellValue('B8', 'Wajib diisi "lokal" atau "cloud".');
        $sheet2->setCellValue('A9', 'Link Cloud');
        $sheet2->setCellValue('B9', 'Opsional. Dapat diisi link (http/https) ke berkas cloud.');

        foreach (range('A', 'B') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }
        $spreadsheet->setActiveSheetIndex(0);

        $writer = new Xlsx($spreadsheet);
        $filename = 'Template_Import_Surat_Keluar.xlsx';

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
            return redirect()->to('/surat-keluar/import')->with('error', 'Silakan pilih file excel terlebih dahulu.');
        }

        $extension = $file->getExtension();
        if (!in_array($extension, ['xls', 'xlsx'])) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Format file tidak didukung. Gunakan .xls atau .xlsx');
        }

        try {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            if ($extension == 'xls') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
            }

            $spreadsheet = $reader->load($file->getTempName());
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, false, false);

            $importData = [];
            for ($i = 1; $i < count($sheetData); $i++) {
                $row = $sheetData[$i];
                if (empty(array_filter($row))) {
                    continue;
                }

                $tanggalSurat = rtrim(trim((string)($row[2] ?? '')));
                if (is_numeric($tanggalSurat)) {
                    $tanggalSurat = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalSurat)->format('Y-m-d');
                } elseif (!empty($tanggalSurat)) {
                    $dateObj = \DateTime::createFromFormat('d/m/Y', $tanggalSurat);
                    if ($dateObj !== false) {
                        $tanggalSurat = $dateObj->format('Y-m-d');
                    } else {
                        $dateObjYmd = \DateTime::createFromFormat('Y-m-d', $tanggalSurat);
                        if ($dateObjYmd !== false) {
                            $tanggalSurat = $dateObjYmd->format('Y-m-d');
                        }
                    }
                }

                $tanggalKirim = rtrim(trim((string)($row[3] ?? '')));
                if (is_numeric($tanggalKirim)) {
                    $tanggalKirim = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggalKirim)->format('Y-m-d');
                } elseif (!empty($tanggalKirim)) {
                    $dateObj = \DateTime::createFromFormat('d/m/Y', $tanggalKirim);
                    if ($dateObj !== false) {
                        $tanggalKirim = $dateObj->format('Y-m-d');
                    } else {
                        $dateObjYmd = \DateTime::createFromFormat('Y-m-d', $tanggalKirim);
                        if ($dateObjYmd !== false) {
                            $tanggalKirim = $dateObjYmd->format('Y-m-d');
                        }
                    }
                }

                $importData[] = [
                    'nomor_surat' => rtrim(trim($row[0] ?? '')),
                    'tujuan' => rtrim(trim($row[1] ?? '')),
                    'tanggal_surat' => $tanggalSurat,
                    'tanggal_kirim' => $tanggalKirim,
                    'perihal' => rtrim(trim($row[4] ?? '')),
                    'lampiran' => rtrim(trim($row[5] ?? '0')),
                    'tipe_penyimpanan' => rtrim(trim($row[6] ?? 'lokal')),
                    'file_link' => rtrim(trim($row[7] ?? '')),
                    'keterangan' => rtrim(trim($row[8] ?? '')),
                ];
            }

            if (empty($importData)) {
                return redirect()->to('/surat-keluar/import')->with('error', 'File Excel kosong atau tidak memiliki data.');
            }

            $data = [
                'title' => 'Preview Import Surat Keluar',
                'importData' => $importData
            ];

            return view('surat_keluar/preview', $data);
        } catch (\Exception $e) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Gagal membaca file Excel. Pastikan format sesuai template. Error: ' . $e->getMessage());
        }
    }

    public function storeImport()
    {
        $base64Data = $this->request->getPost('import_data');
        if (!$base64Data) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Data import tidak ditemukan.');
        }

        $importData = json_decode(base64_decode($base64Data), true);
        if (!is_array($importData)) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Format data tidak valid.');
        }

        $suratKeluarModel = new \App\Models\SuratKeluarModel();
        $logModel = new \App\Models\LogAktivitasModel();
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        $validRows = 0;
        $errors = [];

        $db->transStart();

        foreach ($importData as $index => $row) {
            $rowNum = $index + 2;

            if (empty($row['nomor_surat'])) {
                $errors[] = "Baris {$rowNum}: Nomor surat kosong.";
                continue;
            }
            if (empty($row['tujuan'])) {
                $errors[] = "Baris {$rowNum}: Tujuan surat kosong.";
                continue;
            }
            if (empty($row['tanggal_surat']) || $row['tanggal_surat'] == '1970-01-01') {
                $errors[] = "Baris {$rowNum}: Tanggal surat kosong atau format tidak valid.";
                continue;
            }
            if (empty($row['tanggal_kirim']) || $row['tanggal_kirim'] == '1970-01-01') {
                $errors[] = "Baris {$rowNum}: Tanggal kirim kosong atau format tidak valid.";
                continue;
            }
            if (empty($row['perihal'])) {
                $errors[] = "Baris {$rowNum}: Perihal kosong.";
                continue;
            }

            $metode = strtolower($row['tipe_penyimpanan']);
            if (empty($metode)) {
                $metode = 'lokal'; // Default ke lokal jika dikosongi
            }
            if (!in_array($metode, ['lokal', 'cloud'])) {
                $errors[] = "Baris {$rowNum}: Tipe penyimpanan harus diisi 'lokal' atau 'cloud'.";
                continue;
            }

            // Generate Nomor Agenda (OUT-YYYY-001) for each row
            $lastSurat = $suratKeluarModel->orderBy('id', 'DESC')->first();
            $lastNumber = 0;
            if ($lastSurat && preg_match('/OUT-\d{4}-(\d+)/', $lastSurat['nomor_agenda'], $matches)) {
                $lastNumber = (int)$matches[1];
            }
            $nomor_agenda = 'OUT-' . date('Y') . '-' . sprintf('%03d', $lastNumber + 1);

            $dataInsert = [
                'nomor_agenda'     => $nomor_agenda,
                'nomor_surat'      => $row['nomor_surat'],
                'tanggal_surat'    => $row['tanggal_surat'],
                'tanggal_kirim'    => $row['tanggal_kirim'],
                'tujuan'           => $row['tujuan'],
                'perihal'          => $row['perihal'],
                'lampiran'         => $row['lampiran'] ?: 0,
                'tipe_penyimpanan' => $metode,
                'file_link'        => $metode == 'cloud' ? $row['file_link'] : null,
                'keterangan'       => $row['keterangan'] ?? null,
                'status'           => 'disetujui', // mass import masuk sebagai disetujui
                'created_by'       => $userId,
            ];

            $suratKeluarModel->insert($dataInsert);
            $insertId = $suratKeluarModel->getInsertID();

            // Log activity
            $logModel->save([
                'user_id'    => $userId,
                'surat_id'   => $insertId,
                'aksi'       => 'create',
                'tipe_surat' => 'surat_keluar',
                'detail'     => 'Import data surat keluar via excel ke ' . $dataInsert['tujuan'],
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString()
            ]);

            $validRows++;
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->to('/surat-keluar/import')->with('error', 'Terjadi kesalahan sistem saat menyimpan data Surat Keluar.');
        }

        if ($validRows > 0) {
            $msg = "$validRows surat keluar berhasil diimport.";
            if (count($errors) > 0) {
                $msg .= "<br><br><strong>Pesanan peringatan:</strong> Ada baris yang dilewati karena tidak lengkap:<br>" . implode("<br>", array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $msg .= "<br>...dan " . (count($errors) - 5) . " lainnya.";
                }
            }
            return redirect()->to('/surat-keluar')->with('success', $msg);
        } else {
            $errorMsg = 'Tidak ada data valid yang bisa diimport. Periksa kembali file Excel Anda.';
            if (count($errors) > 0) {
                $errorMsg .= '<br><br><strong>Detail Error Baris Pertama:</strong><br>' . implode("<br>", array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMsg .= "<br>...dan " . (count($errors) - 10) . " lainnya.";
                }
            }
            return redirect()->to('/surat-keluar/import')->with('error', $errorMsg);
        }
    }
}