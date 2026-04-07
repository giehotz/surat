<?php

namespace App\Controllers;

use App\Models\DisposisiModel;
use App\Models\SuratKeluarModel;

class Notification extends BaseController
{
    public function getUnreadCount()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['status' => false, 'message' => 'Unauthorized']);
        }

        $userId = session()->get('user_id');
        $role = session()->get('role');

        $disposisiModel = new DisposisiModel();
        // Disposisi yang ditugaskan ke user ini dan masih pending
        $unreadDisposisi = $disposisiModel->where('ke_user_id', $userId)
            ->where('status', 'pending')
            ->countAllResults();

        $unreadApproval = 0;
        // Jika pimpinan, periksa surat keluar yang masih draft (menunggu persetujuan)
        if ($role === 'pimpinan') {
            $skModel = new SuratKeluarModel();
            $unreadApproval = $skModel->where('status', 'draft')
                ->countAllResults();
        }

        $totalUnread = $unreadDisposisi + $unreadApproval;

        return $this->response->setJSON([
            'status' => true,
            'data' => [
                'disposisi' => $unreadDisposisi,
                'approval' => $unreadApproval,
                'total' => $totalUnread
            ]
        ]);
    }
}
