<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-12">
        <a href="<?= base_url('data-guru') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left icon me-2"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Data Guru / Pegawai</h3>
    </div>
    <div class="card-body">

        <?php if (session()->has('errors')) : ?>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-title">Terdapat kesalahan pengisian form:</h4>
                <div class="text-secondary">
                    <ul>
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        <?php endif ?>

        <form action="<?= base_url('data-guru/update/' . $guru['id']) ?>" method="post">
            <?= csrf_field() ?>

            <div class="row row-cards">
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap & Gelar</label>
                        <input type="text" class="form-control" name="nama_pegawai" placeholder="Masukkan nama..." value="<?= old('nama_pegawai', $guru['nama_pegawai']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">NIP</label>
                        <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP jika ada" value="<?= old('nip', $guru['nip']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">PEG ID / NUPTK</label>
                        <input type="text" class="form-control" name="peg_id_nuptk" placeholder="Masukkan PEG ID / NUPTK" value="<?= old('peg_id_nuptk', $guru['peg_id_nuptk']) ?>">
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" name="tempat_lahir" placeholder="Contoh: Jakarta" value="<?= old('tempat_lahir', $guru['tempat_lahir']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="<?= old('tanggal_lahir', $guru['tanggal_lahir']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Estimasi Tanggal Pensiun (60 Thn)</label>
                        <input type="text" class="form-control" id="tanggal_pensiun" placeholder="Akan terisi otomatis..." readonly>
                    </div>
                </div>
                <input type="hidden" name="tempat_tanggal_lahir" value="<?= old('tempat_tanggal_lahir', $guru['tempat_tanggal_lahir']) ?>">
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Jabatan / Mengajar</label>
                        <input type="text" class="form-control" name="jabatan_mengajar" placeholder="Contoh: Guru Kelas / Kepala Sekolah" value="<?= old('jabatan_mengajar', $guru['jabatan_mengajar']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Pangkat Golongan</label>
                        <input type="text" class="form-control" name="pangkat_golongan" placeholder="Contoh: Pembina Tk. I, IV/b" value="<?= old('pangkat_golongan', $guru['pangkat_golongan']) ?>">
                    </div>
                </div>

                <div class="col-md-6 col-xl-6">
                    <div class="mb-3">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <input type="text" class="form-control" name="pendidikan_terakhir" placeholder="Contoh: S1 / S2" value="<?= old('pendidikan_terakhir', $guru['pendidikan_terakhir']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-6">
                    <div class="mb-3">
                        <label class="form-label">Perguruan Tinggi</label>
                        <input type="text" class="form-control" name="perguruan_tinggi" placeholder="Nama kampus lulusan" value="<?= old('perguruan_tinggi', $guru['perguruan_tinggi']) ?>">
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="mb-3">
                        <label class="form-label">Mulai Tugas (TMT Pertama)</label>
                        <input type="date" class="form-control" name="mulai_tugas" id="mulai_tugas" value="<?= old('mulai_tugas', $guru['mulai_tugas']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="mb-3">
                        <label class="form-label">TMT CPNS / Honorer</label>
                        <input type="date" class="form-control" name="tmt_cpns_honorer" id="tmt_cpns_honorer" value="<?= old('tmt_cpns_honorer', $guru['tmt_cpns_honorer']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="mb-3">
                        <label class="form-label">Masa Kerja (di MIN)</label>
                        <input type="text" class="form-control" name="masa_kerja_min" id="masa_kerja_min" placeholder="Terisi otomatis..." value="<?= old('masa_kerja_min', $guru['masa_kerja_min']) ?>" readonly>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="mb-3">
                        <label class="form-label">Masa Kerja PNS</label>
                        <input type="text" class="form-control" name="masa_kerja_pns" id="masa_kerja_pns" placeholder="Terisi otomatis..." value="<?= old('masa_kerja_pns', $guru['masa_kerja_pns']) ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Kenaikan Pangkat (Terakhir)</label>
                        <input type="text" class="form-control" name="kenaikan_pangkat" placeholder="Contoh: 01 April 2022" value="<?= old('kenaikan_pangkat', $guru['kenaikan_pangkat']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Status Kepegawaian</label>
                        <?php $currentStatus = old('status_kepegawaian', $guru['status_kepegawaian']); ?>
                        <select name="status_kepegawaian" class="form-select">
                            <option value="PNS" <?= $currentStatus == 'PNS' ? 'selected' : '' ?>>PNS</option>
                            <option value="CPNS" <?= $currentStatus == 'CPNS' ? 'selected' : '' ?>>CPNS</option>
                            <option value="PPPK" <?= $currentStatus == 'PPPK' ? 'selected' : '' ?>>PPPK</option>
                            <option value="Honorer" <?= strtolower($currentStatus) == 'honorer' ? 'selected' : '' ?>>Honorer / Non PNS</option>
                            <option value="GTT" <?= $currentStatus == 'GTT' ? 'selected' : '' ?>>GTT</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12"></div> <!-- Spacer -->

                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="email@example.com" value="<?= old('email', $guru['email']) ?>">
                    </div>
                </div>
                <div class="col-md-6 col-xl-4">
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" class="form-control" name="no_handphone" placeholder="08xxxxxxxxxx" value="<?= old('no_handphone', $guru['no_handphone']) ?>">
                    </div>
                </div>

            </div>

            <div class="form-footer text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy icon me-2"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function calculateMasaKerja(startDateInputId, targetInputId) {
            const startDateVal = document.getElementById(startDateInputId).value;
            const targetInput = document.getElementById(targetInputId);

            if (!startDateVal) {
                targetInput.value = '';
                return;
            }

            const start = new Date(startDateVal);
            const now = new Date();

            if (start > now) {
                targetInput.value = '0 th 0 bln';
                return;
            }

            let years = now.getFullYear() - start.getFullYear();
            let months = now.getMonth() - start.getMonth();

            if (months < 0) {
                years--;
                months += 12;
            }

            targetInput.value = years + ' th ' + months + ' bln';
        }

        function formatIndoDate(date) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[date.getDay()];
            const day = date.getDate().toString().padStart(2, '0');
            const month = months[date.getMonth()];
            const year = date.getFullYear();
            
            return `${dayName}, ${day} ${month} ${year}`;
        }

        function calculatePensiun() {
            const birthdayVal = document.getElementById('tanggal_lahir').value;
            const pensiunInput = document.getElementById('tanggal_pensiun');
            
            if (!birthdayVal) {
                pensiunInput.value = '';
                return;
            }
            
            const birthDate = new Date(birthdayVal);
            const pensiunDate = new Date(birthDate);
            pensiunDate.setFullYear(birthDate.getFullYear() + 60);
            
            pensiunInput.value = formatIndoDate(pensiunDate);
        }

        const birthdayInput = document.getElementById('tanggal_lahir');
        if (birthdayInput) {
            birthdayInput.addEventListener('change', calculatePensiun);
            if (birthdayInput.value) calculatePensiun();
        }

        const mulaiTugasInput = document.getElementById('mulai_tugas');
        const tmtCpnsInput = document.getElementById('tmt_cpns_honorer');

        if (mulaiTugasInput) {
            mulaiTugasInput.addEventListener('change', function() {
                calculateMasaKerja('mulai_tugas', 'masa_kerja_min');
            });
            // Initial calc on load
            if (mulaiTugasInput.value) calculateMasaKerja('mulai_tugas', 'masa_kerja_min');
        }

        if (tmtCpnsInput) {
            tmtCpnsInput.addEventListener('change', function() {
                calculateMasaKerja('tmt_cpns_honorer', 'masa_kerja_pns');
            });
            // Initial calc on load
            if (tmtCpnsInput.value) calculateMasaKerja('tmt_cpns_honorer', 'masa_kerja_pns');
        }
    });
</script>
<?= $this->endSection() ?>