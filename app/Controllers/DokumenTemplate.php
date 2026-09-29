<?php

namespace App\Controllers;

use App\Models\TemplateDokumenModel;
use App\Models\TemplateDokumenFieldModel;
use App\Models\DataGuruModel;
use App\Models\PengaturanModel;
use App\Models\SuratKeluarModel;
use App\Models\FormatSuratModel;
use App\Libraries\WordTemplateEngine;
use App\Libraries\WordTemplateScanner;
use Exception;

class DokumenTemplate extends BaseController
{
    protected TemplateDokumenModel $templateModel;
    protected TemplateDokumenFieldModel $fieldModel;
    protected DataGuruModel $guruModel;
    protected PengaturanModel $pengaturanModel;
    protected SuratKeluarModel $suratKeluarModel;

    public function __construct()
    {
        $this->templateModel     = new TemplateDokumenModel();
        $this->fieldModel        = new TemplateDokumenFieldModel();
        $this->guruModel         = new DataGuruModel();
        $this->pengaturanModel   = new PengaturanModel();
        $this->suratKeluarModel  = new SuratKeluarModel();
        helper(['form', 'url', 'tanggal']);
    }

    /**
     * Katalog Template Surat
     */
    public function index()
    {
        $data = [
            'title'       => 'Generator Dokumen Word (Template)',
            'templates'   => $this->templateModel->getActiveTemplates(),
            'appSettings' => $this->pengaturanModel->getSettings(),
        ];

        return view('dokumen_template/index', $data);
    }

    /**
     * Tampilkan Form Input Sesuai Template
     */
    public function create(string $kode)
    {
        $template = $this->templateModel->getByKode($kode);
        if (!$template) {
            return redirect()->to('/dokumen-template')->with('error', 'Template dokumen tidak ditemukan.');
        }

        // Cek keberadaan file fisik di server
        $filePath = WRITEPATH . $template['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->to('/dokumen-template')->with('error', "Peringatan: File fisik template '{$template['nama']}' tidak ada di server! ({$template['file_path']})");
        }

        $settings = $this->pengaturanModel->getSettings();
        $gurus = $this->guruModel->orderBy('nama_pegawai', 'ASC')->findAll();

        // Ambil data nomor surat terakhir dari surat keluar
        $lastSurat = $this->suratKeluarModel
            ->where('nomor_surat !=', '')
            ->where('nomor_surat IS NOT NULL', null, false)
            ->orderBy('id', 'DESC')
            ->first();

        $latestNomorSurat = $lastSurat ? $lastSurat['nomor_surat'] : '-';
        $nextNomor = '';

        if ($lastSurat && !empty($lastSurat['nomor_surat'])) {
            $lastNomor = trim($lastSurat['nomor_surat']);
            // Parsing pola nomor surat: prefix non-digit, angka urut, lalu suffix (contoh: "B-060/MI.08.02/...")
            if (preg_match('/^(.*?[^\d])(\d+)(.*)$/', $lastNomor, $matches)) {
                $prefix     = $matches[1];
                $digits     = $matches[2];
                $suffix     = $matches[3];
                $nextDigits = str_pad((int)$digits + 1, strlen($digits), '0', STR_PAD_LEFT);
                $nextNomor  = $prefix . $nextDigits . $suffix;
            } elseif (preg_match('/^(\d+)(.*)$/', $lastNomor, $matches)) {
                $number       = (int)$matches[1] + 1;
                $paddedNumber = str_pad((string)$number, strlen($matches[1]), '0', STR_PAD_LEFT);
                $nextNomor    = $paddedNumber . $matches[2];
            } else {
                $nextNomor = $lastNomor;
            }

            // Jika di akhir nomor terdapat pola bulan/tahun misal /06/2026, sesuaikan ke bulan & tahun sekarang
            $nextNomor = preg_replace('/\/\d{2}\/\d{4}$/', '/' . date('m/Y'), $nextNomor);
        }

        $data = [
            'title'              => 'Buat ' . $template['nama'],
            'template'           => $template,
            'settings'           => $settings,
            'gurus'              => $gurus,
            'latest_nomor_surat' => $latestNomorSurat,
            'nextNomor'          => $nextNomor,
            'fields'             => $template['kategori'] === 'custom' ? $this->fieldModel->getFieldsByTemplate($template['id']) : [],
        ];

        // Pilih view sesuai kategori atau jenis surat
        $viewPath = 'dokumen_template/forms/' . $template['kode'];
        if ($template['kategori'] === 'custom' || !is_file(APPPATH . 'Views/' . $viewPath . '.php')) {
            $viewPath = 'dokumen_template/forms/custom';
        }

        return view($viewPath, $data);
    }

