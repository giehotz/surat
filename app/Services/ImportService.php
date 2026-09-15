<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Reader\Xls as XlsReader;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use App\Models\SuratMasukModel;
use App\Models\SuratKeluarModel;
use App\Models\LogAktivitasModel;

class ImportService
{
    /**
     * Download Excel template untuk Surat Masuk
     */
    public function downloadTemplateSuratMasuk(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Surat Masuk');

        // Header
        $headers = [
            'Nomor Surat (dari Pengirim)',
            'Pengirim',
            'Tanggal Surat (YYYY-MM-DD)',
            'Tanggal Terima (YYYY-MM-DD)',
            'Perihal',
            'Jumlah Lampiran',
            'Tipe Penyimpanan (lokal/cloud)',
            'Link Cloud',
            'Keterangan (Opsional)'
        ];

        foreach ($headers as $idx => $header) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EFEFEF']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Petunjuk Pengisian
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->setActiveSheetIndex(1);
        $sheet2->setTitle('Panduan Pengisian');
        $sheet2->setCellValue('A1', 'Kolom');
        $sheet2->setCellValue('B1', 'Keterangan');
        $sheet2->setCellValue('A2', 'Nomor Surat');
        $sheet2->setCellValue('B2', 'Wajib isi.');
        $sheet2->setCellValue('A3', 'Pengirim');
        $sheet2->setCellValue('B3', 'Wajib isi.');
        $sheet2->setCellValue('A4', 'Tanggal Surat');
        $sheet2->setCellValue('B4', 'Wajib isi. Format ex: 2024-12-30');
        $sheet2->setCellValue('A5', 'Tanggal Terima');
        $sheet2->setCellValue('B5', 'Wajib isi. Format ex: 2024-12-31');
        $sheet2->setCellValue('A6', 'Perihal');
        $sheet2->setCellValue('B6', 'Wajib isi.');
        $sheet2->setCellValue('A7', 'Jumlah Lampiran');
        $sheet2->setCellValue('B7', 'Opsional. Angka (0, 1, 2, dll).');
        $sheet2->setCellValue('A8', 'Tipe Penyimpanan');
        $sheet2->setCellValue('B8', 'Wajib diisi "lokal" atau "cloud".');
        $sheet2->setCellValue('A9', 'Link Cloud');
        $sheet2->setCellValue('B9', 'Wajib diisi link (http/https) jika Tipe Penyimpanan adalah "cloud".');

        foreach (range('A', 'B') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }
        $spreadsheet->setActiveSheetIndex(0);

        $this->outputSpreadsheet($spreadsheet, 'Template_Import_Surat_Masuk.xlsx');
    }

    /**
     * Download Excel template untuk Surat Keluar
     */
    public function downloadTemplateSuratKeluar(): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Surat Keluar');

        $headers = [
            'Nomor Surat (dari Pengirim)',
            'Tujuan',
            'Tanggal Surat (DD/MM/YYYY)',
            'Tanggal Kirim (DD/MM/YYYY)',
            'Perihal',
            'Jumlah Lampiran',
            'Tipe Penyimpanan (lokal/cloud)',
            'Link Cloud (Opsional)',
            'Keterangan (Opsional)'
        ];

