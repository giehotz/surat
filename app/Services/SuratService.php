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
     * Generate nomor agenda surat keluar berikutnya untuk tahun tertentu
     */
    public function generateNextNomorAgendaKeluar(string $tahun): string
    {
        $suratKeluarModel = new SuratKeluarModel();
        $countThisYear = $suratKeluarModel
            ->where('nomor_agenda LIKE', 'OUT-' . $tahun . '-%')
            ->countAllResults();

        return 'OUT-' . $tahun . '-' . sprintf('%03d', $countThisYear + 1);
    }

    /**
     * Merapikan dan renumber seluruh nomor urut dan nomor agenda Surat Keluar
     * 
     * @return int Jumlah record yang diperbarui
     */
    public function renumberSuratKeluar(): int
    {
        $suratKeluarModel = new SuratKeluarModel();
        $formatModel = new FormatSuratModel();
        $db = $suratKeluarModel->db;
        $updated = 0;

        $hasFormatCol = $suratKeluarModel->db->fieldExists('format_surat_id', 'surat_keluar');
        $hasNomorUrutCol = $suratKeluarModel->db->fieldExists('nomor_urut', 'surat_keluar');

        $db->transBegin();

        try {
            // 1. Proses record yang memiliki format_surat_id (pakai template)
            if ($hasFormatCol && $hasNomorUrutCol) {
                $withFormat = $suratKeluarModel
                    ->where('format_surat_id IS NOT NULL', null, false)
                    ->where('nomor_urut !=', '')
                    ->where('nomor_urut IS NOT NULL', null, false)
                    ->orderBy('format_surat_id', 'ASC')
                    ->orderBy('YEAR(tanggal_surat)', 'ASC')
                    ->orderBy("CAST(nomor_urut AS UNSIGNED)", 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();

                if (!empty($withFormat)) {
                    // Set temporary nomor_surat to avoid duplicate entry on unique key
                    foreach ($withFormat as $rec) {
                        $suratKeluarModel->update($rec['id'], [
                            'nomor_surat' => 'TEMP-FMT-' . $rec['id'] . '-' . uniqid()
                        ]);
                    }

                    $grouped = [];
                    foreach ($withFormat as $s) {
                        if (!empty($s['format_surat_id']) && !empty($s['tanggal_surat'])) {
                            $tahun = date('Y', strtotime($s['tanggal_surat']));
                            $key = $s['format_surat_id'] . '-' . $tahun;
                            $grouped[$key][] = $s;
                        }
                    }

                    foreach ($grouped as $list) {
                        $no = 1;
                        foreach ($list as $rec) {
                            $nomorUrutBaru = str_pad($no, 3, '0', STR_PAD_LEFT);
                            $format = $formatModel->find($rec['format_surat_id']);
                            $tahun = date('Y', strtotime($rec['tanggal_surat']));
                            $nomorSuratBaru = $format
                                ? $this->generateNomorSurat($format['template'], $nomorUrutBaru, $rec['bulan'] ?? null, $tahun)
                                : '';

                            $suratKeluarModel->update($rec['id'], [
                                'nomor_urut' => $nomorUrutBaru,
                                'nomor_surat' => $nomorSuratBaru,
                            ]);
                            if ($rec['nomor_urut'] !== $nomorUrutBaru || $rec['nomor_surat'] !== $nomorSuratBaru) {
                                $updated++;
                            }
                            $no++;
                        }
                    }
                }
            }

            // 2. Proses record legacy (format_surat_id IS NULL)
            $legacyQuery = $suratKeluarModel
                ->where('nomor_surat !=', '')
                ->where('nomor_surat IS NOT NULL', null, false);

            if ($hasFormatCol) {
                $legacyQuery->where('format_surat_id IS NULL', null, false);
            }

            $legacy = $legacyQuery->findAll();

            if (!empty($legacy)) {
                // Set temporary nomor_surat to avoid unique key collisions
                foreach ($legacy as $rec) {
                    $suratKeluarModel->update($rec['id'], [
                        'nomor_surat' => 'TEMP-LEG-' . $rec['id'] . '-' . uniqid()
                    ]);
                }

                $legacyGrouped = [];
                foreach ($legacy as $rec) {
                    $tahun = date('Y', strtotime($rec['tanggal_surat']));
                    if (!$tahun || $tahun == '0000') {
                        preg_match('/(\d{4})$/', $rec['nomor_surat'], $m);
                        $tahun = $m[1] ?? 'unknown';
                    }
                    preg_match('/^([A-Za-z]+)-\d+/', $rec['nomor_surat'], $m);
                    $prefix = $m[1] ?? 'unknown';
                    $key = $prefix . '-' . $tahun;
                    $legacyGrouped[$key][] = $rec;
                }

                foreach ($legacyGrouped as &$list) {
                    usort($list, function ($a, $b) {
                        preg_match('/^[A-Za-z]+-(\d+)/', $a['nomor_surat'], $ma);
                        preg_match('/^[A-Za-z]+-(\d+)/', $b['nomor_surat'], $mb);
                        $na = (int) ($ma[1] ?? 0);
                        $nb = (int) ($mb[1] ?? 0);
                        return $na === $nb ? $a['id'] - $b['id'] : $na - $nb;
                    });
                }

                foreach ($legacyGrouped as $list) {
                    $no = 1;
                    foreach ($list as $rec) {
                        $nomorBaru = str_pad($no, 3, '0', STR_PAD_LEFT);
                        preg_match('/^([A-Za-z]+)-(\d+)(.*)$/', $rec['nomor_surat'], $m);
                        if ($m) {
                            $prefix = $m[1];
                            $rest = $m[3];
                            $newNomorSurat = $prefix . '-' . $nomorBaru . $rest;

                            $updateData = ['nomor_surat' => $newNomorSurat];
                            if ($hasNomorUrutCol) {
                                $updateData['nomor_urut'] = $nomorBaru;
                            }
                            $suratKeluarModel->update($rec['id'], $updateData);

                            if ($rec['nomor_surat'] !== $newNomorSurat) {
                                $updated++;
                            }
                        }
                        $no++;
                    }
                }
            }

            // 3. Renumber nomor_agenda (OUT-YYYY-NNN)
            $allSurat = $suratKeluarModel
                ->where('nomor_agenda !=', '')
                ->where('nomor_agenda IS NOT NULL', null, false)
                ->orderBy('id', 'ASC')
                ->findAll();

            foreach ($allSurat as $s) {
                $suratKeluarModel->update($s['id'], [
                    'nomor_agenda' => 'OUT-TEMP-' . $s['id'] . '-' . uniqid()
                ]);
            }

            $chronological = $suratKeluarModel
                ->orderBy('YEAR(tanggal_surat)', 'ASC')
                ->orderBy('tanggal_surat', 'ASC')
                ->orderBy('id', 'ASC')
                ->findAll();

            $groupedByYear = [];
            foreach ($chronological as $s) {
                $tahun = date('Y', strtotime($s['tanggal_surat']));
                if (!$tahun || $tahun == '0000') {
                    $tahun = date('Y');
                }
                $groupedByYear[$tahun][] = $s;
            }

            foreach ($groupedByYear as $tahun => $list) {
                $no = 1;
                foreach ($list as $rec) {
                    $nomorBaru = 'OUT-' . $tahun . '-' . sprintf('%03d', $no);
                    $suratKeluarModel->update($rec['id'], [
                        'nomor_agenda' => $nomorBaru
                    ]);
                    if ($rec['nomor_agenda'] !== $nomorBaru) {
                        $updated++;
                    }
                    $no++;
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
