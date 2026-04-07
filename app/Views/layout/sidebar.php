<!-- Header Top (Logo & User) -->
<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl d-flex align-items-center justify-content-between">
        <!-- Mobile Menu Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Brand / Logo -->
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3 mb-0">
            <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center text-decoration-none">
                <?php if (!empty($appSettings['sekolah_logo'])): ?>
                    <img src="<?= base_url('uploads/logo/' . $appSettings['sekolah_logo']) ?>" height="32" alt="Logo" class="me-2">
                <?php else: ?>
                    <i class="ti ti-mail-fast text-primary me-2" style="font-size: 24px;"></i>
                <?php endif; ?>
                <?= esc($appSettings['app_nama'] ?? 'SuratApp') ?>
            </a>
        </h1>

        <!-- Navbar Menu (Kanan) -->
        <div class="navbar-nav flex-row order-md-last align-items-center gap-3">
            
            <!-- Group: Theme & Notifications (Desktop Only) -->
            <div class="d-none d-md-flex align-items-center gap-3">
                
                <!-- Dark/Light Mode Toggles -->
                <div class="d-flex align-items-center">
                    <a href="javascript:void(0);" class="nav-link px-0 hide-theme-dark" id="btn-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="toggleTheme('dark')">
                        <i class="ti ti-moon icon"></i>
                    </a>
                    <a href="javascript:void(0);" class="nav-link px-0 hide-theme-light" id="btn-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom" onclick="toggleTheme('light')">
                        <i class="ti ti-sun icon"></i>
                    </a>
                </div>

                <!-- Notification Dropdown -->
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link px-0 d-flex align-items-center" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
                        <i class="ti ti-bell icon"></i>
                        <span class="badge bg-red badge-blink" id="notification-badge" style="display: none;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card shadow-sm">
                        <div class="card border-0">
                            <div class="card-header border-bottom-0">
                                <h3 class="card-title">Notifikasi Terbaru</h3>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable" id="notification-list">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col text-truncate text-center">
                                            <div class="d-block text-secondary mt-n1">Memuat notifikasi...</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex align-items-center lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <?php if (session('foto_profile')): ?>
                        <span class="avatar avatar-sm" style="background-image: url('<?= base_url('uploads/profiles/' . session('foto_profile')) ?>')"></span>
                    <?php else: ?>
                        <span class="avatar avatar-sm" style="background-image: url('https://ui-avatars.com/api/?name=<?= urlencode((string)(session('nama_lengkap') ?? 'User')) ?>')"></span>
                    <?php endif; ?>
                    <div class="d-none d-xl-block ps-2 text-start">
                        <div class="fw-semibold"><?= esc((string)(session('nama_lengkap') ?? 'Pengguna')) ?></div>
                        <div class="mt-1 small text-secondary"><?= ucfirst(esc((string)(session('role') ?? 'staf'))) ?></div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-sm">
                    <a href="<?= base_url('profile') ?>" class="dropdown-item">Profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="<?= base_url('auth/logout') ?>" class="dropdown-item text-danger">Logout</a>
                </div>
            </div>
            
        </div>
    </div>
</header>