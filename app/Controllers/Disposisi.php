<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\DisposisiModel;
use App\Models\SuratMasukModel;
use App\Models\UserModel;
use App\Models\LogAktivitasModel;

class Disposisi extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('disposisi');
        $builder->select('disposisi.*, surat_masuk.nomor_agenda, surat_masuk.perihal, users.nama_lengkap as penerima, users.jabatan, creator.nama_lengkap as pembuat, updater.nama_lengkap as pengupdate');
        $builder->join('surat_masuk', 'surat_masuk.id = disposisi.surat_masuk_id');
        $builder->join('users', 'users.id = disposisi.ke_user_id', 'left');
        $builder->join('users as creator', 'creator.id = disposisi.created_by', 'left');
        $builder->join('users as updater', 'updater.id = disposisi.updated_by', 'left');

        // Filter Tahun Anggaran jika diset
        $pengaturanModel = new \App\Models\PengaturanModel();
        $settings = $pengaturanModel->getSettings();
        $tahunAnggaran = $settings['tahun_anggaran'] ?? '';
        if (!empty($tahunAnggaran)) {
            $builder->where('YEAR(surat_masuk.tanggal_terima)', $tahunAnggaran);
        }

        $builder->orderBy('disposisi.created_at', 'DESC');
        $data['disposisi'] = $builder->get()->getResultArray();

        return view('disposisi/index', $data);
    }

    public function create($surat_masuk_id = null)
    {
        $suratModel = new SuratMasukModel();
        $surat = $suratModel->find($surat_masuk_id);

        if (!$surat) {
            return redirect()->back()->with('error', 'Surat Masuk tidak ditemukan.');
        }

        $userModel = new UserModel();
        // Ambil semua user selain user yang sedang login
        $data['users'] = $userModel->where('id !=', session()->get('user_id'))->findAll();
        $data['surat'] = $surat;
        $data['surat_masuk_id'] = $surat_masuk_id;

        return view('disposisi/create', $data);
    }

    public function store()
    {
        $disposisiModel = new DisposisiModel();

        $data = [
            'surat_masuk_id' => $this->request->getPost('surat_masuk_id'),
            'dari_user_id'   => session()->get('user_id'),
            'ke_user_id'     => $this->request->getPost('ke_user_id'),
            'instruksi'      => $this->request->getPost('instruksi'),
            'batas_waktu'    => $this->request->getPost('tenggat_waktu'),
            'status'         => 'pending',
            'catatan'        => null,
            'created_by'     => session()->get('user_id'),
        ];

        // Update status surat masuk menjadi didisposisikan
        $suratModel = new SuratMasukModel();
        $suratModel->update($data['surat_masuk_id'], ['status' => 'didisposisikan']);

        $disposisiModel->insert($data);
        $insertId = $disposisiModel->getInsertID();

        // Catat aktivitas
        $logModel = new LogAktivitasModel();
        $logModel->save([
            'user_id'    => session()->get('user_id'),
            'surat_id'   => $data['surat_masuk_id'],
            'aksi'       => 'create_disposisi',
            'tipe_surat' => 'surat_masuk',
            'detail'     => 'Membuat disposisi baru ke User ID: ' . $data['ke_user_id'],
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString()
        ]);

        return redirect()->to('/disposisi')->with('success', 'Disposisi berhasil dikirim');
    }

    public function show($id = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('disposisi');
        $builder->select('disposisi.*, surat_masuk.nomor_agenda, surat_masuk.perihal, u_dari.nama_lengkap as pengirim_nama, u_ke.nama_lengkap as penerima_nama, u_ke.jabatan as penerima_jabatan');
        $builder->join('surat_masuk', 'surat_masuk.id = disposisi.surat_masuk_id');
        $builder->join('users as u_dari', 'u_dari.id = disposisi.dari_user_id', 'left');
        $builder->join('users as u_ke', 'u_ke.id = disposisi.ke_user_id', 'left');
        $builder->where('disposisi.id', $id);

        $data['disposisi'] = $builder->get()->getRowArray();

        if (!$data['disposisi']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Data disposisi tidak ditemukan');
        }

        return view('disposisi/detail', $data);
    }

    public function updateStatus($id = null)
    {
        $disposisiModel = new DisposisiModel();
        $disposisi = $disposisiModel->find($id);

        if ($disposisi) {
            $newStatus = $this->request->getPost('status');
            $disposisiModel->update($id, [
                'status'     => $newStatus,
                'catatan'    => $this->request->getPost('tanggapan'),
                'updated_by' => session()->get('user_id')
            ]);

            // Jika status disposisi selesai, update juga status surat_masuk menjadi selesai
            if ($newStatus == 'selesai') {
                $suratModel = new SuratMasukModel();
                $suratModel->update($disposisi['surat_masuk_id'], ['status' => 'selesai']);
            }

            // Catat log
            $logModel = new LogAktivitasModel();
            $logModel->save([
                'user_id'    => session()->get('user_id'),
                'surat_id'   => $disposisi['surat_masuk_id'],
                'aksi'       => 'update_disposisi',
                'tipe_surat' => 'surat_masuk',
                'detail'     => 'Memperbarui status disposisi menjadi: ' . $this->request->getPost('status'),
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getAgentString()
            ]);
        }

        return redirect()->back()->with('success', 'Status disposisi diperbarui');
    }
}
