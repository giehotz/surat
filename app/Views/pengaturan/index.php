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

    // Fungsi untuk menyimpan tab aktif ke localStorage
    document.addEventListener('DOMContentLoaded', function() {
        // Cek apakah ada tab aktif yang disimpan di localStorage
        const activeTab = localStorage.getItem('pengaturanActiveTab');
        if (activeTab) {
            // Hapus kelas aktif dari semua link
            document.querySelectorAll('.nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Hapus kelas aktif dari semua tab pane
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active', 'show');
            });

            // Aktifkan tab yang disimpan
            const targetTab = document.querySelector(`a[href="${activeTab}"]`);
            if (targetTab) {
                targetTab.classList.add('active');

                // Aktifkan pane yang sesuai
                const targetPaneId = targetTab.getAttribute('href');
                const targetPane = document.querySelector(targetPaneId);
                if (targetPane) {
                    targetPane.classList.add('active', 'show');
                }
            }
        }

        // Tambahkan event listener ke semua tab
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
            tab.addEventListener('shown.bs.tab', function(e) {
                // Simpan ID tab yang sedang aktif ke localStorage
                localStorage.setItem('pengaturanActiveTab', e.target.getAttribute('href'));
            });
        });

        // Setelah submit form, pastikan tab aktif tetap sama
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                // Simpan tab aktif saat ini sebelum submit
                const activeTabLink = document.querySelector('.nav-link.active');
                if (activeTabLink) {
                    localStorage.setItem('pengaturanActiveTab', activeTabLink.getAttribute('href'));
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>