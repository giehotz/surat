<?php

namespace App\Controllers;

use App\Models\SuratResmiModel;
use App\Models\PengaturanModel;
use App\Models\TemplateSuratResmiModel;

class SuratResmi extends BaseController
{
    protected $suratResmiModel;
    protected $pengaturanModel;
    protected $templateModel;

    public function __construct()
    {
        $this->suratResmiModel = new SuratResmiModel();
        $this->pengaturanModel = new PengaturanModel();
        $this->templateModel = new TemplateSuratResmiModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pembuat Surat Resmi',
            'surat_resmi' => $this->suratResmiModel->orderBy('created_at', 'DESC')->findAll(),
            'appSettings' => $this->pengaturanModel->getSettings()
        ];
        return view('surat_resmi/index', $data);
    }

    public function create()
    {
        $lastSurat = $this->suratResmiModel->orderBy('id', 'DESC')->first();
        $nextNomor = '';
        
        if ($lastSurat && !empty($lastSurat['nomor_surat'])) {
            $lastNomor = $lastSurat['nomor_surat'];
            if (preg_match('/^(\d+)(.*)$/', $lastNomor, $matches)) {
                $number = (int)$matches[1] + 1;
                $paddedNumber = str_pad($number, strlen($matches[1]), '0', STR_PAD_LEFT);
                $nextNomor = $paddedNumber . $matches[2];
            } else {
                $nextNomor = $lastNomor; 
            }
        }

        $data = [
            'title' => 'Buat Surat Resmi Baru',
            'next_nomor' => $nextNomor,
            'user_jabatan' => session('jabatan'),
            'user_nama' => session('nama_lengkap'),
            'templates' => $this->templateModel->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('surat_resmi/FormSurat/index', $data);
    }

    public function store()
    {
        $rules = $this->suratResmiModel->getValidationRules();
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali isian form Anda.')->with('validation', \Config\Services::validation());
        }

        $data = $this->request->getPost();
        
        if ($this->suratResmiModel->insert($data)) {
            return redirect()->to('/surat-resmi')->with('success', 'Surat resmi berhasil dibuat.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan surat resmi.');
        }
    }

    public function edit($id)
    {
        $surat = $this->suratResmiModel->find($id);
        if (!$surat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Surat Resmi',
            'surat' => $surat,
            'user_jabatan' => session('jabatan'),
            'user_nama' => session('nama_lengkap'),
            'templates' => $this->templateModel->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('surat_resmi/FormSurat/index', $data);
    }

    public function update($id)
    {
        $surat = $this->suratResmiModel->find($id);
        if (!$surat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = $this->suratResmiModel->getValidationRules();
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali isian form Anda.')->with('validation', \Config\Services::validation());
        }

        $data = $this->request->getPost();

        if ($this->suratResmiModel->update($id, $data)) {
            return redirect()->to('/surat-resmi')->with('success', 'Surat resmi berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui surat resmi.');
        }
    }

    public function delete($id)
    {
        $surat = $this->suratResmiModel->find($id);
        if (!$surat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->suratResmiModel->delete($id);
        return redirect()->to('/surat-resmi')->with('success', 'Surat resmi berhasil dihapus.');
    }

    public function printPdf($id)
    {
        $surat = $this->suratResmiModel->find($id);
        if (!$surat) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Cetak Surat Resmi',
            'surat' => $surat,
            'appSettings' => $this->pengaturanModel->getSettings()
        ];
        
        return view('surat_resmi/print', $data);
    }

    public function previewPdf()
    {
        // Ambil data POST dari form
        $postData = $this->request->getPost();
        
        // Render menggunakan view print.php
        $data = [
            'title' => 'Preview Surat Resmi',
            'surat' => $postData,
            'appSettings' => $this->pengaturanModel->getSettings()
        ];
        
        return view('surat_resmi/print', $data);
    }

    // ---- Template CRUD ----

    public function template()
    {
        $data = [
            'title' => 'Daftar Template Surat',
            'templates' => $this->templateModel->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('surat_resmi/Template/index', $data);
    }

    public function templateCreate()
    {
        $data = [
            'title' => 'Buat Template Baru',
        ];
        return view('surat_resmi/Template/form', $data);
    }

    public function templateStore()
    {
        $rules = $this->templateModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali isian form.')->with('validation', \Config\Services::validation());
        }

        $post = $this->request->getPost();
        $post['slug'] = $this->generateSlug($post['slug'] ?? $post['nama']);

        if ($this->templateModel->insert($post)) {
            return redirect()->to('/surat-resmi/template')->with('success', 'Template berhasil dibuat.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan template.');
        }
    }

    public function templateEdit($id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Template',
            'template' => $template,
        ];
        return view('surat_resmi/Template/form', $data);
    }

    public function templateUpdate($id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = $this->templateModel->getValidationRules();

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa kembali isian form.')->with('validation', \Config\Services::validation());
        }

        $post = $this->request->getPost();

        if ($this->templateModel->update($id, $post)) {
            return redirect()->to('/surat-resmi/template')->with('success', 'Template berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui template.');
        }
    }

    public function templateDelete($id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $this->templateModel->delete($id);
        return redirect()->to('/surat-resmi/template')->with('success', 'Template berhasil dihapus.');
    }

    private function generateSlug($string)
    {
        $slug = strtolower($string);
        $slug = preg_replace('/[^a-z0-9]+/', '_', $slug);
        $slug = trim($slug, '_');
        return $slug;
    }

    public function saveKop()
    {
        $data = $this->request->getPost();
        
        if (isset($data['sekolah_kementerian'])) {
            $this->pengaturanModel->updateSetting('sekolah_kementerian', $data['sekolah_kementerian']);
        }
        if (isset($data['sekolah_kantor_kementerian'])) {
            $this->pengaturanModel->updateSetting('sekolah_kantor_kementerian', $data['sekolah_kantor_kementerian']);
        }
        if (isset($data['sekolah_nama'])) {
            $this->pengaturanModel->updateSetting('sekolah_nama', $data['sekolah_nama']);
        }
        if (isset($data['sekolah_alamat'])) {
            $this->pengaturanModel->updateSetting('sekolah_alamat', $data['sekolah_alamat']);
        }
        if (isset($data['sekolah_kontak'])) {
            $this->pengaturanModel->updateSetting('sekolah_kontak', $data['sekolah_kontak']);
        }

        return redirect()->to('/surat-resmi')->with('success', 'Pengaturan Kop Surat berhasil disimpan.');
    }
}
