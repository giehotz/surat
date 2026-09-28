<?php

if (!function_exists('format_tanggal_indo')) {
    function format_tanggal_indo($date)
    {
        if (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
            return '-';
        }

        $bulan_indo = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Ensure date is valid format
        $timestamp = strtotime($date);
        if (!$timestamp) {
            return $date;
        }

        $tahun = date('Y', $timestamp);
        $bulan = date('n', $timestamp);
        $tgl   = date('d', $timestamp);

        // DD MONTH YYYY
        return $tgl . ' ' . $bulan_indo[$bulan] . ' ' . $tahun;
    }
}

if (!function_exists('format_waktu_indo')) {
    function format_waktu_indo($date)
    {
        if (empty($date) || $date == '0000-00-00 00:00:00') {
            return '-';
        }

        $timestamp = strtotime($date);
        if (!$timestamp) {
            return $date;
        }

        // HH:ii WIB
        $waktu = date('H:i', $timestamp);
        return $waktu . ' WIB';
    }
}

if (!function_exists('format_tanggal_waktu_indo')) {
    function format_tanggal_waktu_indo($date)
    {
        if (empty($date) || $date == '0000-00-00 00:00:00') {
            return '-';
        }

        return format_tanggal_indo($date) . ' ' . format_waktu_indo($date);
    }
}

if (!function_exists('hitung_masa_kerja')) {
    /**
     * Menghitung lama masa kerja dari tanggal mulai tugas
     * @param string|null $tanggal
     * @return string
     */
    function hitung_masa_kerja($tanggal)
    {
        if (empty($tanggal) || $tanggal === '0000-00-00') {
            return '-';
        }

        try {
            $start = new \DateTime($tanggal);
            $now   = new \DateTime();

            if ($start > $now) {
                return '0 thn 0 bln';
            }

            $diff = $now->diff($start);
            return $diff->y . ' thn ' . $diff->m . ' bln';
        } catch (\Exception $e) {
            return '-';
        }
    }
}

if (!function_exists('hitung_tanggal_pensiun')) {
    /**
     * Menghitung tanggal dan hari pensiun pegawai
     * @param string|null $tanggal_lahir
     * @param int $usia
     * @return string
     */
    function hitung_tanggal_pensiun($tanggal_lahir, $usia = 60)
    {
        if (empty($tanggal_lahir) || $tanggal_lahir === '0000-00-00') {
            return '-';
        }

        try {
            $birth = new \DateTime($tanggal_lahir);
            $pensiun = clone $birth;
            $pensiun->modify('+' . (int)$usia . ' years');

            $days   = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $months = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

            $dayName   = $days[(int)$pensiun->format('w')];
            $monthName = $months[(int)$pensiun->format('m')];

            return $dayName . ', ' . $pensiun->format('d') . ' ' . $monthName . ' ' . $pensiun->format('Y');
        } catch (\Exception $e) {
            return '-';
        }
    }
}

