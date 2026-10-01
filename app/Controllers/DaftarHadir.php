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
        return $this->rapat();
    }

    public function rapat()
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

    public function harian()
    {
        $data = $this->prepareHarianData();
        // Mode pratinjau web: tampilkan 1 guru saja sebagai sampel dokumen cepat
        $data['total_guru_lengkap']  = count($data['daftar_guru']);
        $data['daftar_guru_preview'] = !empty($data['daftar_guru']) ? array_slice($data['daftar_guru'], 0, 1) : [];

        return view('daftar_hadir/harian', $data);
    }

    public function cetakHarian()
    {
        $data = $this->prepareHarianData();
        $data['total_guru_lengkap']  = count($data['daftar_guru']);
        $data['autoprint']           = $this->request->getGet('autoprint') === '1';

        return view('daftar_hadir/cetak_harian', $data);
    }

    private function prepareHarianData(): array
    {
        helper(['tanggal', 'duk']);

        $bulan   = (int)($this->request->getGet('bulan') ?? date('n'));
        $tahun   = (int)($this->request->getGet('tahun') ?? date('Y'));
        $status  = strtolower(trim((string)($this->request->getGet('status') ?? 'all')));
        $guruId  = $this->request->getGet('guru_id') ?? 'all';

        // Validasi batasan bulan & tahun
        if ($bulan < 1 || $bulan > 12) {
            $bulan = (int)date('n');
        }
        if ($tahun < 2000 || $tahun > 2100) {
            $tahun = (int)date('Y');
        }

        $namaBulanLengkap = [
            1 => 'JANUARI', 2 => 'FEBRUARI', 3 => 'MARET', 4 => 'APRIL',
            5 => 'MEI', 6 => 'JUNI', 7 => 'JULI', 8 => 'AGUSTUS',
            9 => 'SEPTEMBER', 10 => 'OKTOBER', 11 => 'NOVEMBER', 12 => 'DESEMBER'
        ];

        $shortMonths = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agt',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        // Ambil data hari libur nasional via API Kemendesa (dengan cache)
        $liburInfo = $this->getHariLiburInfo($tahun);
        $holidays  = $liburInfo['holidays'] ?? [];

        // Hitung kalender hari 1 s.d. 31
        $daysInMonth = (int)date('t', strtotime(sprintf('%04d-%02d-01', $tahun, $bulan)));
        $days = [];
        $totalKerja = 0;
        $totalLibur = 0;

        for ($d = 1; $d <= 31; $d++) {
            if ($d <= $daysInMonth) {
                $dateStr = sprintf('%04d-%02d-%02d', $tahun, $bulan, $d);
                $dayOfWeek = (int)date('w', strtotime($dateStr)); // 0 = Ahad/Minggu
                $tglLabel = $d . '-' . ($shortMonths[$bulan] ?? 'Bln');

                if ($dayOfWeek === 0) {
                    $days[] = [
                        'no'         => $d,
                        'tanggal'    => $tglLabel,
                        'is_off'     => true,
                        'tipe'       => 'ahad',
                        'keterangan' => 'Ahad',
                        'jam_masuk'  => '-',
                        'jam_pulang' => '-',
                    ];
                    $totalLibur++;
                } elseif (isset($holidays[$dateStr])) {
                    $days[] = [
                        'no'         => $d,
                        'tanggal'    => $tglLabel,
                        'is_off'     => true,
                        'tipe'       => 'libur',
                        'keterangan' => $holidays[$dateStr],
                        'jam_masuk'  => '-',
                        'jam_pulang' => '-',
                    ];
                    $totalLibur++;
                } else {
                    $days[] = [
                        'no'         => $d,
                        'tanggal'    => $tglLabel,
                        'is_off'     => false,
                        'tipe'       => 'kerja',
                        'keterangan' => '',
                        'jam_masuk'  => '',
                        'jam_pulang' => '',
                    ];
                    $totalKerja++;
                }
            } else {
                // Hari ke-31 jika bulan hanya 30/28/29 hari
                $days[] = [
                    'no'         => $d,
                    'tanggal'    => '-',
                    'is_off'     => true,
                    'tipe'       => 'empty',
                    'keterangan' => '-',
                    'jam_masuk'  => '-',
                    'jam_pulang' => '-',
                ];
            }
        }

        // Ambil Data Guru dari Database
        $guruModel = new DataGuruModel();
        $builder = $guruModel->select('id, nama_pegawai, nip, peg_id_nuptk, tempat_lahir, tanggal_lahir, tempat_tanggal_lahir, status_kepegawaian, jabatan_mengajar, pangkat_golongan, pendidikan_terakhir, perguruan_tinggi, mulai_tugas, tmt_cpns_honorer, kenaikan_pangkat');

        if ($status !== 'all' && in_array($status, ['pns', 'pppk', 'honorer'])) {
            $builder->where('LOWER(status_kepegawaian)', $status);
        }

        if ($guruId !== 'all' && !empty($guruId)) {
            $builder->where('id', (int)$guruId);
        }

        $rawGuru = $builder->findAll();

        // Urutkan guru sesuai hierarki DUK
        $daftarGuru = urutkan_duk($rawGuru, 'all');

        // Ambil semua daftar guru untuk opsi dropdown filter perorangan
        $semuaGuru = $guruModel->select('id, nama_pegawai, nip, status_kepegawaian')->orderBy('nama_pegawai', 'ASC')->findAll();

        // Tanggal titimangsa akhir bulan (mengambil data kecamatan dari Pengaturan -> Identitas)
        $namaBulanTitle = ucfirst(strtolower($namaBulanLengkap[$bulan] ?? ''));
        $tanggalAkhirBulan = $daysInMonth . ' ' . $namaBulanTitle . ' ' . $tahun;
        $kotaTitimangsa = !empty($this->appSettings['sekolah_kecamatan']) 
            ? $this->appSettings['sekolah_kecamatan'] 
            : ($this->appSettings['sekolah_kota'] ?? 'Gisting');

        // Hitung jumlah hari libur nasional khusus di bulan yang sedang aktif
        $liburBulanIniCount = 0;
        foreach ($days as $day) {
            if ($day['tipe'] === 'libur') {
                $liburBulanIniCount++;
            }
        }

        return [
            'title'                      => 'Daftar Hadir Harian Pegawai',
            'bulan'                      => $bulan,
            'tahun'                      => $tahun,
            'status'                     => $status,
            'guru_id'                    => $guruId,
            'nama_bulan'                 => $namaBulanLengkap[$bulan] ?? '',
            'nama_bulan_title'           => $namaBulanTitle,
            'days'                       => $days,
            'days_in_month'              => $daysInMonth,
            'total_kerja'                => $totalKerja,
            'total_libur'                => $totalLibur,
            'daftar_guru'                => $daftarGuru,
            'semua_guru'                 => $semuaGuru,
            'tanggal_akhir_bulan'        => $tanggalAkhirBulan,
            'kota_titimangsa'            => $kotaTitimangsa,
            'bulan_list'                 => $namaBulanLengkap,
            'api_libur_info'             => $liburInfo,
            'total_libur_nasional_tahun' => count($holidays),
            'libur_bulan_ini_count'      => $liburBulanIniCount,
        ];
    }

    /**
     * Endpoint untuk memicu sinkronisasi manual API Hari Libur Nasional Kemendesa
     */
    public function syncHariLibur()
    {
        $tahun  = (int)($this->request->getGet('tahun') ?? date('Y'));
        $bulan  = (int)($this->request->getGet('bulan') ?? date('n'));
        $status = $this->request->getGet('status') ?? 'all';
        $guruId = $this->request->getGet('guru_id') ?? 'all';

        $redirectQuery = http_build_query([
            'bulan'   => $bulan,
            'tahun'   => $tahun,
            'status'  => $status,
            'guru_id' => $guruId,
        ]);

        $info = $this->getHariLiburInfo($tahun, forceRefresh: true);

        if ($info['count'] > 0) {
            $count = $info['count'];
            $sourceLabel = ($info['source'] === 'api') ? 'API Resmi Kemendesa' : 'Data Kalender Resmi SKB 3 Menteri';
            return redirect()->to(base_url("daftar-hadir/harian?{$redirectQuery}"))
                ->with('success', "Data hari libur nasional tahun {$tahun} aktif ({$count} tanggal merah - {$sourceLabel}).");
        } else {
            return redirect()->to(base_url("daftar-hadir/harian?{$redirectQuery}"))
                ->with('info', "Koneksi API sedang offline atau tidak ada tanggal merah nasional di bulan ini. Sistem berjalan normal dengan hari Ahad/Minggu otomatis ditandai merah.");
        }
    }

    /**
     * Dapatkan data hari libur nasional beserta status koneksi API (dengan fallback resmi SKB 3 Menteri)
     */
    public function getHariLiburInfo(int $tahun, bool $forceRefresh = false): array
    {
        $cacheKey = "hari_libur_nasional_{$tahun}";

        if ($forceRefresh) {
            cache()->delete($cacheKey);
        } else {
            $cached = cache($cacheKey);
            if (is_array($cached) && !empty($cached)) {
                return [
                    'success'  => true,
                    'holidays' => $cached,
                    'count'    => count($cached),
                    'source'   => 'cache',
                    'error'    => null,
                ];
            }
        }

        $holidays = [];
        $lastError = null;
        $url = "https://api.kemendesa.link/libur-nasional/api/holidays/{$tahun}.json";

        // Upaya 1: Melalui cURL CodeIgniter 4 (dengan timeout cepat dan verify = false)
        try {
            $client = service('curlrequest');
            $response = $client->get($url, [
                'timeout'         => 3,
                'connect_timeout' => 2,
                'verify'          => false,
                'http_errors'     => false,
                'headers'         => [
                    'Accept'     => 'application/json',
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) SuratApp-CI4/1.0',
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $body = json_decode($response->getBody(), true);
                if (!empty($body['data']) && is_array($body['data'])) {
                    foreach ($body['data'] as $item) {
                        if (!empty($item['date']) && !empty($item['name'])) {
                            $holidays[$item['date']] = $item['name'];
                        }
                    }
                }
            } else {
                $lastError = 'HTTP Status ' . $response->getStatusCode();
            }
        } catch (\Throwable $e) {
            $lastError = $e->getMessage();
        }

        // Upaya 2: Fallback stream context file_get_contents jika cURL gagal
        if (empty($holidays) && function_exists('file_get_contents')) {
            try {
                $context = stream_context_create([
                    'ssl' => [
                        'verify_peer'      => false,
                        'verify_peer_name' => false,
                    ],
                    'http' => [
                        'timeout'    => 3,
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) SuratApp-CI4/1.0',
                    ],
                ]);
                $raw = @file_get_contents($url, false, $context);
                if ($raw !== false) {
                    $body = json_decode($raw, true);
                    if (!empty($body['data']) && is_array($body['data'])) {
                        foreach ($body['data'] as $item) {
                            if (!empty($item['date']) && !empty($item['name'])) {
                                $holidays[$item['date']] = $item['name'];
                            }
                        }
                        $lastError = null;
                    }
                }
            } catch (\Throwable $e2) {
                $lastError = $e2->getMessage();
            }
        }

        // Simpan cache jika berhasil dari API
        if (!empty($holidays)) {
            cache()->save($cacheKey, $holidays, 86400 * 7); // Cache selama 7 hari
            return [
                'success'  => true,
                'holidays' => $holidays,
                'count'    => count($holidays),
                'source'   => 'api',
                'error'    => null,
            ];
        }

        // Upaya 3: Fallback ke data bawaan resmi SKB 3 Menteri jika offline / DNS timeout
        $defaultHolidays = $this->getDefaultHolidays($tahun);
        if (!empty($defaultHolidays)) {
            cache()->save($cacheKey, $defaultHolidays, 86400 * 7);
            return [
                'success'  => true,
                'holidays' => $defaultHolidays,
                'count'    => count($defaultHolidays),
                'source'   => 'skb_fallback',
                'error'    => null,
            ];
        }

        // Jika tidak ada hari libur nasional untuk tahun ini
        return [
            'success'  => true,
            'holidays' => [],
            'count'    => 0,
            'source'   => 'none',
            'error'    => null,
        ];
    }

    /**
     * Data cadangan kalender hari libur nasional resmi SKB 3 Menteri
     */
    private function getDefaultHolidays(int $tahun): array
    {
        $data2026 = [
            '2026-01-01' => 'Tahun Baru 2026 Masehi',
            '2026-01-16' => 'Isra Mikraj Nabi Muhammad S.A.W.',
            '2026-02-16' => 'Cuti Bersama Tahun Baru Imlek 2577',
            '2026-02-17' => 'Tahun Baru Imlek 2577 Kongzili',
            '2026-03-18' => 'Cuti Bersama Hari Suci Nyepi',
            '2026-03-19' => 'Hari Suci Nyepi (Tahun Baru Saka 1948)',
            '2026-03-20' => 'Cuti Bersama Idul Fitri 1447 Hijriah',
            '2026-03-21' => 'Hari Raya Idul Fitri 1447 Hijriah',
            '2026-03-22' => 'Hari Raya Idul Fitri 1447 Hijriah',
            '2026-03-23' => 'Cuti Bersama Idul Fitri 1447 Hijriah',
            '2026-03-24' => 'Cuti Bersama Idul Fitri 1447 Hijriah',
            '2026-04-03' => 'Wafat Yesus Kristus',
            '2026-04-05' => 'Kebangkitan Yesus Kristus (Paskah)',
            '2026-05-01' => 'Hari Buruh Internasional',
            '2026-05-14' => 'Kenaikan Yesus Kristus',
            '2026-05-15' => 'Cuti Bersama Kenaikan Yesus Kristus',
            '2026-05-27' => 'Hari Raya Idul Adha 1447 Hijriah',
            '2026-05-28' => 'Cuti Bersama Idul Adha 1447 Hijriah',
            '2026-05-31' => 'Hari Raya Waisak 2570 BE',
            '2026-06-01' => 'Hari Lahir Pancasila',
            '2026-06-16' => '1 Muharam Tahun Baru Islam 1448 Hijriah',
            '2026-08-17' => 'Proklamasi Kemerdekaan RI',
            '2026-08-25' => 'Maulid Nabi Muhammad S.A.W.',
            '2026-12-24' => 'Cuti Bersama Kelahiran Yesus Kristus',
            '2026-12-25' => 'Kelahiran Yesus Kristus (Natal)',
        ];

        return ($tahun === 2026) ? $data2026 : [];
    }

    private function getHariLibur(int $tahun): array
    {
        $info = $this->getHariLiburInfo($tahun);
        return $info['holidays'] ?? [];
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
