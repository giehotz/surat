<?php
$this->setData(['container_class' => $container_class ?? 'container-fluid px-3 px-lg-4']);
?>
<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Page Header (Craft-floor: No forbidden kicker/eyebrow; theme-adaptive heading and description) -->
<div class="page-header d-print-none mb-3">
    <div class="w-100 p-0">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title fw-bold">
                    <i class="ti ti-building-community me-2 text-primary"></i> Data Madrasah
                </h2>
                <div class="text-secondary small mt-1">
                    Pusat data terpadu rombongan belajar, pendidik & tenaga kependidikan, serta peserta didik madrasah.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Institutional Cockpit Telemetry Cards (Fully Theme-Adaptive) -->
<div class="row g-3 mb-4">
    <!-- Stat 1: Rombel / Kelas -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-hover-shadow cursor-pointer transition border shadow-sm rounded-3 telemetry-card" data-tab-target="#nav-tab-kelas">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md bg-blue-lt text-blue rounded-3 me-3">
                        <i class="ti ti-door fs-2"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="text-secondary small fw-medium">Rombongan Belajar</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="h1 mb-0 fw-bold font-tabular"><?= (int)($stats['total_kelas'] ?? 0) ?></span>
                            <span class="text-secondary small">Kelas</span>
                        </div>
                    </div>
                    <div class="text-secondary small d-flex align-items-center">
                        <span class="badge bg-blue-lt">Buka Tab <i class="ti ti-chevron-right ms-1"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 2: Guru & Pegawai -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-hover-shadow cursor-pointer transition border shadow-sm rounded-3 telemetry-card" data-tab-target="#nav-tab-data-guru">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md bg-green-lt text-green rounded-3 me-3">
                        <i class="ti ti-users fs-2"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="text-secondary small fw-medium">Pendidik & Tendik</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="h1 mb-0 fw-bold font-tabular"><?= (int)($stats['total_guru'] ?? 0) ?></span>
                            <span class="text-secondary small">
                                <?= (int)($stats['total_pns'] ?? 0) ?> PNS • <?= (int)($stats['total_honorer'] ?? 0) ?> Non-PNS
                            </span>
                        </div>
                    </div>
                    <div class="text-secondary small d-flex align-items-center">
                        <span class="badge bg-green-lt">Buka Tab <i class="ti ti-chevron-right ms-1"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat 3: Siswa Aktif -->
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card card-hover-shadow cursor-pointer transition border shadow-sm rounded-3 telemetry-card" data-tab-target="#nav-tab-siswa">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-md bg-azure-lt text-azure rounded-3 me-3">
                        <i class="ti ti-school fs-2"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="text-secondary small fw-medium">Peserta Didik</div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="h1 mb-0 fw-bold font-tabular"><?= (int)($stats['total_siswa_aktif'] ?? 0) ?></span>
                            <span class="text-secondary small">
                                Aktif (Total <?= (int)($stats['total_siswa_semua'] ?? 0) ?>)
                            </span>
                        </div>
                    </div>
                    <div class="text-secondary small d-flex align-items-center">
                        <span class="badge bg-azure-lt">Buka Tab <i class="ti ti-chevron-right ms-1"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unified Master Tab Card (Theme-Adaptive) -->
