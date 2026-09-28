<!-- Custom CSS Styling Khusus Cetak & Pratinjau Kertas A4 -->
<style>
    /* Styling Tabs yang Ramah Dark Mode */
    .card-header-tabs {
        background-color: var(--tblr-card-header-bg, rgba(var(--tblr-body-color-rgb), 0.02));
        border-bottom: 1px solid var(--tblr-border-color) !important;
    }
    .card-header-tabs .nav-link {
        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        border-bottom: 3px solid transparent !important;
        color: var(--tblr-secondary);
        background: transparent;
    }
    .card-header-tabs .nav-link:hover:not(.active) {
        background-color: rgba(var(--tblr-body-color-rgb), 0.04);
        color: var(--tblr-body-color);
    }
    .card-header-tabs .nav-link.active {
        color: var(--tblr-primary) !important;
        background-color: var(--tblr-card-bg) !important;
        border-bottom-color: var(--tblr-primary) !important;
    }

    /* Styling Kertas A4 pada Layar */
    .page-a4 {
        width: 21cm;
        min-height: 29.7cm;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        border: 1px solid #e1e4e8;
        font-family: 'Times New Roman', Times, serif;
        color: #000;
        padding: 2.2cm 2cm 2cm 2.2cm;
        box-sizing: border-box;
        position: relative;
        background: white;
    }

    /* Tabel Kop */
    .table-kop {
        border-collapse: collapse;
        border: none;
    }
    .table-kop td {
        border: none;
        padding: 2px 4px;
    }

    /* Tabel Presensi */
    .table-presensi {
        border-collapse: collapse;
        border: 1px solid #000;
        font-size: 10.5pt;
    }
    .table-presensi th {
        border: 1px solid #000;
        background-color: #f2f2f2;
        padding: 6px 8px;
        font-weight: bold;
        text-align: center;
    }
    .table-presensi td {
        border: 1px solid #000;
        padding: 4px 8px;
    }

    /* Dotted Line untuk Lembar Notulen Rapat */
    .notulen-dotted-line {
        border-bottom: 1px dotted #333;
        height: 26px;
        line-height: 26px;
        margin-bottom: 3px;
        font-size: 11pt;
    }

    /* Pengaturan Cetak Native (@media print) */
    @media print {
        @page {
            size: A4 portrait;
            margin: 1.5cm 1.5cm 1.5cm 1.5cm;
        }

        /* Sembunyikan elemen UI website */
        .d-print-none,
        header,
        footer,
        .navbar,
        .page-header,
        .card-header,
        .nav-tabs,
        .btn,
        .tooltip {
            display: none !important;
        }

        body, .page, .page-wrapper, .page-body, .container-xl, .card, .card-body {
            background: white !important;
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            border: none !important;
            box-shadow: none !important;
        }

        .page-a4 {
            width: 100% !important;
            min-height: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            border: none !important;
            box-shadow: none !important;
            page-break-after: always;
            break-after: page;
        }

        .page-a4:last-child {
            page-break-after: auto;
            break-after: auto;
        }

        .table-presensi th {
            background-color: #eee !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .table-presensi tr {
            page-break-inside: avoid;
        }
    }

    /* TinyMCE Fullscreen Mode Fix (Bebas Gangguan) */
    .tox.tox-tinymce--fullscreen {
        z-index: 99999 !important;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
    }

    /* Format Teks Notulen Kaya (Rich Text) */
    .notulen-content-rich {
        font-family: 'Times New Roman', Times, serif;
        font-size: 11pt;
        line-height: 1.6;
        text-align: justify;
    }
    .notulen-content-rich p {
        margin-bottom: 0.65rem;
    }
    .notulen-content-rich ol,
    .notulen-content-rich ul {
        padding-left: 1.5rem;
        margin-bottom: 0.65rem;
    }
    .notulen-content-rich li {
        margin-bottom: 0.3rem;
    }
    .notulen-content-rich table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0.75rem;
    }
    .notulen-content-rich table td,
    .notulen-content-rich table th {
        border: 1px solid #000;
        padding: 4px 6px;
    }

    /* Animasi & Interaksi Tombol Keluar Fullscreen */
    #btn-exit-fullscreen {
        transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, background-color 0.2s ease;
    }
    #btn-exit-fullscreen:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 25px rgba(214, 57, 57, 0.65) !important;
    }
    #btn-exit-fullscreen:active {
        transform: translateY(0) scale(0.98);
    }
</style>
