<?php

namespace App\Libraries;

use ZipArchive;
use Exception;

class WordTemplateScanner
{
    /**
     * Scan .docx file to extract all unique placeholder tags (${tag} or {{tag}}).
     *
     * @param string $filePath
     * @return array
     * @throws Exception
     */
    public function scan(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("File template tidak ditemukan pada lokasi: {$filePath}");
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new Exception("Gagal membuka file .docx. Pastikan file valid.");
        }

        $xmlParts = [];

        // 1. Baca isi utama dokumen
        $mainXml = $zip->getFromName('word/document.xml');
        if ($mainXml !== false) {
            $xmlParts[] = $mainXml;
        }

        // 2. Baca headers dan footers jika ada
        for ($i = 1; $i <= 5; $i++) {
            $header = $zip->getFromName("word/header{$i}.xml");
            if ($header !== false) {
                $xmlParts[] = $header;
            }
            $footer = $zip->getFromName("word/footer{$i}.xml");
            if ($footer !== false) {
                $xmlParts[] = $footer;
            }
        }

        $zip->close();

        if (empty($xmlParts)) {
            return [];
        }

        $tags = [];
        foreach ($xmlParts as $xml) {
            // Hilangkan tag XML di antara tag agar XML Runs yang terpecah oleh MS Word menyatu
            // Contoh: {<w:r><w:t>nama</w:t></w:r>} -> {nama}
            $cleanText = preg_replace('/<[^>]+>/', '', $xml);

            // Deteksi ${variabel} atau {{variabel}}
            if (preg_match_all('/(?:\$\{|\{\{)([a-zA-Z0-9_]+)(?:\}|\}\})/', $cleanText, $matches)) {
                foreach ($matches[1] as $tag) {
                    $tags[] = trim($tag);
                }
            }
        }

        return array_values(array_unique($tags));
    }
}
