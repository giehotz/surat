<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title><?= esc($appSettings['app_nama'] ?? 'Sistem Layanan Surat') ?> | <?= $title ?? 'Dashboard' ?></title>
    <!-- 1. High-Priority Preload for Tabler Icons Webfont (Crossorigin & High Fetch Priority) -->
    <link rel="preload" href="<?= base_url('assets/tabler/fonts/tabler-icons.woff2') ?>" as="font" type="font/woff2" crossorigin="anonymous" fetchpriority="high">

    <!-- 2. Typography: Preconnect & Load Inter -->
    <link rel="preconnect" href="https://rsms.me" crossorigin>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <!-- 3. Local Tabler UI Assets (No render-blocking external CDNs) -->
    <link href="<?= base_url('assets/tabler/css/tabler.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/tabler/css/tabler-vendors.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/tabler/icons/tabler-icons.min.css') ?>" rel="stylesheet" />

    <style>
        /* Inline Critical @font-face with font-display: block to prevent FOUT/icon pop-in */
        @font-face {
            font-family: "tabler-icons";
            font-style: normal;
            font-weight: 400;
            font-display: block;
            src: url("<?= base_url('assets/tabler/fonts/tabler-icons.woff2') ?>") format("woff2"),
                 url("<?= base_url('assets/tabler/fonts/tabler-icons.woff') ?>") format("woff"),
                 url("<?= base_url('assets/tabler/fonts/tabler-icons.ttf') ?>") format("truetype");
        }

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
        }

        /* Prevent Layout Shift & Pop-in: Reserve fixed square dimensions for Tabler Icons */
        i.ti, .ti {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.25em;
            height: 1.25em;
            line-height: 1;
            vertical-align: -0.15em;
            text-align: center;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .btn i.ti, .btn .ti {
            font-size: 1.1rem;
            width: 1.15em;
            height: 1.15em;
            vertical-align: -0.15em;
        }

        .btn-sm i.ti, .btn-sm .ti {
            font-size: 0.95rem;
            width: 1.1em;
            height: 1.1em;
        }

        .btn-lg i.ti, .btn-lg .ti {
            font-size: 1.25rem;
            width: 1.25em;
            height: 1.25em;
        }

        .btn-icon i.ti, .btn-icon .ti {
            font-size: 1.15rem;
            width: 100%;
            height: 100%;
            margin: 0 !important;
            vertical-align: middle;
        }

        .btn-icon.btn-sm i.ti, .btn-icon.btn-sm .ti {
            font-size: 1rem;
        }

        .nav-link i.ti, .nav-link-icon i.ti {
            font-size: 1.2rem;
            width: 1.25em;
            height: 1.25em;
        }
    </style>
    <script>
        // Pre-warm the font in the browser's FontFaceSet immediately before DOM render
        if ('fonts' in document) {
            document.fonts.load('1em "tabler-icons"');
        }
    </script>
</head>

<body>
    <script>
        var themeStorageKey = "tablerTheme";
        var selectedTheme = localStorage.getItem(themeStorageKey) || 'light';
        // Set document element and body attributes untuk theme bawaan Tabler
        document.documentElement.setAttribute('data-bs-theme', selectedTheme);
        document.body.setAttribute('data-bs-theme', selectedTheme);

        function toggleTheme(theme) {
            localStorage.setItem(themeStorageKey, theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
            document.body.setAttribute('data-bs-theme', theme);
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                var instance = bootstrap.Tooltip.getInstance(el);
                if (instance) instance.dispose();
            });
            document.querySelectorAll('.tooltip').forEach(function (t) { t.remove(); });
        }
    </script>
    <div class="page">

        <!-- Navbar (Sidebar in Tabler is usually vertical navbar or combined header) -->
        <?= $this->include('layout/sidebar') ?>
        <?= $this->include('layout/header') ?>

        <div class="page-wrapper">
            <!-- Page header -->
            <?php if (!isset($hide_default_header) || !$hide_default_header): ?>
            <div class="page-header d-print-none">
                <div class="<?= $container_class ?? 'container-xl' ?>">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <!-- Page pre-title -->
                            <div class="page-pretitle">
                                Overview
                            </div>
                            <h2 class="page-title">
                                <?= $title ?? 'Dashboard' ?>
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Page body -->
            <div class="page-body">
                <div class="<?= $container_class ?? 'container-xl' ?>">

                    <!-- Flash Messages (Handled by SweetAlert2 at the bottom) -->

                    <!-- Main Content Section -->
                    <?= $this->renderSection('content') ?>

                </div>
            </div>

            <?= $this->include('layout/footer') ?>
        </div>
    </div>

    <!-- jQuery (Optional if you still have old scripts) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Tabler Core (Local Asset) -->
    <script src="<?= base_url('assets/tabler/js/tabler.min.js') ?>" defer></script>

    <?php if (session()->get('isLoggedIn')): ?>
        <script>
            function fetchNotifications() {
                $.ajax({
                    url: '<?= base_url("api/notifications/unread-count") ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status && response.data.total > 0) {
                            $('#notification-badge').text(response.data.total).show();
                            let html = '';
                            if (response.data.disposisi > 0) {
                                html += `<div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                                            <div class="col text-truncate">
                                                <a href="<?= base_url('disposisi') ?>" class="text-body d-block">Disposisi Baru</a>
                                                <div class="d-block text-secondary text-truncate mt-n1">
                                                    Anda memiliki ${response.data.disposisi} disposisi pending.
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                            }
                            if (response.data.approval > 0) {
                                html += `<div class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-auto"><span class="status-dot status-dot-animated bg-yellow d-block"></span></div>
                                            <div class="col text-truncate">
                                                <a href="<?= base_url('surat-keluar') ?>" class="text-body d-block">Menunggu Approval</a>
                                                <div class="d-block text-secondary text-truncate mt-n1">
                                                    Ada ${response.data.approval} draf surat keluar perlu dicek.
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                            }
                            $('#notification-list').html(html);
                        } else {
                            $('#notification-badge').hide();
                            $('#notification-list').html(`
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col text-truncate text-center">
                                        <div class="d-block text-secondary mt-n1">Belum ada notifikasi baru.</div>
                                    </div>
                                </div>
                            </div>
                        `);
                        }
                    }
                });
            }

            $(document).ready(function() {
                fetchNotifications();
            });
        </script>
    <?php endif; ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        <?php if (session()->getFlashdata('success')) : ?>
            Toast.fire({
                icon: 'success',
                title: '<?= esc(stripslashes((string)session()->getFlashdata("success"))) ?>'
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            Toast.fire({
                icon: 'error',
                title: '<?= esc(stripslashes((string)session()->getFlashdata("error"))) ?>'
            });
        <?php endif; ?>
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (el) {
                new bootstrap.Tooltip(el, { trigger: 'hover focus' });
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>