<div class="card shadow-sm border rounded-3">
    <!-- Header Tabs (Transparent/Theme-Aware Background: No hardcoded bg-light or bg-white) -->
    <div class="card-header p-0 border-bottom">
        <ul class="nav nav-tabs card-header-tabs w-100 flex-column flex-md-row m-0" id="madrasahTabs" role="tablist">
            <li class="nav-item flex-fill" role="presentation">
                <a href="#tab-kelas" id="nav-tab-kelas" class="nav-link py-3 fw-semibold text-center border-0 border-bottom <?= $active_tab === 'kelas' ? 'active' : '' ?>" data-bs-toggle="tab" role="tab" aria-controls="tab-kelas" aria-selected="<?= $active_tab === 'kelas' ? 'true' : 'false' ?>">
                    <i class="ti ti-door icon me-2 fs-3 text-blue"></i>
                    <span>Data Kelas</span>
                    <span class="badge bg-blue-lt ms-2 rounded-pill"><?= (int)($stats['total_kelas'] ?? 0) ?></span>
                </a>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <a href="#tab-data-guru" id="nav-tab-data-guru" class="nav-link py-3 fw-semibold text-center border-0 border-bottom <?= $active_tab === 'data_guru' ? 'active' : '' ?>" data-bs-toggle="tab" role="tab" aria-controls="tab-data-guru" aria-selected="<?= $active_tab === 'data_guru' ? 'true' : 'false' ?>">
                    <i class="ti ti-users icon me-2 fs-3 text-green"></i>
                    <span>Data Guru & Pegawai</span>
                    <span class="badge bg-green-lt ms-2 rounded-pill"><?= (int)($stats['total_guru'] ?? 0) ?></span>
                </a>
            </li>
            <li class="nav-item flex-fill" role="presentation">
                <a href="#tab-siswa" id="nav-tab-siswa" class="nav-link py-3 fw-semibold text-center border-0 border-bottom <?= $active_tab === 'siswa' ? 'active' : '' ?>" data-bs-toggle="tab" role="tab" aria-controls="tab-siswa" aria-selected="<?= $active_tab === 'siswa' ? 'true' : 'false' ?>">
                    <i class="ti ti-school icon me-2 fs-3 text-azure"></i>
                    <span>Data Siswa</span>
                    <span class="badge bg-azure-lt ms-2 rounded-pill"><?= (int)($stats['total_siswa_aktif'] ?? 0) ?></span>
                </a>
            </li>
        </ul>
    </div>

    <!-- Content Panes (Tanpa nested cards) -->
    <div class="card-body p-3 p-md-4">
        <div class="tab-content" id="madrasahTabContent">
            <!-- Tab 1: Kelas -->
            <div class="tab-pane <?= $active_tab === 'kelas' ? 'active show' : '' ?>" id="tab-kelas" role="tabpanel" aria-labelledby="nav-tab-kelas">
                <?= $this->include('data_madrasah/kelas/index') ?>
            </div>

            <!-- Tab 2: Guru -->
            <div class="tab-pane <?= $active_tab === 'data_guru' ? 'active show' : '' ?>" id="tab-data-guru" role="tabpanel" aria-labelledby="nav-tab-data-guru">
                <?= $this->include('data_madrasah/data_guru/index') ?>
            </div>

            <!-- Tab 3: Siswa -->
            <div class="tab-pane <?= $active_tab === 'siswa' ? 'active show' : '' ?>" id="tab-siswa" role="tabpanel" aria-labelledby="nav-tab-siswa">
                <?= $this->include('data_madrasah/siswa/index') ?>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling Tabs dan Font Tabular yang Ramah Dark Mode */
    .font-tabular {
        font-variant-numeric: tabular-nums;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .telemetry-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .telemetry-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.12) !important;
    }
    
    /* Header tabs: adaptif terhadap light & dark theme */
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
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi DataTable untuk Kelas (jika data tersedia)
        if ($.fn.DataTable && $('.datatable-kelas tbody tr td[colspan]').length === 0) {
            $('.datatable-kelas').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json",
                    search: "",
                    searchPlaceholder: "Cari kelas..."
                },
                pageLength: 10,
                columnDefs: [{ orderable: false, targets: [6] }]
            });
        }

        // Inisialisasi DataTable untuk Guru (jika data tersedia)
        if ($.fn.DataTable && $('.datatable-guru tbody tr td[colspan]').length === 0) {
            $('.datatable-guru').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json",
                    search: "",
                    searchPlaceholder: "Cari guru/pegawai..."
                },
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: [7] }]
            });
        }

        // Sync tab saat diklik
        const tabLinks = document.querySelectorAll('#madrasahTabs a[data-bs-toggle="tab"]');
        tabLinks.forEach(function(tabLink) {
            tabLink.addEventListener('shown.bs.tab', function(e) {
                const targetId = e.target.getAttribute('href');
                let tabKey = 'kelas';
                if (targetId === '#tab-data-guru') tabKey = 'data_guru';
                if (targetId === '#tab-siswa') tabKey = 'siswa';

                localStorage.setItem('madrasahActiveTab', tabKey);

                // Update URL query parameter secara seamless tanpa reload
                if (window.history.replaceState) {
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabKey);
                    window.history.replaceState({}, '', url);
                }

                // Adjust DataTables responsive column alignment
                setTimeout(function() {
                    if ($.fn.DataTable && $.fn.DataTable.tables) {
                        $($.fn.DataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
                    }
                }, 50);
            });
        });

        // Telemetry cards: klik langsung membuka tab terkait
        document.querySelectorAll('.telemetry-card').forEach(function(card) {
            card.addEventListener('click', function() {
                const targetSelector = this.getAttribute('data-tab-target');
                const targetTab = document.querySelector(targetSelector);
                if (targetTab) {
                    const tabTrigger = new bootstrap.Tab(targetTab);
                    tabTrigger.show();
                    // Scroll lembut ke tab card jika di mobile
                    if (window.innerWidth < 768) {
                        targetTab.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        // Unified SweetAlert2 Delete Confirmation
        $(document).on('click', '.btn-delete-item', function(e) {
            e.preventDefault();
            const btn = $(this);
            const form = btn.closest('form');
            const title = btn.data('title') || 'Apakah Anda yakin?';
            const text = btn.data('text') || 'Data yang dihapus tidak dapat dipulihkan kembali!';

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d63939',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            } else {
                if (confirm(title + '\n' + text)) {
                    form.submit();
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>