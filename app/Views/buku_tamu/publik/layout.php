<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Buku Tamu Digital | Madrasah</title>
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet" />
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            background-color: #f8f9fa;
        }
        .kiosk-btn {
            transition: all 0.2s ease;
        }
        .kiosk-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="page page-center flex-grow-1">
        <div class="container-xl py-4 text-center">
            <div class="mb-4">
                <a href="<?= base_url('buku-tamu') ?>" class="navbar-brand navbar-brand-autodark">
                    <!-- Replace with actual logo if available -->
                    <div class="d-flex flex-column align-items-center justify-content-center text-center">
                        <i class="ti ti-school text-primary mb-2" style="font-size: 3rem;"></i>
                        <h3 class="m-0 text-muted fw-medium text-uppercase tracking-wide">Buku Tamu</h3>
                        <h1 class="m-0 fw-bold display-6"><?= esc(strtoupper($appSettings['sekolah_nama'] ?? 'MIN 2 Tanggamus')) ?></h1>
                    </div>
                    <!-- <div class="text-muted mt-1">Sistem Layanan Madrasah Terpadu</div> -->
                </a>
            </div>
            
            <?= $this->renderSection('content') ?>
            
        </div>
    </div>
    
    <div class="text-center text-muted mb-4">
        &copy; <?= date('Y') ?> Madrasah. All rights reserved.
    </div>

    <!-- Tabler JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
