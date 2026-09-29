<?php

namespace App\Libraries;

use PhpOffice\PhpWord\TemplateProcessor;
use ZipArchive;
use Exception;

class WordTemplateEngine
{
    protected string $templatePath;
    protected string $workingFilePath;
    protected ?TemplateProcessor $processor = null;

    /**
     * @param string $templatePath Lokasi file .docx template
     * @throws Exception
     */
    public function __construct(string $templatePath)
    {
        if (!file_exists($templatePath)) {
            throw new Exception("File template fisik tidak ditemukan: {$templatePath}");
        }

        $this->templatePath = $templatePath;
        $this->prepareWorkingFile();
        $this->processor = new TemplateProcessor($this->workingFilePath);
    }

    /**
     * Menyiapkan file kerja sementara dan menormalisasi kurung {{tag}} menjadi ${tag}
     */
    protected function prepareWorkingFile(): void
    {
        $tempDir = WRITEPATH . 'generated/';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $this->workingFilePath = $tempDir . 'tmp_' . uniqid() . '.docx';
        copy($this->templatePath, $this->workingFilePath);

        // Buka file zip untuk normalisasi tag {{tag}} -> ${tag}
        $zip = new ZipArchive();
        if ($zip->open($this->workingFilePath) === true) {
            $mainXml = $zip->getFromName('word/document.xml');
            if ($mainXml !== false) {
                // Ganti {{tag}} menjadi ${tag} jika user menulis format kurung ganda
                $normalizedXml = preg_replace('/\{\{([a-zA-Z0-9_]+)\}\}/', '${\1}', $mainXml);
                $zip->addFromString('word/document.xml', $normalizedXml);
            }
            $zip->close();
        }
    }

    /**
     * Mengisi nilai pada satu placeholder ${search}
     */
    public function setValue(string $search, ?string $replace): self
    {
        // Ganti karakter null atau newline agar rapi di Word
        $safeValue = $replace ?? '-';
        // Ubah newline ke tag break Word jika ada multiline
        $safeValue = str_replace(["\r\n", "\r", "\n"], '</w:t><w:br/><w:t>', htmlspecialchars($safeValue, ENT_QUOTES, 'UTF-8'));
        
        $this->processor->setValue($search, $safeValue);
        return $this;
    }

    /**
     * Mengisi banyak nilai sekaligus dari array asosiatif ['key' => 'val']
     */
    public function setValues(array $data): self
    {
        foreach ($data as $key => $val) {
            if (is_scalar($val) || is_null($val)) {
                $this->setValue((string)$key, (string)$val);
            }
        }
        return $this;
    }

    /**
     * Menggandakan baris tabel untuk multi-item (Multi-Pegawai/Anggota)
     * 
     * @param string $primaryTag Tag penanda baris, contoh: 'pegawai_nama'
     * @param array $rows Array berisi baris-baris data: [['no' => 1, 'pegawai_nama' => '...', ...], ...]
     */
    public function cloneRowData(string $primaryTag, array $rows): self
    {
        $count = count($rows);
        if ($count === 0) {
            // Jika kosong, set baris pertama dengan tanda strip
            $this->processor->cloneRow($primaryTag, 1);
            $this->processor->setValue("{$primaryTag}#1", '-');
            return $this;
        }

        $this->processor->cloneRow($primaryTag, $count);

        foreach ($rows as $index => $row) {
            $rowNum = $index + 1;
            foreach ($row as $colKey => $colVal) {
                $safeVal = $colVal ?? '-';
                $safeVal = str_replace(["\r\n", "\r", "\n"], '</w:t><w:br/><w:t>', htmlspecialchars($safeVal, ENT_QUOTES, 'UTF-8'));
                $this->processor->setValue("{$colKey}#{$rowNum}", $safeVal);
            }
        }

        return $this;
    }

    /**
     * Simpan file hasil generate ke lokasi tujuan
     */
    public function saveAs(string $outputPath): string
    {
        $dir = dirname($outputPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $this->processor->saveAs($outputPath);

        // Hapus file temporary kerja
        if (file_exists($this->workingFilePath)) {
            @unlink($this->workingFilePath);
        }

        return $outputPath;
    }

    /**
     * Bersihkan file kerja jika class di-destruct
     */
    public function __destruct()
    {
        if (isset($this->workingFilePath) && file_exists($this->workingFilePath)) {
            @unlink($this->workingFilePath);
        }
    }
}
