<!-- Header Navigation Menu -->
<header class="navbar-expand-md position-sticky top-0 z-3" style="box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <!-- Menambahkan d-flex dan justify-content-center di sini -->
            <div class="container-xl d-flex justify-content-center">
                <!-- Tambahkan mx-auto untuk memastikan list berada di tengah -->
                <ul class="navbar-nav mx-auto gap-2">
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
                    
                    <?php if (session('role') !== 'operator' && session('role') !== 'admin_tamu'): ?>
                    <?php if (strpos(session('role'), 'admin') !== false || session('role') === 'piket'): ?>
                    <li class="nav-item <?= strpos(current_url(), 'admin-buku-tamu') !== false ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= base_url('admin-buku-tamu') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-address-book icon text-pink"></i>
                            </span>
                            <span class="nav-link-title">
                                Rekap Tamu
                            </span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item <?= strpos(current_url(), 'buku-tamu') !== false && strpos(current_url(), 'admin-buku-tamu') === false ? 'active' : '' ?>">
                        <a class="nav-link" target="_blank" href="<?= base_url('buku-tamu') ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-users icon text-orange"></i>
                            </span>
                            <span class="nav-link-title">
                                Kiosk Tamu
                            </span>
                        </a>
                    </li>
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