<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;

class KeputusanController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        // Fetch logs created by this coordinator user
        $logs = $db->table('audit_logs')
            ->where('user_id', $userId)
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Riwayat Keputusan Saya',
            'logs'  => $logs,
        ];

        return view('koordinator/manajemen/keputusan/index', $data);
    }
}
