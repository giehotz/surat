<?php

namespace App\Controllers;

use App\Models\PengaturanModel;
use App\Models\TahunAnggaranModel;
use App\Models\FormatSuratModel;
use App\Models\UserModel;
use App\Models\WajibFieldPengaturanModel;
use App\Services\SuratService;

class Pengaturan extends BaseController
{
    protected PengaturanModel $pengaturanModel;
    protected SuratService $suratService;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
        $this->suratService    = new SuratService();
    }

    public function index()
    {
        $role = session('role');
        if ($role !== 'admin' && $role !== 'admin_tamu') {
            return redirect()->to('/dashboard')->with('error', 'Akses ditolak. Anda tidak memiliki izin.');
        }

        $userModel       = new UserModel();
        $wajibFieldModel = new WajibFieldPengaturanModel();

        $data = [
            'title'               => 'Pengaturan Aplikasi',
            'settings'            => $this->pengaturanModel->getSettings(),
            'active_tab'          => session()->getFlashdata('active_tab') ?? $this->request->getGet('active_tab') ?? 'identitas',
            'users'               => $userModel->findAll(),
            'wajib_fields'        => $wajibFieldModel->getPengaturanByForm('surat_keluar'),
            'tahun_anggaran_list' => (new TahunAnggaranModel())->getList(),
            'format_surat_list'   => (new FormatSuratModel())->findAll()
        ];

        return view('pengaturan/index', $data);
    }

    public function updateIdentitas()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $rules = [
            'sekolah_nama'   => 'required',
            'sekolah_alamat' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Nama institusi dan alamat wajib diisi.')->with('active_tab', 'identitas');
        }

        $this->saveSettingFields(['sekolah_kementerian', 'sekolah_nama', 'sekolah_npsn', 'sekolah_nsm', 'sekolah_alamat', 'sekolah_kontak']);

        // Handle Logo Upload
        $fileLogo = $this->request->getFile('sekolah_logo');
        if ($fileLogo && $fileLogo->isValid() && !$fileLogo->hasMoved()) {
            $logoRules = [
                'sekolah_logo' => 'uploaded[sekolah_logo]|is_image[sekolah_logo]|mime_in[sekolah_logo,image/jpg,image/jpeg,image/png]|max_size[sekolah_logo,2048]'
            ];

            if ($this->validate($logoRules)) {
                $oldLogo = $this->pengaturanModel->getValue('sekolah_logo');
                if ($oldLogo && file_exists(FCPATH . 'uploads/logo/' . $oldLogo)) {
                    @unlink(FCPATH . 'uploads/logo/' . $oldLogo);
                }

                $targetDir = FCPATH . 'uploads/logo';
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $newName = $fileLogo->getRandomName();
                $fileLogo->move($targetDir, $newName);
                $this->pengaturanModel->updateSetting('sekolah_logo', $newName);
            } else {
                return redirect()->to('/pengaturan')->withInput()->with('error', $this->validator->getErrors()['sekolah_logo'])->with('active_tab', 'identitas');
            }
        }

        $this->invalidateSettingsCache();
        return redirect()->to('/pengaturan')->with('success', 'Identitas Sekolah berhasil diperbarui.')->with('active_tab', 'identitas');
    }

    public function updatePimpinan()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $this->saveSettingFields(['pejabat_kepsek_nama', 'pejabat_kepsek_nip', 'pejabat_tu_nama', 'pejabat_tu_nip']);
        $this->invalidateSettingsCache();

        return redirect()->to('/pengaturan')->with('success', 'Data Pimpinan Sekolah berhasil diperbarui.')->with('active_tab', 'pimpinan');
    }

    public function updatePreferensi()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $postData = $this->request->getPost();
        $this->saveSettingFields(['app_nama', 'tahun_anggaran', 'link_folder_drive']);

        $metode = isset($postData['metode_lampiran']) && is_array($postData['metode_lampiran'])
            ? implode(',', $postData['metode_lampiran'])
            : '';
        $this->pengaturanModel->updateSetting('metode_lampiran', $metode);

        $this->invalidateSettingsCache();
        return redirect()->to('/pengaturan')->with('success', 'Preferensi Sistem berhasil diperbarui.')->with('active_tab', 'preferensi');
    }

    public function updateWajibField()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $wajibFieldModel = new WajibFieldPengaturanModel();
        $postData  = $this->request->getPost('wajib_field') ?? [];
        $allFields = $this->request->getPost('all_fields') ?? [];

        foreach ($allFields as $fieldName) {
            $isChecked = isset($postData[$fieldName]) && $postData[$fieldName] === '1';
            $wajibFieldModel->updatePengaturan('surat_keluar', $fieldName, $isChecked);
        }

        $this->invalidateSettingsCache();
        return redirect()->to('/pengaturan')->with('success', 'Pengaturan wajib field berhasil diperbarui.')->with('active_tab', 'wajib_field');
    }

    public function deleteLogo()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $oldLogo = $this->pengaturanModel->getValue('sekolah_logo');
        if ($oldLogo && file_exists(FCPATH . 'uploads/logo/' . $oldLogo)) {
            @unlink(FCPATH . 'uploads/logo/' . $oldLogo);
        }

        $this->pengaturanModel->updateSetting('sekolah_logo', '');
        $this->invalidateSettingsCache();

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
        $this->invalidateSettingsCache();

        return redirect()->to('/pengaturan')->with('success', 'Tahun Anggaran ' . $tahun . ' berhasil diaktifkan.')->with('active_tab', 'tahun-anggaran');
    }

    public function storeFormatSurat()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $rules = [
            'nama'     => 'required|string|max_length[100]',
            'template' => 'required|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Nama dan template format surat wajib diisi.')->with('active_tab', 'format-surat');
        }

        $model = new FormatSuratModel();
        $model->insert([
            'nama'     => $this->request->getPost('nama'),
            'template' => $this->request->getPost('template'),
        ]);

        return redirect()->to('/pengaturan')->with('success', 'Format Surat berhasil ditambahkan.')->with('active_tab', 'format-surat');
    }

    public function updateFormatSurat($id = null)
    {
        if (session('role') !== 'admin') return redirect()->back();

        $model  = new FormatSuratModel();
        $format = $model->find($id);
        if (!$format) {
            return redirect()->to('/pengaturan')->with('error', 'Format surat tidak ditemukan.')->with('active_tab', 'format-surat');
        }

        $rules = [
            'nama'     => 'required|string|max_length[100]',
            'template' => 'required|string|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/pengaturan')->withInput()->with('error', 'Nama dan template format surat wajib diisi.')->with('active_tab', 'format-surat');
        }

        $model->update($id, [
            'nama'     => $this->request->getPost('nama'),
            'template' => $this->request->getPost('template'),
        ]);

        return redirect()->to('/pengaturan')->with('success', 'Format Surat berhasil diperbarui.')->with('active_tab', 'format-surat');
    }

    public function deleteFormatSurat($id = null)
    {
        if (session('role') !== 'admin') return redirect()->back();

        $model  = new FormatSuratModel();
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
        $this->saveSettingFields([
            'buku_tamu_mode',
            'buku_tamu_open_time',
            'buku_tamu_close_time',
            'buku_tamu_closed_message'
        ]);

        $days = isset($postData['buku_tamu_work_days']) && is_array($postData['buku_tamu_work_days'])
            ? implode(',', $postData['buku_tamu_work_days'])
            : '';
        $this->pengaturanModel->updateSetting('buku_tamu_work_days', $days);

        $this->pengaturanModel->updateSetting('buku_tamu_honeypot', isset($postData['buku_tamu_honeypot']) ? '1' : '0');
        $this->pengaturanModel->updateSetting('buku_tamu_throttling', isset($postData['buku_tamu_throttling']) ? '1' : '0');

        $this->invalidateSettingsCache();
        return redirect()->to('/pengaturan')->with('success', 'Pengaturan Buku Tamu berhasil diperbarui.')->with('active_tab', 'buku-tamu');
    }

    public function updateKopSurat()
    {
        if (session('role') !== 'admin') return redirect()->back();

        $this->saveSettingFields(['sekolah_kementerian', 'sekolah_kantor_kementerian', 'sekolah_nama', 'sekolah_alamat', 'sekolah_kontak']);
        $this->invalidateSettingsCache();

        return redirect()->to('/pengaturan')->with('success', 'Pengaturan Kop Surat berhasil disimpan.')->with('active_tab', 'kop-surat');
    }

    public function getLinkDrive()
    {
        $linkDrive = $this->pengaturanModel->getValue('link_folder_drive');

        return $this->response->setJSON([
            'status' => true,
            'link'   => $linkDrive ?? '',
        ]);
    }

    /**
     * Helper privat untuk menyimpan serangkaian field ke pengaturan
     */
    private function saveSettingFields(array $fields): void
    {
        $postData = $this->request->getPost();
        foreach ($fields as $field) {
            if (isset($postData[$field])) {
                $this->pengaturanModel->updateSetting($field, $postData[$field]);
            }
        }
    }

    /**
     * Invalidate settings cache
     */
    private function invalidateSettingsCache(): void
    {
        cache()->delete('app_settings');
    }
}