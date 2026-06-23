<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row gx-lg-4">
    <div class="col-lg-3">
        <h3 class="mb-3">Kategori Pengaturan</h3>
        <ul class="nav nav-pills nav-vertical" data-bs-toggle="tabs">
            <?php if (session('role') === 'admin'): ?>
            <li class="nav-item">
                <a href="#tab-identitas" class="nav-link <?= $active_tab == 'identitas' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-building icon me-2"></i> Identitas Institusi
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-pimpinan" class="nav-link <?= $active_tab == 'pimpinan' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-users icon me-2"></i> Data Pimpinan
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-preferensi" class="nav-link <?= $active_tab == 'preferensi' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-settings icon me-2"></i> Preferensi Sistem
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-pengguna" class="nav-link <?= $active_tab == 'pengguna' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-users icon me-2 text-pink"></i> Manajemen Pengguna
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-backup" class="nav-link <?= $active_tab == 'backup' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-database icon me-2 text-red"></i> Backup Database
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-wajib-field" class="nav-link <?= $active_tab == 'wajib-field' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-checklist icon me-2 text-warning"></i> Wajib Isi Field
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-tahun-anggaran" class="nav-link <?= $active_tab == 'tahun-anggaran' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-calendar-event icon me-2 text-azure"></i> Manajemen Tahun
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item">
                <a href="#tab-format-surat" class="nav-link <?= $active_tab == 'format-surat' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-file-description icon me-2 text-indigo"></i> Format Surat Keluar
                </a>
            </li>
            <li class="nav-item">
                <a href="#tab-buku-tamu" class="nav-link <?= $active_tab == 'buku-tamu' || session('role') === 'admin_tamu' ? 'active' : '' ?>" data-bs-toggle="tab">
                    <i class="ti ti-notebook icon me-2 text-green"></i> Pengaturan Buku Tamu
                </a>
            </li>
        </ul>
    </div>
    <div class="col-lg-9">
        <div class="card">
            <div class="tab-content">
                <?php if (session('role') === 'admin'): ?>
                <?= $this->include('pengaturan/identitas') ?>
                <?= $this->include('pengaturan/pimpinan') ?>
                <?= $this->include('pengaturan/preferensi') ?>
                <?= $this->include('pengaturan/pengguna') ?>
                <?= $this->include('pengaturan/backup') ?>
                <?= $this->include('pengaturan/wajib_field') ?>
                <?= $this->include('pengaturan/tahun_anggaran') ?>
                <?= $this->include('pengaturan/format_surat') ?>
                <?php endif; ?>
                <?= $this->include('pengaturan/buku_tamu') ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview').style.backgroundImage = 'url(' + e.target.result + ')';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Hanya restore dari localStorage jika server tidak mengirim tab spesifik (default = identitas)
    var serverActiveTab = '<?= $active_tab ?? "identitas" ?>';

    document.addEventListener('DOMContentLoaded', function() {
        // Jika server mengirim tab spesifik (via flashdata setelah submit), jangan timpa
        if (serverActiveTab === 'identitas') {
            var storedTab = localStorage.getItem('pengaturanActiveTab');
            if (storedTab) {
                document.querySelectorAll('.nav-link').forEach(function(link) {
                    link.classList.remove('active');
                });
                document.querySelectorAll('.tab-pane').forEach(function(pane) {
                    pane.classList.remove('active', 'show');
                });
                var targetTab = document.querySelector('a[href="' + storedTab + '"]');
                if (targetTab) {
                    targetTab.classList.add('active');
                    var targetPane = document.querySelector(targetTab.getAttribute('href'));
                    if (targetPane) {
                        targetPane.classList.add('active', 'show');
                    }
                }
            }
        }

        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
            tab.addEventListener('shown.bs.tab', function(e) {
                localStorage.setItem('pengaturanActiveTab', e.target.getAttribute('href'));
            });
        });

        var forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            form.addEventListener('submit', function() {
                var activeTabLink = document.querySelector('.nav-link.active');
                if (activeTabLink) {
                    localStorage.setItem('pengaturanActiveTab', activeTabLink.getAttribute('href'));
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>