<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Kelas extends BaseController
{
    public function index()
    {
        // Since we unified the index, redirecting to the main dashboard
        return redirect()->to('/data-madrasah')->with('active_tab', 'kelas');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Data Kelas'
        ];
        return view('data_madrasah/kelas/create', $data);
    }

    public function store()
    {
        // Placeholder for store logic
        return redirect()->to('/data-madrasah')->with('active_tab', 'kelas')->with('success', 'Data kelas berhasil ditambahkan (Demo).');
    }

    public function edit($id = null)
    {
        $data = [
            'title' => 'Edit Data Kelas',
            'id'    => $id,
            'kelas' => [
                'id' => $id, 'nama_kelas' => '', 'tingkat' => '', 'jurusan' => '', 'deskripsi' => ''
            ]
        ];
        return view('data_madrasah/kelas/edit', $data);
    }

    public function update($id = null)
    {
        // Placeholder for update logic
        return redirect()->to('/data-madrasah')->with('active_tab', 'kelas')->with('success', 'Data kelas berhasil diperbarui (Demo).');
    }

    public function delete($id = null)
    {
        // Placeholder for delete logic
        return redirect()->to('/data-madrasah')->with('active_tab', 'kelas')->with('success', 'Data kelas berhasil dihapus (Demo).');
    }

    public function siswa($id = null)
    {
        $data = [
            'title' => 'Data Siswa per Kelas',
            'kelas_id' => $id
        ];
        return view('data_madrasah/kelas/siswa', $data);
    }
}
