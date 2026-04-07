<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $user = $userModel->find(session()->get('user_id'));

        $data = [
            'title' => 'My Profile',
            'user'  => $user
        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'nama_lengkap' => 'required',
            'email'        => "required|valid_email|is_unique[users.email,id,{$userId}]",
        ];

        // Validasi foto (jika ada file yang diunggah)
        $foto = $this->request->getFile('foto_profile');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $rules['foto_profile'] = 'max_size[foto_profile,2048]|is_image[foto_profile]|mime_in[foto_profile,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $dataUpdate = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
        ];

        // Update password jika diisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $dataUpdate['password'] = password_hash($password, PASSWORD_BCRYPT);
        }

        // Proses upload foto
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(FCPATH . 'uploads/profiles', $newName);

            // Hapus foto lama jika ada dan bukan bawaan
            if (!empty($user['foto_profile']) && file_exists(FCPATH . 'uploads/profiles/' . $user['foto_profile'])) {
                unlink(FCPATH . 'uploads/profiles/' . $user['foto_profile']);
            }

            $dataUpdate['foto_profile'] = $newName;
        }

        $userModel->update($userId, $dataUpdate);

        // Update session
        session()->set('nama_lengkap', $dataUpdate['nama_lengkap']);
        session()->set('email', $dataUpdate['email']);
        if (isset($dataUpdate['foto_profile'])) {
            session()->set('foto_profile', $dataUpdate['foto_profile']);
        }

        return redirect()->to('/profile')->with('success', 'Profil berhasil diperbarui.');
    }

    public function deletePhoto()
    {
        $userModel = new UserModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // Hapus file fisik jika ada
        if (!empty($user['foto_profile']) && file_exists(FCPATH . 'uploads/profiles/' . $user['foto_profile'])) {
            unlink(FCPATH . 'uploads/profiles/' . $user['foto_profile']);
        }

        // Update database (set null)
        $userModel->update($userId, ['foto_profile' => null]);

        // Update session
        session()->set('foto_profile', null);

        return redirect()->to('/profile')->with('success', 'Foto profil berhasil dihapus.');
    }
}
