<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Siswa extends BaseController
{
    public function index()
    {
        return redirect()->to('/data-madrasah')->with('active_tab', 'siswa');
    }

    public function create()
    {
        $data = [
            'title'               => 'Tambah Data Siswa',
            'kelasList'           => [],
            'hide_default_header' => true
        ];
        return view('data_madrasah/siswa/create', $data);
    }

    public function store()
    {
        return redirect()->to('/data-madrasah')->with('active_tab', 'siswa')->with('success', 'Data siswa berhasil ditambahkan (Demo).');
    }

    public function edit($id = null)
    {
        $data = [
            'title'     => 'Edit Data Siswa',
            'id'        => $id,
            'kelasList' => [],
            'siswa'     => [
                'id' => $id, 'nis' => '', 'nama' => '', 'jenis_kelamin' => '',
                'kelas_id' => '', 'tempat_lahir' => '', 'tanggal_lahir' => '',
                'alamat' => '', 'email' => '', 'telepon' => '', 'status' => 'aktif'
            ]
        ];
        return view('data_madrasah/siswa/edit', $data);
    }

    public function update($id = null)
    {
        return redirect()->to('/data-madrasah')->with('active_tab', 'siswa')->with('success', 'Data siswa berhasil diperbarui (Demo).');
    }

    public function delete($id = null)
    {
        return redirect()->to('/data-madrasah')->with('active_tab', 'siswa')->with('success', 'Data siswa berhasil dihapus (Demo).');
    }

    public function import()
    {
        $data = [
            'title' => 'Import Data Siswa'
        ];
        return view('data_madrasah/siswa/import', $data);
    }

    public function downloadTemplate()
    {
        // Mock download template redirect
        return redirect()->back()->with('success', 'Template berhasil diunduh (Demo).');
    }

    public function storeImport()
    {
        // Mock file import logic
        return redirect()->to('/data-madrasah')->with('active_tab', 'siswa')->with('success', 'Data siswa berhasil diimport (Demo).');
    }
}
