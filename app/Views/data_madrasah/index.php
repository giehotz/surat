<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<!-- Page Header Baru: Memberikan hirarki visual yang lebih baik -->
<div class="page-header d-print-none mb-4">
    <div class="container-xl p-0">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle text-muted text-uppercase tracking-wide mb-1">
                    Manajemen
                </div>
                <h2 class="page-title text-dark fw-bold">
                    <i class="ti ti-database me-2 text-primary"></i> Data Madrasah
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <!-- Pindah ke Horizontal Tabs di Card Header -->
            <div class="card-header p-0 border-bottom">
                <ul class="nav nav-tabs nav-fill w-100 flex-column flex-md-row bg-light rounded-top" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tab-kelas" class="nav-link py-3 fw-medium <?= $active_tab == 'kelas' ? 'active bg-white border-bottom-0' : 'text-muted' ?>" data-bs-toggle="tab" aria-selected="<?= $active_tab == 'kelas' ? 'true' : 'false' ?>" role="tab">
                            <i class="ti ti-door icon me-2 fs-3 text-azure"></i> Data Kelas
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-data-guru" class="nav-link py-3 fw-medium <?= $active_tab == 'data_guru' ? 'active bg-white border-bottom-0' : 'text-muted' ?>" data-bs-toggle="tab" aria-selected="<?= $active_tab == 'data_guru' ? 'true' : 'false' ?>" role="tab">
                            <i class="ti ti-users icon me-2 fs-3 text-blue"></i> Data Guru / Pegawai
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tab-siswa" class="nav-link py-3 fw-medium <?= $active_tab == 'siswa' ? 'active bg-white border-bottom-0' : 'text-muted' ?>" data-bs-toggle="tab" aria-selected="<?= $active_tab == 'siswa' ? 'true' : 'false' ?>" role="tab">
                            <i class="ti ti-school icon me-2 fs-3 text-primary"></i> Data Siswa
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="card-body p-0">
                <div class="tab-content">
                    <!-- Tambahkan p-3 atau p-4 di dalam tab-pane untuk spasi -->
                    <div class="tab-pane <?= $active_tab == 'kelas' ? 'active show' : '' ?> p-4" id="tab-kelas" role="tabpanel">
                        <?= $this->include('data_madrasah/kelas/index') ?>
                    </div>
                    
                    <div class="tab-pane <?= $active_tab == 'data_guru' ? 'active show' : '' ?> p-4" id="tab-data-guru" role="tabpanel">
                        <?= $this->include('data_madrasah/data_guru/index') ?>
                    </div>

                    <div class="tab-pane <?= $active_tab == 'siswa' ? 'active show' : '' ?> p-4" id="tab-siswa" role="tabpanel">
                        <?= $this->include('data_madrasah/siswa/index') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

/* Tambahan CSS sedikit untuk mempercantik transisi tab */
<style>
    .nav-tabs .nav-link {
        transition: all 0.3s ease;
        border: none;
        border-bottom: 2px solid transparent;
    }
    .nav-tabs .nav-link:hover {
        background-color: rgba(0,0,0,0.02);
        border-bottom: 2px solid #e6e8e9;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 2px solid #206bc4; /* Warna primary Bootstrap/Tabler */
        color: #206bc4 !important;
    }
    .nav-tabs .nav-link.active i {
        transform: scale(1.1);
        transition: transform 0.2s;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika LocalStorage untuk mengingat tab terakhir yang dibuka
        const activeTab = localStorage.getItem('madrasahActiveTab');
        if (activeTab) {
            // Hapus semua class active
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active', 'bg-white', 'border-bottom-0');
                link.classList.add('text-muted');
                link.setAttribute('aria-selected', 'false');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active', 'show'));
            
            // Set tab yang tersimpan menjadi active
            const targetTab = document.querySelector(`a[href="${activeTab}"]`);
            if (targetTab) {
                targetTab.classList.add('active', 'bg-white', 'border-bottom-0');
                targetTab.classList.remove('text-muted');
                targetTab.setAttribute('aria-selected', 'true');
                
                const targetPaneId = targetTab.getAttribute('href');
                const targetPane = document.querySelector(targetPaneId);
                if (targetPane) targetPane.classList.add('active', 'show');
            }
        }

        // Simpan ke LocalStorage saat tab diklik
        document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                const href = e.target.getAttribute('href');
                localStorage.setItem('madrasahActiveTab', href);
                
                // Ubah styling untuk tab yang baru aktif
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.classList.remove('bg-white', 'border-bottom-0');
                    link.classList.add('text-muted');
                });
                e.target.classList.add('bg-white', 'border-bottom-0');
                e.target.classList.remove('text-muted');
            });
        });
        
        // Re-initialize datatables untuk mencegah error header tidak sejajar
        // Dijalankan dengan sedikit delay agar DOM selesai render
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            setTimeout(function() {
                if ($.fn.DataTable.isDataTable($.fn.dataTable.tables(true))) {
                    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
                }
            }, 10);
        });
    });
</script>
<?= $this->endSection() ?>