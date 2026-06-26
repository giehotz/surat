<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h3 class="card-title mb-0"><?= esc($title) ?></h3>
                <a href="<?= base_url('surat-resmi/template/create') ?>" class="btn btn-primary btn-sm">
                    <i class="ti ti-plus me-1"></i> Template Baru
                </a>
            </div>
            <div class="card-body">
                <?php if (session()->get('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->get('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Template</th>
                                <th>Slug</th>
                                <th>Perihal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($templates)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada template surat.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($templates as $t): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= esc($t['nama']) ?></strong></td>
                                        <td><code><?= esc($t['slug']) ?></code></td>
                                        <td><?= esc($t['perihal']) ?></td>
                                        <td>
                                            <a href="<?= base_url('surat-resmi/template/edit/' . $t['id']) ?>" class="btn btn-outline-warning btn-icon" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="<?= base_url('surat-resmi/template/delete/' . $t['id']) ?>" method="post" style="display:inline;">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-outline-danger btn-icon" title="Hapus" onclick="return confirm('Hapus template \'<?= esc($t['nama']) ?>\'?');">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
