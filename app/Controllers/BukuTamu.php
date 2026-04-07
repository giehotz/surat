<?php

namespace App\Controllers;

use App\Models\TamuModel;
use App\Models\KunjunganModel;

class BukuTamu extends BaseController
{
    protected $tamuModel;
    protected $kunjunganModel;

    public function __construct()
    {
        $this->tamuModel      = new TamuModel();
        $this->kunjunganModel = new KunjunganModel();
    }

    public function index()
    {
        if (!$this->isGuestbookOpen()) {
            return view('buku_tamu/publik/closed', ['message' => $this->appSettings['buku_tamu_closed_message'] ?? 'Layanan sedang ditutup.']);
        }
        return view('buku_tamu/publik/index');
    }

    public function formUmum()
    {
        if (!$this->isGuestbookOpen()) return redirect()->to('/buku-tamu');
        
        $dbGurus = (new \App\Models\DataGuruModel())->findAll() ?: [];
        $data['guruList'] = array_merge([
            ['id' => 'Kepala Madrasah', 'nama_pegawai' => 'Kepala Madrasah'],
            ['id' => 'Staf Tata Usaha', 'nama_pegawai' => 'Staf Tata Usaha']
        ], $dbGurus);
        return view('buku_tamu/publik/form_umum', $data);
    }

    public function formDinas()
    {
        if (!$this->isGuestbookOpen()) return redirect()->to('/buku-tamu');

        $dbGurus = (new \App\Models\DataGuruModel())->findAll() ?: [];
        $data['guruList'] = array_merge([
            ['id' => 'Kepala Madrasah', 'nama_pegawai' => 'Kepala Madrasah'],
            ['id' => 'Staf Tata Usaha', 'nama_pegawai' => 'Staf Tata Usaha']
        ], $dbGurus);
        return view('buku_tamu/publik/form_dinas', $data);
    }

    public function store()
    {
        // 1. Check if closed
        if (!$this->isGuestbookOpen()) {
            return redirect()->to('/buku-tamu')->with('error', 'Maaf, layanan sudah ditutup.');
        }

        // 2. Throttling (Spam Protection)
        if (($this->appSettings['buku_tamu_throttling'] ?? '1') == '1') {
            $throttler = \Config\Services::throttler();
            // Max 10 submissions per hour per IP
            if ($throttler->check(md5($this->request->getIPAddress()), 10, HOUR) === false) {
                return redirect()->back()->withInput()->with('error', 'Terlalu banyak pengiriman data. Mohon tunggu beberapa saat lagi.');
            }
        }

        $jenis = $this->request->getPost('jenis_tamu'); // 'umum' atau 'khusus'

        $tamuData = [
            'jenis_tamu'      => $jenis,
            'nama_lengkap'    => $this->request->getPost('nama_lengkap'),
            'alamat_instansi' => $this->request->getPost('alamat_instansi'),
            'nip'             => $this->request->getPost('nip'), // bisa null
            'jabatan'         => $this->request->getPost('jabatan'), // bisa null
            'no_hp'           => $this->request->getPost('no_hp'),
            'consent_wa'      => $this->request->getPost('consent_wa') ? 1 : 0,
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        $id_tamu = $this->tamuModel->findOrCreateTamu($tamuData);

        // Upload berkas jika tamu dinas
        $dokumen_pendukung = null;
        $dokumen = $this->request->getFile('dokumen_pendukung');
        if ($dokumen && $dokumen->isValid() && !$dokumen->hasMoved()) {
            $newName = $dokumen->getRandomName();
            $dokumen->move('uploads/tamu/dokumen', $newName);
            $dokumen_pendukung = 'uploads/tamu/dokumen/' . $newName;
        }

        // 1. Proses Foto Wajah (Manual Upload atau Webcam)
        $foto_wajah_path = null;
        $foto_file = $this->request->getFile('foto_wajah_file');

        if ($foto_file && $foto_file->isValid() && !$foto_file->hasMoved()) {
            // Jika ada unggahan file manual
            $newName = $foto_file->getRandomName();
            $foto_file->move('uploads/tamu/foto', $newName);
            $foto_wajah_path = 'uploads/tamu/foto/' . $newName;
        } else {
            // Jika menggunakan kamera (Base64)
            $foto_base64 = $this->request->getPost('foto_wajah_base64');
            if (!empty($foto_base64)) {
                $foto_wajah_path = $this->saveBase64Image($foto_base64, 'foto');
            }
        }

        // 2. Proses Tanda Tangan (Selalu Base64 dari Canvas)
        $tanda_tangan_path = null;
        $ttd_base64 = $this->request->getPost('tanda_tangan_base64');
        if (!empty($ttd_base64)) {
            $tanda_tangan_path = $this->saveBase64Image($ttd_base64, 'ttd');
        }

        $kunjunganData = [
            'id_tamu'           => $id_tamu,
            'tanggal_waktu'     => date('Y-m-d H:i:s'),
            'tujuan_kunjungan'  => $this->request->getPost('tujuan_kunjungan'),
            'id_pegawai_dituju' => $this->request->getPost('id_pegawai_dituju') ? $this->request->getPost('id_pegawai_dituju') : null,
            'id_siswa_dituju'   => $this->request->getPost('id_siswa_dituju') ? $this->request->getPost('id_siswa_dituju') : null,
            'pesan_kesan'       => $this->request->getPost('pesan_kesan'),
            'dokumen_pendukung' => $dokumen_pendukung,
            'foto_wajah'        => $foto_wajah_path,
            'tanda_tangan'      => $tanda_tangan_path,
            'status_kunjungan'  => 'menunggu'
        ];

        $this->kunjunganModel->insert($kunjunganData);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data tamu.');
        }

        return redirect()->to('/buku-tamu/success');
    }

    public function successPage()
    {
        return view('buku_tamu/publik/success');
    }

    /**
     * Logika pengecekan status operasional Buku Tamu
     */
    private function isGuestbookOpen(): bool
    {
        $mode = $this->appSettings['buku_tamu_mode'] ?? 'auto';

        if ($mode === 'open') return true;
        if ($mode === 'closed') return false;

        // Auto Schedule Logic
        $dayNow = date('w'); // 0 (Minggu) - 6 (Sabtu)
        $timeNow = date('H:i');
        
        $workDays = explode(',', $this->appSettings['buku_tamu_work_days'] ?? '1,2,3,4,5,6');
        $openTime = $this->appSettings['buku_tamu_open_time'] ?? '07:30';
        $closeTime = $this->appSettings['buku_tamu_close_time'] ?? '16:00';

        // Check Day
        if (!in_array((string)$dayNow, $workDays)) return false;

        // Check Time
        return true;
    }

    /**
     * Konversi Base64 ke File dan simpan di folder uploads/tamu
     */
    private function saveBase64Image($base64, $subfolder)
    {
        if (empty($base64)) return null;

        // Pisahkan header dan data
        if (strpos($base64, ',') !== false) {
            $parts = explode(',', $base64);
            $data = $parts[1];
            $extension = 'png';
            if (strpos($parts[0], 'jpeg') !== false) $extension = 'jpg';
        } else {
            $data = $base64;
            $extension = 'png';
        }

        $decodedData = base64_decode(str_replace(' ', '+', $data));
        if (!$decodedData) return null;

        $filename = $subfolder . '_' . time() . '_' . uniqid() . '.' . $extension;
        $relativePath = 'uploads/tamu/' . $subfolder . '/' . $filename;
        $absolutePath = FCPATH . $relativePath;

        if (file_put_contents($absolutePath, $decodedData)) {
            return $relativePath;
        }

        return null;
    }
}
