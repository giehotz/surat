<?php

namespace App\Services;

use App\Models\SuratKeluarModel;
use App\Models\FormatSuratModel;

class SuratService
{
    /**
     * Upload berkas surat fisik ke direktori uploads
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile|null $file
     * @param string $subFolder Nama subfolder di bawah FCPATH/uploads (misal 'surat_masuk' atau 'surat_keluar')
     * @param string|null $oldFilePath Path file lama yang akan dihapus jika upload baru berhasil
     * @return array|null Info file ['file_name' => ..., 'file_path' => ..., 'file_size' => ...] atau null
     */
    public function handleFileUpload($file, string $subFolder, ?string $oldFilePath = null): ?array
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        // Hapus berkas lama jika disediakan
        if ($oldFilePath) {
            $this->deleteFile($oldFilePath);
        }

        $targetDir = FCPATH . 'uploads/' . trim($subFolder, '/');
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $fileName = $file->getRandomName();
        $file->move($targetDir, $fileName);

        return [
            'file_name' => $fileName,
            'file_path' => 'uploads/' . trim($subFolder, '/') . '/' . $fileName,
            'file_size' => $file->getSize('kb'),
        ];
    }

    /**
     * Hapus berkas fisik dari filesystem
     * 
     * @param string|null $filePath
     * @return bool
     */
    public function deleteFile(?string $filePath): bool
    {
        if (empty($filePath)) {
            return false;
        }

        $fullPath = FCPATH . ltrim($filePath, '/\\');
        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }

    /**
     * Format penomoran surat keluar berdasarkan template
     */
    public function generateNomorSurat(string $template, string $nomorUrut, string $bulan, string $tahun): string
    {
        $replacements = [
            '{nomor}' => $nomorUrut,
            '{bulan}' => $bulan,
            '{tahun}' => $tahun,
        ];
        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }

    /**
     * Mengembalikan daftar bulan dalam format dua digit => nama bulan
     */
    public function getBulanList(): array
    {
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }

    /**
     * Generate nomor agenda surat keluar berikutnya untuk tahun tertentu dan bulan tertentu
     * Format: OUT-{bulan}-{tahun}-{nomor} (nomor urut berlanjut sepanjang tahun anggaran)
     */
    public function generateNextNomorAgendaKeluar(string $tahun, ?string $bulan = null): string
    {
        $bulanStr = !empty($bulan) ? str_pad($bulan, 2, '0', STR_PAD_LEFT) : date('m');
        $suratKeluarModel = new SuratKeluarModel();

        // Cari nomor urut agenda tertinggi pada tahun bersangkutan
        $rows = $suratKeluarModel
            ->groupStart()
                ->like('nomor_agenda', 'OUT-%-' . $tahun . '-%', 'after')
                ->orLike('nomor_agenda', 'OUT-' . $tahun . '-%', 'after')
                ->orWhere('YEAR(tanggal_surat)', $tahun)
            ->groupEnd()
            ->select('nomor_agenda')
            ->findAll();

        $maxNum = 0;
        foreach ($rows as $r) {
            if (!empty($r['nomor_agenda']) && preg_match('/-(\d+)$/', $r['nomor_agenda'], $m)) {
                $num = (int) $m[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = max($maxNum + 1, count($rows) + 1);
        $padded = str_pad((string)$nextNum, 2, '0', STR_PAD_LEFT);

        return 'OUT-' . $bulanStr . '-' . $tahun . '-' . $padded;
    }

    /**
     * Ambil nomor urut surat keluar berikutnya untuk tahun anggaran tertentu (tidak reset saat ganti format)
     */
    public function getNextNomorUrut(string $tahun): string
    {
        $suratKeluarModel = new SuratKeluarModel();
        $rows = $suratKeluarModel
            ->groupStart()
                ->where('YEAR(tanggal_surat)', $tahun)
                ->orLike('nomor_surat', '/' . $tahun)
            ->groupEnd()
            ->select('nomor_urut, nomor_surat')
            ->findAll();

        $maxNum = 0;
        foreach ($rows as $r) {
            if (!empty($r['nomor_urut']) && is_numeric($r['nomor_urut'])) {
                $n = (int) $r['nomor_urut'];
                if ($n > $maxNum) $maxNum = $n;
            } elseif (!empty($r['nomor_surat']) && preg_match('/^[A-Za-z0-9.]+-(\d+)/', $r['nomor_surat'], $m)) {
                $n = (int) $m[1];
                if ($n > $maxNum) $maxNum = $n;
            }
        }

        $next = $maxNum + 1;
        return str_pad((string)$next, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Generate nomor agenda khusus surat sisipan (contoh: nomor urut 005-A menghasilkan agenda OUT-01-2026-05A)
     */
    public function generateAgendaForSisipan(string $tahun, ?string $bulan, string $nomorUrut): string
    {
        $bulanStr = !empty($bulan) ? str_pad($bulan, 2, '0', STR_PAD_LEFT) : date('m');
        $nomorUrut = trim($nomorUrut);
        if (preg_match('/^(\d+)-([A-Za-z0-9]+)$/', $nomorUrut, $m)) {
            $baseSeq = str_pad((string)(int)$m[1], 2, '0', STR_PAD_LEFT);
            $suffix  = strtoupper($m[2]);
            return 'OUT-' . $bulanStr . '-' . $tahun . '-' . $baseSeq . $suffix;
        }

        return $this->generateNextNomorAgendaKeluar($tahun, $bulan);
    }

    /**
     * Validasi format nomor surat keluar berdasarkan standar template
     * Format acuan: [Klasifikasi]-[Nomor]/[KodeSatker]/[Bulan]/[Tahun]
     * Mendukung nomor urut standar (001) maupun nomor sisipan (005-A)
     */
    public function validateNomorSuratFormat(string $nomorSurat, ?string $kodeSatker = null): array
    {
        $nomorSurat = trim($nomorSurat);
        if (empty($nomorSurat)) {
            return ['valid' => false, 'message' => 'Nomor surat tidak boleh kosong.'];
        }

        if (!preg_match('/^([A-Za-z0-9.]+)-(\d+(?:-[A-Za-z0-9]+)?)\/(.+)\/(\d{1,2})\/(\d{4})$/', $nomorSurat, $m)) {
            return [
                'valid' => false,
                'message' => 'Format nomor surat tidak sesuai acuan standar: [Klasifikasi]-[Nomor]/[KodeSatker]/[Bulan]/[Tahun] (contoh: B-001/MI.08.02/PP.00.4/01/2026 atau surat sisipan B-005-A/MI.08.02/PP.00.4/01/2026).'
            ];
        }

        $prefix     = $m[1];
        $nomorUrut  = $m[2];
        $satkerPart = $m[3];
        $bulan      = (int)$m[4];
        $tahun      = (int)$m[5];

        if (!empty($kodeSatker) && trim($satkerPart) !== trim($kodeSatker)) {
            return [
                'valid' => false,
                'message' => "Kode satker pada nomor surat ('{$satkerPart}') tidak sesuai dengan kode satker aktif ('{$kodeSatker}')."
            ];
        }

        if ($bulan < 1 || $bulan > 12) {
            return [
                'valid' => false,
                'message' => "Bulan pada nomor surat ($m[4]) tidak valid. Harus antara 01 hingga 12."
            ];
        }

        if ($tahun < 2000 || $tahun > 2100) {
            return [
                'valid' => false,
                'message' => "Tahun pada nomor surat ($m[5]) tidak valid."
            ];
        }

        return [
            'valid'       => true,
            'message'     => 'Format nomor surat valid.',
            'prefix'      => $prefix,
            'nomor_urut'  => $nomorUrut,
            'kode_satker' => $satkerPart,
            'bulan'       => str_pad((string)$bulan, 2, '0', STR_PAD_LEFT),
            'tahun'       => (string)$tahun,
        ];
    }

    /**
     * Merapikan dan renumber seluruh nomor urut dan nomor agenda Surat Keluar secara kronologis
     * Mengurutkan berdasarkan bulan/tanggal surat, nomor agenda berformat OUT-{bulan}-{tahun}-{nomor},
     * dan nomor urut berlanjut terus sepanjang tahun (tidak reset ke 1 antar format).
     * 
     * @return int Jumlah record yang diperbarui
     */
    public function renumberSuratKeluar(): int
    {
        $suratKeluarModel = new SuratKeluarModel();
        $formatModel = new FormatSuratModel();
        $pengaturanModel = new \App\Models\PengaturanModel();
        $db = $suratKeluarModel->db;
        $updated = 0;

        $hasFormatCol = $suratKeluarModel->db->fieldExists('format_surat_id', 'surat_keluar');
        $hasNomorUrutCol = $suratKeluarModel->db->fieldExists('nomor_urut', 'surat_keluar');
        $hasBulanCol = $suratKeluarModel->db->fieldExists('bulan', 'surat_keluar');

        $kodeSatker = $pengaturanModel->getValue('kode_satker') ?? 'MI.08.02/PP.00.4';
        $defaultFormat = $formatModel->first();
        $defaultTemplate = $defaultFormat['template'] ?? ('B-{nomor}/' . $kodeSatker . '/{bulan}/{tahun}');

        $db->transBegin();

        try {
            // Urutkan seluruh data surat keluar secara kronologis (Tahun -> Bulan -> Tanggal -> ID)
            $allSurat = $suratKeluarModel
                ->orderBy('YEAR(tanggal_surat)', 'ASC')
                ->orderBy('MONTH(tanggal_surat)', 'ASC')
                ->orderBy('tanggal_surat', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            if (empty($allSurat)) {
                $db->transCommit();
                return 0;
            }

            // Set temporary nomor_surat & nomor_agenda untuk mencegah collision unique constraint saat renumber
            foreach ($allSurat as $s) {
                $suratKeluarModel->update($s['id'], [
                    'nomor_surat'  => 'TEMP-S-' . $s['id'] . '-' . uniqid(),
                    'nomor_agenda' => 'TEMP-A-' . $s['id'] . '-' . uniqid(),
                ]);
            }

            // Kelompokkan per Tahun Anggaran
            $groupedByYear = [];
            foreach ($allSurat as $s) {
                $tahun = date('Y', strtotime($s['tanggal_surat']));
                if (!$tahun || $tahun == '0000' || $tahun == '1970') {
                    if (!empty($s['nomor_surat']) && preg_match('/(\d{4})$/', $s['nomor_surat'], $my)) {
                        $tahun = $my[1];
                    } else {
                        $tahun = date('Y');
                    }
                }
                $groupedByYear[$tahun][] = $s;
            }

            // Lakukan penomoran berlanjut per tahun
            foreach ($groupedByYear as $tahun => $list) {
                $no = 1;
                foreach ($list as $rec) {
                    $tglSurat = $rec['tanggal_surat'];
                    $bulanStr = !empty($rec['bulan']) ? str_pad($rec['bulan'], 2, '0', STR_PAD_LEFT) : (!empty($tglSurat) ? date('m', strtotime($tglSurat)) : '01');

                    // Deteksi apakah data merupakan surat sisipan ber-suffix (misal 005-A)
                    $isSisipan = false;
                    $suffix = '';
                    if (!empty($rec['nomor_urut']) && preg_match('/^(\d+)-([A-Za-z0-9]+)$/', trim($rec['nomor_urut']), $ms)) {
                        $isSisipan = true;
                        $suffix = strtoupper($ms[2]);
                    } elseif (!empty($rec['nomor_surat']) && preg_match('/^[A-Za-z0-9.]+-(\d+)-([A-Za-z0-9]+)\//', $rec['nomor_surat'], $ms)) {
                        $isSisipan = true;
                        $suffix = strtoupper($ms[2]);
                    }

                    if ($isSisipan) {
                        // Suffix mengikuti nomor induk sebelumnya (tidak menaikkan nomor reguler $no)
                        $baseNo = max(1, $no - 1);
                        $agendaSeq = str_pad((string)$baseNo, 2, '0', STR_PAD_LEFT) . $suffix;
                        $nomorAgendaBaru = 'OUT-' . $bulanStr . '-' . $tahun . '-' . $agendaSeq;
                        $nomorUrutBaru = str_pad((string)$baseNo, 3, '0', STR_PAD_LEFT) . '-' . $suffix;
                    } else {
                        // 1. Nomor agenda baru: OUT-{bulan}-{tahun}-{nomor} (minimal 2 digit: 01..99, 100, dst)
                        $agendaSeq = str_pad((string)$no, 2, '0', STR_PAD_LEFT);
                        $nomorAgendaBaru = 'OUT-' . $bulanStr . '-' . $tahun . '-' . $agendaSeq;

                        // 2. Nomor urut baru (3 digit: 001..020..021..102) berlanjut terus lintas format
                        $nomorUrutBaru = str_pad((string)$no, 3, '0', STR_PAD_LEFT);
                    }

                    // 3. Nomor surat baru berdasarkan template format
                    $template = '';
                    if (!empty($rec['format_surat_id'])) {
                        $fmt = $formatModel->find($rec['format_surat_id']);
                        if ($fmt) {
                            $template = $fmt['template'];
                        }
                    }

                    if (empty($template)) {
                        if (!empty($rec['nomor_surat']) && preg_match('/^([A-Za-z0-9.]+)-(\d+(?:-[A-Za-z0-9]+)?)\/(.+)\/(\d{1,2})\/(\d{4})$/', $rec['nomor_surat'], $m)) {
                            // Selalu standarkan kode satker ke kodeSatker resmi jika merupakan satker internal instansi
                            $satkerToUse = (stripos($m[3], 'MI.08.02') !== false || stripos($m[3], 'PP.') !== false) ? $kodeSatker : $m[3];
                            $template = $m[1] . '-{nomor}/' . $satkerToUse . '/{bulan}/{tahun}';
                        } else {
                            $template = $defaultTemplate;
                        }
                    }

                    // Pastikan tidak ada variasi salah ketik (PP.004 atau PP.04) yang lolos ke template
                    $template = str_replace(['MI.08.02/PP.004', 'MI.08.02/PP.04'], $kodeSatker, $template);

                    $nomorSuratBaru = $this->generateNomorSurat($template, $nomorUrutBaru, $bulanStr, $tahun);

                    $updateData = [
                        'nomor_agenda' => $nomorAgendaBaru,
                        'nomor_surat'  => $nomorSuratBaru,
                    ];
                    if ($hasNomorUrutCol) {
                        $updateData['nomor_urut'] = $nomorUrutBaru;
                    }
                    if ($hasBulanCol) {
                        $updateData['bulan'] = $bulanStr;
                    }

                    $suratKeluarModel->update($rec['id'], $updateData);

                    if ($rec['nomor_agenda'] !== $nomorAgendaBaru || $rec['nomor_surat'] !== $nomorSuratBaru) {
                        $updated++;
                    }

                    // Hanya naikkan urutan jika bukan surat sisipan
                    if (!$isSisipan) {
                        $no++;
                    }
                }
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }

        return $updated;
    }
}