    /**
     * Generate & Download Dokumen Word
     */
    public function generate(string $kode)
    {
        $template = $this->templateModel->getByKode($kode);
        if (!$template) {
            return redirect()->to('/dokumen-template')->with('error', 'Template dokumen tidak ditemukan.');
        }

        $filePath = WRITEPATH . $template['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->back()->withInput()->with('error', 'File fisik template tidak ditemukan di server.');
        }

        $settings = $this->pengaturanModel->getSettings();
        $postData = $this->request->getPost();

        try {
            $engine = new WordTemplateEngine($filePath);

            // Data Kop & Pimpinan Default Sesuai Pengaturan Aplikasi
            $kopKementerian = $settings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA';
            $kopKantor      = $settings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS';
            $kopNama        = $settings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS';
            $kopAlamat      = $settings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378';
            $kopKontak      = $settings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com';
            
            $kepalaNama     = $postData['kepala_nama'] ?? ($settings['pejabat_kepsek_nama'] ?? 'SIPULLOH, M.Pd.I');
            $kepalaNip      = $postData['kepala_nip'] ?? ($settings['pejabat_kepsek_nip'] ?? '197005272007011022');
            $kepalaJabatan  = $postData['kepala_jabatan'] ?? 'Kepala Madrasah';
            $kepalaPangkat  = $postData['kepala_pangkat'] ?? 'Pembina / IV.a';

            $tempatSurat    = $postData['tempat_surat'] ?? 'Tanggamus';
            $tanggalInput   = $postData['tanggal_surat'] ?? date('Y-m-d');
            $tanggalSurat   = function_exists('format_tanggal_indo') ? format_tanggal_indo($tanggalInput) : date('d F Y', strtotime($tanggalInput));

            $engine->setValues([
                'kop_kementerian'        => $kopKementerian,
                'kop_kantor_kementerian' => $kopKantor,
                'kop_nama'               => $kopNama,
                'kop_alamat'             => $kopAlamat,
                'kop_kontak'             => $kopKontak,
                'nomor_surat'            => $postData['nomor_surat'] ?? '-',
                'tempat_surat'           => $tempatSurat,
                'tanggal_surat'          => $tanggalSurat,
                'kepala_nama'            => $kepalaNama,
                'kepala_nip'             => $kepalaNip,
                'kepala_jabatan'         => $kepalaJabatan,
                'kepala_pangkat'         => $kepalaPangkat,
            ]);

            // Handling Khusus Tiap Jenis Surat
            if ($kode === 'surat_tugas') {
                $engine->setValues([
                    'keperluan'        => $postData['keperluan'] ?? '-',
                    'tempat_tujuan'    => $postData['tempat_tujuan'] ?? '-',
                    'tanggal_kegiatan' => $postData['tanggal_kegiatan'] ?? '-',
                ]);

                // Multi-pegawai repeater
                $pegawaiList = [];
                $postPegawai = $this->request->getPost('pegawai');
                if (!empty($postPegawai) && is_array($postPegawai)) {
                    $no = 1;
                    foreach ($postPegawai as $p) {
                        if (!empty($p['nama'])) {
                            $pegawaiList[] = [
                                'no'              => $no++,
                                'pegawai_nama'    => $p['nama'],
                                'pegawai_nip'     => !empty($p['nip']) ? $p['nip'] : '-',
                                'pegawai_pangkat' => !empty($p['pangkat']) ? $p['pangkat'] : '-',
                                'pegawai_jabatan' => !empty($p['jabatan']) ? $p['jabatan'] : '-',
                            ];
                        }
                    }
                }

                if (empty($pegawaiList)) {
                    $pegawaiList[] = [
                        'no'              => 1,
                        'pegawai_nama'    => $postData['pegawai_nama_single'] ?? '-',
                        'pegawai_nip'     => $postData['pegawai_nip_single'] ?? '-',
                        'pegawai_pangkat' => $postData['pegawai_pangkat_single'] ?? '-',
                        'pegawai_jabatan' => $postData['pegawai_jabatan_single'] ?? '-',
                    ];
                }

                $engine->cloneRowData('pegawai_nama', $pegawaiList);

            } elseif ($kode === 'spd') {
                $engine->setValues([
                    'pegawai_nama'       => $postData['pegawai_nama'] ?? '-',
                    'pegawai_nip'        => $postData['pegawai_nip'] ?? '-',
                    'pegawai_pangkat'    => $postData['pegawai_pangkat'] ?? '-',
                    'pegawai_jabatan'    => $postData['pegawai_jabatan'] ?? '-',
                    'tingkat_biaya'      => $postData['tingkat_biaya'] ?? 'Tingkat D / Standar',
                    'maksud_perjalanan'  => $postData['maksud_perjalanan'] ?? '-',
                    'alat_angkutan'      => $postData['alat_angkutan'] ?? 'Kendaraan Darat / Dinas',
                    'tempat_berangkat'   => $postData['tempat_berangkat'] ?? 'MIN 2 Tanggamus',
                    'tempat_tujuan'      => $postData['tempat_tujuan'] ?? '-',
                    'lama_perjalanan'    => $postData['lama_perjalanan'] ?? '1',
                    'tanggal_berangkat'  => !empty($postData['tanggal_berangkat']) ? (function_exists('format_tanggal_indo') ? format_tanggal_indo($postData['tanggal_berangkat']) : $postData['tanggal_berangkat']) : '-',
                    'tanggal_kembali'    => !empty($postData['tanggal_kembali']) ? (function_exists('format_tanggal_indo') ? format_tanggal_indo($postData['tanggal_kembali']) : $postData['tanggal_kembali']) : '-',
                    'daftar_pengikut'    => $postData['daftar_pengikut'] ?? '-',
                    'instansi_anggaran'  => $postData['instansi_anggaran'] ?? 'DIPA MIN 2 Tanggamus',
                    'mata_anggaran'      => $postData['mata_anggaran'] ?? '-',
                    'keterangan_lain'    => $postData['keterangan_lain'] ?? '-',
                ]);

            } elseif ($kode === 'surat_kuasa') {
                $engine->setValues([
                    'pemberi_nama'     => $postData['pemberi_nama'] ?? $kepalaNama,
                    'pemberi_nip'      => $postData['pemberi_nip'] ?? $kepalaNip,
                    'pemberi_jabatan'  => $postData['pemberi_jabatan'] ?? $kepalaJabatan,
                    'pemberi_alamat'   => $postData['pemberi_alamat'] ?? $kopAlamat,
                    'penerima_nama'    => $postData['penerima_nama'] ?? '-',
                    'penerima_nip'     => $postData['penerima_nip'] ?? '-',
                    'penerima_jabatan' => $postData['penerima_jabatan'] ?? '-',
                    'penerima_alamat'  => $postData['penerima_alamat'] ?? '-',
                    'keperluan_kuasa'  => $postData['keperluan_kuasa'] ?? '-',
                ]);

            } elseif ($kode === 'sk') {
                $engine->setValues([
                    'tentang_sk'    => strtoupper($postData['tentang_sk'] ?? '-'),
                    'menimbang'     => $postData['menimbang'] ?? '-',
                    'mengingat'     => $postData['mengingat'] ?? '-',
                    'diktum_kesatu' => $postData['diktum_kesatu'] ?? '-',
                    'diktum_kedua'  => $postData['diktum_kedua'] ?? '-',
                ]);

            } elseif ($kode === 'gangguan_absen') {
                $tglGangguan = $postData['hari_tanggal_gangguan'] ?? date('Y-m-d');
                $engine->setValues([
                    'lampiran'              => $postData['lampiran'] ?? '1 (satu) Berkas',
                    'hari_tanggal_gangguan' => function_exists('format_tanggal_indo') ? format_tanggal_indo($tglGangguan) : $tglGangguan,
                    'waktu_gangguan'        => $postData['waktu_gangguan'] ?? '06.30 - 08.00 WIB',
                    'keterangan_gangguan'   => $postData['keterangan_gangguan'] ?? 'Server PUSAKA mengalami kendala error 500 / Network Timeout.',
                ]);

            } else {
                // Custom Template
                $fields = $this->fieldModel->getFieldsByTemplate($template['id']);
                foreach ($fields as $f) {
                    $key = $f['field_key'];
                    $val = $postData[$key] ?? '';
                    if ($f['field_type'] === 'date' && !empty($val)) {
                        $val = function_exists('format_tanggal_indo') ? format_tanggal_indo($val) : $val;
                    }
                    $engine->setValue($key, (string)$val);
                }
            }

            // Simpan file hasil generate
            $cleanNomor = preg_replace('/[^a-zA-Z0-9_-]/', '_', $postData['nomor_surat'] ?? 'surat');
            $cleanNama = preg_replace('/[^a-zA-Z0-9_-]/', '_', $template['nama']);
            $outputFilename = $cleanNama . '_' . $cleanNomor . '_' . date('Ymd_His') . '.docx';
            $outputPath = WRITEPATH . 'generated/' . $outputFilename;

            $engine->saveAs($outputPath);

            // Opsional: Catat ke Agenda Surat Keluar jika dicentang
            if (!empty($postData['catat_surat_keluar']) && $postData['catat_surat_keluar'] === '1') {
                $suratService = new \App\Services\SuratService();
                $tahunAgenda  = date('Y', strtotime($tanggalInput));
                $nomorAgenda  = $suratService->generateNextNomorAgendaKeluar($tahunAgenda);

                $this->suratKeluarModel->insert([
                    'nomor_agenda'     => $nomorAgenda,
                    'nomor_surat'      => $postData['nomor_surat'] ?? '',
                    'tanggal_surat'    => $tanggalInput,
                    'tujuan'           => $postData['tempat_tujuan'] ?? ($postData['penerima_nama'] ?? ($postData['tentang_sk'] ?? $template['nama'])),
                    'perihal'          => $postData['keperluan'] ?? ($postData['keperluan_kuasa'] ?? ($postData['tentang_sk'] ?? $template['nama'])),
                    'tipe_penyimpanan' => 'lokal',
                    'lampiran'         => 0,
                    'keterangan'       => 'Digenerate via Template Dokumen Word: ' . $template['nama'],
                    'status'           => 'disetujui',
                    'created_by'       => session('user_id') ?? (session('id') ?? 1),
                ]);
            }

            // Download file ke browser pengguna
            return $this->response->download($outputPath, null)->setFileName($outputFilename);

        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memproses dokumen: ' . $e->getMessage());
        }
    }

