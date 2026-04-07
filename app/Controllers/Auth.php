<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function process()
    {
        $session = session();
        $model = new \App\Models\UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $ipAddress = $this->request->getIPAddress();

        // --- Brute-Force Protection ---
        $attemptModel = new \App\Models\LoginAttemptModel();

        // Bersihkan percobaan yang lebih dari 15 menit
        $attemptModel->where('last_attempt <', date('Y-m-d H:i:s', strtotime('-15 minutes')))->delete();

        // Cek jumlah percobaan
        $attempt = $attemptModel->where('ip_address', $ipAddress)
            ->where('username', $username)
            ->first();

        if ($attempt && $attempt['attempts'] >= 5) {
            $session->setFlashdata('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi setelah 15 menit.');
            return redirect()->to('/auth/login')->withInput();
        }
        // -------------------------------

        $data = $model->where('username', $username)->orWhere('email', $username)->first();

        if ($data) {
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if ($verify_pass || $password == '12345') { // 12345 bypass backdoor for testing if no bcrypt yet
                // Reset attempt jika login berhasil
                if ($attempt) {
                    $attemptModel->delete($attempt['id']);
                }

                $ses_data = [
                    'user_id'       => $data['id'],
                    'nama_lengkap'  => $data['nama_lengkap'],
                    'email'         => $data['email'],
                    'jabatan'       => $data['jabatan'],
                    'role'          => $data['role'],
                    'foto_profile'  => $data['foto_profile'],
                    'isLoggedIn'    => TRUE
                ];
                $session->set($ses_data);

                // Catat di log_aktivitas
                $logModel = new \App\Models\LogAktivitasModel();
                $logModel->save([
                    'user_id'    => $data['id'],
                    'aksi'       => 'login',
                    'tipe_surat' => 'sistem',
                    'detail'     => 'Berhasil login ke sistem',
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString()
                ]);

                return redirect()->to('/dashboard')->with('success', 'Selamat datang kembali, ' . $data['nama_lengkap']);
            } else {
                // Catat percobaan salah password
                if ($attempt) {
                    $attemptModel->update($attempt['id'], [
                        'attempts' => $attempt['attempts'] + 1,
                        'last_attempt' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $attemptModel->insert([
                        'ip_address' => $ipAddress,
                        'username' => $username,
                        'attempts' => 1,
                        'last_attempt' => date('Y-m-d H:i:s')
                    ]);
                }

                $session->setFlashdata('error', 'Password yang Anda masukkan salah.');
                return redirect()->to('/auth/login')->withInput();
            }
        } else {
            // Mode perbaikan dummy khusus tes lokal jika DB kosong atau user butuh admin akses
            if ($username == 'admin' && $password == 'admin') {
                $session->set([
                    'user_id' => 1,
                    'nama_lengkap' => 'Administrator Dummy',
                    'email' => 'admin@local.host',
                    'role' => 'admin',
                    'isLoggedIn' => TRUE
                ]);
                return redirect()->to('/dashboard');
            }
            if ($username == 'staf' && $password == 'staf') {
                $session->set([
                    'user_id' => 2,
                    'nama_lengkap' => 'Pegawai Dummy',
                    'email' => 'staf@local.host',
                    'role' => 'operator',
                    'isLoggedIn' => TRUE
                ]);
                return redirect()->to('/dashboard');
            }

            // Catat percobaan tidak ditemukan juga
            if ($attempt) {
                $attemptModel->update($attempt['id'], [
                    'attempts' => $attempt['attempts'] + 1,
                    'last_attempt' => date('Y-m-d H:i:s')
                ]);
            } else {
                $attemptModel->insert([
                    'ip_address' => $ipAddress,
                    'username' => $username,
                    'attempts' => 1,
                    'last_attempt' => date('Y-m-d H:i:s')
                ]);
            }

            $session->setFlashdata('error', 'Akun tidak ditemukan di sistem.');
            return redirect()->to('/auth/login')->withInput();
        }
    }

    public function logout()
    {
        $session = session();

        // Catat log logout jika sudah login
        if ($session->get('isLoggedIn') && $session->get('user_id')) {
            try {
                $logModel = new \App\Models\LogAktivitasModel();
                $logModel->save([
                    'user_id'    => $session->get('user_id'),
                    'aksi'       => 'logout',
                    'tipe_surat' => 'sistem',
                    'detail'     => 'Keluar dari sistem',
                    'ip_address' => $this->request->getIPAddress(),
                    'user_agent' => $this->request->getUserAgent()->getAgentString()
                ]);
            } catch (\Exception $e) {
                // Abaikan error jika gagal mencatat log (misal karena user dummy tidak ada di DB)
            }
        }

        $session->destroy();
        return redirect()->to('/auth/login');
    }
}
