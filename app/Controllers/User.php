<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $this->userModel->findAll()
        ];
        return view('users/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pengguna Baru'
        ];
        return view('users/create', $data);
    }

    public function store()
    {
        $rules = [
            'username'     => 'required|is_unique[users.username]',
            'password'     => 'required|min_length[5]',
            'nama_lengkap' => 'required',
            'email'        => 'required|valid_email|is_unique[users.email]',
            'role'         => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal, silakan periksa kembali isian Anda.');
        }

        $this->userModel->save([
            'username'     => $this->request->getPost('username'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'jabatan'      => $this->request->getPost('jabatan'),
            'role'         => $this->request->getPost('role'),
        ]);

        return redirect()->to('/pengaturan')->with('success', 'Pengguna berhasil ditambahkan')->with('active_tab', 'pengguna');
    }

    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/pengaturan')->with('error', 'Pengguna tidak ditemukan')->with('active_tab', 'pengguna');
        }

        $data = [
            'title' => 'Edit Pengguna',
            'user'  => $user
        ];
        return view('users/edit', $data);
    }

    public function update($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/pengaturan')->with('error', 'Pengguna tidak ditemukan')->with('active_tab', 'pengguna');
        }

        $rules = [
            'username'     => "required|is_unique[users.username,id,{$id}]",
            'nama_lengkap' => 'required',
            'email'        => "required|valid_email|is_unique[users.email,id,{$id}]",
            'role'         => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal, silakan periksa kembali isian Anda.');
        }

        $data = [
            'id'           => $id,
            'username'     => $this->request->getPost('username'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'jabatan'      => $this->request->getPost('jabatan'),
            'role'         => $this->request->getPost('role'),
        ];

        // Jika password diisi, update password
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        $this->userModel->save($data);

        return redirect()->to('/pengaturan')->with('success', 'Data pengguna berhasil diperbarui')->with('active_tab', 'pengguna');
    }

    public function delete($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/pengaturan')->with('error', 'Pengguna tidak ditemukan')->with('active_tab', 'pengguna');
        }

        // Jangan izinkan admin menghapus dirinya sendiri
        if ($id == session()->get('user_id')) {
            return redirect()->to('/pengaturan')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.')->with('active_tab', 'pengguna');
        }

        $this->userModel->delete($id);
        return redirect()->to('/pengaturan')->with('success', 'Pengguna berhasil dihapus')->with('active_tab', 'pengguna');
    }
}
