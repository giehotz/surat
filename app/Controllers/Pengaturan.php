<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\TahunAnggaranModel;
use App\Models\FormatSuratModel;

class Pengaturan extends BaseController
{
    protected $pengaturanModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
    }

    public function index()
    {
        $role = session('role');
        // View accessible to admins and admin_tamu (guestbook manager)
        if ($role !== 'admin' && $role !== 'admin_tamu') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin.');
        }

        $userModel = new \App\Models\UserModel();
        $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();

        $data = [
            'title'    => 'Pengaturan Aplikasi',
            'settings' => $this->pengaturanModel->getSettings(),
            'active_tab' => session()->getFlashdata('active_tab') ?? $this->request->getGet('active_tab') ?? 'identitas',
            'users'    => $userModel->findAll(),
            'wajib_fields' => $wajibFieldModel->getPengaturanByForm('surat_keluar'),
            'tahun_anggaran_list' => (new TahunAnggaranModel())->getList(),
            'format_surat_list' => (new FormatSuratModel())->findAll()
        ];

        return view('pengaturan/index', $data);
    }

    public function updateIdentitas()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $rules = [
            'sekolah_nama' => 'required',
            'sekolah_alamat' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Nama institusi dan alamat wajib diisi.')->with('active_tab', 'identitas');
        }

        $postData = $this->request->getPost();

        // Save text-based fields
        $fields = ['sekolah_kementerian', 'sekolah_nama', 'sekolah_npsn', 'sekolah_nsm', 'sekolah_alamat', 'sekolah_kontak'];
        foreach ($fields as $field) {
            if (isset($postData[$field])) {
                $this->pengaturanModel->updateSetting($field, $postData[$field]);
            }
        }

        // Handle Logo Upload
        $fileLogo = $this->request->getFile('sekolah_logo');
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {

            // Validasi file type and size
            $rules = [
                'sekolah_logo' => 'uploaded[sekolah_logo]|is_image[sekolah_logo]|mime_in[sekolah_logo,image/jpg,image/jpeg,image/png]|max_size[sekolah_logo,2048]'
            ];

            if ($this->validate($rules)) {
                $newName = $fileLogo->getRandomName();
                $fileLogo->move(FCPATH . 'uploads/logo', $newName);

                // Hapus logo lama jika ada
                $oldLogo = $this->pengaturanModel->getValue('sekolah_logo');
                if ($oldLogo && file_exists(FCPATH . 'uploads/logo/' . $oldLogo)) {
                    unlink(FCPATH . 'uploads/logo/' . $oldLogo);
                }

                $this->pengaturanModel->updateSetting('sekolah_logo', $newName);
            } else {
                return redirect()->to('/pengaturan')->withInput()->with('error', $this->validator->getErrors()['sekolah_logo'])->with('active_tab', 'identitas');
            }
        }

        cache()->delete('app_settings');
        return redirect()->to('/pengaturan')->with('success', 'Identitas Sekolah berhasil diperbarui.')->with('active_tab', 'identitas');
    }

    public function updatePimpinan()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $postData = $this->request->getPost();

        $fields = ['pejabat_kepsek_nama', 'pejabat_kepsek_nip', 'pejabat_tu_nama', 'pejabat_tu_nip'];
        foreach ($fields as $field) {
            if (isset($postData[$field])) {
                $this->pengaturanModel->updateSetting($field, $postData[$field]);
            }
        }

        cache()->delete('app_settings');
        return redirect()->to('/pengaturan')->with('success', 'Data Pimpinan Sekolah berhasil diperbarui.')->with('active_tab', 'pimpinan');
    }

    public function updatePreferensi()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $postData = $this->request->getPost();

        $fields = ['app_nama', 'tahun_anggaran', 'link_folder_drive'];
        foreach ($fields as $field) {
            if (isset($postData[$field])) {
                $this->pengaturanModel->updateSetting($field, $postData[$field]);
            }
        }

        // Handle array of metode_lampiran
        if (isset($postData['metode_lampiran']) && is_array($postData['metode_lampiran'])) {
            $metode = implode(',', $postData['metode_lampiran']);
            $this->pengaturanModel->updateSetting('metode_lampiran', $metode);
        } else {
            // Default to empty if nothing checked
            $this->pengaturanModel->updateSetting('metode_lampiran', '');
        }

        cache()->delete('app_settings');
        return redirect()->to('/pengaturan')->with('success', 'Preferensi Sistem berhasil diperbarui.')->with('active_tab', 'preferensi');
    }
    
    public function updateWajibField()
    {
        if (session('role') !== 'admin') return redirect()->back();
        
        $wajibFieldModel = new \App\Models\WajibFieldPengaturanModel();
        $postData = $this->request->getPost('wajib_field') ?? [];
        $allFields = $this->request->getPost('all_fields') ?? [];
        
        // Update semua field wajib berdasarkan status toggle
        foreach ($allFields as $fieldName) {
            $isChecked = isset($postData[$fieldName]) && $postData[$fieldName] === '1';
            $wajibFieldModel->updatePengaturan('surat_keluar', $fieldName, $isChecked);
        }
        
        cache()->delete('app_settings');
        return redirect()->to('/pengaturan')->with('success', 'Pengaturan wajib field berhasil diperbarui.')->with('active_tab', 'wajib_field');
    }

    public function deleteLogo()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $oldLogo = $this->pengaturanModel->getValue('sekolah_logo');
        if ($oldLogo && file_exists(FCPATH . 'uploads/logo/' . $oldLogo)) {
            unlink(FCPATH . 'uploads/logo/' . $oldLogo);
        }

        $this->pengaturanModel->updateSetting('sekolah_logo', '');
        cache()->delete('app_settings');

        return redirect()->to('/pengaturan')->with('success', 'Logo resmi sekolah berhasil dihapus.')->with('active_tab', 'identitas');
    }

    public function storeTahunAnggaran()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $tahun = $this->request->getPost('tahun');
        $model = new TahunAnggaranModel();

        if ($model->where('tahun', $tahun)->first()) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Tahun Anggaran sudah ada.')->with('active_tab', 'tahun-anggaran');
        }

        $model->insert(['tahun' => $tahun]);

        return redirect()->to('/pengaturan')->with('success', 'Tahun Anggaran berhasil ditambahkan.')->with('active_tab', 'tahun-anggaran');
    }

    public function deleteTahunAnggaran($id)
    {
        if (session('role') !== 'admin') return redirect()->back();

        $model = new TahunAnggaranModel();
        $tahunRow = $model->find($id);

        if (!$tahunRow) {
            return redirect()->to('/pengaturan')->with('error', 'Data tidak ditemukan.')->with('active_tab', 'tahun-anggaran');
        }

        // Cek apakah tahun ini sedang digunakan sebagai tahun aktif di pengaturan
        $activeYear = $this->pengaturanModel->getValue('tahun_anggaran');
        if ($activeYear == $tahunRow['tahun']) {
            return redirect()->to('/pengaturan')->with('error', 'Tahun ini tidak bisa dihapus karena sedang aktif digunakan.')->with('active_tab', 'tahun-anggaran');
        }

        $model->delete($id);

        return redirect()->to('/pengaturan')->with('success', 'Tahun Anggaran berhasil dihapus.')->with('active_tab', 'tahun-anggaran');
    }

    public function aktifkanTahunAnggaran($tahun)
    {
        if (session('role') !== 'admin') return redirect()->back();

        $this->pengaturanModel->updateSetting('tahun_anggaran', $tahun);
        cache()->delete('app_settings');

        return redirect()->to('/pengaturan')->with('success', 'Tahun Anggaran ' . $tahun . ' berhasil diaktifkan.')->with('active_tab', 'tahun-anggaran');
    }

    public function storeFormatSurat()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $rules = [
            'nama' => 'required|string|max_length[100]',
            'template' => 'required|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Nama dan template format surat wajib diisi.')->with('active_tab', 'format-surat');
        }

        $model = new FormatSuratModel();
        $model->insert([
            'nama' => $this->request->getPost('nama'),
            'template' => $this->request->getPost('template'),
        ]);

        return redirect()->to('/pengaturan')->with('success', 'Format Surat berhasil ditambahkan.')->with('active_tab', 'format-surat');
    }

    public function updateFormatSurat($id = null)
    {
        if (session('role') !== 'admin') return redirect()->back();

        $model = new FormatSuratModel();
        $format = $model->find($id);
        if (!$format) {
            return redirect()->to('/pengaturan')->with('error', 'Format surat tidak ditemukan.')->with('active_tab', 'format-surat');
        }

        $rules = [
            'nama' => 'required|string|max_length[100]',
            'template' => 'required|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Nama dan template format surat wajib diisi.')->with('active_tab', 'format-surat');
        }

        $model->update($id, [
            'nama' => $this->request->getPost('nama'),
            'template' => $this->request->getPost('template'),
        ]);

        return redirect()->to('/pengaturan')->with('success', 'Format Surat berhasil diperbarui.')->with('active_tab', 'format-surat');
    }

    public function deleteFormatSurat($id = null)
    {
        if (session('role') !== 'admin') return redirect()->back();

        $model = new FormatSuratModel();
        $format = $model->find($id);
        if (!$format) {
            return redirect()->to('/pengaturan')->with('error', 'Format surat tidak ditemukan.')->with('active_tab', 'format-surat');
        }

        $model->delete($id);

        return redirect()->to('/pengaturan')->with('success', 'Format Surat berhasil dihapus.')->with('active_tab', 'format-surat');
    }

    public function updateBukuTamu()
    {
        $role = session('role');
        if ($role !== 'admin' && $role !== 'admin_tamu') return redirect()->back();

        $postData = $this->request->getPost();

        $fields = [
            'buku_tamu_mode', 
            'buku_tamu_open_time', 
            'buku_tamu_close_time', 
            'buku_tamu_closed_message'
        ];

        foreach ($fields as $field) {
            if (isset($postData[$field])) {
                $this->pengaturanModel->updateSetting($field, $postData[$field]);
            }
        }

        // Handle array of days
        if (isset($postData['buku_tamu_work_days']) && is_array($postData['buku_tamu_work_days'])) {
            $days = implode(',', $postData['buku_tamu_work_days']);
            $this->pengaturanModel->updateSetting('buku_tamu_work_days', $days);
        } else {
            $this->pengaturanModel->updateSetting('buku_tamu_work_days', '');
        }

        // Handle switches
        $this->pengaturanModel->updateSetting('buku_tamu_honeypot', isset($postData['buku_tamu_honeypot']) ? '1' : '0');
        $this->pengaturanModel->updateSetting('buku_tamu_throttling', isset($postData['buku_tamu_throttling']) ? '1' : '0');

        cache()->delete('app_settings');
        return redirect()->to('/pengaturan')->with('success', 'Pengaturan Buku Tamu berhasil diperbarui.')->with('active_tab', 'buku-tamu');
    }

    /**
     * API: Mengembalikan link folder Google Drive dalam format JSON.
     * Dipanggil via AJAX dari form Surat Masuk & Surat Keluar.
     */
    public function getLinkDrive()
    {
        $linkDrive = $this->pengaturanModel->getValue('link_folder_drive');

        return $this->response->setJSON([
            'status'  => true,
            'link'    => $linkDrive ?? '',
        ]);
    }
}