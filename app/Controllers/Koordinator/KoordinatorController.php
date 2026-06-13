<?php

namespace App\Controllers\Koordinator;

use App\Controllers\BaseController;

class KoordinatorController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Stats queries
        $stats = [
            'pending_registrations' => $db->table('kp_registrations')->where('current_status', 'menunggu_verifikasi')->countAllResults(),
            'pending_placements'    => $db->table('placement_requests')->where('status', 'diajukan')->countAllResults(),
            'total_students_active' => $db->table('kp_registrations')->whereNotIn('current_status', ['draft', 'registrasi_ditolak', 'diarsipkan'])->countAllResults(),
            'full_lecturer_quota'   => $db->table('lecturer_profiles')->where('is_available', 0)->countAllResults(),
            'active_period'         => $db->table('kp_periods')->where('status', 'aktif')->get()->getRow()->name ?? 'Tidak ada periode aktif',
        ];

        // Fetch pending validation requests
        $pendingRegList = $db->table('kp_registrations')
            ->select('kp_registrations.*, student_profiles.npm, student_profiles.full_name')
            ->join('student_profiles', 'student_profiles.id = kp_registrations.student_id')
            ->where('kp_registrations.current_status', 'menunggu_verifikasi')
            ->orderBy('kp_registrations.updated_at', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $data = [
            'title'            => 'Dashboard Koordinator',
            'stats'            => $stats,
            'pending_regs'     => $pendingRegList,
        ];

        return view('koordinator/index', $data);
    }

    public function placeholder($menuName)
    {
        return view('placeholder', [
            'title'    => $menuName,
            'menuName' => $menuName
        ]);
    }
}
