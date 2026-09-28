<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataGuruModel;
use App\Models\NotulenRapatModel;
use App\Models\LogAktivitasModel;

class DaftarHadir extends BaseController
{
    public function index()
    {
        helper(['tanggal', 'duk']);

        $guruModel    = new DataGuruModel();
        $notulenModel = new NotulenRapatModel();

        // Ambil data guru lengkap untuk kalkulasi DUK (Cakupan 'all': Kamad -> PNS -> PPPK -> Honorer)
        $rawGuru = $guruModel
            ->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_lahir, tanggal_lahir, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, kenaikan_pangkat')
            ->findAll();

        // Urutkan daftar guru sesuai hierarki baku DUK (Kepala Madrasah #1, PNS, PPPK, Honorer)
        $daftarGuru = urutkan_duk($rawGuru, 'all');

        $riwayatNotulen = $notulenModel
            ->orderBy('tanggal_kegiatan', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        $data = [
            'title'           => 'Daftar Hadir & Notulen Rapat',
            'daftar_guru'     => $daftarGuru,
            'riwayat_notulen' => $riwayatNotulen,
            'default_tanggal' => date('Y-m-d'),
            'active_tab'      => session()->getFlashdata('active_tab') ?? 'buat',
        ];

        return view('daftar_hadir/index', $data);
    }

    public function storeNotulen()
    {
        $notulenModel = new NotulenRapatModel();
        $metode       = $this->request->getPost('metode_notulen') ?? 'editor';

        $rules = [
            'judul_rapat'      => 'required|min_length[3]|max_length[255]',
            'tanggal_kegiatan' => 'required|valid_date',
            'metode_notulen'   => 'required|in_list[editor,upload,manual]',
        ];

        $messages = [
            'judul_rapat' => [
                'required'   => 'Judul agenda / rapat wajib diisi.',
                'min_length' => 'Judul rapat minimal 3 karakter.',
            ],
            'tanggal_kegiatan' => [
                'required'   => 'Tanggal kegiatan rapat wajib diisi.',
                'valid_date' => 'Format tanggal kegiatan tidak valid.',
            ],
        ];

        // Validasi khusus berkas upload jika metode upload dipilih
        if ($metode === 'upload') {
            $rules['file_notulen'] = 'uploaded[file_notulen]|max_size[file_notulen,5120]|ext_in[file_notulen,pdf,doc,docx,jpg,jpeg,png]';
            $messages['file_notulen'] = [
                'uploaded' => 'File berkas notulen wajib diunggah.',
                'max_size' => 'Ukuran file berkas maksimal 5MB.',
                'ext_in'   => 'Format file yang diperbolehkan hanya PDF, DOC, DOCX, JPG, JPEG, atau PNG.',
            ];
        }

        if (!$this->validate($rules, $messages)) {
            return redirect()->to('/daftar-hadir')->withInput()->with('errors', $this->validator->getErrors())->with('active_tab', 'buat');
        }

        $fileName = null;
        if ($metode === 'upload') {
            $file = $this->request->getFile('file_notulen');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $uploadDir = FCPATH . 'uploads/notulen';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = $file->getRandomName();
                $file->move($uploadDir, $fileName);
            }
        }

        $saveData = [
            'judul_rapat'      => trim($this->request->getPost('judul_rapat')),
            'subjudul'         => trim($this->request->getPost('subjudul') ?? ''),
            'tanggal_kegiatan' => $this->request->getPost('tanggal_kegiatan'),
            'waktu'            => trim($this->request->getPost('waktu') ?? ''),
            'tempat'           => trim($this->request->getPost('tempat') ?? ''),
            'nama_notulis'     => trim($this->request->getPost('nama_notulis') ?? ''),
            'nip_notulis'      => trim($this->request->getPost('nip_notulis') ?? ''),
            'jumlah_lembar'    => max(1, (int)($this->request->getPost('jumlah_lembar') ?? 1)),
            'metode_notulen'   => $metode,
            'isi_notulen'      => ($metode === 'editor') ? $this->request->getPost('isi_notulen') : null,
            'file_lampiran'    => $fileName,
            'user_id'          => session()->get('user_id') ?? null,
        ];

        if ($notulenModel->insert($saveData)) {
            // Catat log audit aktivitas
            try {
                (new LogAktivitasModel())->insert([
                    'user_id'    => session()->get('user_id'),
                    'aksi'       => 'create',
                    'tipe_surat' => 'notulen',
                    'surat_id'   => $notulenModel->getInsertID(),
                    'detail'     => 'Menyimpan arsip notulen rapat: ' . $saveData['judul_rapat'],
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => (string) $this->request->getUserAgent(),
                ]);
            } catch (\Exception $e) {
                // Abaikan jika log aktivitas gagal agar tidak memutus alur
            }

            return redirect()->to('/daftar-hadir')->with('success', 'Notulen rapat berhasil disimpan ke arsip.')->with('active_tab', 'riwayat');
        }

        return redirect()->to('/daftar-hadir')->withInput()->with('error', 'Gagal menyimpan notulen rapat ke database.')->with('active_tab', 'buat');
    }

    public function deleteNotulen($id = null)
    {
        $notulenModel = new NotulenRapatModel();
        $notulen      = $notulenModel->find($id);

        if (!$notulen) {
            return redirect()->to('/daftar-hadir')->with('error', 'Data notulen tidak ditemukan.')->with('active_tab', 'riwayat');
        }

        // Hapus file fisik jika ada
        if (!empty($notulen['file_lampiran'])) {
            $filePath = FCPATH . 'uploads/notulen/' . $notulen['file_lampiran'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        if ($notulenModel->delete($id)) {
            try {
                (new LogAktivitasModel())->insert([
                    'user_id'    => session()->get('user_id'),
                    'aksi'       => 'delete',
                    'tipe_surat' => 'notulen',
                    'surat_id'   => $id,
                    'detail'     => 'Menghapus arsip notulen rapat: ' . $notulen['judul_rapat'],
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => (string) $this->request->getUserAgent(),
                ]);
            } catch (\Exception $e) {}

            return redirect()->to('/daftar-hadir')->with('success', 'Arsip notulen berhasil dihapus.')->with('active_tab', 'riwayat');
        }

        return redirect()->to('/daftar-hadir')->with('error', 'Gagal menghapus data notulen.')->with('active_tab', 'riwayat');
    }

    public function downloadNotulen($id = null)
    {
        $notulenModel = new NotulenRapatModel();
        $notulen      = $notulenModel->find($id);

        if (!$notulen || empty($notulen['file_lampiran'])) {
            return redirect()->to('/daftar-hadir')->with('error', 'Berkas lampiran notulen tidak ditemukan.')->with('active_tab', 'riwayat');
        }

        $filePath = FCPATH . 'uploads/notulen/' . $notulen['file_lampiran'];

        if (!file_exists($filePath)) {
            return redirect()->to('/daftar-hadir')->with('error', 'File berkas fisik tidak ditemukan di server.')->with('active_tab', 'riwayat');
        }

        // Beri nama download yang representatif
        $ext          = pathinfo($filePath, PATHINFO_EXTENSION);
        $cleanTitle   = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $notulen['judul_rapat']);
        $downloadName = 'Notulen_' . $cleanTitle . '_' . $notulen['tanggal_kegiatan'] . '.' . $ext;

        return $this->response->download($filePath, null)->setFileName($downloadName);
    }
}
