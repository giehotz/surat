<?php

if (!function_exists('parse_tanggal_tmt')) {
    /**
     * Parsing tanggal fleksibel dari format string (e.g. '1-October-2023', '2023-10-01')
     */
    function parse_tanggal_tmt(?string $tanggalStr): ?string
    {
        if (empty($tanggalStr) || trim($tanggalStr) === '' || $tanggalStr === '0000-00-00') {
            return null;
        }

        $clean = trim($tanggalStr);
        // Coba parsing standar Y-m-d
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $clean)) {
            return $clean;
        }

        // Coba parsing format teks bahasa inggris / umum
        $timestamp = strtotime($clean);
        if ($timestamp !== false && $timestamp > 0) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }
}

if (!function_exists('hitung_total_bulan_masa_kerja')) {
    /**
     * Hitung total bulan masa kerja secara realtime (otomatis ter-update tiap bulan)
     */
    function hitung_total_bulan_masa_kerja(?string $tmt): int
    {
        $parsedTmt = parse_tanggal_tmt($tmt);
        if (!$parsedTmt) {
            return 0;
        }

        try {
            $startDate = new DateTime($parsedTmt);
            $currentDate = new DateTime('now');

            if ($startDate > $currentDate) {
                return 0;
            }

            $diff = $startDate->diff($currentDate);
            return ($diff->y * 12) + $diff->m;
        } catch (\Exception $e) {
            return 0;
        }
    }
}

if (!function_exists('format_masa_kerja_lengkap')) {
    /**
     * Format masa kerja menjadi teks "X Thn Y Bln"
     */
    function format_masa_kerja_lengkap(?string $tmt): string
    {
        $totalBulan = hitung_total_bulan_masa_kerja($tmt);
        if ($totalBulan <= 0) {
            return '-';
        }

        $tahun = (int) floor($totalBulan / 12);
        $bulan = $totalBulan % 12;

        if ($tahun > 0 && $bulan > 0) {
            return "{$tahun} Thn {$bulan} Bln";
        } elseif ($tahun > 0) {
            return "{$tahun} Thn";
        } else {
            return "{$bulan} Bln";
        }
    }
}