        foreach ($headers as $idx => $header) {
            $col = chr(65 + $idx);
            $sheet->setCellValue($col . '1', $header);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'EFEFEF']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
            ]
        ];
        $sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

        // Panduan Pengisian
        $spreadsheet->createSheet();
        $sheet2 = $spreadsheet->setActiveSheetIndex(1);
        $sheet2->setTitle('Panduan Pengisian');
        $sheet2->setCellValue('A1', 'Kolom');
        $sheet2->setCellValue('B1', 'Keterangan');
        $sheet2->setCellValue('A2', 'Nomor Surat');
        $sheet2->setCellValue('B2', 'Wajib isi.');
        $sheet2->setCellValue('A3', 'Tujuan');
        $sheet2->setCellValue('B3', 'Wajib isi.');
        $sheet2->setCellValue('A4', 'Tanggal Surat');
        $sheet2->setCellValue('B4', 'Wajib isi. Format ex: 30/12/2024');
        $sheet2->setCellValue('A5', 'Tanggal Kirim');
        $sheet2->setCellValue('B5', 'Wajib isi. Format ex: 31/12/2024');
        $sheet2->setCellValue('A6', 'Perihal');
        $sheet2->setCellValue('B6', 'Wajib isi.');
        $sheet2->setCellValue('A7', 'Jumlah Lampiran');
        $sheet2->setCellValue('B7', 'Opsional. Angka (0, 1, 2, dll).');
        $sheet2->setCellValue('A8', 'Tipe Penyimpanan');
        $sheet2->setCellValue('B8', 'Wajib diisi "lokal" atau "cloud".');
        $sheet2->setCellValue('A9', 'Link Cloud');
        $sheet2->setCellValue('B9', 'Opsional. Dapat diisi link (http/https) ke berkas cloud.');

        foreach (range('A', 'B') as $columnID) {
            $sheet2->getColumnDimension($columnID)->setAutoSize(true);
        }
        $spreadsheet->setActiveSheetIndex(0);

        $this->outputSpreadsheet($spreadsheet, 'Template_Import_Surat_Keluar.xlsx');
    }

    /**
     * Parse file Excel untuk import Surat Masuk
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @return array
     */
    public function parseExcelSuratMasuk($file): array
    {
        $extension = $file->getExtension();
        $reader = ($extension === 'xls') ? new XlsReader() : new XlsxReader();
        $spreadsheet = $reader->load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $importData = [];
        for ($i = 1; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];
            if (empty(array_filter($row))) {
                continue;
            }

            $tanggalSurat = rtrim(trim((string)($row[2] ?? '')));
            if (is_numeric($tanggalSurat)) {
                $tanggalSurat = ExcelDate::excelToDateTimeObject((int)$tanggalSurat)->format('Y-m-d');
            }

            $tanggalTerima = rtrim(trim((string)($row[3] ?? '')));
            if (is_numeric($tanggalTerima)) {
                $tanggalTerima = ExcelDate::excelToDateTimeObject((int)$tanggalTerima)->format('Y-m-d');
            }

            $importData[] = [
                'nomor_surat'      => rtrim(trim((string)($row[0] ?? ''))),
                'pengirim'         => rtrim(trim((string)($row[1] ?? ''))),
                'tanggal_surat'    => $tanggalSurat,
                'tanggal_terima'   => $tanggalTerima,
                'perihal'          => rtrim(trim((string)($row[4] ?? ''))),
                'lampiran'         => rtrim(trim((string)($row[5] ?? '0'))),
                'tipe_penyimpanan' => rtrim(trim((string)($row[6] ?? 'lokal'))),
                'file_link'        => rtrim(trim((string)($row[7] ?? ''))),
                'keterangan'       => rtrim(trim((string)($row[8] ?? ''))),
            ];
        }

        return $importData;
    }

    /**
     * Parse file Excel untuk import Surat Keluar
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @return array
     */
    public function parseExcelSuratKeluar($file): array
    {
        $extension = $file->getExtension();
        $reader = ($extension === 'xls') ? new XlsReader() : new XlsxReader();
        $spreadsheet = $reader->load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, false, false);

        $importData = [];
        for ($i = 1; $i < count($sheetData); $i++) {
            $row = $sheetData[$i];
            if (empty(array_filter($row))) {
                continue;
            }

            $tanggalSurat = $this->parseDateString(rtrim(trim((string)($row[2] ?? ''))));
            $tanggalKirim = $this->parseDateString(rtrim(trim((string)($row[3] ?? ''))));

            $importData[] = [
                'nomor_surat'      => rtrim(trim((string)($row[0] ?? ''))),
                'tujuan'           => rtrim(trim((string)($row[1] ?? ''))),
                'tanggal_surat'    => $tanggalSurat,
                'tanggal_kirim'    => $tanggalKirim,
                'perihal'          => rtrim(trim((string)($row[4] ?? ''))),
                'lampiran'         => rtrim(trim((string)($row[5] ?? '0'))),
                'tipe_penyimpanan' => rtrim(trim((string)($row[6] ?? 'lokal'))),
                'file_link'        => rtrim(trim((string)($row[7] ?? ''))),
                'keterangan'       => rtrim(trim((string)($row[8] ?? ''))),
            ];
        }

        return $importData;
    }

    /**
     * Eksekusi penyimpanan data batch Surat Masuk dengan transaksi
     */
    public function importSuratMasuk(array $importData, int $userId, string $ipAddress, string $userAgent): array
    {
        $suratMasukModel = new SuratMasukModel();
        $logModel = new LogAktivitasModel();
        $db = \Config\Database::connect();
        $validRows = 0;

        $db->transStart();

        foreach ($importData as $row) {
            if (empty($row['nomor_surat']) || empty($row['pengirim']) || empty($row['tanggal_surat']) || empty($row['tanggal_terima']) || empty($row['perihal'])) {
                continue;
            }

            $metode = strtolower($row['tipe_penyimpanan']);
            if (!in_array($metode, ['lokal', 'cloud'])) {
                continue;
            }

            if ($metode === 'cloud' && empty($row['file_link'])) {
                continue;
            }

            $tahunSurat = date('Y', strtotime($row['tanggal_surat']));
            $nomor_agenda = 'IN-' . $tahunSurat . '-TEMP-' . time() . '-' . $validRows;

            $dataInsert = [
                'nomor_agenda'     => $nomor_agenda,
                'nomor_surat'      => $row['nomor_surat'],
                'tanggal_surat'    => $row['tanggal_surat'],
                'tanggal_terima'   => $row['tanggal_terima'],
                'pengirim'         => $row['pengirim'],
                'perihal'          => $row['perihal'],
                'lampiran'         => $row['lampiran'] ?: 0,
                'tipe_penyimpanan' => $metode,
                'file_link'        => $metode === 'cloud' ? $row['file_link'] : null,
                'keterangan'       => $row['keterangan'] ?? null,
                'status'           => 'tercatat',
                'created_by'       => $userId,
            ];

            $suratMasukModel->insert($dataInsert);
            $insertId = $suratMasukModel->getInsertID();

            $logModel->save([
                'user_id'    => $userId,
                'surat_id'   => $insertId,
                'aksi'       => 'create',
                'tipe_surat' => 'surat_masuk',
                'detail'     => 'Import data surat masuk via excel dari ' . $dataInsert['pengirim'],
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]);

            $validRows++;
        }

        if ($validRows > 0) {
            $suratMasukModel->reassignNomorAgenda();
        }

        $db->transComplete();

        return [
            'success' => $db->transStatus() !== false && $validRows > 0,
            'count'   => $validRows
        ];
    }

    /**
     * Eksekusi penyimpanan data batch Surat Keluar dengan transaksi
     */
    public function importSuratKeluar(array $importData, int $userId, string $ipAddress, string $userAgent): array
    {
        $suratKeluarModel = new SuratKeluarModel();
        $logModel = new LogAktivitasModel();
        $db = \Config\Database::connect();
        $validRows = 0;
        $errors = [];

        $db->transStart();

        $tahunAgenda = date('Y');
        $agendaCounter = $suratKeluarModel
            ->where('nomor_agenda LIKE', 'OUT-' . $tahunAgenda . '-%')
            ->countAllResults();

        foreach ($importData as $index => $row) {
            $rowNum = $index + 2;

            if (empty($row['nomor_surat'])) {
                $errors[] = "Baris {$rowNum}: Nomor surat kosong.";
                continue;
            }
            if (empty($row['tujuan'])) {
                $errors[] = "Baris {$rowNum}: Tujuan surat kosong.";
                continue;
            }
            if (empty($row['tanggal_surat']) || $row['tanggal_surat'] === '1970-01-01') {
                $errors[] = "Baris {$rowNum}: Tanggal surat kosong atau format tidak valid.";
                continue;
            }
            if (empty($row['tanggal_kirim']) || $row['tanggal_kirim'] === '1970-01-01') {
                $errors[] = "Baris {$rowNum}: Tanggal kirim kosong atau format tidak valid.";
                continue;
            }
            if (empty($row['perihal'])) {
                $errors[] = "Baris {$rowNum}: Perihal kosong.";
                continue;
            }

            $metode = strtolower($row['tipe_penyimpanan'] ?? 'lokal');
            if (empty($metode)) {
                $metode = 'lokal';
            }
            if (!in_array($metode, ['lokal', 'cloud'])) {
                $errors[] = "Baris {$rowNum}: Tipe penyimpanan harus diisi 'lokal' atau 'cloud'.";
                continue;
            }

            $agendaCounter++;
            $nomor_agenda = 'OUT-' . $tahunAgenda . '-' . sprintf('%03d', $agendaCounter);

            $dataInsert = [
                'nomor_agenda'     => $nomor_agenda,
                'nomor_surat'      => $row['nomor_surat'],
                'tanggal_surat'    => $row['tanggal_surat'],
                'tanggal_kirim'    => $row['tanggal_kirim'],
                'tujuan'           => $row['tujuan'],
                'perihal'          => $row['perihal'],
                'lampiran'         => $row['lampiran'] ?: 0,
                'tipe_penyimpanan' => $metode,
                'file_link'        => $metode === 'cloud' ? $row['file_link'] : null,
                'keterangan'       => $row['keterangan'] ?? null,
                'status'           => 'disetujui',
                'created_by'       => $userId,
            ];

            $suratKeluarModel->insert($dataInsert);
            $insertId = $suratKeluarModel->getInsertID();

            $logModel->save([
                'user_id'    => $userId,
                'surat_id'   => $insertId,
                'aksi'       => 'create',
                'tipe_surat' => 'surat_keluar',
                'detail'     => 'Import data surat keluar via excel ke ' . $dataInsert['tujuan'],
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent
            ]);

            $validRows++;
        }

        $db->transComplete();

        return [
            'success' => $db->transStatus() !== false && $validRows > 0,
            'count'   => $validRows,
            'errors'  => $errors
        ];
    }

    private function parseDateString(string $dateStr): string
    {
        if (empty($dateStr)) {
            return '';
        }

        if (is_numeric($dateStr)) {
            return ExcelDate::excelToDateTimeObject((int)$dateStr)->format('Y-m-d');
        }

        $dateObj = \DateTime::createFromFormat('d/m/Y', $dateStr);
        if ($dateObj !== false) {
            return $dateObj->format('Y-m-d');
        }

        $dateObjYmd = \DateTime::createFromFormat('Y-m-d', $dateStr);
        if ($dateObjYmd !== false) {
            return $dateObjYmd->format('Y-m-d');
        }

        return $dateStr;
    }

    private function outputSpreadsheet(Spreadsheet $spreadsheet, string $filename): void
    {
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
