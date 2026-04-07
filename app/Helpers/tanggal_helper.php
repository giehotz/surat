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
