<div class="tab-pane <?= ($active_tab ?? '') == 'backup' ? 'active show' : '' ?>" id="tab-backup">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="ti ti-database-export me-2 text-primary"></i>Backup Database Sistem
        </h3>
    </div>
    
    <div class="card-body">
        <!-- Informasi Peringatan -->
        <div class="alert alert-info bg-info-lt mb-4" role="alert">
            <div class="d-flex">
                <div>
                    <i class="ti ti-info-circle icon alert-icon text-info me-3 mt-1" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="alert-title mb-1">Pentingnya Pencadangan Rutin</h4>
                    <div class="text-secondary">
                        Harap lakukan pencadangan (backup) database secara berkala untuk menghindari kehilangan data akibat kejadian yang tidak diinginkan. File akan diunduh dalam format <code>.sql</code> yang dapat di-restore kapan saja.
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Aksi Backup -->
        <div class="empty bg-light-subtle rounded border border-light py-5 my-4">
            <div class="empty-icon text-muted mb-4">
                <i class="ti ti-server-2" style="font-size: 4rem; opacity: 0.5;"></i>
            </div>
            
            <h3 class="empty-title">Unduh Data Sistem Saat Ini</h3>
            
            <p class="empty-subtitle text-muted w-75 mx-auto mb-4">
                Proses ini akan mengumpulkan seluruh data Anda (pengaturan, tabel, dan log) dan mengemasnya ke dalam satu file. Simpan file tersebut di tempat yang aman.
            </p>
            
            <div class="empty-action text-center">
                <a href="<?= base_url('admin/backup-db') ?>" class="btn btn-primary btn-lg shadow-sm d-inline-flex align-items-center gap-2">
                    <i class="ti ti-download fs-3"></i>
                    Unduh File Backup (.sql)
                </a>
            </div>
            
            <div class="text-muted mt-3 small">
                <i class="ti ti-clock me-1"></i> Proses unduhan mungkin memakan waktu beberapa saat tergantung pada ukuran database.
            </div>
        </div>
    </div>
</div>