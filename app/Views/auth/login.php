<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login | <?= esc($appSettings['app_nama'] ?? 'Sistem Layanan Surat') ?></title>

    <!-- CSS files (Local Tabler UI Assets) -->
    <link href="<?= base_url('assets/tabler/css/tabler.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/tabler/css/tabler-vendors.min.css') ?>" rel="stylesheet" />

    <!-- Tabler Icons (Local Webfont) -->
    <link rel="preload" href="<?= base_url('assets/tabler/fonts/tabler-icons.woff2?v2.47.0') ?>" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?= base_url('assets/tabler/icons/tabler-icons.min.css') ?>">

    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root,
        [data-bs-theme="light"],
        [data-bs-theme="dark"],
        body {
            --tblr-font-sans-serif: 'Inter Var', 'Inter', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
            --tblr-body-font-family: var(--tblr-font-sans-serif);
            font-family: var(--tblr-font-sans-serif);
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            min-height: 100vh;
        }

        /* --- CSS Container Vanta.js Background --- */
        #vanta-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
        }

        /* Glassmorphism Card Style */
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 24px;
            box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 255, 255, 0.5) inset;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .brand-badge-wrap {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(32, 107, 196, 0.12) 0%, rgba(99, 102, 241, 0.18) 100%);
            border: 1px solid rgba(32, 107, 196, 0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 14px rgba(32, 107, 196, 0.15);
            margin-bottom: 0.75rem;
        }

        .brand-badge-img {
            max-width: 48px;
            max-height: 48px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .login-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(32, 107, 196, 0.08);
            color: #206bc4;
            border: 1px solid rgba(32, 107, 196, 0.18);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        .input-group-text {
            background-color: #ffffff;
            border-color: #d1d5db;
        }

        .form-control {
            border-color: #d1d5db;
        }

        .form-control:focus {
            border-color: #206bc4;
            box-shadow: 0 0 0 0.25rem rgba(32, 107, 196, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #206bc4 0%, #0054a6 100%);
            border: none;
            color: #ffffff;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(32, 107, 196, 0.35);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(32, 107, 196, 0.45);
            color: #ffffff;
        }

        .portal-link-btn {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(226, 232, 240, 0.9);
            color: #475569;
            transition: all 0.2s ease;
        }
        .portal-link-btn:hover {
            background: #ffffff;
            color: #206bc4;
            border-color: #206bc4;
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center">
    <!-- Elemen Animasi Background Vanta.js -->
    <div id="vanta-bg"></div>

    <div class="page page-center py-4">
        <div class="container container-tight">

            <!-- Card Login Modern Glassmorphism -->
            <div class="card login-card p-4 p-md-5">
                <div class="card-body p-0">
                    <!-- Brand & Header -->
                    <div class="text-center mb-4">
                        <div class="brand-badge-wrap">
                            <?php if (!empty($appSettings['sekolah_logo']) && file_exists(FCPATH . 'uploads/logo/' . $appSettings['sekolah_logo'])): ?>
                                <img src="<?= base_url('uploads/logo/' . $appSettings['sekolah_logo']) ?>" alt="Logo" class="brand-badge-img">
                            <?php else: ?>
                                <i class="ti ti-mail-fast text-primary fs-1"></i>
                            <?php endif; ?>
                        </div>

                        <div>
                            <span class="login-tag">
                                <i class="ti ti-shield-lock"></i> Portal Masuk Petugas
                            </span>
                        </div>

                        <h1 class="h2 fw-extrabold text-dark m-0 mb-1" style="letter-spacing: -0.5px;">
                            <?= esc($appSettings['app_nama'] ?? 'SuratApp') ?>
                        </h1>
                        <p class="text-muted small m-0">
                            <?= esc($appSettings['sekolah_nama'] ?? 'Sistem Informasi Administrasi Surat') ?>
                        </p>
                    </div>

                    <!-- Alert Flashdata -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-important alert-danger alert-dismissible shadow-sm fade show mb-4 rounded-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-alert-circle fs-2 me-2"></i>
                                <div><?= session()->getFlashdata('error') ?></div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-important alert-success alert-dismissible shadow-sm fade show mb-4 rounded-3" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-check fs-2 me-2"></i>
                                <div><?= session()->getFlashdata('success') ?></div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Form Login -->
                    <form action="<?= base_url('auth/process') ?>" method="post" autocomplete="off" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Username atau Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-user text-muted"></i></span>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username atau email Anda" autocomplete="username" required autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-lock text-muted"></i></span>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password akun Anda" autocomplete="current-password" required>
                                <button type="button" class="btn btn-outline-light border text-muted px-3" id="togglePassword" title="Tampilkan password" tabindex="-1">
                                    <i class="ti ti-eye icon" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <label class="form-check m-0 cursor-pointer user-select-none">
                                <input type="checkbox" class="form-check-input" name="remember" />
                                <span class="form-check-label text-muted small">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <div class="form-footer mb-3">
                            <button type="submit" class="btn btn-login w-100" id="submitBtn">
                                <i class="ti ti-login-2 fs-2"></i>
                                <span>Masuk ke Dashboard</span>
                            </button>
                        </div>
                    </form>

                    <!-- Link ke Buku Tamu Publik -->
                    <div class="text-center pt-2 border-top">
                        <a href="<?= base_url('buku-tamu') ?>" class="btn btn-sm portal-link-btn rounded-pill px-3 py-1 shadow-sm">
                            <i class="ti ti-notebook me-1 text-primary"></i> Buka Buku Tamu Digital Publik
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Hak Cipta -->
            <div class="text-center text-white text-opacity-75 small mt-3" style="text-shadow: 0 1px 3px rgba(0,0,0,0.5);">
                &copy; <?= date('Y') ?> <strong><?= esc($appSettings['sekolah_nama'] ?? 'Madrasah') ?></strong>. All rights reserved.
            </div>

        </div>
    </div>

    <!-- Tabler Core JS -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>

    <!-- Vanta.js & Three.js Dependencies (Background Dipertahankan) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>

    <!-- Inisialisasi Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Background Vanta.js Waves (Dipertahankan sesuai permintaan)
            VANTA.WAVES({
                el: "#vanta-bg",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0x096e39,
                shininess: 113.00,
                waveHeight: 23.50
            });

            // Toggle Tampilkan / Sembunyikan Password
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            if (togglePassword && password && eyeIcon) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isPassword = password.getAttribute('type') === 'password';
                    password.setAttribute('type', isPassword ? 'text' : 'password');

                    if (isPassword) {
                        eyeIcon.classList.remove('ti-eye');
                        eyeIcon.classList.add('ti-eye-off');
                        this.setAttribute('title', 'Sembunyikan password');
                    } else {
                        eyeIcon.classList.remove('ti-eye-off');
                        eyeIcon.classList.add('ti-eye');
                        this.setAttribute('title', 'Tampilkan password');
                    }
                });
            }
        });
    </script>
</body>

</html>