    /**
     * Download File Source Template .docx Asli
     */
    public function downloadTemplateSource(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to('/dokumen-template')->with('error', 'Template tidak ditemukan.');
        }

        $filePath = WRITEPATH . $template['file_path'];
        if (!file_exists($filePath)) {
            return redirect()->to('/dokumen-template')->with('error', 'File template fisik tidak ditemukan.');
        }

        return $this->response->download($filePath, null)->setFileName(basename($filePath));
    }

    /**
     * Form Upload Template Kustom (Admin/Operator)
     */
    public function upload()
    {
        $data = [
            'title' => 'Upload Template Kustom (.docx)',
        ];
        return view('dokumen_template/upload', $data);
    }

    /**
     * Proses Upload File .docx & Ekstraksi Tag Placeholder
     */
    public function storeUpload()
    {
        $rules = [
            'nama'      => 'required|max_length[150]',
            'deskripsi' => 'permit_empty|max_length[500]',
            'file_docx' => 'uploaded[file_docx]|ext_in[file_docx,docx]|max_size[file_docx,10240]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Silakan periksa form dan pastikan file berformat .docx (maks 10MB).')->with('validation', \Config\Services::validation());
        }

        $file = $this->request->getFile('file_docx');
        $nama = $this->request->getPost('nama');
        $kode = 'custom_' . strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', trim($nama))) . '_' . uniqid();

        $newName = $kode . '.docx';
        $targetDir = WRITEPATH . 'templates/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $file->move($targetDir, $newName);
        $savedPath = $targetDir . $newName;

        // Scan placeholder dari file .docx
        $scanner = new WordTemplateScanner();
        try {
            $tags = $scanner->scan($savedPath);
        } catch (Exception $e) {
            @unlink($savedPath);
            return redirect()->back()->withInput()->with('error', 'Gagal memindai placeholder: ' . $e->getMessage());
        }

        // Simpan ke template_dokumen
        $templateId = $this->templateModel->insert([
            'kode'            => $kode,
            'nama'            => $nama,
            'kategori'        => 'custom',
            'file_path'       => 'templates/' . $newName,
            'deskripsi'       => $this->request->getPost('deskripsi'),
            'is_has_repeater' => 0,
            'is_active'       => 1,
        ]);

        // Simpan default fields dari hasil scanning
        $order = 1;
        foreach ($tags as $tag) {
            $label = ucwords(str_replace('_', ' ', $tag));
            $type  = 'text';
            if (strpos($tag, 'tanggal') !== false || strpos($tag, 'tgl') !== false) {
                $type = 'date';
            } elseif (strpos($tag, 'keperluan') !== false || strpos($tag, 'isi') !== false || strpos($tag, 'uraian') !== false) {
                $type = 'textarea';
            } elseif (strpos($tag, 'guru') !== false || strpos($tag, 'pegawai') !== false) {
                $type = 'guru_select';
            }

            $this->fieldModel->insert([
                'template_id' => $templateId,
                'field_key'   => $tag,
                'field_label' => $label,
                'field_type'  => $type,
                'is_required' => 1,
                'urutan'      => $order++,
            ]);
        }

        return redirect()->to('/dokumen-template/configure-fields/' . $templateId)
                         ->with('success', 'Template berhasil diupload! Sistem menemukan ' . count($tags) . ' placeholder tag.');
    }

    /**
     * Konfigurasi Field Hasil Scanning
     */
    public function configureFields(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template || $template['kategori'] !== 'custom') {
            return redirect()->to('/dokumen-template')->with('error', 'Template kustom tidak ditemukan.');
        }

        $fields = $this->fieldModel->getFieldsByTemplate($id);

        $data = [
            'title'    => 'Konfigurasi Field Template: ' . $template['nama'],
            'template' => $template,
            'fields'   => $fields,
        ];

        return view('dokumen_template/configure_fields', $data);
    }

    /**
     * Simpan Konfigurasi Field
     */
    public function saveFields(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to('/dokumen-template')->with('error', 'Template tidak ditemukan.');
        }

        $fieldsPost = $this->request->getPost('fields');
        if (!empty($fieldsPost) && is_array($fieldsPost)) {
            foreach ($fieldsPost as $fId => $fData) {
                $this->fieldModel->update($fId, [
                    'field_label' => $fData['label'] ?? '',
                    'field_type'  => $fData['type'] ?? 'text',
                    'is_required' => isset($fData['is_required']) ? 1 : 0,
                    'urutan'      => (int)($fData['urutan'] ?? 0),
                ]);
            }
        }

        return redirect()->to('/dokumen-template')->with('success', 'Konfigurasi field template berhasil disimpan!');
    }

    /**
     * Hapus Template Kustom
     */
    public function delete(int $id)
    {
        $template = $this->templateModel->find($id);
        if (!$template) {
            return redirect()->to('/dokumen-template')->with('error', 'Template tidak ditemukan.');
        }

        if ($template['kategori'] === 'builtin') {
            return redirect()->to('/dokumen-template')->with('error', 'Template bawaan sistem tidak boleh dihapus.');
        }

        $filePath = WRITEPATH . $template['file_path'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        $this->templateModel->delete($id);
        return redirect()->to('/dokumen-template')->with('success', 'Template kustom berhasil dihapus.');
    }
}
