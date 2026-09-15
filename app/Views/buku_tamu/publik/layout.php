<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title><?= esc($title ?? 'Buku Tamu Digital') ?> | <?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?></title>
    
    <!-- Google Fonts: Inter & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tabler CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet" />
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    
    <style>
        :root {
            --font-main: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            --tblr-font-sans-serif: var(--font-main);
            --primary-gradient: linear-gradient(135deg, #206bc4 0%, #0054a6 100%);
            --success-gradient: linear-gradient(135deg, #2fb344 0%, #1e822d 100%);
        }
        
        body {
            font-family: var(--font-main);
            background: #f4f6fb;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Subtle glowing background mesh */
        .ambient-glow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .glow-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.45;
        }
        .glow-1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(32, 107, 196, 0.25) 0%, rgba(32, 107, 196, 0) 70%);
            top: -200px;
            right: -100px;
        }
        .glow-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(47, 179, 68, 0.2) 0%, rgba(47, 179, 68, 0) 70%);
            bottom: -150px;
            left: -100px;
        }

        .main-wrapper {
            position: relative;
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Header Navbar Branding */
        .kiosk-navbar {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -5px rgba(0, 0, 0, 0.03);
        }

        .brand-logo-img {
            height: 52px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: dotPulse 2s infinite;
        }

        @keyframes dotPulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* Glassmorphism Card Style */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            border-radius: 16px;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Ambient glowing shapes -->
    <div class="ambient-glow">
        <div class="glow-circle glow-1"></div>
        <div class="glow-circle glow-2"></div>
    </div>

    <div class="main-wrapper">
        <!-- Modern Top Header -->
        <header class="kiosk-navbar py-2 px-3 sticky-top">
            <div class="container-xl d-flex align-items-center justify-content-between">
                <a href="<?= base_url('buku-tamu') ?>" class="text-decoration-none d-flex align-items-center gap-3">
                    <?php if (!empty($appSettings['sekolah_logo']) && file_exists(FCPATH . 'uploads/logo/' . $appSettings['sekolah_logo'])): ?>
                        <img src="<?= base_url('uploads/logo/' . $appSettings['sekolah_logo']) ?>" alt="Logo" class="brand-logo-img">
                    <?php else: ?>
                        <div class="avatar bg-primary-lt text-primary rounded-3 p-2 shadow-sm" style="width: 48px; height: 48px;">
                            <i class="ti ti-school fs-1"></i>
                        </div>
                    <?php endif; ?>

                    <div class="text-start">
                        <div class="text-uppercase text-muted fw-bold" style="font-size: 0.72rem; letter-spacing: 1px;">
                            <?= esc($appSettings['sekolah_kementerian'] ?? 'Kementerian Agama RI') ?>
                        </div>
                        <h2 class="m-0 fw-bold text-dark fs-3" style="letter-spacing: -0.3px;">
                            <?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?>
                        </h2>
                    </div>
                </a>

                <div class="d-none d-md-flex align-items-center gap-3">
                    <div class="status-pill shadow-sm">
                        <span class="status-dot"></span>
                        <span>Buku Tamu Digital Aktif</span>
                    </div>
                    <a href="<?= base_url('auth/login') ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3" title="Login Admin / Petugas">
                        <i class="ti ti-lock me-1"></i> Petugas
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="container-xl py-4 flex-grow-1">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-important alert-danger alert-dismissible shadow-sm fade show mb-4 rounded-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-alert-triangle fs-2 me-2"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-important alert-success alert-dismissible shadow-sm fade show mb-4 rounded-3" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-check fs-2 me-2"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="py-3 mt-auto text-center border-top bg-white bg-opacity-75">
            <div class="container-xl">
                <div class="row align-items-center justify-content-between gy-2">
                    <div class="col-12 col-md-auto text-muted small">
                        <i class="ti ti-map-pin me-1 text-primary"></i> <?= esc($appSettings['sekolah_alamat'] ?? 'Alamat Madrasah') ?>
                        <?php if (!empty($appSettings['sekolah_kontak'])): ?>
                            <span class="mx-2">•</span> <i class="ti ti-phone me-1 text-success"></i> <?= esc($appSettings['sekolah_kontak']) ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-12 col-md-auto text-muted small">
                        &copy; <?= date('Y') ?> <strong><?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?></strong>. Sistem Buku Tamu Terpadu.
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
