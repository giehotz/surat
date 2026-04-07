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
    <!-- CSS files -->
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler-flags.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler-payments.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/css/tabler-vendors.min.css" rel="stylesheet" />
    <!-- FontAwesome (Optional, if you still want to use FA icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    </style>
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
                <div class="container-xl">
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
                <div class="container-xl">

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
    <!-- Tabler Core -->
    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta19/dist/js/tabler.min.js" defer></script>

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

    <?= $this->renderSection('scripts') ?>
</body>

</html>