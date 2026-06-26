<style>
/* Kop Surat */
.kop-surat {
    display: flex;
    align-items: center;
    border-bottom: 3px solid #000;
    padding-bottom: 10px;
    margin-bottom: 20px;
    position: relative;
}
.kop-surat::after { /* Garis tipis di bawah garis tebal */
    content: '';
    position: absolute;
    left: 0;
    right: 0;
    bottom: -5px;
    border-bottom: 1px solid #000;
}
.logo-kop {
    width: 90px;
    height: auto;
    margin-right: 20px;
}
.teks-kop {
    text-align: center;
    flex-grow: 1;
    line-height: 1;
}
.teks-kop h1 {
    font-size: 16pt;
    margin: 0;
    text-transform: uppercase;
}
.teks-kop h2 {
    font-size: 18pt;
    margin: 0;
    font-weight: bold;
    text-transform: uppercase;
}
.teks-kop p {
    font-size: 11pt;
    margin: 2px 0 0 0;
}
</style>

<div class="kop-surat">
    <?php 
        // Gunakan logo dari appSettings jika ada, atau logo default
        $logo = !empty($appSettings['sekolah_logo']) ? base_url('uploads/logo/' . $appSettings['sekolah_logo']) : 'https://via.placeholder.com/90';
    ?>
    <img src="<?= $logo ?>" class="logo-kop" alt="Logo Instansi">
    <div class="teks-kop">
        <h1 style="font-size: 12pt; margin-bottom: 2px; font-weight: bold;"><?= esc($appSettings['sekolah_kementerian'] ?? 'KEMENTERIAN AGAMA REPUBLIK INDONESIA') ?></h1>
        <h1 style="font-size: 12pt; margin-bottom: 2px; font-weight: bold;"><?= esc($appSettings['sekolah_kantor_kementerian'] ?? 'KANTOR KEMENTERIAN AGAMA KABUPATEN TANGGAMUS') ?></h1>
        <h2 style="font-size: 12pt; margin-bottom: 5px;"><?= esc($appSettings['sekolah_nama'] ?? 'MADRASAH IBTIDAIYAH NEGERI 2 TANGGAMUS') ?></h2>
        <p style="font-size: 9pt; margin-bottom: 0;"><?= esc($appSettings['sekolah_alamat'] ?? 'Jln. Lap. Ampera No. 109 Purwodadi Kec. Gisting Kab. Tanggamus (0729) 347578 35378') ?></p>
        <p style="font-size: 9pt;">Email : <?= esc($appSettings['sekolah_kontak'] ?? 'minduatanggamus@gmail.com') ?></p>
    </div>
</div>