if (!function_exists('hitung_usia_pegawai')) {
    /**
     * Hitung usia pegawai saat ini dari tanggal lahir
     */
    function hitung_usia_pegawai(?string $tanggalLahir): string
    {
        if (empty($tanggalLahir) || $tanggalLahir === '2099-12-31') {
            return '-';
        }

        try {
            $born = new DateTime($tanggalLahir);
            $now = new DateTime('now');
            if ($born > $now) {
                return '-';
            }
            $diff = $born->diff($now);
            return "{$diff->y} Thn";
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('ekstrak_tier_pegawai')) {
    /**
     * Tentukan kelompok hierarki utama (Tier):
     * Tier 4: Kepala Madrasah / Sekolah (Paling Utama / Top DUK) -> Bobot 4000
     * Tier 3: Guru PNS -> Bobot 3000
     * Tier 2: Guru PPPK -> Bobot 2000
     * Tier 1: Guru / Pegawai Honorer (Non-PNS) -> Bobot 1000
     */
    function ekstrak_tier_pegawai(array $guru): int
    {
        $jabatan = strtolower((string) ($guru['jabatan_mengajar'] ?? ''));
        $status  = strtolower((string) ($guru['status_kepegawaian'] ?? ''));

        // 1. Kepala Madrasah selalu paling atas
        if (strpos($jabatan, 'kepala madrasah') !== false || strpos($jabatan, 'kepala sekolah') !== false) {
            return 4000;
        }

        // 2. PNS
        if ($status === 'pns') {
            return 3000;
        }

        // 3. PPPK
        if ($status === 'pppk') {
            return 2000;
        }

        // 4. Honorer / GTT / PTT
        return 1000;
    }
}

if (!function_exists('ekstrak_bobot_golongan')) {
    /**
     * Ekstraksi bobot angka golongan ruang PNS (IV/e s.d I/a) dan PPPK (XVII s.d I)
     */
    function ekstrak_bobot_golongan(?string $pangkatStr, ?string $statusStr = ''): float
    {
        if (empty($pangkatStr)) {
            return 0.0;
        }

        $pangkat = strtoupper($pangkatStr);
        $status  = strtolower((string) $statusStr);

        // Jika PPPK dengan penamaan Golongan Romawi / Angka
        if ($status === 'pppk' || strpos($pangkat, 'GOL') !== false || strpos($pangkat, 'PPPK') !== false) {
            $petaPppk = [
                'XVII' => 17.0, 'XVI' => 16.0, 'XV' => 15.0, 'XIV' => 14.0, 'XIII' => 13.0,
                'XII'  => 12.0, 'XI'  => 11.0, 'X'  => 10.0, 'IX'   => 9.0,  'VIII' => 8.0,
                'VII'  => 7.0,  'VI'  => 6.0,  'V'  => 5.0,  'IV'   => 4.0,  'III'  => 3.0,
                'II'   => 2.0,  'I'   => 1.0,
                '17' => 17.0, '16' => 16.0, '15' => 15.0, '14' => 14.0, '13' => 13.0,
                '12' => 12.0, '11' => 11.0, '10' => 10.0, '9'  => 9.0,  '8'  => 8.0,
                '7'  => 7.0,  '6'  => 6.0,  '5'  => 5.0,  '4'  => 4.0,  '3'  => 3.0,
                '2'  => 2.0,  '1'  => 1.0
            ];

            foreach ($petaPppk as $key => $val) {
                if (preg_match('/\b' . $key . '\b/', $pangkat)) {
                    return $val;
                }
            }
        }

        // Pangkat / Golongan Ruang PNS Standar (IV/e s.d I/a)
        $petaPns = [
            'IV/E' => 17.0, 'IV/D' => 16.0, 'IV/C' => 15.0, 'IV/B' => 14.0, 'IV/A' => 13.0,
            'III/D' => 12.0, 'III/C' => 11.0, 'III/B' => 10.0, 'III/A' => 9.0,
            'II/D' => 8.0, 'II/C' => 7.0, 'II/B' => 6.0, 'II/A' => 5.0,
            'I/D' => 4.0, 'I/C' => 3.0, 'I/B' => 2.0, 'I/A' => 1.0,
            // Format tanpa garis miring (contoh: "III c", "III a", "IV a")
            'IV E' => 17.0, 'IV D' => 16.0, 'IV C' => 15.0, 'IV B' => 14.0, 'IV A' => 13.0,
            'III D' => 12.0, 'III C' => 11.0, 'III B' => 10.0, 'III A' => 9.0,
            'II D' => 8.0, 'II C' => 7.0, 'II B' => 6.0, 'II A' => 5.0,
            'I D' => 4.0, 'I C' => 3.0, 'I B' => 2.0, 'I A' => 1.0,
        ];

        foreach ($petaPns as $pattern => $val) {
            if (strpos($pangkat, $pattern) !== false) {
                return $val;
            }
        }

        // Fallback pencocokan regex
        if (preg_match('/(IV|III|II|I)[\/\s\-_]?([A-E])/i', $pangkat, $m)) {
            $romawi = strtoupper($m[1]);
            $huruf  = strtoupper($m[2]);
            $base   = ['IV' => 12, 'III' => 8, 'II' => 4, 'I' => 0][$romawi] ?? 0;
            $add    = ['A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5][$huruf] ?? 0;
            return (float) ($base + $add);
        }

        return 0.0;
    }
}

if (!function_exists('ekstrak_label_golongan')) {
    /**
     * Dapatkan representasi label singkat golongan yang rapi (e.g. "III/c", "IV/a", "Gol. IX")
     */
    function ekstrak_label_golongan(?string $pangkatStr): string
    {
        if (empty($pangkatStr)) return '-';
        if (preg_match('/(IV|III|II|I)[\/\s]?([a-eA-E])/i', $pangkatStr, $m)) {
            return strtoupper($m[1]) . '/' . strtolower($m[2]);
        }
        if (preg_match('/GOL(?:ONGAN)?\s*([IVXLCDM\d]+)/i', $pangkatStr, $m)) {
            return 'Gol. ' . strtoupper($m[1]);
        }
        return $pangkatStr;
    }
}

if (!function_exists('ekstrak_bobot_jabatan')) {
    /**
     * Pembobotan hierarki jabatan guru
     */
    function ekstrak_bobot_jabatan(?string $jabatanStr): int
    {
        if (empty($jabatanStr)) return 1;
        $j = strtolower($jabatanStr);

        if (strpos($j, 'kepala madrasah') !== false || strpos($j, 'kepala sekolah') !== false) {
            return 10;
        }
        if (strpos($j, 'guru utama') !== false) {
            return 5;
        }
        if (strpos($j, 'guru madya') !== false) {
            return 4;
        }
        if (strpos($j, 'guru muda') !== false) {
            return 3;
        }
        if (strpos($j, 'guru pertama') !== false) {
            return 2;
        }

        // Guru Mapel / Kelas / Tenaga Kependidikan
        return 1;
    }
}

if (!function_exists('ekstrak_bobot_pendidikan')) {
    /**
     * Pembobotan jenjang kelulusan formal
     */
    function ekstrak_bobot_pendidikan(?string $pendidikanStr): int
    {
        if (empty($pendidikanStr)) return 0;
        $p = strtoupper(trim($pendidikanStr));

        if (strpos($p, 'S3') !== false || strpos($p, 'DOKTOR') !== false) return 6;
        if (strpos($p, 'S2') !== false || strpos($p, 'MAGISTER') !== false) return 5;
        if (strpos($p, 'S1') !== false || strpos($p, 'D4') !== false || strpos($p, 'SARJANA') !== false) return 4;
        if (strpos($p, 'D3') !== false || strpos($p, 'DIPLOMA 3') !== false) return 3;
        if (strpos($p, 'D2') !== false || strpos($p, 'D1') !== false) return 2;
        if (strpos($p, 'SMA') !== false || strpos($p, 'MA') !== false || strpos($p, 'SMK') !== false) return 1;

        return 1;
    }
}

if (!function_exists('urutkan_duk')) {
    /**
     * Logika Utama Multi-Level Sorting DUK:
     * 1. Kepala Sekolah di puncak utama (Tier 4000)
     * 2. Guru PNS (Tier 3000): Golongan Tertinggi -> Masa Kerja Golongan / TMT Lama -> Jabatan -> Pendidikan -> Usia
     * 3. PPPK (Tier 2000): Golongan Tertinggi -> Masa Kerja / TMT Lama -> Pendidikan -> Usia
     * 4. Honorer (Tier 1000): Mengikuti TMT paling lama/lampau -> Masa Kerja -> Pendidikan -> Usia
     *
     * @param array $listGuru
     * @param string $scope 'pns', 'asn', 'all'
     * @return array
     */
    function urutkan_duk(array $listGuru, string $scope = 'pns'): array
    {
        if (empty($listGuru)) {
            return [];
        }

        $enriched = [];

        foreach ($listGuru as $guru) {
            $tier = ekstrak_tier_pegawai($guru);
            $status = strtolower((string) ($guru['status_kepegawaian'] ?? ''));

            // Filter cakupan pegawai
            if ($scope === 'pns') {
                // Tampilkan hanya PNS atau Kepala Madrasah
                if ($tier !== 4000 && $status !== 'pns') {
                    continue;
                }
            } elseif ($scope === 'asn') {
                // Tampilkan Kamad, PNS, dan PPPK
                if ($tier !== 4000 && !in_array($status, ['pns', 'pppk'])) {
                    continue;
                }
            }

            // TMT Pangkat / Kenaikan Terakhir (fallback ke TMT CPNS)
            $tmtPangkatRaw = !empty($guru['kenaikan_pangkat']) ? $guru['kenaikan_pangkat'] : ($guru['tmt_cpns_honorer'] ?? '');
            $tmtPangkat = parse_tanggal_tmt($tmtPangkatRaw) ?? ($guru['tmt_cpns_honorer'] ?? '2099-12-31');

            // TMT Pengangkatan Awal / Mulai Tugas
            $tmtPengangkatan = parse_tanggal_tmt($guru['tmt_cpns_honorer'] ?? '') ?? parse_tanggal_tmt($guru['mulai_tugas'] ?? '') ?? '2099-12-31';

            // Total bulan masa kerja dari pengangkatan
            $totalBulanMasaKerja = hitung_total_bulan_masa_kerja($tmtPengangkatan);

            // Tanggal lahir untuk komparasi usia (yang lebih tua / tanggal lebih lampau di atas)
            $parsedLahir = parse_tanggal_tmt($guru['tanggal_lahir'] ?? null);
            if (!$parsedLahir && !empty($guru['nip'])) {
                $nipDigits = preg_replace('/\D/', '', (string)$guru['nip']);
                if (strlen($nipDigits) >= 8) {
                    $thn = substr($nipDigits, 0, 4);
                    $bln = substr($nipDigits, 4, 2);
                    $tgl = substr($nipDigits, 6, 2);
                    if (checkdate((int)$bln, (int)$tgl, (int)$thn)) {
                        $parsedLahir = "{$thn}-{$bln}-{$tgl}";
                    }
                }
            }
            if (!$parsedLahir && !empty($guru['tempat_tanggal_lahir'])) {
                $parts = explode(',', (string)$guru['tempat_tanggal_lahir']);
                if (isset($parts[1])) {
                    $parsedLahir = parse_tanggal_tmt(trim($parts[1]));
                }
            }
            $finalTanggalLahir = $parsedLahir ?? '2099-12-31';

            $enriched[] = array_merge($guru, [
                'duk_tier'              => $tier,
                'duk_bobot_golongan'    => ekstrak_bobot_golongan($guru['pangkat_golongan'] ?? '', $status),
                'duk_label_golongan'    => ekstrak_label_golongan($guru['pangkat_golongan'] ?? ''),
                'duk_tmt_pangkat'       => $tmtPangkat,
                'duk_tmt_pengangkatan'  => $tmtPengangkatan,
                'duk_total_bulan'       => $totalBulanMasaKerja,
                'duk_masa_kerja_format' => format_masa_kerja_lengkap($tmtPengangkatan),
                'duk_bobot_jabatan'     => ekstrak_bobot_jabatan($guru['jabatan_mengajar'] ?? ''),
                'duk_bobot_pendidikan'  => ekstrak_bobot_pendidikan($guru['pendidikan_terakhir'] ?? ''),
                'duk_tanggal_lahir'     => $finalTanggalLahir,
                'duk_usia_format'       => hitung_usia_pegawai($finalTanggalLahir),
            ]);
        }

        // Multi-level sorting comparator
        usort($enriched, function ($a, $b) {
            // Level 1: Tier Status Kepegawaian (Kamad 4000 > PNS 3000 > PPPK 2000 > Honorer 1000)
            if ($a['duk_tier'] !== $b['duk_tier']) {
                return $b['duk_tier'] <=> $a['duk_tier'];
            }

            $currentTier = $a['duk_tier'];

            // Level 2: Logika Khusus per Tier
            if ($currentTier === 4000) {
                // Kepala Madrasah: Golongan > Masa Kerja > TMT
                if ($a['duk_bobot_golongan'] != $b['duk_bobot_golongan']) {
                    return $b['duk_bobot_golongan'] <=> $a['duk_bobot_golongan'];
                }
                return $b['duk_total_bulan'] <=> $a['duk_total_bulan'];
            }

            if ($currentTier === 3000) {
                // Tier Guru PNS:
                // 1. Golongan tertinggi ke terendah (DESC)
                if ($a['duk_bobot_golongan'] != $b['duk_bobot_golongan']) {
                    return $b['duk_bobot_golongan'] <=> $a['duk_bobot_golongan'];
                }
                // 2. Jika Golongan sama -> Masa kerja lebih lama di atas (DESC)
                if ($a['duk_total_bulan'] !== $b['duk_total_bulan']) {
                    return $b['duk_total_bulan'] <=> $a['duk_total_bulan'];
                }
                // 3. Jika masa kerja sama -> TMT Pangkat lebih lama/lampau di atas (ASC)
                if ($a['duk_tmt_pangkat'] !== $b['duk_tmt_pangkat']) {
                    return strcmp($a['duk_tmt_pangkat'], $b['duk_tmt_pangkat']);
                }
                // 4. Bobot Jabatan (DESC)
                if ($a['duk_bobot_jabatan'] !== $b['duk_bobot_jabatan']) {
                    return $b['duk_bobot_jabatan'] <=> $a['duk_bobot_jabatan'];
                }
                // 5. Bobot Pendidikan (DESC)
                if ($a['duk_bobot_pendidikan'] !== $b['duk_bobot_pendidikan']) {
                    return $b['duk_bobot_pendidikan'] <=> $a['duk_bobot_pendidikan'];
                }
                // 6. Tanggal Lahir (Usia lebih tua / tanggal lebih lampau di atas - ASC)
                return strcmp($a['duk_tanggal_lahir'], $b['duk_tanggal_lahir']);
            }

            if ($currentTier === 2000) {
                // Tier Guru PPPK:
                // 1. Golongan tertinggi ke terendah (DESC)
                if ($a['duk_bobot_golongan'] != $b['duk_bobot_golongan']) {
                    return $b['duk_bobot_golongan'] <=> $a['duk_bobot_golongan'];
                }
                // 2. Masa kerja lebih lama di atas (DESC)
                if ($a['duk_total_bulan'] !== $b['duk_total_bulan']) {
                    return $b['duk_total_bulan'] <=> $a['duk_total_bulan'];
                }
                // 3. TMT lebih lampau (ASC)
                if ($a['duk_tmt_pengangkatan'] !== $b['duk_tmt_pengangkatan']) {
                    return strcmp($a['duk_tmt_pengangkatan'], $b['duk_tmt_pengangkatan']);
                }
                // 4. Pendidikan (DESC)
                if ($a['duk_bobot_pendidikan'] !== $b['duk_bobot_pendidikan']) {
                    return $b['duk_bobot_pendidikan'] <=> $a['duk_bobot_pendidikan'];
                }
                // 5. Usia (ASC)
                return strcmp($a['duk_tanggal_lahir'], $b['duk_tanggal_lahir']);
            }

            // Tier 1000 (Honorer / Non-PNS):
            // "untuk honorer mengikuti TMT, TMT yang lama maka lebih tinggi"
            // 1. TMT paling lampau / lama berada di atas (ASC)
            if ($a['duk_tmt_pengangkatan'] !== $b['duk_tmt_pengangkatan']) {
                return strcmp($a['duk_tmt_pengangkatan'], $b['duk_tmt_pengangkatan']);
            }
            // 2. Total bulan masa kerja (DESC)
            if ($a['duk_total_bulan'] !== $b['duk_total_bulan']) {
                return $b['duk_total_bulan'] <=> $a['duk_total_bulan'];
            }
            // 3. Pendidikan (DESC)
            if ($a['duk_bobot_pendidikan'] !== $b['duk_bobot_pendidikan']) {
                return $b['duk_bobot_pendidikan'] <=> $a['duk_bobot_pendidikan'];
            }
            // 4. Usia (ASC)
            return strcmp($a['duk_tanggal_lahir'], $b['duk_tanggal_lahir']);
        });

        // Berikan nomor urut DUK definitif (1, 2, 3...)
        foreach ($enriched as $idx => &$item) {
            $item['no_urut_duk'] = $idx + 1;
        }

        return $enriched;
    }
}
