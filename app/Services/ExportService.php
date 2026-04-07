<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class ExportService
{
    /**
     * Export generic data to Excel
     * 
     * @param array $headers List of column headers
     * @param array $data Array of arrays containing the data rows
     * @param string $filename Output filename (without extension)
     * @param string $title Title to display inside the Excel sheet
     */
    public function exportExcel(array $headers, array $data, string $filename, string $title = 'Laporan Data')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Data Laporan');

        // Title row
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:' . chr(64 + count($headers)) . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Header row
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getColumnDimension($col)->setAutoSize(true);
            $col++;
        }

        // Data rows
        $row = 4;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);

            // Start from column B
            $colData = 'B';
            foreach ($item as $value) {
                $sheet->setCellValue($colData . $row, $value);
                $colData++;
            }
            $row++;
        }

        // Apply Borders to all cells
        $lastCol = chr(64 + count($headers));
        $lastRow = $row - 1;
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle('A3:' . $lastCol . $lastRow)->applyFromArray($styleArray);

        // Stream to browser
        $writer = new Xlsx($spreadsheet);
        $filepath = WRITEPATH . 'uploads/' . $filename . '.xlsx';
        $writer->save($filepath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        readfile($filepath);
        unlink($filepath); // Clean up temp file
        exit();
    }

    /**
     * Export generic HTML to PDF using Dompdf
     * 
     * @param string $html The HTML view contents
     * @param string $filename Output filename (without extension)
     * @param string $paperSize Paper size (e.g., 'A4')
     * @param string $orientation Paper orientation ('portrait' or 'landscape')
     */
    public function exportPdf(string $html, string $filename, string $paperSize = 'A4', string $orientation = 'landscape')
    {
        $dompdf = new Dompdf();

        // Optional: you can set options here, like enabling remote images
        $options = $dompdf->getOptions();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper($paperSize, $orientation);
        $dompdf->render();

        $dompdf->stream($filename . ".pdf", ["Attachment" => false]);
        exit();
    }
}
