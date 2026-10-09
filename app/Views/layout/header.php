<!-- Header Navigation Menu -->
<style>
    /* Paksa navbar dan setiap teks item menu tetap 1 baris tanpa wrapping */
    #navbar-menu .navbar-nav {
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
    }
    #navbar-menu .nav-item {
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }
    #navbar-menu .nav-link {
        white-space: nowrap !important;
        padding-left: 0.55rem !important;
        padding-right: 0.55rem !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    #navbar-menu .nav-link-title {
        white-space: nowrap !important;
        word-break: keep-all !important;
        display: inline !important;
    }
    #navbar-menu .nav-link-icon {
        margin-right: 0.35rem !important;
    }
</style>
<header class="navbar-expand-md position-sticky top-0" style="z-index: 1030; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl d-flex justify-content-center">
                <ul class="navbar-nav mx-auto flex-nowrap gap-1 gap-xl-2">
                    <?php if (session('role') !== 'admin_tamu'): ?>
                    <li class="nav-item <?= current_url() == base_url('dashboard') ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-home icon text-blue"></i>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (session('role') !== 'admin_tamu'): ?>
                    <li class="nav-item <?= strpos(current_url(), 'surat-keluar') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('surat-keluar') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-send icon text-green"></i>
                            </span>
                            <span class="nav-link-title">
                                Surat Keluar
                            </span>
                        </a>
                    </li>
                    <li class="nav-item <?= strpos(current_url(), 'surat-masuk') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('surat-masuk') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-mail icon text-red"></i>
                            </span>
                            <span class="nav-link-title">
                                Surat Masuk
                            </span>
                        </a>
                    </li>

                    <li class="nav-item <?= strpos(current_url(), 'disposisi') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('disposisi') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-directions icon text-yellow"></i>
                            </span>
                            <span class="nav-link-title">
                                Disposisi
                            </span>
                        </a>
                    </li>

                    <?php if (in_array(session('role'), ['admin', 'operator', 'pimpinan'])): ?>
                    <li class="nav-item <?= strpos(current_url(), 'laporan') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('laporan') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-file-analytics icon text-indigo"></i>
                            </span>
                            <span class="nav-link-title">
                                Laporan
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="nav-item <?= strpos(current_url(), 'prestasi-siswa') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('prestasi-siswa') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-award icon text-cyan"></i>
                            </span>
                            <span class="nav-link-title">
                                Prestasi Siswa
                            </span>
                        </a>
                    </li>

                    <!-- Menu Daftar Hadir (Submenu: Daftar Hadir Rapat & Daftar Hadir Harian) -->
                    <li class="nav-item dropdown <?= strpos(current_url(), 'daftar-hadir') !== false ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" href="#navbar-daftar-hadir" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-clipboard-list icon text-teal"></i>
                            </span>
                            <span class="nav-link-title">
                                Daftar Hadir
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item <?= (strpos(current_url(), 'daftar-hadir/rapat') !== false || current_url() == base_url('daftar-hadir')) ? 'active' : '' ?>" href="<?= base_url('daftar-hadir/rapat') ?>">
                                <span class="nav-link-icon d-inline-block me-1"><i class="ti ti-notes icon text-teal"></i></span>
                                1. Daftar Hadir Rapat
                            </a>
                            <a class="dropdown-item <?= strpos(current_url(), 'daftar-hadir/harian') !== false ? 'active' : '' ?>" href="<?= base_url('daftar-hadir/harian') ?>">
                                <span class="nav-link-icon d-inline-block me-1"><i class="ti ti-calendar-event icon text-primary"></i></span>
                                2. Daftar Hadir Harian
                            </a>
                        </div>
                    </li>

                    <?php endif; ?>
                    <?php if (session('role') !== 'operator' && session('role') !== 'admin_tamu'): ?>
                    <li class="nav-item <?= strpos(current_url(), 'data-madrasah') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('data-madrasah') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-school icon text-purple"></i>
                            </span>
                            <span class="nav-link-title">
                                Data Madrasah
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Menu Buku Tamu (Submenu: Rekap Tamu & Kiosk Tamu) -->
                    <li class="nav-item dropdown <?= (strpos(current_url(), 'buku-tamu') !== false) ? 'active' : '' ?>">
                        <a class="nav-link dropdown-toggle" href="#navbar-buku-tamu" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-notebook icon text-pink"></i>
                            </span>
                            <span class="nav-link-title">
                                Buku Tamu
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <?php if (strpos(session('role'), 'admin') !== false || session('role') === 'piket' || session('role') === 'operator' || session('role') === 'admin_tamu'): ?>
                            <a class="dropdown-item <?= strpos(current_url(), 'admin-buku-tamu') !== false ? 'active' : '' ?>" href="<?= base_url('admin-buku-tamu') ?>">
                                <span class="nav-link-icon d-inline-block me-1"><i class="ti ti-address-book icon text-pink"></i></span>
                                Rekap Tamu
                            </a>
                            <?php endif; ?>
                            <a class="dropdown-item <?= (strpos(current_url(), 'buku-tamu') !== false && strpos(current_url(), 'admin-buku-tamu') === false) ? 'active' : '' ?>" target="_blank" href="<?= base_url('buku-tamu') ?>">
                                <span class="nav-link-icon d-inline-block me-1"><i class="ti ti-users icon text-orange"></i></span>
                                Kiosk Tamu
                            </a>
                        </div>
                    </li>

                    <?php if (session('role') !== 'operator' && session('role') !== 'admin_tamu' && session('role') !== 'piket'): ?>
                    <li class="nav-item <?= strpos(current_url(), 'pengaturan') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('pengaturan') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-settings icon text-secondary"></i>
                            </span>
                            <span class="nav-link-title">
                                Pengaturan
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</header>