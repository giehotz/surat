<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login | Sistem Layanan Surat</title>

    <!-- CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler-flags.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler-payments.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler-vendors.min.css" rel="stylesheet" />

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        /* --- CSS Container Vanta.js --- */
        #vanta-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
        }
    </style>
</head>

<body class="d-flex flex-column">
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/demo-theme.min.js"></script>

    <!-- Elemen Animasi Background Vanta.js -->
    <div id="vanta-bg"></div>

    <div class="page page-center">
        <div class="container container-tight py-4">

            <!-- Login Card -->
            <div class="card card-md shadow-lg border-0" style="background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px);">
                <div class="card-body">
                    <!-- Logo & Brand -->
                    <div class="text-center mb-4">
                        <h1 class="m-0"><i class="ti ti-mail-fast text-primary me-2"></i><?= esc($appSettings['app_nama'] ?? 'SuratApp') ?></h1>
                    </div>
                    <h2 class="h2 text-center mb-4">Masuk untuk mengelola Surat</h2>

                    <!-- Alert Error -->
                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-important alert-danger alert-dismissible" role="alert">
                            <div class="d-flex">
                                <div><i class="ti ti-alert-circle icon me-2"></i></div>
                                <div><?= session()->getFlashdata('error') ?></div>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
                    <?php endif; ?>

                    <!-- Form Login -->
                    <form action="<?= base_url('auth/process') ?>" method="post" autocomplete="off" novalidate>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label class="form-label">Username atau Email</label>
                            <input type="text" class="form-control" name="username" placeholder="Masukkan username Anda" autocomplete="off" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group input-group-flat">
                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password Anda" autocomplete="off" required>
                                <span class="input-group-text">
                                    <a href="#" class="link-secondary" title="Tampilkan password" data-bs-toggle="tooltip" id="togglePassword">
                                        <i class="ti ti-eye icon" id="eyeIcon"></i>
                                    </a>
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" />
                                <span class="form-check-label">Ingat saya di perangkat ini</span>
                            </label>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="ti ti-login me-2"></i> Masuk
                            </button>
                        </div>
                    </form>
                    <div class="text-center text-muted mt-3">
                        Sistem Layanan Surat MIN 2 Tanggamus &copy; <?= date('Y') ?>
                    </div>
                </div>

            </div>


        </div>
    </div>

    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>

    <!-- Vanta.js & Three.js Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi Vanta.js (Net Effect)
            VANTA.WAVES({
                el: "#vanta-bg",
                mouseControls: true,
                touchControls: true,
                gyroControls: false,
                minHeight: 200.00,
                minWidth: 200.00,
                scale: 1.00,
                scaleMobile: 1.00,
                color: 0x96e39,
                shininess: 113.00,
    waveHeight: 23.50
            });

            // Script untuk fitur Show/Hide Password
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeIcon = document.querySelector('#eyeIcon');

            togglePassword.addEventListener('click', function(e) {
                // Mencegah link pindah halaman
                e.preventDefault();

                // Toggle tipe input
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle icon (Mata Terbuka / Tertutup)
                if (type === 'password') {
                    eyeIcon.classList.remove('ti-eye-off');
                    eyeIcon.classList.add('ti-eye');
                    this.setAttribute('title', 'Tampilkan password');
                } else {
                    eyeIcon.classList.remove('ti-eye');
                    eyeIcon.classList.add('ti-eye-off');
                    this.setAttribute('title', 'Sembunyikan password');
                }
            });
        });
    </script>
</body>

</html>