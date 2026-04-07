<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DataGuru extends BaseController
{
    public function index()
    {
        return redirect()->to('/data-madrasah')->with('active_tab', 'data_guru');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Guru/Pegawai'
        ];
        return view('data_madrasah/data_guru/create', $data);
    }

    public function store()
    {
        $model = new \App\Models\DataGuruModel();
        $data = $this->request->getPost();
        
        // Manual sync for legacy field if needed
        if (!empty($data['tempat_lahir']) && !empty($data['tanggal_lahir'])) {
            $data['tempat_tanggal_lahir'] = $data['tempat_lahir'] . ', ' . $data['tanggal_lahir'];
        }

        if ($model->save($data)) {
            return redirect()->to('/data-madrasah')->with('active_tab', 'data_guru')->with('success', 'Data guru berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('errors', $model->errors());
    }

    public function edit($id = null)
    {
        $model = new \App\Models\DataGuruModel();
        $guru = $model->find($id);

        if (!$guru) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Data Guru/Pegawai',
            'id'    => $id,
            'guru'  => $guru
        ];
        return view('data_madrasah/data_guru/edit', $data);
    }

    public function update($id = null)
    {
        $model = new \App\Models\DataGuruModel();
        $data = $this->request->getPost();
        
        // Manual sync for legacy field if needed
        if (!empty($data['tempat_lahir']) && !empty($data['tanggal_lahir'])) {
            $data['tempat_tanggal_lahir'] = $data['tempat_lahir'] . ', ' . $data['tanggal_lahir'];
        }

        if ($model->update($id, $data)) {
            return redirect()->to('/data-madrasah')->with('active_tab', 'data_guru')->with('success', 'Data guru berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('errors', $model->errors());
    }

    public function delete($id = null)
    {
        $model = new \App\Models\DataGuruModel();
        if ($model->delete($id)) {
            return redirect()->to('/data-madrasah')->with('active_tab', 'data_guru')->with('success', 'Data guru berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Gagal menghapus data.');
    }

    public function berkas($id = null)
    {
        $guru = (new \App\Models\DataGuruModel())->find($id);

        $data = [
            'title'     => 'Kelola Berkas Pegawai',
            'id'        => $id,
            'guru'      => $guru,
            'maxSizeMB' => 2
        ];
        return view('data_madrasah/data_guru/berkas', $data);
    }

    public function import()
    {
        $data = [
            'title' => 'Import Data Pegawai'
        ];
        return view('data_madrasah/data_guru/import', $data);
    }

    public function downloadTemplate()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $columns = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N'];
        $headers = [
            'nama_pegawai', 'nip', 'peg_id_nuptk', 'tempat_lahir', 'tanggal_lahir',
            'jabatan_mengajar', 'pangkat_golongan', 'pendidikan_terakhir', 'perguruan_tinggi',
            'mulai_tugas', 'tmt_cpns_honorer', 'status_kepegawaian', 'email', 'no_handphone'
        ];

        foreach ($headers as $key => $header) {
            $sheet->setCellValue($columns[$key] . '1', $header);
        }

        // Contoh Data
        $sheet->setCellValue('A2', 'Ahmad Sukaryo, S.Pd.');
        $sheet->setCellValue('B2', '198001012005011001');
        $sheet->setCellValue('D2', 'Jakarta');
        $sheet->setCellValue('E2', '1980-01-01');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = 'Template_Import_Guru_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function storeImport()
    {
        $file = $this->request->getFile('file_excel');

        if (!$file->isValid()) {
            return redirect()->back()->with('error', $file->getErrorString());
        }

        $ext = $file->getClientExtension();
        if ($ext == 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $spreadsheet = $reader->load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        $model = new \App\Models\DataGuruModel();
        $successCount = 0;
        $errorCount = 0;

        foreach ($sheetData as $key => $row) {
            if ($key == 0) continue; // Skip header

            if (empty($row[0])) {
                $errorCount++;
                continue;
            }

            $data = [
                'nama_pegawai'        => $row[0],
                'nip'                 => $row[1],
                'peg_id_nuptk'        => $row[2],
                'tempat_lahir'        => $row[3],
                'tanggal_lahir'       => $row[4],
                'jabatan_mengajar'    => $row[5],
                'pangkat_golongan'    => $row[6],
                'pendidikan_terakhir' => $row[7],
                'perguruan_tinggi'    => $row[8],
                'mulai_tugas'         => !empty($row[9]) ? $row[9] : null,
                'tmt_cpns_honorer'    => !empty($row[10]) ? $row[10] : null,
                'status_kepegawaian'  => $row[11],
                'email'               => $row[12],
                'no_handphone'        => $row[13],
            ];

            // Sync legacy field
            if (!empty($data['tempat_lahir']) && !empty($data['tanggal_lahir'])) {
                $data['tempat_tanggal_lahir'] = $data['tempat_lahir'] . ', ' . $data['tanggal_lahir'];
            }

            if ($model->save($data)) {
                $successCount++;
            } else {
                $errorCount++;
            }
        }

        return redirect()->to('/data-madrasah')->with('active_tab', 'data_guru')
            ->with('success', "Import selesai. Berhasil: $successCount, Gagal: $errorCount.");
    }
}
