<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTemplateSuratResmiTable extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('template_surat_resmi')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'unique'     => true,
                ],
                'perihal' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                ],
                'lampiran' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'default'    => '-',
                    'null'       => true,
                ],
                'tujuan_nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'tujuan_alamat' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'salam_pembuka' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'default'    => 'Dengan hormat,',
                    'null'       => true,
                ],
                'isi_surat' => [
                    'type' => 'TEXT',
                ],
                'salam_penutup' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'default'    => 'Hormat kami,',
                    'null'       => true,
                ],
                'pengirim_jabatan' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'pengirim_nama' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '255',
                    'null'       => true,
                ],
                'pengirim_nip' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->createTable('template_surat_resmi');

            // Insert default templates
            $now = date('Y-m-d H:i:s');
            $templates = [
                [
                    'nama'             => 'Pemberitahuan Gangguan Absensi (PUSAKA)',
                    'slug'             => 'gangguan_absen',
                    'perihal'          => 'Pemberitahuan Gangguan Absensi (Aplikasi PUSAKA)',
                    'lampiran'         => '-',
                    'tujuan_nama'      => 'Yth. Admin Kepegawaian Kementerian Agama',
                    'tujuan_alamat'    => 'Kabupaten Tanggamus',
                    'salam_pembuka'    => 'Dengan hormat,',
                    'isi_surat'        => '<p>Sehubungan adanya gangguan pada aplikasi PUSAKA, maka beberapa pegawai tidak bisa melakukan presensi pada aplikasi PUSAKA sebagaimana seharusnya. Gangguan yang dimaksud terjadi pada:</p><table style="width: 100%; border-collapse: collapse; margin: 10px 0;" border="0"><tbody><tr><td style="width: 25%;">Hari/Tanggal</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Isi Hari, Tanggal]</strong></td></tr><tr><td style="width: 25%;">Waktu</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Isi Jam]</strong> WIB s.d. <strong>[Isi Jam]</strong> WIB</td></tr></tbody></table><p>Demikian surat pemberitahuan ini kami sampaikan, untuk digunakan sebagai keterangan gangguan absensi pada waktu yang dimaksud.</p>',
                    'salam_penutup'    => 'Kepala Madrasah,',
                    'pengirim_jabatan' => 'Kepala Madrasah',
                    'pengirim_nama'    => 'NAMA KEPALA MADRASAH, M.Pd',
                    'pengirim_nip'     => '197005272007011022',
                    'created_at'       => $now,
                ],
                [
                    'nama'             => 'Surat Keterangan Aktif Mengajar',
                    'slug'             => 'keterangan_aktif',
                    'perihal'          => 'Surat Keterangan Aktif Mengajar',
                    'lampiran'         => '-',
                    'tujuan_nama'      => 'Yth. Pihak Terkait',
                    'tujuan_alamat'    => 'di Tempat',
                    'salam_pembuka'    => 'Yang bertanda tangan di bawah ini:',
                    'isi_surat'        => '<table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;" border="0"><tbody><tr><td style="width: 25%;">Nama</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Nama Kepala Madrasah]</strong></td></tr><tr><td style="width: 25%;">NIP</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[NIP Kepala]</strong></td></tr><tr><td style="width: 25%;">Jabatan</td><td style="width: 5%;">:</td><td style="width: 70%;">Kepala Madrasah</td></tr></tbody></table><p>Menerangkan dengan sesungguhnya bahwa:</p><table style="width: 100%; border-collapse: collapse; margin: 10px 0;" border="0"><tbody><tr><td style="width: 25%;">Nama</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Nama Guru]</strong></td></tr><tr><td style="width: 25%;">NIP</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[NIP Guru]</strong></td></tr><tr><td style="width: 25%;">Tugas</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Mata Pelajaran]</strong></td></tr></tbody></table><p>Adalah benar-benar aktif mengajar di madrasah kami terhitung sejak tanggal <strong>[Tanggal Mulai]</strong> sampai dengan saat ini.</p><p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>',
                    'salam_penutup'    => 'Hormat kami,',
                    'pengirim_jabatan' => 'Kepala Madrasah',
                    'pengirim_nama'    => 'NAMA KEPALA MADRASAH, M.Pd',
                    'pengirim_nip'     => '197005272007011022',
                    'created_at'       => $now,
                ],
                [
                    'nama'             => 'Undangan Rapat',
                    'slug'             => 'undangan_rapat',
                    'perihal'          => 'Undangan Rapat',
                    'lampiran'         => '1 (satu) lembar',
                    'tujuan_nama'      => 'Yth. Bapak/Ibu Dewan Guru',
                    'tujuan_alamat'    => 'di Tempat',
                    'salam_pembuka'    => 'Dengan hormat,',
                    'isi_surat'        => '<p>Sehubungan dengan akan dilaksanakannya <strong>[Nama Kegiatan]</strong>, maka kami mengundang Bapak/Ibu untuk hadir pada:</p><table style="width: 100%; border-collapse: collapse; margin: 10px 0;" border="0"><tbody><tr><td style="width: 25%;">Hari/Tanggal</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Hari, Tanggal]</strong></td></tr><tr><td style="width: 25%;">Waktu</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Jam]</strong> WIB s.d. selesai</td></tr><tr><td style="width: 25%;">Tempat</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Ruangan/Lokasi]</strong></td></tr><tr><td style="width: 25%;">Acara</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Nama Acara]</strong></td></tr></tbody></table><p>Demikian undangan ini disampaikan. Atas perhatian dan kehadirannya diucapkan terima kasih.</p>',
                    'salam_penutup'    => 'Kepala Madrasah,',
                    'pengirim_jabatan' => 'Kepala Madrasah',
                    'pengirim_nama'    => 'NAMA KEPALA MADRASAH, M.Pd',
                    'pengirim_nip'     => '197005272007011022',
                    'created_at'       => $now,
                ],
                [
                    'nama'             => 'Permohonan Bantuan',
                    'slug'             => 'permohonan_bantuan',
                    'perihal'          => 'Permohonan Bantuan',
                    'lampiran'         => '1 (satu) berkas proposal',
                    'tujuan_nama'      => 'Yth. [Nama Instansi/Pihak]',
                    'tujuan_alamat'    => '[Alamat Lengkap]',
                    'salam_pembuka'    => 'Dengan hormat,',
                    'isi_surat'        => '<p>Dalam rangka <strong>[Tujuan Kegiatan]</strong>, kami Madrasah Ibtidaiyah Negeri 2 Tanggamus bermaksud mengajukan permohonan bantuan <strong>[Jenis Bantuan]</strong> sebesar <strong>[Jumlah]</strong>.</p><p>Adapun proposal permohonan kami lampirkan bersama surat ini untuk dapat diperiksa lebih lanjut.</p><p>Demikian permohonan ini kami sampaikan. Atas perhatian dan bantuan Bapak/Ibu, kami ucapkan terima kasih.</p>',
                    'salam_penutup'    => 'Kepala Madrasah,',
                    'pengirim_jabatan' => 'Kepala Madrasah',
                    'pengirim_nama'    => 'NAMA KEPALA MADRASAH, M.Pd',
                    'pengirim_nip'     => '197005272007011022',
                    'created_at'       => $now,
                ],
                [
                    'nama'             => 'Surat Tugas Perjalanan Dinas',
                    'slug'             => 'tugas_perjalanan',
                    'perihal'          => 'Surat Tugas Perjalanan Dinas',
                    'lampiran'         => '-',
                    'tujuan_nama'      => 'Yth. [Nama Pejabat/Tujuan]',
                    'tujuan_alamat'    => '[Alamat Tujuan]',
                    'salam_pembuka'    => 'Dengan hormat,',
                    'isi_surat'        => '<p>Yang bertanda tangan di bawah ini:</p><table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;" border="0"><tbody><tr><td style="width: 25%;">Nama</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Nama Kepala]</strong></td></tr><tr><td style="width: 25%;">NIP</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[NIP]</strong></td></tr><tr><td style="width: 25%;">Jabatan</td><td style="width: 5%;">:</td><td style="width: 70%;">Kepala Madrasah</td></tr></tbody></table><p>Menugaskan kepada:</p><table style="width: 100%; border-collapse: collapse; margin: 10px 0;" border="0"><tbody><tr><td style="width: 25%;">Nama</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Nama Petugas]</strong></td></tr><tr><td style="width: 25%;">NIP</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[NIP Petugas]</strong></td></tr><tr><td style="width: 25%;">Tujuan</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Tempat Tujuan]</strong></td></tr><tr><td style="width: 25%;">Waktu</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Tanggal]</strong></td></tr><tr><td style="width: 25%;">Keperluan</td><td style="width: 5%;">:</td><td style="width: 70%;"><strong>[Keperluan Dinas]</strong></td></tr></tbody></table><p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>',
                    'salam_penutup'    => 'Kepala Madrasah,',
                    'pengirim_jabatan' => 'Kepala Madrasah',
                    'pengirim_nama'    => 'NAMA KEPALA MADRASAH, M.Pd',
                    'pengirim_nip'     => '197005272007011022',
                    'created_at'       => $now,
                ],
            ];

            $this->db->table('template_surat_resmi')->insertBatch($templates);
        }
    }

    public function down()
    {
        $this->forge->dropTable('template_surat_resmi', true);
    }
}
