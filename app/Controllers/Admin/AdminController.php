<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        
        // Fetch stats
        $stats = [
            'total_students'      => $db->table('student_profiles')->countAllResults(),
            'total_lecturers'     => $db->table('lecturer_profiles')->countAllResults(),
            'total_institutions'  => $db->table('institution_profiles')->countAllResults(),
            'total_prodis'        => $db->table('study_programs')->countAllResults(),
            'pending_verifs'      => $db->table('kp_registrations')->where('current_status', 'menunggu_verifikasi')->countAllResults(),
            'active_period'       => $db->table('kp_periods')->where('status', 'aktif')->get()->getRow()->name ?? 'Tidak ada periode aktif',
        ];

        // Fetch recent users
        $recentUsers = $db->table('users')->orderBy('id', 'DESC')->limit(5)->get()->getResultArray();
        
        // Fetch recent logs
        $recentLogs = $db->table('audit_logs')->orderBy('id', 'DESC')->limit(5)->get()->getResultArray();

        $data = [
            'title'        => 'Dashboard Admin',
            'stats'        => $stats,
            'recent_users' => $recentUsers,
            'recent_logs'  => $recentLogs,
        ];

        return view('admin/index', $data);
    }

    public function placeholder($menuName)
    {
        return view('placeholder', [
            'title'    => $menuName,
            'menuName' => $menuName
        ]);
    }
}
